<?php
/**
 * Created by PhpStorm.
 * User: romai
 * Date: 11/12/2017
 * Time: 22:49
 */

class Main{
    public $bdd;
    private static $index = null;

    private function __construct(){
        include "../model/Connect.php";

        $this->bdd = Connect::getInstance();
    }

    public static function getInstance(){
        if(is_null(self::$index))
            self::$index = new Main();
        return self::$index;
    }
}