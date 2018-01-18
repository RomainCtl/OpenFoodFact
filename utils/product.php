<?php
/**
 * Created by PhpStorm.
 * User: romai
 * Date: 11/12/2017
 * Time: 22:51
 */

class Produit{
    public $product, $nutrition, $additifs, $countries;
    public $bdd;

    public function __construct($bdd, array $info, array $countries=null, array $additifs=null){
        $this->bdd = $bdd;
        $this->product = array(
            "code"=> $info['code'],
            "create_date" => $info['create_date'],
            "last_change_date" => $info['last_change_date'],
            "name" => $info['name'],
            "creator" => $info['creator'],
            "brands" => $info['brands'],
            "image_url" => $info['image_url'],
            "image_ingredients_url" => $info['image_ingredients_url'],
            "servingsize" => $info['servingsize'],
            "ingredients" => $info['ingredients'],
            "nutritiongrade" => $info['nutritiongrade'],
            "fromPalmOil" => $info['frompalmoil'],
            "nbadditives" => $info['nbadditives']
        );
        $this->nutrition = array(
            "Energie" => $info['energy'],
            "Gras" => $info['fat'],
            "Gras saturé" => $info['satured_fat'],
            "Gras trans" => $info['trans_fat'],
            "Cholesterol" => $info['cholesterol'],
            "Carbohydrates" => $info['carbohydrates'],
            "Sucre" => $info['sugars'],
            "Fibre" => $info['fiber'],
            "Proteine" => $info['proteins'],
            "Sel" => $info['salt'],
            "Sodium" => $info['sodium'],
            "Vitamin A" => $info['vitamina'],
            "Vitamin C" => $info['vitaminc'],
            "Calcium" => $info['calcium'],
            "Fer" => $info['iron'],
            "Score nutritionnel" => $info['nutrition_score']
        );
        if ($countries == null) $this->countries = $this->setCountries();
        else $this->countries = $countries;

        if ($additifs == null) $this->additifs = $this->setAdditifs();
        else $this->additifs = $additifs;
    }

    public function add($realname){
        $nutri = array();
        foreach($this->nutrition as $k=>$v)
            $nutri[array_search($k, $realname)] = $v;

        $prod = $this->product;
        unset($prod['create_date']);
        unset($prod['last_change_date']);
        unset($prod['nbadditives']);
        unset($prod['fromPalmOil']);
        $prod['frompalmoil'] = $this->product['fromPalmOil'];

        //inserer le produit
        if (!$this->bdd->insertProduct(array_merge($prod, $nutri))) return false;
        //les additifs
        if ($this->additifs != null)
            if (!$this->bdd->insertAdditives($this->additifs, $this->product["code"])) return false;
        //les countries
        if ($this->countries != null)
            if (!$this->bdd->insertCountries($this->countries, $this->product['code'])) return false;
        return true;
    }

    public function update($realname){
        $nutri = array();
        foreach($this->nutrition as $k=>$v)
            $nutri[array_search($k, $realname)] = $v;

        $prod = $this->product;
        unset($prod['create_date']);
        unset($prod['last_change_date']);
        unset($prod['nbadditives']);
        unset($prod['fromPalmOil']);
        $prod['frompalmoil'] = $this->product['fromPalmOil'];

        //inserer le produit
        if (!$this->bdd->updateProduct(array_merge($prod, $nutri))) return false;
        //les additifs
        if ($this->additifs != null)
            if (!$this->bdd->updateAdditifs($this->additifs, $this->product['code'])) return false;
        //les countries
        if ($this->countries != null)
            if (!$this->bdd->updateCountries($this->countries, $this->product['code'])) return false;
        return true;
    }

    private function setAdditifs(){
        $tmp = $this->bdd->queryArray("Select * from additives_produit Where code='".$this->product['code']."';");
        if (empty($tmp)) return null;
        else return $tmp;
    }

    private function setCountries(){
        $tmp = $this->bdd->queryArray("Select * from origne_produit Where code='".$this->product['code']."';");
        if (empty($tmp)) return null;
        else return $tmp;
    }

    public static function withCode($bdd, $code){
        return new self($bdd, $bdd->queryArray("Select * from produits where code='".$code."'")[0]);
    }

    public function getProduct(){
        return $this->product;
    }

    public function getNutrition(){
        return $this->nutrition;
    }

    public function getAdditifs(){
        return $this->additifs;
    }

    public function getCountries(){
        return $this->countries;
    }
}