<?php
/**
 * Created by PhpStorm.
 * User: romai
 * Date: 12/12/2017
 * Time: 00:09
 */

class Main{
    public $bdd;
    private static $index = null;
    public $webHost;
    public $defaultIMG;

    private function __construct(){
        $this->webHost = "http://".$_SERVER['HTTP_HOST']."/OpenFoodFact/Site";
        $this->defaultIMG = $this->webHost."/assets/img/default.jpg";

        include "./model/Connect.php";

        $this->bdd = Base::getInstance($this->webHost);
    }

    public static function getInstance(){
        if(is_null(self::$index))
            self::$index = new Main();
        return self::$index;
    }

    public function index(){ //page accueil, affichage par default
        $_GET['host'] = $this->webHost;
        include "view/include/head.html";
        include "view/include/header.php";

        $res = $this->bdd->queryArray("Select code, name, image_url from produits Order By last_change_date Limit 40;");

        foreach ($res as $row){
            $_POST['figures'][$row['code']]['src'] = ($row['image_url']==null ? $this->defaultIMG : $row['image_url']);
            $_POST['figures'][$row['code']]['alt'] = "Image ".substr($row['name'], 0, 14);
            $_POST['figures'][$row['code']]['legend'] = substr($row['name'], 0, 20);
        }

        include "./view/figure.php";

        include "./view/widget.php";

        include "./view/include/foot.html";
    }
}