<?php
/**
 * Created by PhpStorm.
 * User: romai
 * Date: 14/12/2017
 * Time: 17:28
 */

echo "<div id='contains'>";

if (isset($_POST['msg']) && !empty($_POST['msg'])) echo $_POST['msg'];

foreach($_POST['figures'] as $k => $v) {
    echo "<a href='".$_GET['host']."/index.php?action=consult&num=".$k."'>";  //manque que le vrai liens
    echo "<figure class='product'>";
    echo "<img src='".$v['src']."' alt='".$v['alt']."' width='100' />";
    echo "<figcaption>".$v['legend']."</figcaption>";
    echo "</figure>";
    echo "</a>";
}

echo "</div>";
