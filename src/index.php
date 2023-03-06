<?php

/**
 * Nom, Prenom: DE CASTILHO E SOUSA Rodrigo
 * Projet:      Intégrer des contenus multimédias dans des applications Web
 * Description: Créer des éléments multimédia pour des pagesWeb selon données ou
 *              préparer, optimiser des contenus multimédia existants et intégrer dans
 *              des pages Web existantes
 * Date:        02/2023
 * Version:     1.0.0.0
 */
require_once "./model/tools.php";

$posts = RecupererPosts();
$affichage = '<div class="container"><div class="row">';
$donnees = "";

foreach ($posts as $unPost) {;
    $affichage .= '<div class="col-md-4"><div class="card mb-4 box-shadow">';

    $donnees = RecupererImages($unPost['idPost']);

    foreach ($donnees as $donnee) {
        if ($donnee['typeMedia'] == "jpg" || $donnee['typeMedia'] == "png" || $donnee['typeMedia'] == 'jpeg') {
            $affichage .= '<img id="images" class="card-img-top" src="./uploads/' . $donnee['nomMedia'] . '" alt="'. $donnee['nomMedia'] .'">';
        } else if ($donnee['typeMedia'] == "mp4") {
            $affichage .= '<video autoplay loop muted><source  src="./uploads/' . $donnee['nomMedia'] . '" alt="'.$donnee['nomMedia'] .'"></video>';
        }
        else if ($donnee['typeMedia'] == "mp3"){
            $affichage .='<audio title="Noir Désir" preload="auto" controls loop>
            <source src="./uploads/'. $donnee['nomMedia'] .'" type="audio/mp3" alt="'.$donnee['nomMedia'] .'">
            </audio>';
        }
    }

    $affichage .= '<div class="card-body"><p class="card-text">' . $unPost['commentaire'] . '</p></div>';

    $affichage .= "</div></div>";
}

$affichage .= "</div></div>";

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="./style/stylesheet.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>
    <header class="py-3 mb-2 bg-primary textwhite">
        <ul class="nav nav-pills d-flex justify-content-between">
            <li calss="nav-item">
                <i id="logo" class="fab fa-bootstrap"></i>
            <li calss="nav-item">
                <form class="navbar-form navbar-left">
                    <div class="input-group input-group-sm">
                        <input id="search" class="form-control" placeholder="Search" name="srch-term" id="srch-term" type="text">
                        <div class="input-group-btn">
                            <button class="btn btn-light" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </div>
                </form>
            </li>
            <li class="nav-item">
                <a href="./index.php" class="fa-solid fa-house nav-link active" aria-current="page"> Home</a>
            </li>
            <li class="nav-item">
                <a href="./post.php" class="fa-solid fa-plus nav-link active" aria-current="page"> Post</a>
            </li>
            <li class="nav-item">
                <a href="#" class="fa-solid fa-user fa-solid fa-plus nav-link active" aria-current="page"></a>
            </li>
        </ul>
    </header>
    <main>
        <div class="container">
            <h1>
                Welcome
            </h1>
        </div>
        <div class="container">
            <p><img src="./img/imageProfil.png" alt="imageProfil"></p>
        </div>

        <?= $affichage ?>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>