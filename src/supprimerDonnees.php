<?php
require_once "./model/tools.php";

$idPost = filter_input(INPUT_GET,'idPost',FILTER_SANITIZE_NUMBER_INT);
$nomDonnee = filter_input(INPUT_GET,'nomDonnee');

$message = "";

$targetDir = dirname(__DIR__)."/src/uploads/";

if(SupprimerDonnees($idPost,$nomDonnee)){

    var_dump($targetDir.$nomDonnee);
    unlink($targetDir.$nomDonnee);
    

    $message = '<div class="alert alert-success">suppression réussi</div>';
}else{
    $message = '<div class="alert alert-danger">échec de la suppression</div>';
}

header("Location: ./index.php?messageSuppression=".$message);