<?php
/**
 * Created by PhpStorm.
 * User: romai
 * Date: 11/12/2017
 * Time: 20:22
 */

class Connect{
    private $jdbc, $user, $pass, $host, $dbname, $defaultSchema;
    public $bdd;
    private $filename = "G:\DUT2A\Prolog\Projet Open Food Facts\Project\assets\config\db.json";
    private static $connect = null;

    private function __construct(){
        $content = file_get_contents($this->filename);
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

    public static function getInstance(){
        if(is_null(self::$connect))
            self::$connect = new Connect();
        return self::$connect;
    }
}