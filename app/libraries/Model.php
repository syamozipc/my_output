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

        if (count($params) > 0) $this->initProperty($params);
    }

    /**
     * constructorで渡された連想配列を、keyをproperty名、valueをpropertyの値としてセットする
     * ただし$ignoreKeysに含まれるkeyはセットしない
     *
     * @param array $params
     * @return void
     */
    public function initProperty($params)
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
        foreach ($this as $key => $_) {
            if (!isset($this->fillable[$key])) continue;

            $existProperties[] = $key;

            $sql .= "`{$key}`,";
            $sqlValues .= ":{$key},";
        }

        // 最後の,を除去
        $sql = rtrim($sql, ',');
        $sqlValues = rtrim($sqlValues, ',');

        $sql = "{$sql}) {$sqlValues})";
  
        $this->db->prepare(sql:$sql);

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
        foreach ($this as $key => $_) {
            if (!isset($this->fillable[$key])) continue;

            $existProperties[] = $key;

            $sql .= " `{$key}` = :{$key},";
        }

        $sql = rtrim($sql, ',');
        $sql .= " WHERE `{$this->primaryKey}` = :{$this->primaryKey}";

        $this->db->prepare(sql:$sql);

        foreach ($existProperties as $property) {
            $this->db->bindValue(param:":{$property}", value:$this->{$property});
        }

        $this->db
            ->bindValue(":{$this->primaryKey}", $this->{$this->primaryKey})
            ->execute();
    }
}