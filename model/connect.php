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

    public function insertProduct($infos){
        $res = $this->bdd->prepare("INSERT INTO produits (code,name,brands, creator,image_url,image_ingredients_url, ingredients, servingsize, 
nutritiongrade, energy, fat, satured_fat, trans_fat, cholesterol, carbohydrates, sugars, fiber, proteins, salt, sodium, vitamina, vitaminc, 
 calcium, iron, nutrition_score, frompalmoil) VALUES (:code, :name, :brands, :creator, :image_url, :image_ingredients_url, :ingredients, :servingsize, 
:nutritiongrade, :energy, :fat, :satured_fat, :trans_fat, :cholesterol, :carbohydrates, :sugars, :fiber, :proteins, :salt, :sodium, :vitamina, :vitaminc, 
 :calcium, :iron, :nutrition_score, :frompalmoil)");
        try{
            $res->execute($infos);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateProduct($infos){
        $res = $this->bdd->prepare("UPDATE produits SET name=:name, brands=:brands, creator=:creator, image_url=:image_url, image_ingredients_url=:image_ingredients_url, ingredients=:ingredients,
    servingsize=:servingsize, nutritiongrade=:nutritiongrade, energy=:energy, fat=:fat, satured_fat=:satured_fat, trans_fat=:trans_fat, cholesterol=:cholesterol,
    carbohydrates=:carbohydrates, sugars=:sugars, fiber=:fiber, proteins=:proteins, salt=:salt, sodium=:sodium, vitamina=:vitamina, vitaminc=:vitaminc,
    calcium=:calcium, iron=:iron, nutrition_score=:nutrition_score, frompalmoil=:frompalmoil WHERE code=:code");
        try{
            $res->execute($infos);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function insertCountries($codeother, $codeprod){
        $sql = "INSERT INTO origine VALUES ";
        $first=true;
        $infos = array();
        foreach($codeother as $k=>$v){
            if ($first)
                $sql .= "(?, ?)";
            else
                $sql .= ",(?, ?)";
            $first=false;
            array_push($infos,$k,$codeprod);
        }
        $res = $this->bdd->prepare($sql);
        try{
            $res->execute($infos);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function insertAdditives($codeother, $codeprod){
        $sql = "INSERT INTO contains_additives VALUES ";
        $first=true;
        $infos = array();
        foreach($codeother as $k=>$v){
            if ($first)
                $sql .= "(?, ?)";
            else
                $sql .= ",(?, ?)";
            $first=false;
            array_push($infos,$codeprod,$k);
        }
        $res = $this->bdd->prepare($sql);
        try{
            $res->execute($infos);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateCountries($codeother, $codeprod){
        $res = $this->bdd->prepare("DELETE FROM origine WHERE codeprod=?");
        $res->execute(array($codeprod));
        return $this->insertCountries($codeother, $codeprod);
    }

    public function updateAdditifs($codeother, $codeprod){
        $res = $this->bdd->prepare("DELETE FROM contains_additives WHERE code=?");
        $res->execute(array($codeprod));
        return $this->insertAdditives($codeother, $codeprod);
    }
}