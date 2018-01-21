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
    public $prodconf, $advsearchconf, $nutriscoreconf;

    private function __construct(){
        //$this->webHost = "http://".$_SERVER['HTTP_HOST'];
        $this->webHost = "http://".$_SERVER['HTTP_HOST']."/OpenFoodFact/Site";
        $this->defaultIMG = $this->webHost."/assets/img/default.jpg";
        $this->defaultLimit = 40;

        include "./model/Connect.php";

        $this->bdd = Base::getInstance($this->webHost);

        $content = file_get_contents($this->webHost."/config/norme.json");
        $this->prodconf = json_decode($content, true);

        $content = file_get_contents($this->webHost."/config/research.json");
        $this->advsearchconf = json_decode($content, true);

        $content = file_get_contents($this->webHost."/config/normeNutriScore.json");
        $this->nutriscoreconf = json_decode($content, true);
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
        if (!class_exists("Produit")) include "./utils/product.php";
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

    public function advsearch(){
        if (isset($_POST['research'])){
            include "./utils/advsearch.php";
            $advsearch = new Advsearch($this->bdd, $this->defaultIMG); //, $this->advsearchconf);

            $res = $advsearch->search($_POST);

            if (empty($res)) $this->viewWithInclude("./view/include/noresult.php");
            else {
                foreach($res as $r) {
                    $_POST['figures'][$r['code']]['src'] = (!isset($r['image_url']) && empty($r['image_url']) ?
                        $this->defaultIMG : $r['image_url']);
                    $_POST['figures'][$r['code']]['alt'] = "Image " . substr($r['name'], 0, 14);
                    $_POST['figures'][$r['code']]['legend'] = substr($r['name'], 0, 20);
                }
                $this->viewWithInclude("./view/figure.php");
            }
        } else {
            $_POST['criteres'] = $this->advsearchconf['criteres'];
            $_POST['nutriments'] = $this->advsearchconf['nutriments'];

            $this->viewWithInclude("./view/advResearch.php");
        }
    }

    public function add(){
        if (isset($_POST['code'])){
            if ($this->toProduct($_POST, true)) {
                $_POST['msg'] = "<div class='success'><p>Produit ajouté avec succès !</p></div>";
                $this->consult($_POST['code']);
            } else {
                $_POST['msg'] = "<div class='echec'><p>Echec de l'ajout du produit !</p></div>";
                $this->index();
            }
        } else {
            $_POST['criteres'] = $this->advsearchconf['criteres'];
            $_POST['nutriments'] = $this->advsearchconf['nutriments'];
            $_POST['additives'] = $this->bdd->getAdditives();
            $_POST['countries'] = $this->bdd->getCountries();

            $this->viewWithInclude("./view/addProduct.php");
        }
    }

    public function edit($code){
        if (isset($_POST['ingredients'])){
            $_POST['code'] = $code;
            if ($this->toProduct($_POST, false)){
                $_POST['msg'] = "<div class='success'><p>Produit modifié avec succès !</p></div>";
                $this->consult($_POST['code']);
            } else {
                $_POST['msg'] = "<div class='echec'><p>Echec de la modification du produit !</p></div>";
                $this->consult($_POST['code']);
            }
        } else {
            $_POST['criteres'] = $this->advsearchconf['criteres'];
            $_POST['nutriments'] = $this->advsearchconf['nutriments'];
            $_POST['additives'] = $this->bdd->getAdditives();
            $_POST['countries'] = $this->bdd->getCountries();

            include "./utils/product.php";
            $produit = Produit::withCode($this->bdd, $code);

            $_POST['product'] = $produit->getProduct();
            $_POST['prdNutrition'] = $produit->getNutrition();
            $_POST['prdAdditifs'] = $produit->getAdditifs();
            $_POST['prdCountries'] = $produit->getCountries();

            $this->viewWithInclude("./view/addProduct.php");
        }
    }
    private function toProduct($post, $add){
        $infos = array();
        $countries = null;
        $additifs = null;

        $infos['nutritiongrade'] = null;
        $infos['create_date'] = null; //ne sert a rien mais obligatoire pour l'initialisation d'un produit
        $infos['last_change_date'] = null; //ne sert a rien mais obligatoire pour l'initialisation d'un produit
        $infos['creator'] = "openfoodfacts-contributors"; //le creator sera l'identifiant de l'utilisateur qui
        // ajoute le produit (c'est une valeur par default pour le moment)

        //nutrition grade
        if ($post['valnut']['nutrition_score'] != ''){
            $tmp = $this->calcNutriScore(intval($post['valnut']['nutrition_score']), (isset($post['isdrink']) ?
                true : false));
            $infos['nutritiongrade'] = $tmp;
            if (isset($post['isdrink'])) unset($post['isdrink']);
        } else {
            if (isset($post['isdrink'])) unset($post['isdrink']);
        }

        //valeur nutritionnel
        foreach ($post['valnut'] as $k=>$v){
            if ($v == '') $infos[$k]=0;
            else $infos[$k]=intval($v);
        }
        unset($post['valnut']);

        //countries
        if (isset($post['countries'])){
            foreach($post['countries'] as $k=>$v)
                $countries[$k] = $v;
            unset($post['countries']);
        }

        $nbadditif = 0;
        //additifs
        if (isset($post['additives'])){
            foreach($post['additives'] as $k=>$v) {
                $additifs[$k] = $v;
                $nbadditif++;
            }
            unset($post['additives']);
        }
        $infos['nbadditives'] = $nbadditif;

        foreach($post as $k=>$v){
            $infos[$k] = $v;
        }

        include "./utils/product.php";
        $prod = new Produit($this->bdd, $infos, $countries, $additifs);

        if ($add){
            return $prod->add($this->advsearchconf['nutriments']);
        } else {
            return $prod->update($this->advsearchconf['nutriments']);
        }
    }

    //return le nutri grade en fonction du nutriscore
    private function calcNutriScore($score, $isdrink){
        $cat = "eat";
        if ($isdrink) $cat = "drink";

        foreach ($this->nutriscoreconf[$cat] as $k=>$inter){
            $a=$inter[0];
            $b=$inter[1];

            if ($a == "x" && $score <= $b) return $k;
            else if ($b == "x" && $score >= $a) return $k;
            else { if ($score <= $b && $score >= $a) return $k;}
        }
        return null;
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