<?php
/*
 * User: Pelliesi
 * Date: 15/01/2018
 * Time: 11:10
*/
?>
<div>
<h1>Ajout d'un produit</h1>
<form method="post" role="add" action="<?php echo $_GET['host']."/index.php?action=add" ?>" id="addProduct">

    <?php
    $isedit=False;
    if (isset($_POST['product']) && !empty($_POST['product']))
        $isedit=True;
    ?>

    <div class="col" id="adderinfos">
        <input id="codeb" name="codeb" type="number" placeholder="Code barre du produit" required <?php if ($isedit)
            echo "value='".$_POST['product']['code']."'";
            ?>/>
        <input id="name" name="name" type="text" placeholder="Nom du produit" <?php if ($isedit)
            echo (empty($_POST['product']['servingsize']) || preg_match('/\s*/', $_POST['product']['servingsize'])
            == '' ? "indéfini" : "value='".$_POST['product']['servingsize']."'") ?>
        <input id="brand" name="brand" type="text" placeholder="Entreprise du produit" />
        <input id="imageurl" name="imageurl" type="url" placeholder="Url de l'image du produtit" maxlength="120"/>
        <input id="imageingrurl" name="imageingrurl" type="url" placeholder="Url de l'image des ingrédients du produtit" maxlength="120"/>
        <input id="servingsize" name="servingsize" type="text" placeholder="Quantité" />
        <textarea rows="4" cols="50" placeholder="Ingrédients" required></textarea>
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
            <div id="firstAddi">
                <select id="countrieselected" class="inlineSelect" name="countries[]">
                    <?php
                    if ($isedit) {
                        foreach ($_POST['prdCountries'] as $val => $txt) {
                            echo "<option value='" . $txt['codeiso'] . "'>" . $txt['codeiso'] . " " . $txt['alias'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <input id="removeAddi" class="adder" type="button" value="Retirer ce Pays" onclick="removeCountries()"/>

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
            <div id="firstAddi">
                <select id="addiselected" class="inlineSelect" name="additives[]">
                    <?php
                    if ($isedit) {
                        foreach ($_POST['prdAdditifs'] as $val => $txt) {
                            echo "<option value='" . $txt['id'] . "'>" . $txt['id'] . " " . $txt['name'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <input id="removeAddi" class="adder" type="button" value="Retirer cet Additive" onclick="removeAdditives()"/>

        <hr/>
    <?php } ?>

    <h2>Ingrédients</h2>
    <div class="row">
        <div>
            <h4>Additifs</h4>
            <div>
                <input type="radio" id="yes" value="true" name="additifs" <?php if ($isedit && $_POST['product']['nbadditives']
                    > 0) echo "checked"; ?>/>
                <label for="yes">Avec</label>
                <input type="radio" id="no" value="false" name="additifs" <?php if ($isedit && $_POST['product']['nbadditives']
                    == 0) echo "checked"; ?>/>
                <label for="no">Sans</label>
            </div>
        </div>
        <div>
            <h4>Huile de Palme</h4>
            <div>
                <input type="radio" id="palm" value="true" name="palm" <?php if ($isedit && $_POST['product']['fromPalmOil'])
                    echo "checked"; ?>/>
                <label for="palm">Avec</label>
                <input type="radio" id="npalm" value="false" name="palm" <?php if ($isedit && !$_POST['product']['fromPalmOil'])
                    echo "checked"; ?>/>
                <label for="npalm">Sans</label>
            </div>
        </div>
    </div>

    <hr/>

    <?php if (isset($_POST['nutriments']) && !empty($_POST['nutriments'])){ ?>
        <h2>Nutriments</h2>
        <div id="nutlist">
            <div id="nutri">
                <?php
                foreach($_POST['prdNutrition'] as $k => $v){
                    if (in_array($k, $_POST['nutriments']))
                        echo "$k : <input type='number' value='valnut[".$v."]'/>";
                }
                ?>
            </div>
            <div id="firstnut">
                <select id="nutrimselect" class="inlineSelect" name="nutriment[]">
                    <option value="none" selected>Choisir un critère</option>
                    <?php
                    foreach($_POST['nutriments'] as $val => $txt) {
                        echo "<option value='$val'>$txt</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
        <input id="addNut" class="adder" type="button" value="Ajouter un Nutriment" onclick="addNutr2()"/>
    <?php } ?>

    <button id="addbtn" type="submit">Valider</button>
</form>
<script src="./assets/js/advSearch_method.js"></script>
</div>
