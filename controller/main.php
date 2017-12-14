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

    private function __construct(){
        include "../model/Connect.php";

        $this->bdd = Base::getInstance();
    }

    public static function getInstance(){
        if(is_null(self::$index))
            self::$index = new Main();
        return self::$index;
    }

    public function index(){ //page accueil, affichage par default

    }
}