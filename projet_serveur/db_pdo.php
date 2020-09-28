<?php

if (!isset($index_loaded)) {
    http_response_code(403);
    die('<h2 style="color: red">Acces direct a ce fichier est interdit !!!</h2>');
}
/**class pour bd de tous types avec PDO */
class DB
{
    private $host = 'localhost';
    // nom db
    private $db_name = 'classicmodels';
    //USER SUR LE SERVEUR SQL
    private $db_user_name = 'site_web';
    private $db_user_pw = '12345678';
    private $pdo; //connection object

    public function __construct()
    {
        $port = 3306;
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$this->host;dbname=$this->db_name;charset=$charset;port=$port";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO:: ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            $this->pdo = new PDO($dsn, $this->db_user_name, $this->db_user_pw, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int) $e->getCode());
        }

        // echo 'connected';
    }

    public function query($sql_str)
    {
        //PDO=-------------------------------------
        try {
            $result = $this->pdo->query($sql_str);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int) $e->getCode());
        }

        return $result;
    }

    public function queryParam($sql_str, $params)
    {
        $stmt = $this->pdo->prepare($sql_str);
        $stmt->execute($params);

        return true;
    }

    /**
     * requete avec parametres pour se protegeer contre injection SQL.
     */
    public function querySelectParam($sql_str, $params)
    {
        $stmt = $this->pdo->prepare($sql_str);
        $stmt->execute($params);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function querySelect($sql_str)
    {
        $records = $this->query($sql_str)->fetchAll();
        // var_dump($records);

        return $records;
    }

    public function table($table_str)
    {
        $records = $this->querySelect('SELECT * FROM '.$table_str);

        return $records;
    }

    public function disconnect()
    {
        $this->pdo = null;
    }
}
