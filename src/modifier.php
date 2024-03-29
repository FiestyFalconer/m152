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

//constantes
const TAILLE_MAX = 73400320;

$idPost = filter_input(INPUT_GET, 'idPost', FILTER_SANITIZE_NUMBER_INT);
$submit = filter_input(INPUT_POST, 'submit', FILTER_SANITIZE_SPECIAL_CHARS);
$commentaire =  "";
$affichage = "";

$messageSuppresion = filter_input(INPUT_GET,'messageSuppression');
$message = "";
$typesDonnees = array("image/jpg", "image/png", "image/jpeg", "video/mp4", "audio/mpeg");

if ($submit == "Submit") {

    $targetDir = dirname(__DIR__) . "/src/uploads/";
    $nouveauCommentaire = filter_input(INPUT_POST, 'commentaire', FILTER_SANITIZE_SPECIAL_CHARS);

    $nomFichiers = array_filter($_FILES['files']['name']);
    $sizeFiles = 0;

    //recuperer la somme des tailees des images
    foreach ($_FILES['files']['size'] as $value) {
        $sizeFiles += $value;
    }

    if ($nouveauCommentaire != "" && !empty($nomFichiers)) {

        if ($sizeFiles < TAILLE_MAX) {
            if ($commentaire != $nouveauCommentaire) {
                ModificationCommentaire($idPost, $nouveauCommentaire);
                $message = ModificationDonneesPost($nouveauCommentaire, $nomFichiers, $targetDir, $typesDonnees, $idPost);
            } else {
                $message = ModificationDonneesPost($commentaire, $nomFichiers, $targetDir, $typesDonnees,$idPost);
            }
        } else {
            $message = '<div id="messageErreur" class="alert alert-danger">ERREUR : Image(s) trop grand(es) </div>';
            exit();
        }
    } else if ($nouveauCommentaire != "" && empty($nomFichiers)) {

        if ($commentaire != $nouveauCommentaire) {
            ModificationCommentaire($idPost, $nouveauCommentaire);
        }
        $message = '<div id="messageErreur" class="alert alert-success">Commentaire modifier</div>';
    } else {
        $message = '<div id="messageErreur" class="alert alert-danger">ERREUR : Il faut mettre au moins un commentaire </div>';
    }
}

$commentaire = RecupererUnPost($idPost)['commentaire'];
$affichage = AfficherDonnees($idPost);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

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
                Modification d'un post
            </h1>
            <form action="./modifier.php?idPost=<?= $idPost ?>" enctype="multipart/form-data" method="post">
                <div class="form-group">
                    <label for="Textarea">Changer le text du post :</label>
                    <textarea class="form-control" name="commentaire" id="Textarea" rows="3"><?= $commentaire ?></textarea>
                </div>
                <div class="form-group">
                    <label for="File">Ajoutez un(s) donnée(s) :</label>
                    <input type="file" name="files[]" accept="audio/mp3, image/png, image/jpg, image/jpeg, video/mp4" multiple class="form-control-file" id="File">
                </div>
                <button type="submit" name="submit" class="btn btn-primary" value="Submit">Submit</button>
            </form>
            <?= $message ?>
            <?= $messageSuppresion?>

        </div>
        <div class="container">
            <?= $affichage ?>
        </div>

    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>