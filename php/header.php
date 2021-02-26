<?php include('bdd.php'); ?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <title>My cinema</title>
  </head>
  <body>
    <header>
        <div class="connexion">
          <a href="../connexion.php"><button class="btn btn-outline-success" type="button" name="button">Connexion / Inscription</button></a>
        </div>
        <div class="search">
            <form action="index.php?page=1" method="post">

                <input type="text" name="film" placeholder="Rechercher un film.">
                <input type="submit" value="Rechercher" class="btn btn-outline-success">
                <p style="color:white">Ex: Genre un nom de film connard tu sais pas ce que c'est ? </p>
                <a class="btn btn-outline-info" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                    Recherche Avancée +
                </a>
                <div class="collapse" id="collapseExample">
                    <div class="card card-body" id="card">
                    <select class="advanceds" name="genre" value='NULL'>
                        <option value='NULL'>Genre</option>
                        <?php
                        $stmt = $conn->prepare("SELECT nom FROM genre ORDER BY nom");
                        $stmt->execute();
                        $genre = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($genre as $key => $val) {
                            $nom = $val['nom'];
                            echo "
                            <option value='$nom'>$nom</option>
                            ";
                        }
                        ?>
                    </select>
                    <select class="advanceds" name="distrib" value='NULL'>
                        <option value='NULL'>Distributeur</option>
                        <?php
                        $stmt = $conn->prepare("SELECT nom FROM distrib ORDER BY nom");
                        $stmt->execute();
                        $distrib = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($distrib as $key => $val) {
                            $nom = $val['nom'];
                            echo "
                            <option value='$nom'>$nom</option>
                            ";
                        }
                        ?>
                    </select>
                        <div class='limit'>
                        Date de Projection (DD/MM/YYYY) :
                        <input id="adate" type="date" name="date" placeholder="Date de projection">
                        </div>
                        <div class='limit'>
                        Nombre d'éléments affichés (1-100)
                        <input id='limit' type="number" name="limit" value="5" min="1" max="100">
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </header>
