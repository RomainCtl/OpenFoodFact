<?php
/**
 * Created by PhpStorm.
 * User: romai
 * Date: 11/12/2017
 * Time: 22:51
 */

class Produit{
    public $code, $create_date, $last_change_date, $name, $creator, $brands, $image_url,
$image_ingredients_url, $ingredients, $servingSize;
    public $nutritiongrade, $energy, $fat, $satured_fat, $trans_fat, $cholesterol, $carbohydrates, $sugars, $fiber,
$proteins, $salt, $sodium, $vitamina, $vitaminc, $calcium, $iron, $nutrition_score;
    public $fromPalmOil;

    public function __construct(array $info = array()){
        $this->code = $info['code'];
        $this->create_date = $info['create_date'];
        $this->last_change_date = $info['last_change_date'];
        $this->name = $info['name'];
        $this->creator = $info['creator'];
        $this->brands = $info['brands'];
        $this->image_url = $info['image_url'];
        $this->image_ingredients_url = $info['image_ingredients_url'];
        $this->ingredients = $info['ingredients'];
        $this->nutritiongrade = $info['nutritiongrade'];
        $this->energy = $info['energy'];
        $this->fat = $info['fat'];
        $this->satured_fat = $info['satured_fat'];
        $this->trans_fat = $info['trans_fat'];
        $this->cholesterol = $info['cholesterol'];
        $this->carbohydrates = $info['carbohydrates'];
        $this->sugars = $info['sugars'];
        $this->fiber = $info['fiber'];
        $this->proteins = $info['proteins'];
        $this->salt = $info['salt'];
        $this->sodium = $info['sodium'];
        $this->vitamina = $info['vitamin&'];
        $this->vitaminc = $info['vitaminc'];
        $this->calcium = $info['calcium'];
        $this->iron = $info['iron'];
        $this->fromPalmOil = $info['fromPalmOil'];
    }

    public static function withCode($bdd, $code){
        return new self($bdd->queryArray("Select * from produits where code='".$code."'"));
    }
}