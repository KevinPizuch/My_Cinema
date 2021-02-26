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
<div class="mainAccountAbonnement">
  <h3>Nos abonnements</h3>
  <?php
  try {
    $stmt = $conn->prepare("SELECT * FROM abonnement");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

  } catch (Exception $e) {
      //echo $e->getMessage();
  }
  for ($i=0; $i < count($rows); $i++) {
    echo "<p class=\"desc_abo\" style=\"color:yellow; text-align:center;\">" . $rows[$i]['nom'] . "   |   " . $rows[$i]['resum'] . "   |   " . $rows[$i]['prix'] . "€   |   " . $rows[$i]['duree_abo'] . " jours.</p>";
  }
  echo "<h3>Mon abonnement actuel</h3>";
  $id_abo = $_SESSION['id_abo'];
  try {
    $stmt = $conn->prepare("SELECT * FROM abonnement WHERE id_abo='$id_abo'");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

  } catch (Exception $e) {
      //echo $e->getMessage();
  }
  echo "<p class=\"desc_abo\" style=\"color:yellow; text-align:center;\">" . $rows[0]['nom'] . "   |   " . $rows[0]['resum'] . "   |   " . $rows[0]['prix'] . "€   |   " . $rows[0]['duree_abo'] . " jours.</p>";
  echo "<form class=\"abo\" action=\"indexmember.php\" method=\"post\">
    <select class=\"\" name=\"new_abo\" style=\"display:block; margin:auto; margin-top:30px;margin-bottom:30px;\">
      <option value=\"0\">Rien changer</option>
      <option value=\"1\">V.I.P</option>
      <option value=\"2\">G.O.L.D</option>
      <option value=\"3\">C.L.A.S.S.I.C</option>
      <option value=\"4\">P.A.S.S..D.A.Y</option>
      <option value=\"5\">M.A.L.S.C.H</option>
      <option value=\"6\">Supprimer</option>
    </select>
    <h2><input  type=\"submit\" value=\"Changer mon abonnement pask je le vo bi1 !\" class=\"btn btn-outline-warning\"></h2>
  </form>";
  ?>
</div>
</div>
  <div class="bottom">

  </div>
