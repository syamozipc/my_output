<?php
namespace App\Libraries;

/**
 * PDOを直接扱う唯一のクラス
 * singleton化することで、1リクエスト中は1つのDatabase instance（pdo instance）を使い回す
 */
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private static self $dababase;
    private $pdo;
    private $pdoStatement;

    /**
     * 外部からnewされないよう、privateアクセス修飾子にする
     */
    private function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
        $options = [
            \PDO::ATTR_PERSISTENT => true,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ];

        try {
            $this->pdo = new \PDO($dsn, $this->user, $this->pass, $options);
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    /**
     * singletonパターンの実装
     *
     * @return self
     */
    public static function getSingleton(): self
    {
        if (!isset(self::$dababase)) self::$dababase = new self();

        return self::$dababase;
    }

    public function prepare(string $sql): self
    {
        $this->pdoStatement = $this->pdo->prepare($sql);

        return $this;
    }

    /**
     * prepared statementにbindValueする（typeは動的に生成）
     *
     * @param string $param
     * @param integer|boolean|null|string $value
     * @param integer|null $type
     * @return self
     */
    public function bindValue(string $param, int|bool|null|string $value, int $type = null): self
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

    public function execute(): bool
    {
        return $this->pdoStatement->execute();
    }

    /**
     * execute→fetchAll後、$clasNameのinstanceの配列として結果を取得
     *
     * @param string $className fetch結果をinstance化classの名前
     * @return array $className の配列（無ければ[]の配列）
     */
    public function executeAndFetchAll(string $className = 'stdClass', array ...$args): array
    {
        $this->execute();

        $this->pdoStatement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $className, $args);
        
        return $this->pdoStatement->fetchAll();
    }

    /**
     * execute→fetch後、$clasNameのinstanceとして結果を取得
     *
     * @param string $className fetch結果をinstance化classの名前
     * @return object|false $classNameに指定したクラスのinstance（失敗したらfalse）
     */
    public function executeAndFetch(string $className = 'stdClass', array ...$args): object|false
    {
        $this->execute();

        // 任意のクラスのインスタンスとして取得。コンストラクタ → propertyをセットの順になる
        $this->pdoStatement->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, $className, $args);

        // fetchObject にすると \PDO::FETCH_PROPS_LATE を指定できずpropertyのセット→コンストラクタ実行 という逆順になるので、上でモードを定義
        return $this->pdoStatement->fetch();
    }

    /**
     * rowCount()は影響を与えたレコード数を返さないDBMSもあるので、確実に件数を取得したければ count(*) を使用する
     * ref：独習PHP p442
     *
     * @return void
     */
    public function rowCount(): int
    {
        return $this->pdoStatement->rowCount();
    }

    public function lastInsertId(): string|false
    {
        return $this->pdo->lastInsertId();
    }

    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    public function rollBack(): bool
    {
        return $this->pdo->rollBack();
    }

    public function inTransaction(): bool
    {
        return $this->pdo->inTransaction();
    }
}