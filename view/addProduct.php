<?php
/*
 * User: Pelliesi
 * Date: 15/01/2018
 * Time: 11:10
*/
?>
<div>
    <?php
    $isedit=False;
    if (isset($_POST['product']) && !empty($_POST['product']))
        $isedit=True;
    ?>
<form method="post" action="<?php
if ($isedit)
    echo $_GET['host']."/index.php?action=edit&code=".$_POST['product']['code'];
else
    echo $_GET['host']."/index.php?action=add";
?>" id="addProduct">
    <?php
    if ($isedit)
        echo "<h1>Modification du Produit : " .$_POST['product']['code']."</h1>";
    else
        echo "<h1>Ajout d'un produit</h1>";
    ?>
    <div class="col" id="adderinfos">
        <?php if (!$isedit){ ?>
        <input id="code" name="code" type="number" placeholder="Code barre du produit" required />
        <?php } ?>
        <input id="name" name="name" type="text" placeholder="Nom du produit" <?php if ($isedit)
            echo (empty($_POST['product']['name']) ? "" : "value='".$_POST['product']['name']."'") ?>/>
        <input id="brands" name="brands" type="text" placeholder="Entreprise du produit" <?php if ($isedit) if (!empty
        ($_POST['product']['brands'])) echo "value='".$_POST['product']['brands']."'"; ?>/>
        <input id="image_url" name="image_url" type="url" placeholder="Url de l'image du produtit" maxlength="120"
                <?php if ($isedit) echo ($_POST['product']['image_url'] == null ? "" : "value='".$_POST['product']['image_url']."'")
                ?>/>
        <input id="image_ingredients_url" name="image_ingredients_url" type="url" placeholder="Url de l'image des ingrédients du produtit" maxlength="120"
            <?php if ($isedit) echo ($_POST['product']['image_ingredients_url'] == null ? "" : "value='".$_POST['product']['image_ingredients_url']."'") ?>/>
        <input id="servingsize" name="servingsize" type="text" placeholder="Quantité"  <?php if ($isedit)
            echo (empty($_POST['product']['servingsize']) || preg_match('/\s*/', $_POST['product']['servingsize'])
            == '' ? "indéfini" : "value='".$_POST['product']['servingsize']."'") ?>/>
        <textarea name="ingredients" rows="4" cols="50" placeholder="Ingrédients" required><?php if ($isedit) echo (empty
            ($_POST['product']['ingredients']) || preg_match('/\s*/',$_POST['product']['ingredients']) == '' ? "" : $_POST['product']['ingredients']) ?></textarea>
    </div>
    <hr/>

    <?php if (isset($_POST['countries']) && !empty($_POST['countries'])){ ?>
        <h2>Pays</h2>
        <h4 class="additifh4">Liste des pays</h4>
        <div id="addilist">
            <div id="firstAddi">
                <select id="countiresselect" class="inlineSelect">
                    <option value="none" selected>Choisir un pays</option>
                    <?php
                    foreach($_POST['countries'] as $val => $txt) {
                        if ($isedit){
                            $tmp=True;
                            foreach($_POST['prdCountries'] as $k => $v) if ($v['codeiso'] == $txt['code']) $tmp=False;
                            if ($tmp)
                                echo "<option value='".$txt['code']."'>".$txt['code']." ".$txt['alias']."</option>";
                        } else
                            echo "<option value='".$txt['code']."'>".$txt['code']." ".$txt['alias']."</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <input id="addAddi" class="adder" type="button" value="Ajouter ce Pays" onclick="addCountries()"/>
        <h4 class="additifh4">Les pays du produit</h4>
        <div id="addilist">
            <div id="countrieselected" class="col">
                <?php
                if ($isedit) {
                    foreach ($_POST['prdCountries'] as $val => $txt) {
                        echo "<input type='text' id='countries[".$txt['codeiso']."]' name='countries[".$txt['codeiso']."]' value='" .
                            $txt['codeiso']." ".$txt['alias']."' readonly /><input id='countries[".$txt['codeiso']."]2' class=\"adder\" type=\"button\" value=\"Retirer ce Pays\" onclick=\"removeCountries('countries[".$txt['codeiso']."]')\"/>";
                    }
                }
                ?>
            </div>
        </div>

        <hr/>
    <?php } ?>

    <?php if (isset($_POST['additives']) && !empty($_POST['additives'])){ ?>
        <h2>Additives</h2>
        <h4 class="additifh4">Liste des additifs</h4>
        <div id="addilist">
            <div id="firstAddi">
                <select id="additivesselect" class="inlineSelect">
                    <option value="none" selected>Choisir un critère</option>
                    <?php
                    foreach($_POST['additives'] as $val => $txt) {
                        if ($isedit){
                            $tmp=True;
                            foreach($_POST['prdAdditifs'] as $k => $v) if ($v['id'] == $txt['id']) $tmp=False;
                            if ($tmp)
                                echo "<option value='".$txt['id']."'>".$txt['id']." ".$txt['name']."</option>";
                        } else
                            echo "<option value='".$txt['id']."'>".$txt['id']." ".$txt['name']."</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <input id="addAddi" class="adder" type="button" value="Ajouter cet Additive" onclick="addAdditives()"/>
        <h4 class="additifh4">Les additifs du produit</h4>
        <div id="addilist">
            <div id="addiselected"  class="col">
                <?php
                if ($isedit && !empty($_POST['prdAdditifs'])) {
                    foreach ($_POST['prdAdditifs'] as $val => $txt) {
                        echo "<option name='additives[".$txt['id']."]' value='" . $txt['id'] . "'>" . $txt['id']
                            . " " .$txt['name'] . "</option>";
                        echo "<input type='text' id='additives[".$txt['id']."]' name='additives[".$txt['id']."]' value='" .
                            $txt['id']." ".$txt['name']."' readonly /><input id='additives[".$txt['id']."]2' class=\"adder\" type=\"button\" value=\"Retirer cet Additif\" onclick=\"removeAdditives('additives[".$txt['id']."]')\"/>";
                    }
                }
                ?>
            </div>
        </div>

        <hr/>
    <?php } ?>

    <h2>Ingrédients</h2>
    <div class="row">
        <div>
            <h4>Huile de Palme</h4>
            <div>
                <input type="radio" id="palm" value="true" name="frompalmoil" <?php if ($isedit && $_POST['product']['fromPalmOil'])
                    echo "checked"; ?>/>
                <label for="palm">Avec</label>
                <input type="radio" id="npalm" value="false" name="frompalmoil" <?php if ($isedit && !$_POST['product']['fromPalmOil'])
                    echo "checked"; ?>/>
                <label for="npalm">Sans</label>
            </div>
        </div>
    </div>

    <hr/>

    <?php if (isset($_POST['nutriments']) && !empty($_POST['nutriments'])){ ?>
        <h2>Nutriments</h2>
        <div id="nutlist">
            <div class="col" id="nutri">
                <?php
                foreach($_POST['nutriments'] as $val => $txt) {
                    if ($isedit && array_key_exists($txt, $_POST['prdNutrition'])){
                        echo "<div class='row'>$txt : <input type='number' name='valnut[$val]'
 value='".$_POST['prdNutrition'][$txt]."'/></div>";
                    } else
                        echo "<div class='row'>$txt : <input type='number' name='valnut[$val]' /></div>";
                }
                ?>
            </div>

        </div>
    <?php } ?>
    <input type='checkbox' id='isdrink' name='isdrink' /><label for='isdrink'>Ce produit est une boisson</label>

    <button id="addbtn" type="submit">Valider</button>
</form>
<script src="./assets/js/advSearch_method.js"></script>
</div>
