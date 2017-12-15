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
    public $defaultLimit;

    private function __construct(){
        $this->webHost = "http://".$_SERVER['HTTP_HOST']."/OpenFoodFact/Site";
        $this->defaultIMG = $this->webHost."/assets/img/default.jpg";
        $this->defaultLimit = 40;

        include "./model/Connect.php";

        $this->bdd = Base::getInstance($this->webHost);
    }

    public static function getInstance(){
        if(is_null(self::$index))
            self::$index = new Main();
        return self::$index;
    }

    public function index(){ //page accueil, affichage par default
        $this->headView();
        $_GET['host'] = $this->webHost;

        $res = $this->bdd->queryArray("Select code, name, image_url from produits Order By last_change_date Limit "
            .$this->defaultLimit);

        foreach ($res as $row){
            $_POST['figures'][$row['code']]['src'] = ($row['image_url']==null ? $this->defaultIMG : $row['image_url']);
            $_POST['figures'][$row['code']]['alt'] = "Image ".substr($row['name'], 0, 14);
            $_POST['figures'][$row['code']]['legend'] = substr($row['name'], 0, 20);
        }

        include "./view/figure.php";
        $this->footView();
    }

    public function consult($code){
        $this->headView();
        $_GET['host'] = $this->webHost;

        $res = $this->bdd->queryArray("Select * from produits Where code='".$code."';");

        include "./view/product.php";
        $this->footView();
    }

    public function search($string){
        $this->headView();
        $_GET['host'] = $this->webHost;

        include "./utils/search.php";
        $search = new Search($this->bdd);
        $res = $search->search(strtolower($string));

        if (empty($res)) include "./view/include/noresult.php";
        else {
            foreach ($res as $row) {
                foreach($row as $r) {
                    $_POST['figures'][$r['code']]['src'] = (!isset($r['image_url']) && empty($r['image_url']) ?
                        $this->defaultIMG : $r['image_url']);
                    $_POST['figures'][$r['code']]['alt'] = "Image " . substr($r['name'], 0, 14);
                    $_POST['figures'][$r['code']]['legend'] = substr($r['name'], 0, 20);
                }
            }

            include "./view/figure.php";
        }
        $this->footView();
    }

    private function headView(){
        $_GET['host'] = $this->webHost;
        include "view/include/head.html";
        include "view/include/header.php";
    }

    private function footView(){
        include "./view/widget.php";
        include "./view/include/foot.html";
    }
}