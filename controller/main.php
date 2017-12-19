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
    public $prodconf;

    private function __construct(){
        $this->webHost = "http://".$_SERVER['HTTP_HOST']."/OpenFoodFact/Site";
        $this->defaultIMG = $this->webHost."/assets/img/default.jpg";
        $this->defaultLimit = 40;

        include "./model/Connect.php";

        $this->bdd = Base::getInstance($this->webHost);

        $content = file_get_contents($this->webHost."/config/norme.json");
        $this->prodconf = json_decode($content, true);
    }

    public static function getInstance(){
        if(is_null(self::$index))
            self::$index = new Main();
        return self::$index;
    }

    public function index(){ //page accueil, affichage par default
        $res = $this->bdd->queryArray("Select code, name, image_url from produits Order By last_change_date Limit "
            .$this->defaultLimit);

        foreach ($res as $row){
            $_POST['figures'][$row['code']]['src'] = ($row['image_url']==null ? $this->defaultIMG : $row['image_url']);
            $_POST['figures'][$row['code']]['alt'] = "Image ".substr($row['name'], 0, 14);
            $_POST['figures'][$row['code']]['legend'] = substr($row['name'], 0, 20);
        }

        $this->viewWithInclude("./view/figure.php");
    }

    public function consult($code){
        include "./utils/product.php";
        $produit = Produit::withCode($this->bdd, $code);

        $_POST['defaultIMG'] = $this->defaultIMG;
        $_POST['prodconf'] = $this->prodconf;
        $_POST['product'] = $produit->getProduct();
        $_POST['nutrition'] = $produit->getNutrition();
        $_POST['additifs'] = $produit->getAdditifs();
        $_POST['countries'] = $produit->getCountries();

        $this->viewWithInclude("./view/product.php");
    }

    public function search($string){
        include "./utils/search.php";
        $search = new Search($this->bdd, $this->defaultIMG);
        $res = $search->search(strtolower($string));
        $_POST['search'] = $string;

        if (empty($res)) $this->viewWithInclude("./view/include/noresult.php");
        else {
            foreach ($res as $row) {
                foreach($row as $r) {
                    $_POST['figures'][$r['code']]['src'] = (!isset($r['image_url']) && empty($r['image_url']) ?
                        $this->defaultIMG : $r['image_url']);
                    $_POST['figures'][$r['code']]['alt'] = "Image " . substr($r['name'], 0, 14);
                    $_POST['figures'][$r['code']]['legend'] = substr($r['name'], 0, 20);
                }
            }
            $this->viewWithInclude("./view/figure.php");
        }
    }

    private function viewWithInclude($path){
        $_GET['host'] = $this->webHost;
        include "view/include/head.html";
        include "view/include/header.php";

        include $path;

        include "./view/widget.php";
        include "./view/include/foot.html";
    }
}