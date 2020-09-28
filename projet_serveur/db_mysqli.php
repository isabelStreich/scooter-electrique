<?php

if (!isset($index_loaded)) {
    http_response_code(403);
    die('<h2 style="color: red">Acces direct a ce fichier est interdit !!!</h2>');
}

/**
 * classe pour BD de type mysql uniquement.
 * https://www.php.net/manual/en/book.mysqli.php.
 */
class DB
{
    // URL DU SERVEUR
    // $host = '127.0.0.1';
    private $host = 'localhost';
    // nom db
    private $db_name = 'classicmodels';
    //USER SUR LE SERVEUR SQL
    private $db_user_name = 'site_web';
    private $db_user_pw = '12345678';

    private $connection; //mysqli objet connection

    /**
     * constructeur. automaticament connecte a la bd et set $connection object.
     */
    public function __construct()
    {
        $this->connection = new mysqli($this->host, $this->db_user_name, $this->db_user_pw, $this->db_name);

        //POUR CARACTERE FRANCAIS
        mysqli_set_charset($this->connection, 'utf8');

        if (mysqli_connect_error()) {
            http_response_code(400); //bad request
            die('Erreur avec la Connection a la base de donnee ');
        } else {
            // echo'Felicitations! vous etes connecte a la BD';
        }
    }

    /**
     * function universel pour tout type de requette.
     */
    public function query($sql_str)
    {
        $result = $this->connection->query($sql_str);
        // var_dump($result);

        if (!$result) {
            http_response_code(400);
            die('Erreur requette SQL');
        }

        return $result;
    }

    /**
     * querySelect($sql_str) executes any query onn the sql server
     * use this for queries not returning records like INSERT, DELETE, UPDATE, ETC
     * 1)returns full sql server response
     * whatever the query (select, delete, update, etc)
     * 20 for insert, delete update retuns simply true
     * 3)for select no records conversions.
     */
    public function querySelect($sql_str)
    {
        $result = $this->query($sql_str); //voir function c-dessou
        // convertir la liste des enregistremenst en un tablea

        // inicialiser un tableau vide
        //convertir chaque enregistrament de a table en un array key=>value
        $records = [];
        while ($un_record = $result->fetch_array()) {
            array_push($records, $un_record);
        }

        return $records;
    }

    //returns all rows and all colons of a table
    public function table($nom_table)
    {
        return $this->querySelect('SELECT * FROM '.$nom_table);
    }

    //desconecter de la bd
    public function disconnect()
    {
        // fin de la connection
        mysqli_close($this->connection);
    }
}
