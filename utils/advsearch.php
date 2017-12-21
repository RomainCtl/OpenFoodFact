<?php
/**
 * Created by PhpStorm.
 * User: romai
 * Date: 19/12/2017
 * Time: 20:25
 */

class Advsearch{

    private $ressearch, $keyWord, $result, $bdd, $defaultIMG;
    private $presql, $sql, $intersect;

    public function __construct($bdd, $img){
        $this->ressearch = array("name", "code");
        $this->keyWord = array(
            "get" => "Like",
            "noget" => "Not Like",
            "less" => "<",
            "lessequal" => "<=",
            "more" => ">",
            "moreequal" => ">=",
            "equal" => "="
        );
        $this->result = array();
        $this->bdd = $bdd;
        $this->defaultIMG = $img;
        $this->presql = "(Select p.code, p.name, image_url, create_date, last_change_date From produits as p ";
    }

    public function search(array $inf){
        $this->result = array();
        $this->sql = $this->presql."Where ";
        $this->intersect = array();

        $this->research($inf['research']);
        $this->critere($inf['critere'], $inf['critereOP'], $inf['valcrit']);
        $this->nutriment($inf['nutriment'], $inf['nutrimentOP'], $inf['valnut']);
        if ($inf['palm'] != 'null')
            $this->sql .= "And frompalmoil = '".$inf['palm']."' ";
        if ($inf['additifs'] != 'null') {
            if ($inf['additifs'] == 'true')
                $this->sql .= "And nbAdditives = 0";
            else
                $this->sql .= "And nbAdditives > 0";
        }
        $this->sql .= ")";

        foreach ($this->intersect as $n => $r)
            $this->sql .= " Intersect ".$r;

        $this->sql .= " Order by ".$inf['trie']." Limit ".$inf['nbresult'];

        $this->result = $this->bdd->queryArray($this->sql);

        $k=true;

        foreach($this->result as $a)
            if (!empty($a)) $k=false;

        if ($k)
            return array();
        else
            return $this->result;
    }

    private function research($inf){
        $inf = strtolower($inf);
        $inf = str_replace("+", " ", trim($inf));
        $inf = str_replace("\"", " ", $inf);
        $inf = str_replace(",", " ", $inf);
        $inf = str_replace(":", " ", $inf);
        $inf = str_replace(";", " ", $inf);

        $tab = explode(" ", $inf);

        $z = false;
        foreach ($tab as $a) foreach($this->ressearch as $b){
            if ($z) $this->sql .= "OR ";
            $this->sql .= "LOWER($b) Like '%$a%' ";
            $z=true;
        }
    }

    private function critere(array $names, array $f, array $vals){
        for ($i = 0 ; $i < count($names) ; $i++)
            if (!preg_match("/\\s/",  $vals[$i]) && $names[$i] != "none") {
                if ($names[$i] == 'additifs') $this->additives($f[$i], $vals[$i]);
                else if ($names[$i] == 'countries') $this->countires($f[$i], $vals[$i]);
                else $this->sql .= "And LOWER(" . $names[$i] . ") " . $this->keyWord[$f[$i]] . " '%" . $vals[$i] . "%' ";
            }
    }

    private function additives($f, $val){
        $this->intersect = array_merge($this->intersect, array($this->presql." Inner Join additives_produit as a On p.code = a.code Where a.id ".$this->keyWord[$f]." '%$val%' Or a.name ".$this->keyWord[$f]." '%$val%') "));
    }

    private function countires($f, $val){
        $this->intersect = array_merge($this->intersect, array($this->presql." Inner Join origne_produit as o On p.code = o.code Where o.codeiso ".$this->keyWord[$f]." '%$val%' Or o.alias ".$this->keyWord[$f]." '%$val%') "));
    }

    private function nutriment(array $names, array $f, array $vals){
        for ($i = 0 ; $i < count($names) ; $i++)
            if (!preg_match("/\\s/",  $vals[$i]) && $names[$i] != "none")
                $this->sql .= "And " . $names[$i] . " " .$this->keyWord[$f[$i]] . " " . $vals[$i] . " ";
    }
}

/*
 * requete modele
 *


(select code, name, image_url, create_date, last_change_date from produits where frompalmoil='true' and brands is not null and creator not Like '%usda-ndb-import%' and energy < 1000)
Intersect
(select p.code, p.name, image_url, create_date, last_change_date
from produits as p
inner join origne_produit as o
on p.code = o.code
where codeiso Like '%US%')
intersect
(select p.code, p.name, image_url, create_date, last_change_date
from produits as p
inner join additives_produit as a
on p.code = a.code
where a.id like '%E100%')
order by create_date
limit 20;


(select code, name, image_url, create_date, last_change_date from produits where frompalmoil='true')
Intersect
(select code, name, image_url, create_date, last_change_date from produits where brands is not null)
Intersect
(select code, name, image_url, create_date, last_change_date from produits where creator not Like '%usda-ndb-import%')
Intersect
(select code, name, image_url, create_date, last_change_date from produits where energy < 1000)
Intersect
(select p.code, p.name, image_url, create_date, last_change_date
from produits as p
inner join origne_produit as o
on p.code = o.code
where codeiso Like '%US%')
intersect
(select p.code, p.name, image_url, create_date, last_change_date
from produits as p
inner join additives_produit as a
on p.code = a.code
where a.id like '%E100%')
order by create_date
limit 20;


(select * from produits where frompalmoil='true')
Intersect
(select * from produits where brands is not null)
Intersect
(select * from produits where creator not Like '%usda-ndb-import%')
Intersect
(select * from produits where energy < 1000)
Intersect
(select p.code, create_date, last_change_date, name, creator, brands, image_url, image_ingredients_url, ingredients, servingsize, nutritiongrade, energy, fat, satured_fat, trans_fat, cholesterol, carbohydrates, sugars, fiber, proteins, salt, sodium, vitamina, vitaminc, calcium, iron, nutrition_score, frompalmoil
from produits as p
inner join origne_produit as o
on p.code = o.code
where codeiso Like '%US%')
intersect
(select p.code, create_date, last_change_date, p.name, creator, brands, image_url, image_ingredients_url, ingredients, servingsize, nutritiongrade, energy, fat, satured_fat, trans_fat, cholesterol, carbohydrates, sugars, fiber, proteins, salt, sodium, vitamina, vitaminc, calcium, iron, nutrition_score, frompalmoil
from produits as p
inner join additives_produit as a
on p.code = a.code
where a.id='E100')
order by create_date
limit 20;


 */