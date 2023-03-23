<?php
require_once "./model/tools.php";

$idPost = filter_input(INPUT_GET,'idPost',FILTER_SANITIZE_NUMBER_INT);
$nomDonnee = filter_input(INPUT_GET,'nomDonnee');

$message = "";

$targetDir = dirname(__DIR__)."/src/uploads/";

if(SupprimerDonnees($idPost,$nomDonnee,$targetDir)){
    $message = '<div id="messageErreur" class="alert alert-success">Suppression réussi</div>';
}else{
    $message = '<div id="messageErreur" class="alert alert-danger">Échec de la suppression</div>';
}

header("Location: ./modifier.php?messageSuppression=".$message."&idPost=".$idPost);