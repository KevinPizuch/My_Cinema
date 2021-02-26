<div class="midHistory">
<div class="menu">
  <p style="color:white">Hello
  <?php
    //session_start();
    echo $_SESSION['prenom'];
  ?>
  !</p>
  <a href="indexmember.php">Mon profil</a>
  <a href="history.php">Mon Historique</a>
  <a href="abonnement.php">Mon Abonnement</a>
  <!--<a href="#">Ajouter un avis</a>-->
</div>
<div class="mainAccountHistoryTitle">


<?php

$id = $_SESSION['id_perso'];
try{
$stmt = $conn->prepare("SELECT id_membre FROM membre WHERE id_fiche_perso = '$id'");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
  echo $e->getMessage();
}
$id = $rows[0]['id_membre'];
try{
$stmt = $conn->prepare("SELECT id_film,date FROM historique_membre WHERE id_membre= '$id'");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
  echo $e->getMessage();
}
$id_film = [];
$date = [];
$titre = [];
for ($i=0; $i < count($rows); $i++) {
  array_push($id_film,$rows[$i]['id_film']);
  array_push($date,$rows[$i]['date']);
}
$count = count($rows);
for($i = 0; $i < $count; $i++){
  try{
  $stmt = $conn->prepare("SELECT titre FROM film WHERE id_film= '$id_film[$i]'");
  $stmt->execute();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  catch (PDOException $e) {
    echo $e->getMessage();
  }
  array_push($titre,$rows[0]['titre']);

  try{
  $stmt = $conn->prepare("SELECT avis FROM historique_membre WHERE id_film= '$id_film[$i]' AND avis IS NOT NULL");
  $stmt->execute();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  catch (PDOException $e) {
    echo $e->getMessage();
  }
  $regex = "/(src=\")(.*?)(\" width)/";
  $titree = preg_replace("/ /", "+", $titre[$i]);
  $html=file_get_contents("https://www.google.com/search?q=" . $titree . "+affiche&tbm=isch&source=lnt&tbs=isz:lt,islt:xga&sa=X&ved=0ahUKEwiJo9v03vHbAhVDaRQKHdL2B7AQpwUIHg&biw=1920&bih=911&dpr=1");
  preg_match($regex, $html, $matches);
  $img = $matches[2];
  echo "<div class = \"historySlot\">
    <h2>".$titre[$i]."</h2>
    <img src=\"$img\">
    <p>Vue le : ". substr($date[$i], 0,10) . "</p>";
    if(count($rows) == 0){
      echo "<p>Aucun avis !</p>
      <form class=\"form-historique\"action=\"avismember.php?id=$id_film[$i]&amp;titre=$titre[$i]\" method=\"post\">
        <input type=\"submit\"  value=\"Soyez le premier !\" class=\"btn btn-outline-warning\">
      </form>";
    }
    else{
      echo "<p>Avis (".count($rows).")</p>
      <form class=\"form-historique\"action=\"avismember.php?id=$id_film[$i]&amp;titre=$titre[$i]\" method=\"post\">
        <input type=\"submit\" value=\"Voir les avis\" class=\"btn btn-outline-success\">
      </form>";
    }
    echo "</div>";
}


?>

</div>
</div>
</div>
