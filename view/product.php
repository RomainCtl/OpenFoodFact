<?php
/**
 * Created by PhpStorm.
 * User: romai
 * Date: 14/12/2017
 * Time: 22:29
 */

?>
<div class="col" id="consult">
    <div class="row">
        <div class="col">
            <h1><?php echo $_POST['product']['name'] ?></h1>
            <p>Code barre : <?php echo $_POST['product']['code'] ?></p>
            <p>Quantité : <?php echo (empty($_POST['product']['servingsize']) || preg_match('/\s*/',
                        $_POST['product']['servingsize']) == '' ? "indéfini" : $_POST['product']['servingsize']) ?></p>
            <p>Pays de ventes : <?php
                if (empty($_POST['countries'])) echo "inconnu";
                else foreach($_POST['countries'] as $p) echo $p['alias']." ";
                ?></p>
        </div>

        <img src="<?php echo ($_POST['product']['image_url'] == null ? $_POST['defaultIMG'] : $_POST['product']['image_url']) ?>" alt='Image Produit' width='200'/>
    </div>

    <div class='row'>
        <div class="col">
            <h2>Ingredients</h2>
            <p><?php echo (empty($_POST['product']['ingredients']) || preg_match('/\s*/',$_POST['product']['ingredients']) == '' ? "indéfini" : $_POST['product']['ingredients']) ?></p>
        </div>

        <img src="<?php echo ($_POST['product']['image_ingredients_url'] == null ? $_POST['defaultIMG'] : $_POST['product']['image_ingredients_url']) ?>" alt='Image Ingrédients du produit' width='200'/>
    </div>
    <div>
        <?php if (!empty($_POST['additifs'])) { ?>
        <h3>Additifs</h3>
        <ul>
            <?php
            foreach ($_POST['additifs'] as $k => $a)
                echo "<li>".$a['id']." - ".$a['name']."</li>";
            ?>
        </ul>
        <?php }
        if ($_POST['product']['fromPalmOil'])
            echo "<h3>Ce produit peut contenir des ingrédients issus de l'huile de palme</h3>";
        ?>
    </div>

    <h2>Informations nutritionnelles</h2>

    <?php if (preg_match('/(a|b|c|d|e)/', $_POST['product']['nutritiongrade'])){ ?>
    <h3>Nutri-score</h3>
    <div id="nutgrade">
        <?php
        $var = array(
            'a' => "#03870d",
            'b' => "#82c023",
            'c' => "#D6D600",
            'd' => "#CF8600",
            'e' => "#ad0000"
        );

        foreach($var as $l => $c){
            echo "<p style='background-color:$c'".(preg_match("/$l/", $_POST['product']['nutritiongrade'])
                ? "class='active'" : "").">$l</p>";
        }
        ?>
    </div>
    <?php } ?>
    <h3>Repères nutritionnels pour 100g</h3>
    <div id="repnutri">
    <?php
    $rep = array(
        "Gras" => "Matières grasses / Lipides",
        "Gras saturé" => "Acide gras saturés",
        "Sucre" => "Sucres",
        "Sel" => "Sel"
    );
    foreach($rep as $r => $v){
        if (empty($_POST['nutrition'][$r]))
            echo "<div><div class='faible'></div>0 g <strong>&nbsp;$v&nbsp;</strong> en faible quantité</div>";
        else if ($_POST['nutrition'][$r] < $_POST['prodconf'][$v][0])
            echo "<div><div class='faible'></div>".$_POST['nutrition'][$r]." <strong>&nbsp;$v&nbsp;</strong> en faible quantité</div>";
        else if ($_POST['nutrition'][$r] > $_POST['prodconf'][$v][1])
            echo "<div><div class='moyen'></div>".$_POST['nutrition'][$r]." <strong>&nbsp;$v&nbsp;</strong> en quantité modérée</div>";
        else
            echo "<div><div class='fort'></div>".$_POST['nutrition'][$r]." <strong>&nbsp;$v&nbsp;</strong> en quantité élevée</div>";
    }
    ?>
    </div>

    <table id="infnutri">
        <tr>
            <th>Informations nutritionnelles</th>
            <th>pour 100g / 100ml</th>
        </tr>
        <?php
        foreach($_POST['nutrition'] as $k => $v){
            echo "<tr>";
            echo "<td>$k</td>";
            echo "<td>$v&nbsp;".($k != 'Score nutritionnel' ? "g" : "")."</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <p>Produit ajouté le <?php echo $_POST['product']['create_date'].(empty($_POST['product']['creator']) ? "" : " par ".$_POST['product']['creator'])."\n" ?>.</p>
    <?php echo (empty($_POST['product']['last_change_date']) ? "" : "<p>Dernière modification de la page le ".$_POST['product']['last_change_date'].".</p>")."\n" ?>

</div>