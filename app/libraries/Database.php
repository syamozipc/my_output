<?php
namespace App\Libraries;

class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $pdo;
    private $pdoStatement;
    private $error;

    public function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbname}";
        $options = [
            \PDO::ATTR_PERSISTENT => true,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ];

        try {
            $this->pdo = new \PDO($dsn, $this->user, $this->pass, $options);
        } catch (\PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    public function prepare($sql)
    {
        $this->pdoStatement = $this->pdo->prepare($sql);

        return $this;
    }

    public function bindValue($param, $value, $type = null)
    {
        if (is_null($type)) {
            $type = match(true) {
                is_int($value) => \PDO::PARAM_INT,
                is_bool($value) => \PDO::PARAM_BOOL,
                is_null($value) => \PDO::PARAM_NULL,
                default => \PDO::PARAM_STR
            };
        }

        $this->pdoStatement->bindValue($param, $value, $type);

        return $this;
    }

    public function execute()
    {
        return $this->pdoStatement->execute();
    }

    /**
     * execute→fetchAll後、$clasNameのinstanceの配列として結果を取得
     *
     * @param string $className fetch結果をinstance化classの名前
     * @return array $className の配列（無ければ[]の配列）
     */
    public function executeAndFetchAll($className): array
    {
        $this->execute();

        $this->pdoStatement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $className);
        
        return $this->pdoStatement->fetchAll();
    }

    /**
     * execute→fetch後、$clasNameのinstanceとして結果を取得
     *
     * @param string $className fetch結果をinstance化classの名前
     * @return object|false $classNameに指定したクラスのinstance（失敗したらfalse）
     */
    public function executeAndFetch($className): object|false
    {
        $this->execute();

        // 任意のクラスのインスタンスとして取得。コンストラクタ → propertyをセットの順になる
        $this->pdoStatement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $className);

        // fetchObject にすると \PDO::FETCH_PROPS_LATE を指定できずpropertyのセット→コンストラクタ実行 という逆順になるので、上でモードを定義
        return $this->pdoStatement->fetch();
    }

    public function rowCount()
    {
        return $this->pdoStatement->rowCount();
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }

    public function commit()
    {
        return $this->pdo->commit();
    }

    public function rollBack()
    {
        return $this->pdo->rollBack();
    }
}