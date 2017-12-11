<?php
/**
 * Created by PhpStorm.
 * User: romai
 * Date: 11/12/2017
 * Time: 23:21
 */

class User{

    public $login, $nom, $prenom, $mail, $pays, $date_creation;

    public function __construct(array $info = array()){
        $this->login = $info['login'];
        $this->nom = $info['nom'];
        $this->prenom = $info['prenom'];
        $this->mail = $info['mail'];
        $this->pays = $info['pays'];
        $this->date_creation = $info['date_creation'];
    }

    public static function withLogin($bdd, $login){
        $res = $bdd->query("Select login, nom, prenom, mail, alias as pays, dat_creation from users inner join countries on code=code_pays where 
login='".$login."'");

        $result = array();

        if ($res)
            while ($row = $res->fetch(PDO::FETCH_ASSOC))
                array_push($result, $row);

        return new self($result);
    }
}