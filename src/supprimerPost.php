<?php
require_once "./model/tools.php";

$idPost = filter_input(INPUT_GET,'idPost',FILTER_SANITIZE_NUMBER_INT);

$message = "";

$targetDir = dirname(__DIR__)."/src/uploads/";

if($donnees = SupprimerPost($idPost, $donnees,$targetDir)){
    $message = '<div id="messageErreur" class="alert alert-success">Suppression réussi</div>';

}else if($donnee == null){
    $message = '<div id="messageErreur" class="alert alert-success">Suppression réussi</div>';
}
else{
    $message = '<div id="messageErreur" class="alert alert-danger">Échec de la suppression</div>';
}

header("Location: ./index.php?messageSuppression=".$message);