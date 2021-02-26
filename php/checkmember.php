<?php

/*module connexion*/
if(isset($_POST['emailMember']) && isset($_POST['nameMember'])){
  if($_POST['emailMember'] == "admin@admin.com" && $_POST['nameMember'] == "ptdr"){
    echo "<script type=\"text/javascript\">
      document.location.href=\"indexadmin.php\";;
    </script>";
    exit;
  }

$mail = $_POST['emailMember'];
$name = $_POST['nameMember'];
//var_dump($_POST['emailMember'],$_POST['nameMember']);
try{
$stmt = $conn->prepare("SELECT nom,email,prenom FROM fiche_personne WHERE email = '$mail' AND nom = '$name'");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
  echo $e->getMessage();
}

if(count($rows) == 0){
  echo "Mauvais mot de pass ou email";
  echo "<script type=\"text/javascript\">
    setTimeout(function(){document.location.href=\"connexion.php\";},3000);
  </script>";
  exit;
}
$email = $rows[0]['email'];
$namesql = $rows[0]['nom'];
$prenom = $rows[0]['prenom'];
if($namesql == $name && $email == $mail){
  try{
  $stmt = $conn->prepare("SELECT * FROM fiche_personne WHERE email = '$mail' AND nom = '$name'");
  $stmt->execute();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  catch (PDOException $e) {
    echo $e->getMessage();
  }
  //session_start();
  $_SESSION['name'] = $rows[0]['nom'];
  $_SESSION['prenom'] = $rows[0]['prenom'];
  $_SESSION['date_naissance'] = $rows[0]['date_naissance'];
  $_SESSION['email'] = $rows[0]['email'];
  $_SESSION['cpostal'] = $rows[0]['cpostal'];
  $_SESSION['ville'] = $rows[0]['ville'];
  $_SESSION['id_perso'] = $rows[0]['id_perso'];
  $id_fiche_perso = $_SESSION['id_perso'];
  try{
  $stmt = $conn->prepare("SELECT id_membre FROM membre WHERE id_fiche_perso= '$id_fiche_perso'");
  $stmt->execute();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  catch (PDOException $e) {
    echo $e->getMessage();
  }
  $_SESSION['id_user'] = $rows[0]['id_membre'];
  echo "hello";
  echo "<script type=\"text/javascript\">
    setTimeout(function(){document.location.href=\"indexmember.php\";},3000);
  </script>";
}
}
/*module inscription*/
else if($_POST['newEmail'] != "" && $_POST['newDate'] != "" && $_POST['newLastName'] != "" && $_POST['newName'] != "" && $_POST['newAdress'] != "" && $_POST['newCity'] != "" && $_POST['newZip'] != "") {
  $email = $_POST['newEmail'];
  $stmt = $conn->prepare("SELECT email FROM fiche_personne WHERE email = '$email'");
  $stmt->execute();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  if(count($rows) != 0){
    echo "cet email existe déjà";
    echo "<script type=\"text/javascript\">
      setTimeout(function(){document.location.href=\"connexion.php\";},3000);
    </script>";
    exit;
  }
  $birth = $_POST['newDate'];
  $lastName = $_POST['newLastName'];
  $name = $_POST['newName'];
  $adress = $_POST['newAdress'];
  $city = $_POST['newCity'];
  $zip = $_POST['newZip'];
  $stmt = $conn->prepare("SELECT id_perso FROM fiche_personne");
  $stmt->execute();
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $id_perso = count($rows)+1;
try {
  $id_perso++;
  $sql = "INSERT INTO fiche_personne (id_perso,nom,prenom,date_naissance,email,adresse,cpostal,ville) VALUES ('$id_perso','$lastName','$name','$birth','$email','','$zip','$city')";
  $conn->exec($sql);

} catch (PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}
try {
//251 id fiche
//250 id membre
  $today = date("Ymd");
  $idmember = $id_perso;
  $idmember--;
  $sql = "INSERT INTO membre(id_membre,id_fiche_perso,id_dernier_film,date_inscription) VALUES ('$idmember','$id_perso','0',$today)";
  $conn->exec($sql);

} catch (PDOException $e) {
  echo $sql . "<br>" . $e->getMessage();
}

echo "Inscription réussi ! tu va être rediriger pour pouvoir te connecter";
echo "<script type=\"text/javascript\">
  setTimeout(function(){document.location.href=\"connexion.php\";},3000);
</script>";

}
/*form vide ou autre choses qui ne doivent pas arriver*/
else{
  echo "Whops look like something went wrong";
  echo "<script type=\"text/javascript\">
    setTimeout(function(){document.location.href=\"connexion.php\";},3000);
  </script>";
}



?>
<div class="bottom">

</div>
<div class="bottom">

</div>
<div class="bottom">

</div>
</div>
<div class="bottom">

</div>
<div class="bottom">

</div>
