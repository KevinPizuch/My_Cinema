<div class="mid-visiteur">


<?php
/*
name="film"
name="genre"
name="distrib"
name="date"
name="limit"
*/

function showResult($queryResult, $genre = NULL,$distrib = NULL){

  for ($i=0; $i < count($queryResult); $i++) {
  echo "<div class=\"random-film-slot\">
      <h2>". $queryResult[$i]['titre'] ."</h2>";
  grabImageGoogle($queryResult[$i]['titre']);
  echo "<p> A l'affiche du ".$queryResult[$i]['date_debut_affiche']." au ".$queryResult[$i]['date_fin_affiche']."</p>";
  convertDuree($queryResult[$i]['duree_min']);
  if($genre != NULL)
    echo "<p> Genre : " . $genre . "</p>";
  if($distrib != NULL)
    echo "<p> Distributeur : " .$distrib ."</p>";
  anneProd($queryResult[$i]['annee_prod']);
  echo "<p> Résumé du film : ".$queryResult[$i]['resum']. "</p></div>";
  echo "<div class=\"space-random\"></div>";
  }
  echo "</div>
  <div class=\"bottom\">

  </div>";
}
function searchRandFilm ($conn) {
    $stmt = $conn->prepare("SELECT * FROM film ORDER BY RAND() LIMIT 3");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<h2>Films Random</h2>";
    showResult($rows);
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

if(!isset($_POST['film']) && !isset($_POST['genre']) && !isset($_POST['distrib']) && !isset($_POST['date'])) {
  searchRandFilm($conn);
}
else if(strlen($_POST['film']) == 0 && $_POST['genre'] == "NULL" && $_POST['distrib'] == "NULL" && $_POST['date'] == ""){
  searchRandFilm($conn);
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
//string(0) "" string(4) "NULL" string(4) "NULL" string(0) ""
?>
<div class="bottom">

</div>
