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
  function showResult($queryResult, $genre = NULL,$distrib = NULL){
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $database = "epitech_tp";
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    catch(PDOException $e)
    {
        echo "Connection failed: " . $e->getMessage();
    }

    for ($i=0; $i < count($queryResult); $i++) {

    try {
      $stmt = $conn->prepare("SELECT id_film FROM historique_membre WHERE id_membre=".$_SESSION['id_user']." AND id_film = ".$queryResult[$i]['id_film']."  ");
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        //echo $e->getMessage();
    }

    echo "<div class=\"random-film-slot-member\">
        <h2>". $queryResult[$i]['titre'] ."</h2>";
    grabImageGoogle($queryResult[$i]['titre']);
    echo "<p> A l'affiche du ".$queryResult[$i]['date_debut_affiche']." au ".$queryResult[$i]['date_fin_affiche']."</p>";
    convertDuree($queryResult[$i]['duree_min']);
    if($genre != NULL)
      echo "<p> Genre : " . $genre . "</p>";
    if($distrib != NULL)
      echo "<p> Distributeur : " .$distrib ."</p>";
    anneProd($queryResult[$i]['annee_prod']);
    echo "<p> Résumé du film : ".$queryResult[$i]['resum']. "</p>";

    if(count($rows) == 0){
      echo "<form class=\"form-historique\"action=\"indexmember.php?id_film=".$queryResult[$i]['id_film']."\" method=\"post\">
            <input type=\"submit\" value=\"J'ai pas encore vu ce film je vais le voir maintenant\" class=\"btn btn-outline-warning\">
            </form></div>";
      echo "<div class=\"space-random\"></div>";
      }
    else{
      echo "<h3 style=\"text-align:center; color:red \"><p>J'ai déjà vu ce film </p></h3>";
      echo "<div class=\"space-random\"></div>";
    }
    }
    echo "</div>
    </div>
    </div>
    <div class=\"bottom\">
    </div>";
  }
  function anneProd($annee){
    if($annee == 0)
      echo "<p> Année de production : inconnue </p>";
    else {
      echo "<p>Année de production :". $annee. "</p>" ;
    }
  }
  function convertDuree($temps){
    if($temps == 0)
      echo "<p>Durée : inconnue</p>";
    else{
      $heures = floor( $temps / 60 );
      $minutes = $temps % 60 ;
      echo "<p>Durée :".$heures. " h " . $minutes. " min</p>";
    }
  }

  function grabImageGoogle($titre){
    $regex = "/(src=\")(.*?)(\" width)/";
    $titree = preg_replace("/ /", "+", $titre);
    $html=file_get_contents("https://www.google.com/search?q=" . $titree . "+affiche&tbm=isch&source=lnt&tbs=isz:lt,islt:xga&sa=X&ved=0ahUKEwiJo9v03vHbAhVDaRQKHdL2B7AQpwUIHg&biw=1920&bih=911&dpr=1");
    preg_match($regex, $html, $matches);
    $img = $matches[2];
    echo "<img src=\"$img\">";
  }
  $idUnique = $_SESSION['id_user'];
  if(isset($_POST['new_abo']) && $_POST['new_abo'] != 0){
    $new_id_abo = $_POST['new_abo'];
    intval($new_id_abo);
    $new_id_abo--;
    try {
      $stmt = $conn->prepare("UPDATE membre SET id_abo = '$new_id_abo' WHERE id_membre ='$idUnique'");
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        //echo $e->getMessage();
    }
    echo "<script type=\"text/javascript\">
      setTimeout(function(){
      alert(\"Votre abonement a été changer !\");
      },300);
    </script>";
  }
  if(isset($_GET['id_film'])){
    $idfilm = $_GET['id_film'];
    $currDate = date("Y-m-d H:i:s");
    try {
      $sql = "INSERT INTO historique_membre(id_membre,id_film,date,avis) VALUES ('$idUnique','$idfilm','$currDate',NULL)";
      $conn->exec($sql);

    } catch (Exception $e) {
        //echo $e->getMessage();
    }
    echo "<script type=\"text/javascript\">
      setTimeout(function(){
      alert(\"Ce film a été rajouter à votre historique !\");
      },300);
    </script>";
  }
  try {
    $stmt = $conn->prepare("SELECT id_abo FROM membre WHERE id_membre = '$idUnique'");
    $stmt->execute();
    $iduser = $stmt->fetchAll(PDO::FETCH_ASSOC);

  } catch (Exception $e) {
      //echo $e->getMessage();
  }
  $id_abo = $iduser[0]['id_abo'];
  try {
    $stmt = $conn->prepare("SELECT nom,resum FROM abonnement WHERE id_abo = '$id_abo'");
    $stmt->execute();
    $idabo = $stmt->fetchAll(PDO::FETCH_ASSOC);

  } catch (Exception $e) {
      //echo $e->getMessage();
  }

  if(isset($_POST['avis']) && $_POST['avis'] != ""){
    $avis = $_POST['avis'];

    $id_film = $_GET['id'];
    try {
      $stmt = $conn->prepare("UPDATE historique_membre SET avis = '$avis' WHERE id_membre ='$idUnique' AND id_film = '$id_film'");
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        //echo $e->getMessage();
    }

    echo "<script type=\"text/javascript\">
      setTimeout(function(){
      alert('Votre avis est bien enregister !');
      },200);
    </script>";
    echo "<h2>Mon profil</h2>";
    echo "<p> Nom : ".$_SESSION['name']."</p>";
    echo "<p> Prenom : ".$_SESSION['prenom']."</p>";
    echo "<p> Date de naissance : ".substr($_SESSION['date_naissance'],0,10)."</p>";
    echo "<p> Email : ".$_SESSION['email']."</p>";
    echo "<p> Code postal : ".$_SESSION['cpostal']."</p>";
    echo "<p> Ville : ".$_SESSION['ville']."</p>";
    echo "<p> Abonnement " . $idabo[0]['nom'] . " : " . $idabo[0]['resum'] ."</p>";
  }
  else if(!isset($_POST['film']) && !isset($_POST['genre']) && !isset($_POST['distrib']) && !isset($_POST['date'])) {
    echo "<h2>Mon profil</h2>";
    echo "<p> Nom : ".$_SESSION['name']."</p>";
    echo "<p> Prenom : ".$_SESSION['prenom']."</p>";
    echo "<p> Date de naissance : ".substr($_SESSION['date_naissance'],0,10)."</p>";
    echo "<p> Email : ".$_SESSION['email']."</p>";
    echo "<p> Code postal : ".$_SESSION['cpostal']."</p>";
    echo "<p> Ville : ".$_SESSION['ville']."</p>";
    echo "<p> Abonnement " . $idabo[0]['nom'] . " : " . $idabo[0]['resum'] ."</p>";
  }
  else if(strlen($_POST['film']) == 0 && $_POST['genre'] == "NULL" && $_POST['distrib'] == "NULL" && $_POST['date'] == ""){
    echo "<h2>Mon profil</h2>";
    echo "<p> Nom : ".$_SESSION['name']."</p>";
    echo "<p> Prenom : ".$_SESSION['prenom']."</p>";
    echo "<p> Date de naissance : ".substr($_SESSION['date_naissance'],0,10)."</p>";
    echo "<p> Email : ".$_SESSION['email']."</p>";
    echo "<p> Code postal : ".$_SESSION['cpostal']."</p>";
    echo "<p> Ville : ".$_SESSION['ville']."</p>";
    echo "<p> Abonnement " . $idabo[0]['nom'] . " : " . $idabo[0]['resum'] ."</p>";
  }
  else if(strlen($_POST['film']) != 0 && $_POST['genre'] == "NULL" && $_POST['distrib'] == "NULL" && $_POST['date'] == ""){
    $titreFilm = $_POST['film'];
    try {
      $stmt = $conn->prepare("SELECT * FROM film WHERE film.titre = '$titreFilm' LIMIT " .$_POST['limit']. "");
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        echo $e->getMessage();
    }
    if(count($rows) == 0){
      echo "Aucun résultat";
      exit;
    }
    else
      showResult($rows);
  }
  else if(strlen($_POST['film']) == 0 && $_POST['genre'] != "NULL" && $_POST['distrib'] == "NULL" && $_POST['date'] == "")
  {
    $genre = $_POST['genre'];
    try {
      $stmt = $conn->prepare("SELECT id_genre FROM genre WHERE genre.nom = '$genre'");
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        echo $e->getMessage();
    }
    $id_genre = $rows[0]['id_genre'];
    try {
      $stmt = $conn->prepare("SELECT * FROM film WHERE film.id_genre = '$id_genre' LIMIT " .$_POST['limit']. "");
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        echo $e->getMessage();
    }
    showResult($rows,$genre);
  }
  else if(strlen($_POST['film']) == 0 && $_POST['genre'] == "NULL" && $_POST['distrib'] != "NULL" && $_POST['date'] == "") {
    $distrib = $_POST['distrib'];
    try {
      $stmt = $conn->prepare("SELECT id_distrib FROM distrib WHERE distrib.nom = '$distrib'");
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        echo $e->getMessage();
    }
    $id_distrib = $rows[0]['id_distrib'];
    try {
      $stmt = $conn->prepare("SELECT * FROM film WHERE film.id_distrib = '$id_distrib' LIMIT " .$_POST['limit']. "");
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        echo $e->getMessage();
    }
    showResult($rows,NULL ,$distrib);
  }
  else if(strlen($_POST['film']) == 0 && $_POST['genre'] == "NULL" && $_POST['distrib'] == "NULL" && $_POST['date'] != "") {
    //"2002-12-20"
    $date = $_POST['date'];
    try {
      $stmt = $conn->prepare("SELECT * FROM film WHERE (date_debut_affiche <= '$date') AND (date_fin_affiche >= '$date') LIMIT " .$_POST['limit']. "");
      $stmt->execute();
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        echo $e->getMessage();
    }
    showResult($rows);
  }
  else{
    echo "<h2>Mon profil</h2>";
    echo "<p> Nom : ".$_SESSION['name']."</p>";
    echo "<p> Prenom : ".$_SESSION['prenom']."</p>";
    echo "<p> Date de naissance : ".substr($_SESSION['date_naissance'],0,10)."</p>";
    echo "<p> Email : ".$_SESSION['email']."</p>";
    echo "<p> Code postal : ".$_SESSION['cpostal']."</p>";
    echo "<p> Ville : ".$_SESSION['ville']."</p>";
    echo "<p> Abonnement " . $idabo[0]['nom'] . " : " . $idabo[0]['resum']. "</p>";

  }
  $_SESSION['id_abo'] = $id_abo;
  ?>




</div>
</div>
<div class="bottom">

</div>
