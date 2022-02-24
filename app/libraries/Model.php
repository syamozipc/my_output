<?php
namespace App\Libraries;

use App\Libraries\Database;

class Model {
    public object $db;

    // 遅いin_arrayでは無く高速なissetを使うため、連想配列にする
    protected $ignorekeys = [
        '_csrf_token' => '',
        'MAX_FILE_SIZE' => ''
    ];

    protected $primaryKey = 'id';

    public function __construct(array $params = [])
    {
        $this->db = new Database;

        if (count($params) > 0) $this->fill($params);
    }

    /**
     * 引数で渡された連想配列を、keyをproperty名、valueをpropertyの値としてセットする
     * ただし$ignoreKeysに含まれるkeyはセットしない
     *
     * @param array $params
     * @return void
     */
    public function fill($params)
    {
        foreach ($params as $key => $param) {
            if (isset($this->ignorekeys[$key])) continue;

            $this->{$key} = $param;
        }
    }

    /**
     * modelに対応するテーブルに保存される
     *
     * @return void
     */
    public function save()
    {
        // idがあればupdate
        if (isset($this->id)) $this->update();
        // idが無ければinsert
        else $this->insert();
    }

    /**
     * 対応するテーブルに新規にinsert
     *
     * @return void
     */
    public function insert()
    {
        $sql = "INSERT INTO `{$this->table}` (";
        $sqlValues = "VALUES (";
        $existProperties = [];

        
        // @todo iterateraggregaterとかでもっとスマートに
        foreach ($this as $key => $_) {
            // @todo そのプロパティが存在するか、みたいな判定をつける
            if (!isset($this->fillable[$key])) continue;

            $existProperties[] = $key;

            $sql .= "`{$key}`,";
            $sqlValues .= ":{$key},";
        }

        // 最後の,を除去
        $sql = rtrim($sql, ',');
        $sqlValues = rtrim($sqlValues, ',');

        // insert文を合体
        $sql = "{$sql}) {$sqlValues})";
  
        // sqlをprepare
        $this->db->prepare(sql:$sql);

        // 名前付きプレースホルダーに値を入れる
        foreach ($existProperties as $property) {
            $this->db->bindValue(param:":{$property}", value:$this->{$property});
        }

        $this->db->execute();
    }

    /**
     * 対応するテーブルのレコードをupdate
     *
     * @return void
     */
    public function update()
    {
        $sql = "UPDATE `{$this->table}` SET";
        $existProperties = [];

        // where句以外のSQL文を生成
        // @todo iterateraggregaterとかでもっとスマートに
        foreach ($this as $key => $_) {
            // @todo そのプロパティが存在するか、みたいな判定をつける
            if (!isset($this->fillable[$key])) continue;

            $existProperties[] = $key;

            $sql .= " `{$key}` = :{$key},";
        }

        $sql = rtrim($sql, ',');
        // 更新対象のレコードを指定
        $sql .= " WHERE `{$this->primaryKey}` = :{$this->primaryKey}";

        // sqlをprepare
        $this->db->prepare(sql:$sql);

        // 名前付きプレースホルダーに値を入れる
        foreach ($existProperties as $property) {
            $this->db->bindValue(param:":{$property}", value:$this->{$property});
        }

        // WHERE句のプレースホルダーに値を入れ、実行
        $this->db
            ->bindValue(":{$this->primaryKey}", $this->{$this->primaryKey})
            ->execute();
    }

    /**
     * 対応するmodelのレコードを削除
     *
     * @return void
     */
    public function delete()
    {
        $sql = "DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = :{$this->primaryKey}";
        
        $this->db
            ->prepare(sql:$sql)
            ->bindValue(param:":{$this->primaryKey}", value:$this->id)
            ->execute();
    }
}