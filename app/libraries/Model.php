<?php
namespace App\Libraries;

use App\Libraries\Database;

class Model implements \IteratorAggregate {
    public Database $db;

    // 遅いin_arrayでは無く高速なissetを使うため、連想配列にする
    protected array $ignorekeys = [
        '_csrf_token' => '',
        'MAX_FILE_SIZE' => '',
        'loopProperties',
        'table' => '',
        'primaryKey' => '',
        'db' => '',
    ];

    protected string $primaryKey = 'id';

    private array $loopProperties = [];

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
     * IteratorAggregate実装method の override
     * $thisをループ時、これが呼び出される
     *
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        if ($this->loopProperties) return new \ArrayIterator($this->loopProperties);

        foreach ($this as $property) {
            // dd($property);
            if (!property_exists($this, $property)) continue;

            $this->loopProperties[] = $property;
        }

        return new \ArrayIterator($this->loopProperties);
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

        // getIterator()が呼び出される
        foreach ($this as $property) {
            $sql .= "`{$property}`,";
            $sqlValues .= ":{$property},";
        }

        // 最後の,を除去
        $sql = rtrim($sql, ',');
        $sqlValues = rtrim($sqlValues, ',');

        // insert文を合体
        $sql = "{$sql} {$sqlValues})";
  
        // sqlをprepare
        $this->db->prepare(sql:$sql);

        // 名前付きプレースホルダーに値を入れる
        // getIterator()が呼び出される
        foreach ($this as $property) {
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

        // where句以外のSQL文を生成
        // getIterator()が呼び出される
        foreach ($this as $property) {
            $sql .= " `{$property}` = :{$property},";
        }

        $sql = rtrim($sql, ',');
        // 更新対象のレコードを指定
        $sql .= " WHERE `{$this->primaryKey}` = :{$this->primaryKey}";

        // sqlをprepare
        $this->db->prepare(sql:$sql);

        // 名前付きプレースホルダーに値を入れる
        // getIterator()が呼び出される
        foreach ($this as $property) {
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
            ->bindValue(param:":{$this->primaryKey}", value:$this->{$this->primaryKey})
            ->execute();
    }

    // public function __set($name, $value)
    // {
    //     // dd($name, $value);
    // }

    // public function __get($name)
    // {
    //     echo '<pre>';var_dump($name);die;
    //     return $this->{"get{$name}"}();
    // }

    // public function __call($name, $arguments)
    // {
    //     echo '<pre>';var_dump($name, $this->{$name});die;
    //     echo '<pre>';var_dump($this->{$name});die;
    //     return $this->{$name};
    // }
}