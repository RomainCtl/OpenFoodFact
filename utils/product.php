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

    public function __construct($bdd, array $info = array()){
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
            "fromPalmOil" => $info['frompalmoil']
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
        $this->additifs = $this->setAdditifs();
        $this->countries = $this->setCountries();
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