<?php
namespace app\engine;



use \PDO;
use PDOException;


class Db
{
    use TSingleton;

    private $config = [
        'driver' => 'mysql',
        'host' => 'localhost',
        'login' => 'root',
        'password' => 'master',
        'database' => 'zbrain',
        'charset' => 'utf8'
    ];


    /**
     * @var PDO $connection
     */
    private $connection = null;


    public function getConnection() {
        try {
            if (is_null($this->connection)) {
                $this->connection = new PDO($this->prepareDsnString(),
                    $this->config['login'],
                    $this->config['password']
                );
                $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            }

            return $this->connection;
        }catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    private function prepareDsnString() {
        return sprintf("%s:host=%s;dbname=%s;charset=%s",
            $this->config['driver'],
            $this->config['host'],
            $this->config['database'],
            $this->config['charset']
        );
    }

    private function query($sql, $param) {
        $stmt = $this->getConnection()->prepare($sql);
        if( ! $stmt ){
            die( "SQL Error: {$this->getConnection()->errorCode()} - {$this->getConnection()->errorInfo()}" );
        }
        $stmt->execute($param);
        return $stmt;
    }

    public function execute($sql, $params)
    {
        $this->query($sql, $params);
        return true;
    }

    public function queryObject($sql, $params, $class)
    {
        $stmt = $this->query($sql, $params);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $class);
        return $stmt->fetch();
    }

    public function queryOne($sql, $param = [])
    {
        return $this->queryAll($sql, $param)[0];
    }

    public function queryAll($sql, $param = [])
    {
        return $this->query($sql, $param)->fetchAll();
    }
}