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
<div class="mainAccount">

<?php
  if(isset($_POST['name']) && $_POST['name'] != ""){
    $name = $_POST['name'];
    $separ = explode(' ',$name);
    $name1 = $separ[0];
    if(isset($separ[1]))
      $name2 = $separ[1];
    else
      $name2 = '';
    try {
      $stmt = $conn->prepare("SELECT * FROM fiche_personne WHERE nom = '$name1' OR nom = '$name2' OR prenom = '$name1' OR prenom = '$name2'");
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        echo $e->getMessage();
    }
    if(count($rows) == 0){
      echo "Aucun membre avec ce nom";
      echo "<p>redirection dans 3 secondes ! </p>";
      echo "<script type=\"text/javascript\">
        setTimeout(function(){document.location.href=\"indexmember.php\";},3000);
      </script>";
    }
    else{
    echo "<h2>Profil du membre</h2>";
    echo "
    <p> Nom : " . $rows[0]['nom'] ."</p>
    <p> Prenom : " . $rows[0]['prenom'] ."</p>
    <p> Date de naissance : " . substr($rows[0]['date_naissance'],0,10) ."</p>
    <p> Email : " . $rows[0]['email'] ."</p>
    <p> Code postal : " . $rows[0]['cpostal'] ."</p>
    <p> Ville : " . $rows[0]['ville'] ."</p>
    ";
    }
  }
  else {
    echo "<p>entrez un nom ! </p>";
    echo "<p>redirection dans 3 secondes ! </p>";
    echo "<script type=\"text/javascript\">
      setTimeout(function(){document.location.href=\"indexmember.php\";},3000);
    </script>";
  }


?>
</div>
</div>
<div class="bottom">

</div>
