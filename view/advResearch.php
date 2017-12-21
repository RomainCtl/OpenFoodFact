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
<form method="post" role="search" action="<?php echo $_GET['host']."/index.php?action=advsearch" ?>" id="advResearch">

    <input id="advSearch" type="text" placeholder="Mots présents dans le nom du produit ou le code barre"
           name="research" required autofocus <?php if (isset($_POST['val']) && !empty($_POST['val'])) echo "value='".$_POST['val']."'" ?>/>

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
                <option value="get" selected>Contient</option>
                <option value="noget">Ne contient pas</option>
            </select>
            <input type="text" placeholder="Valeur" name="valcrit[]"/>
        </div>
    </div>
    <input id="addCrit" class="adder" type="button" value="Ajouter un Critère" onclick="addCrite()"/>

    <hr/>
    <?php } ?>

    <h2>Ingrédients</h2>
    <div class="row">
        <div>
            <h4>Additifs</h4>
            <div>
                <input type="radio" id="yes" value="true" name="additifs"/>
                <label for="yes">Avec</label>
                <input type="radio" id="no" value="false" name="additifs"/>
                <label for="no">Sans</label>
                <input type="radio" id="null" value="null" name="additifs" checked/>
                <label for="null">Indifférent</label>
            </div>
        </div>
        <div>
            <h4>Huile de Palme</h4>
            <div>
                <input type="radio" id="palm" value="true" name="palm"/>
                <label for="palm">Avec</label>
                <input type="radio" id="npalm" value="false" name="palm"/>
                <label for="npalm">Sans</label>
                <input type="radio" id="nullp" value="null" name="palm" checked/>
                <label for="nullp">Indifférent</label>
            </div>
        </div>
    </div>

    <hr/>

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
                    <option value="less" selected>&lt;</option>
                    <option value="lessequal">≤</option>
                    <option value="more">&gt;</option>
                    <option value="moreequal">≥</option>
                    <option value="equal">=</option>
                </select>
                <input type="number" placeholder="" name="valnut[]"/>
            </div>
        </div>
    <input id="addNut" class="adder" type="button" value="Ajouter un Nutriment" onclick="addNutr()"/>

    <hr/>
    <?php } ?>

    <label class="options" for="trie">Trier par :</label>
    <select class="options inlineSelect" name="trie">
        <option value="name" selected>Nom du produit</option>
        <option value="create_date">Date d'ajout</option>
        <option value="last_change_date">Date de dernière modification</option>
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
<script src="./assets/js/advSearch_method.js"></script>
</div>