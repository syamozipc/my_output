<?php
namespace App\Libraries;

use App\Libraries\Database;

class Model {
    public Database $db;

    // 遅いin_arrayでは無く高速なissetを使うため、連想配列にする
    protected $ignorekeys = [
        '_csrf_token' => '',
        'MAX_FILE_SIZE' => ''
    ];

    protected $primaryKey = 'id';

    public function __construct(array $params = [])
    {
        $this->db = Database::getSingleton();

        if (count($params) > 0) $this->fill($params);
    }

    /**
     * 引数で渡された連想配列を、keyをproperty名、valueをpropertyの値としてセットする
     * ただし$ignoreKeysに含まれるkeyはセットしない
     *
     * @param array $params
     * @return self $this
     */
    public function fill($params):self
    {
        foreach ($params as $key => $param) {
            if (isset($this->ignorekeys[$key])) continue;

            $this->{$key} = $param;
        }

        return $this;
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
        $sqlValues = ") VALUES (";
        
        foreach ($this->fillable as $key => $_) {
            if (!property_exists($this, $key)) continue;

            $sql .= "`{$key}`,";
            $sqlValues .= ":{$key},";
        }

        // 最後の,を除去
        $sql = rtrim($sql, ',');
        $sqlValues = rtrim($sqlValues, ',');

        // insert文を合体
        $sql = "{$sql} {$sqlValues})";
  
        // sqlをprepare
        $this->db->prepare(sql:$sql);

        // 名前付きプレースホルダーに値を入れる
        foreach ($this->fillable as $key => $_) {
            if (!property_exists($this, $key)) continue;

            $this->db->bindValue(param:":{$key}", value:$this->{$key});
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
        foreach ($this->fillable as $key => $_) {
            $sql .= " `{$key}` = :{$key},";
        }

        $sql = rtrim($sql, ',');
        // 更新対象のレコードを指定
        $sql .= " WHERE `{$this->primaryKey}` = :{$this->primaryKey}";

        // sqlをprepare
        $this->db->prepare(sql:$sql);

        // 名前付きプレースホルダーに値を入れる
        foreach ($this->fillable as $key => $_) {
            $this->db->bindValue(param:":{$key}", value:$this->{$key});
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
            ->bindValue(param:":{$this->primaryKey}", value:$this->{$this->primaryKey})
            ->execute();
    }
}