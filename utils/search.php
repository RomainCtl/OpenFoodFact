<?php
/**
 * Created by PhpStorm.
 * User: romai
 * Date: 11/12/2017
 * Time: 22:46
 */
 class Search{

     private $attr, $result, $bdd;

     public function __construct($bdd){
        $this->attr = array("produits" => array("name", "code", "creator", "ingredients", "brands"));
        $this->result = array("produits" => array());
        $this->bdd = $bdd;
     }

     public function search($string){
         $string = str_replace("+", " ", trim($string));
         $string = str_replace("\"", " ", $string);
         $string = str_replace(",", " ", $string);
         $string = str_replace(":", " ", $string);
         $string = str_replace(";", " ", $string);

         $tab = explode(" ", $string);

         foreach ($this->attr as $k => $v){
             if (gettype($v) == "array")
                 foreach($v as $vv)
                     self::get($tab, $k, $vv);
             else
                 self::get($tab, $k, $v);
         }
     }

     private function get($tab, $k, $v){
         $sql = "Select * from $k where LOWER($v) Like '%$tab[0]%' ";

         for($i=1 ; $i <count($tab) ; $i++) $sql .= "OR LOWER($v) Like '%$tab[$i]%' ";

         $sql .= "limit 100;";

         $this->result[$k] = $this->bdd->queryArrayNoDouble($sql, 'code');
     }
}