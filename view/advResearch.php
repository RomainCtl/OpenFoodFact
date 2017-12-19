<?php
/**
 * Created by PhpStorm.
 * User: romai
 * Date: 14/12/2017
 * Time: 17:34
 */

?>
<div>
<h1>Recherche Avancée</h1>
<form method="post" role="search" action="search.php" id="advResearch">

    <input id="advSearch" type="text" placeholder="Mots présents dans le nom du produit, le nom générique, les
    marques ou ingredients" name="research" required autofocus <?php if (isset($_POST['val']) && !empty
    ($_POST['val'])) echo "value='".$_POST['val']."'" ?>>


    // Les Critères

    <?php if (isset($_POST['criteres']) && !empty($_POST['criteres'])){ ?>
    <h2>Critères</h2>
    <div id="critlist">
        <div id="firstcrit">
            <select class="inlineSelect" name="critere[]">
                <option value="none" selected>Choisir un critère</option>
                <?php
                foreach($_POST['criteres'] as $val => $txt) {
                    echo "<option value='$val'>$txt</option>";
                }
                ?>
            </select>
            <select class="inlineSelect" name="critereOP[]">
                <option value="none" selected>Contient</option>
                <option value="france">Ne contient pas</option>
            </select>
            <input type="text" placeholder="Valeur" name="val[]"/>
        </div>
    </div>
    <input id="addCrit" class="adder" type="button" value="Ajouter un Critère" onclick="addCrite()"/>

    <hr/>
    <?php } ?>


    //Les options d'ingrédients

    <h2>Ingrédients</h2>
    <div class="row">
        <div>
            <h4>Additifs</h4>
            <div>
                <input type="radio" id="yes" value="yes" name="additifs"/>
                <label for="yes">Avec</label>
                <input type="radio" id="no" value="no" name="additifs"/>
                <label for="no">Sans</label>
                <input type="radio" id="null" value="null" name="additifs" checked/>
                <label for="null">Indifférent</label>
            </div>
        </div>
        <div>
            <h4>Huile de Palme</h4>
            <div>
                <input type="radio" id="palm" value="yes" name="palm"/>
                <label for="palm">Avec</label>
                <input type="radio" id="npalm" value="no" name="palm"/>
                <label for="npalm">Sans</label>
                <input type="radio" id="nullp" value="null" name="palm" checked/>
                <label for="nullp">Indifférent</label>
            </div>
        </div>
    </div>

    <hr/>


    // Les Nutriments

    <?php if (isset($_POST['nutriments']) && !empty($_POST['nutriments'])){ ?>
        <h2>Nutriments</h2>
        <div id="nutlist">
            <div id="firstnut">
                <select class="inlineSelect" name="nutriment[]">
                    <option value="none" selected>Choisir un critère</option>
                    <?php
                    foreach($_POST['nutriments'] as $val => $txt) {
                        echo "<option value='$val'>$txt</option>";
                    }
                    ?>
                </select>
                <select class="inlineSelect" name="nutrimentOP[]">
                    <option value="none" selected>&lt;</option>
                    <option value="france">≤</option>
                    <option value="france">&gt;</option>
                    <option value="france">≥</option>
                    <option value="france">=</option>
                </select>
                <input type="number" placeholder="" name="val"/>
            </div>
        </div>
    <input id="addNut" class="adder" type="button" value="Ajouter un Nutriment" onclick="addNutr()"/>

    <hr/>
    <?php } ?>


    //Autres options

    <label class="options" for="trie">Trier par :</label>
    <select class="options inlineSelect" name="trie">
        <option value="name" selected>Nom du produit</option>
        <option value="dateA">Date d'ajout</option>
        <option value="dateM">Date de dernière modification</option>
    </select>

    <label class="options" for="nbresult">Resultat par page :</label>
    <select class="options inlineSelect" name="nbresult">
        <option value="20" selected>20</option>
        <option value="40">40</option>
        <option value="100">100</option>
        <option value="500">500</option>
        <option value="1000">1000</option>
    </select>
    <br/>

    <button id="advsearchbtn" type="submit">Rechercher</button>

</form>
<script src="../assets/js/advSearch_method.js"></script>