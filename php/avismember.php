<div class="mid">
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
<div class="mainAccountAvis">

<?php
  $titre = $_GET['titre'];
  $id_film = $_GET['id'];
  $regex = "/(src=\")(.*?)(\" width)/";
  $titree = preg_replace("/ /", "+", $titre);
  $html=file_get_contents("https://www.google.com/search?q=" . $titree . "+affiche&tbm=isch&source=lnt&tbs=isz:lt,islt:xga&sa=X&ved=0ahUKEwiJo9v03vHbAhVDaRQKHdL2B7AQpwUIHg&biw=1920&bih=911&dpr=1");
  preg_match($regex, $html, $matches);
  $img = $matches[2];
  echo "<h2>".$titre."</h2>";
  echo "<img src=\"$img\" style=\"width:300px\">";
  try{
  $stmt = $conn->prepare("SELECT id_membre,avis FROM historique_membre WHERE id_film= '$id_film' AND avis IS NOT NULL ");
  $stmt->execute();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  catch (PDOException $e) {
    echo $e->getMessage();
  }
  if(count($rows) == 0){
    echo "<form action=\"indexmember.php?id=$id_film\" method=\"post\">
    <textarea name=\"avis\"style=\"margin-left:230px;\"rows=\"4\" cols=\"50\"></textarea>
          <h2><input type=\"submit\" value=\"Donner mon avis en premier !\" class=\"btn btn-outline-success\"></h2>
          </form>";
    echo "</div>
    </div>
    <div class=\"bottom\">

    </div>";
    exit;
  }
  $avis = [];
  $id_membre = [];
  $id_fiche_perso = [];
  $nomAvis = [];
  for ($i=0; $i < count($rows); $i++) {
    array_push($avis,$rows[$i]['avis']);
    array_push($id_membre,$rows[$i]['id_membre']);
    try{
    $stmt = $conn->prepare("SELECT id_fiche_perso FROM membre WHERE id_membre = '$id_membre[$i]'");
    $stmt->execute();
    $rowss = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e) {
      echo $e->getMessage();
    }
    array_push($id_fiche_perso,$rowss[0]['id_fiche_perso']);
    try{
    $stmt = $conn->prepare("SELECT prenom FROM fiche_personne WHERE id_perso = '$id_fiche_perso[$i]'");
    $stmt->execute();
    $rowsss = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e) {
      echo $e->getMessage();
    }
    array_push($nomAvis,$rowsss[0]['prenom']);
    echo "<p><strong>".$nomAvis[$i]. " a publié : </strong>&nbsp;&nbsp;&nbsp;&nbsp;".$avis[$i]."</p>";
  }
    echo "<form action=\"indexmember.php?id=$id_film\" method=\"post\">
    <textarea name=\"avis\"style=\"margin-left:230px;\"rows=\"4\" cols=\"50\"></textarea>
    <h2><input type=\"submit\" value=\"Donner mon avis\" class=\"btn btn-outline-success\"></h2>
    </form>";

?>
</div>
</div>
<div class="bottom">

</div>
