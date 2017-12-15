<?php
/**
 * Created by PhpStorm.
 * User: romai
 * Date: 11/12/2017
 * Time: 20:02
 */
?>
<header>
    <nav id="navbar">
        <a href="<?php echo $_GET['host'] ?>">
            <img id="nav_img" src="assets/img/openFoodFactLogo_w.png" height=80 alt="Open Food Fact Logo"/>
        </a>
        <h1 id="nav_title">Open Food Fact</h1>
        <div class="row">
            <div class="navbtn" id="nav_search">
                <div class="dropdown" id="dropsearch">
                    <form role="search" method="post" action="<?php echo $_GET['host']."/index.php?action=search" ?>">
                        <input type="text" placeholder="Chercher" id="research" name="research" required>
                        <button type="submit">Chercher</button>
                    </form>
                    <div>
                        <a href="<?php $_GET['host']."/index.php?action=advsearch" ?>">+ Filtres</a>
                    </div>
                </div>
            </div>
            <div class="navbtn" id="nav_profil">
                <div class="dropdown" id="dropuser">
                    <a href="<?php echo $_GET['host']."/index.php?action=add" ?>">Ajouter un produit</a>
                    <a href="<?php echo $_GET['host']."/index.php?action=myproducts" ?>">Mes produit</a>
                    <a href="<?php echo $_GET['host']."/index.php?action=account" ?>">Mon Compte</a>
                    <a href="<?php echo $_GET['host']."/index.php?action=logout" ?>">Déconnexion</a>
                </div>
            </div>
        </div>
    </nav>
</header>
<section>