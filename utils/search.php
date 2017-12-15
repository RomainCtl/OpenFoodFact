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
         $this->attr = array("name", "code", "creator", "ingredients", "brands");
         $this->result = array();
         $this->bdd = $bdd;
     }

     public function search($string){
         $this->result = array();

         $string = str_replace("+", " ", trim($string));
         $string = str_replace("\"", " ", $string);
         $string = str_replace(",", " ", $string);
         $string = str_replace(":", " ", $string);
         $string = str_replace(";", " ", $string);

         $tab = explode(" ", $string);

         foreach ($this->attr as $v){
             $this->get($tab, $v);
         }

         foreach ($this->result as $row) {
             foreach($row as $r) {
                 $_POST['figures'][$r['code']]['src'] = (!isset($r['image_url']) && empty($r['image_url']) ?
                     $this->defaultIMG : $r['image_url']);
                 $_POST['figures'][$r['code']]['alt'] = "Image " . substr($r['name'], 0, 14);
                 $_POST['figures'][$r['code']]['legend'] = substr($r['name'], 0, 20);
             }
         }
         return $this->result;
     }

     private function get($tab, $v){
         $sql = "Select code, name, image_url from produits where LOWER($v) Like '%$tab[0]%' ";

         for($i=1 ; $i <count($tab) ; $i++) $sql .= "OR LOWER($v) Like '%$tab[$i]%' ";

         $sql .= "limit 100;";

         array_push($this->result, $this->bdd->queryArrayNoDouble($sql, 'code'));
     }
}