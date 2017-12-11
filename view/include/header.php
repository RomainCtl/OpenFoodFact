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
        <a href="index.php">
            <img id="nav_img" src="openFoodFactLogo_w.png" height=80 alt="Open Food Fact Logo"/>
        </a>
        <h1 id="nav_title">Open Food Fact</h1>
        <div class="row">
            <div class="navbtn" id="nav_search">
                <div class="dropdown" id="dropsearch">
                    <form role="search" method="post" action="search.php">
                        <input type="text" placeholder="Chercher" name="research" required>
                        <button type="submit">Chercher</button>
                    </form>
                    <div>
                        <a href="">+ Filtres</a>
                    </div>
                </div>
            </div>
            <div class="navbtn" id="nav_profil">
                <div class="dropdown" id="dropuser">
                    <a href="">Ajouter un produit</a>
                    <a href="">Mes produit</a>
                    <a href="">Mon Compte</a>
                    <a href="">Déconnexion</a>
                </div>
            </div>
        </div>
    </nav>
</header>
