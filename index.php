<?php
/**
 * Created by PhpStorm.
 * User: romai
 * Date: 11/12/2017
 * Time: 21:55
 */

include "./controller/main.php";

$main = Main::getInstance();

if (isset($_GET['action']) && !empty($_GET|'action')){
    switch ($_GET['action']){
        case "search":
            $main->search($_POST['research']);
            break;
        case "consult":
            $main->consult($_GET['num']);
            break;
        case "advsearch"://peut etre afficher le formulaire ou bien le resultat de la recherche
            $main->advsearch();
            break;
        case "add":
            $main->add();
            break;
        case "edit":
            if (isset($_GET['code']) && !empty($_GET|'code'))
                $main->edit($_GET['code']);
            else
                $main->add();
            break;
        case "myproducts":
            break;
        case "account": //parametre de compte (optionnel au projet)
            break;
        case "logout": //deconnection (optionnel au projet)
            break;
        case "login": //connection (optionnel au projet)
            break;
        case "signup": //inscription (optionnel au projet)
            break;
        default:
            $main->index();
    }
} else {
    $main->index();
}
