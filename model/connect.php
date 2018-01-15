<?php
/**
 * Created by PhpStorm.
 * User: romai
 * Date: 11/12/2017
 * Time: 20:22
 */

class Base{
    private $jdbc, $user, $pass, $host, $dbname, $defaultSchema;
    public $bdd;
    private $filename = "/config/db.json";
    private static $connect = null;

    private function __construct($webhost){
        $content = file_get_contents($webhost.$this->filename);
        $dbconf = json_decode($content, true);

        $this->user = $dbconf['user'];
        $this->pass = $dbconf['pass'];
        $this->jdbc = $dbconf['jdbc'];
        $this->host = $dbconf['host'];
        $this->dbname = $dbconf['dbname'];
        $this->defaultSchema = $dbconf['default_schema'];

        $this->connect();
    }

    public function connect(){
        try {
            $this->bdd = new PDO($this->jdbc.':host='.$this->host.';dbname='.$this->dbname, $this->user, $this->pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $this->bdd->query("Set Schema '".$this->defaultSchema."';");
        } catch (Exception $e) {
            die('Erreur : '.$e->getMessage());
        }
    }

    public static function getInstance($webhost){
        if(is_null(self::$connect))
            self::$connect = new Base($webhost);
        return self::$connect;
    }

    public function queryArray($sql){
        $res = $this->bdd->query($sql);

        $result = array();

        if ($res)
            while ($row = $res->fetch(PDO::FETCH_ASSOC))
                array_push($result, $row);
        return $result;
    }

    public function queryArrayNoDouble($sql, $pk){
        $res = $this->bdd->query($sql);

        $result = array();

        if ($res)
            while ($row = $res->fetch(PDO::FETCH_ASSOC))
                if (!self::alreadyPush($result, $row, $pk))
                    array_push($result, $row);
        return $result;
    }

    private function alreadyPush($result, $row, $pk){
        foreach ($result as $k => $v) if ($v[$pk] == $row[$pk]) return true;
        return false;
    }

    public function getAdditives(){
        return $this->queryArray("SELECT * FROM additives");
    }

    public function getCountries(){
        return $this->queryArray("SELECT * FROM countries");
    }
}