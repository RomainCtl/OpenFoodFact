<?php
/**
 * Created by PhpStorm.
 * User: romai
 * Date: 11/12/2017
 * Time: 22:46
 */
 class Search{

     private $attr, $result, $bdd, $defaultIMG;

     public function __construct($bdd, $img){
         $this->attr = array("name", "code", "creator", "ingredients", "brands");
         $this->result = array();
         $this->bdd = $bdd;
         $this->defaultIMG = $img;
     }

     public function search($string){
         $this->result = array();

         $string = str_replace("+", " ", trim($string));
         $string = str_replace("\"", " ", $string);
         $string = str_replace(",", " ", $string);
         $string = str_replace(":", " ", $string);
         $string = str_replace(";", " ", $string);

         $tab = explode(" ", $string);

         foreach ($this->attr as $v) $this->get($tab, $v);

         $k=true;

         foreach($this->result as $a)
             if (!empty($a)) $k=false;

         if ($k)
             return array();
         else
             return $this->result;
     }

     private function get($tab, $v){
         $sql = "Select code, name, image_url from produits where LOWER($v) Like '%$tab[0]%' ";

         for($i=1 ; $i <count($tab) ; $i++) $sql .= "OR LOWER($v) Like '%$tab[$i]%' ";

         $sql .= "limit 100;";

         array_push($this->result, $this->bdd->queryArrayNoDouble($sql, 'code'));
     }
}