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

require_once("baseDonne.php");

const UNE_IMAGE = 3145728;

function AfficherDonnees($idPost)
{
    $affichage = '<div class="container"><div class="row">';

    $donnees = RecupererDonnees($idPost);

    foreach ($donnees as $donnee) {
        $affichage .= '<div class="col-md-4"><div class="card mb-4 box-shadow">';

        if ($donnee['typeMedia'] == "jpg" || $donnee['typeMedia'] == "png" || $donnee['typeMedia'] == 'jpeg') {
            $affichage .= '<img id="images" class="card-img-top" src="./uploads/' . $donnee['nomMedia'] . '" alt="' . $donnee['nomMedia'] . '">
            <a class="btn btn-primary" href="./supprimerDonnees.php?idPost=' . $idPost . '&nomDonnee=' . $donnee['nomMedia'] . '">Supprimer donnée</a>';
        } else if ($donnee['typeMedia'] == "mp4") {
            $affichage .= '<video autoplay loop muted><source  src="./uploads/' . $donnee['nomMedia'] . '" alt="' . $donnee['nomMedia'] . '"></video>
            <a class="btn btn-primary" href="./supprimerDonnees.php?idPost=' . $idPost . '&nomDonnee=' . $donnee['nomMedia'] . '">Supprimer donnée</a>';
        } else if ($donnee['typeMedia'] == "mp3") {
            $affichage .= '<audio title="Noir Désir" preload="auto" controls loop>
            <source src="./uploads/' . $donnee['nomMedia'] . '" type="audio/mp3" alt="' . $donnee['nomMedia'] . '">
            </audio>
            <a class="btn btn-primary" href="./supprimerDonnees.php?idPost=' . $idPost . '&nomDonnee=' . $donnee['nomMedia'] . '">Supprimer donnée</a>';
        }

        $affichage .= '</div></div>';
    }

    $affichage .= "</div></div>";

    return $affichage;
}

function NouveauPost($commentaire, $nomFichiers, $targetDir, $typesDonnees)
{
    try {
        $db = ConnexionBD();
        $db->beginTransaction();

        //declaration des variables
        $message = "";
        $uniquesavename = "";
        $mimeType = "";
        $etatTransaction = true;

        $tableauNomDonnee = [];

        //insérer le commentaire dans la base de données
        $query = $db->prepare("
                INSERT INTO `POST`(`commentaire`) 
                VALUES (?);
            ");
        $query->execute([$commentaire]);

        //foreach pour parcourir les differents fichiers
        foreach ($nomFichiers as $key => $val) {

            //creer une partie du nom unique
            $uniquesavename = time() . uniqid(rand());
            $nomFichier = basename($nomFichiers[$key]);
            //avoir le path du fichier
            $targetFilePath = $targetDir . $uniquesavename . $nomFichier;

            //recuperer le type de fichier pour le mettre dans la base de données
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            // recuperer le type de fichier grace a mime_content_type 
            $mimeType = mime_content_type($_FILES["files"]["tmp_name"][$key]);

            //tester si c'est le bon type d'image et la bonne taille
            if (in_array($mimeType, $typesDonnees) && $_FILES['files']['size'][$key] < UNE_IMAGE && $etatTransaction) {
                //tester si on arrive a garder les images sur le serveur
                if (move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)) {

                    array_push($tableauNomDonnee, $targetFilePath);
                    //message de success
                    $message = '<div id="messageErreur" class="alert alert-success">Post créé</div>';
                    //insérer le fichier dans la base de données
                    $query = $db->prepare("
                        INSERT INTO `MEDIA`(`typeMedia`, `nomMedia`, `idPost`) 
                        VALUES (?,?,(SELECT MAX(`idPost`) FROM `POST` WHERE `commentaire` = ?));
                    ");
                    $query->execute([$fileType, $uniquesavename . $nomFichier, $commentaire]);
                }
            } else {
                if ($etatTransaction) {
                    $db->rollback();

                    foreach ($tableauNomDonnee as $nom) {
                        unlink($nom);
                    }
                }
                $etatTransaction = false;
                //message d'erreur
                $message = '<div id="messageErreur" class="alert alert-danger">ERREUR : Image(s) trop grand(es) ou pas une image </div>';
            }
        }
        //verifier l'etat de la transaction
        if ($etatTransaction) {
            $db->commit();
        }

        //renvoie le message
        return $message;
    } catch (PDOException $e) {
        echo 'Exception reçue : ',  $e->getMessage(), "\n";
    }
}

function NouveauCommentaire($commentaire)
{
    try {
        $db = ConnexionBD();
        $db->beginTransaction();
        $query = $db->prepare("
                INSERT INTO `POST`(`commentaire`) 
                VALUES (?);
                ");
        $query->execute([$commentaire]);
        $db->commit();
    } catch (PDOException $e) {
        $db->rollback();
        echo 'Exception reçue : ',  $e->getMessage(), "\n";
    }
}

function RecupererPosts()
{
    try {
        $query = ConnexionBD()->prepare("
        SELECT `commentaire`, `idPost`
        FROM `POST` 
        ");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Exception reçue : ',  $e->getMessage(), "\n";
    }
}

function RecupererDonnees($idPost)
{
    try {
        $query = ConnexionBD()->prepare("
        SELECT `nomMedia`, `typeMedia`
        FROM `MEDIA` 
        WHERE `idPost` = ?
        ");
        $query->execute([$idPost]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Exception reçue : ',  $e->getMessage(), "\n";
    }
}

function SupprimerPost($idPost, $donnees, $targetDir)
{
    try {
        $db = ConnexionBD();
        $db->beginTransaction();
        $donnees = RecupererDonnees($idPost);
        $query = $db->prepare("
            DELETE FROM `MEDIA` 
            WHERE `idPost` = ?;
                ");
        $query->execute([$idPost]);

        $query = $db->prepare("
            DELETE FROM `POST` 
            WHERE `idPost` = ?;
                ");
        $query->execute([$idPost]);
        $db->commit();
        foreach ($donnees as $donnee) {
            unlink($targetDir . $donnee['nomMedia']);
        }
        return $donnees;
    } catch (PDOException $e) {
        $db->rollback();
        echo 'Exception reçue : ',  $e->getMessage(), "\n";
        return false;
    }
}

function SupprimerDonnees($idPost, $nomDonnee, $targetDir)
{
    try {
        $db = ConnexionBD();
        $db->beginTransaction();
        $query = $db->prepare("
            DELETE FROM `MEDIA` 
            WHERE `idPost` = ?
            AND `nomMedia` = ?
                ");
        $query->execute([$idPost, $nomDonnee]);
        $db->commit();
        unlink($targetDir . $nomDonnee);
        return true;
    } catch (PDOException $e) {
        $db->rollback();
        echo 'Exception reçue : ',  $e->getMessage(), "\n";
        return false;
    }
}

function RecupererUnPost($idPost)
{
    try {
        $query = ConnexionBD()->prepare("
        SELECT `commentaire`
        FROM `POST` 
        WHERE `idPost` = ?
        ");
        $query->execute([$idPost]);
        return $query->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Exception reçue : ',  $e->getMessage(), "\n";
    }
}

function ModificationCommentaire($idPost, $commentaire)
{
    try {
        $db = ConnexionBD();
        $db->beginTransaction();
        $query = $db->prepare("
            UPDATE `POST` 
            SET `commentaire`= ?,`modificationDate`= NOW()
            WHERE `idPost` = ?
        ");
        $query->execute([$commentaire, $idPost]);
        $db->commit();
    } catch (PDOException $e) {
        $db->rollback();
        echo 'Exception reçue : ',  $e->getMessage(), "\n";
    }
}

function ModificationDonneesPost($commentaire, $nomFichiers, $targetDir, $typesDonnees)
{
    try {
        $db = ConnexionBD();
        $db->beginTransaction();

        //declaration des variables
        $message = "";
        $uniquesavename = "";
        $mimeType = "";
        $etatTransaction = true;

        $tableauNomDonnee = [];

        //foreach pour parcourir les differents fichiers
        foreach ($nomFichiers as $key => $val) {

            //creer une partie du nom unique
            $uniquesavename = time() . uniqid(rand());
            $nomFichier = basename($nomFichiers[$key]);
            //avoir le path du fichier
            $targetFilePath = $targetDir . $uniquesavename . $nomFichier;

            //recuperer le type de fichier pour le mettre dans la base de données
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            // recuperer le type de fichier grace a mime_content_type 
            $mimeType = mime_content_type($_FILES["files"]["tmp_name"][$key]);

            //tester si c'est le bon type d'image et la bonne taille
            if (in_array($mimeType, $typesDonnees) && $_FILES['files']['size'][$key] < UNE_IMAGE && $etatTransaction) {
                //tester si on arrive a garder les images sur le serveur
                if (move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)) {

                    array_push($tableauNomDonnee, $targetFilePath);
                    //message de success
                    $message = '<div id="messageErreur" class="alert alert-success">Modification réussie</div>';
                    //insérer le fichier dans la base de données
                    $query = $db->prepare("
                        INSERT INTO `MEDIA`(`typeMedia`, `nomMedia`, `idPost`) 
                        VALUES (?,?,(SELECT MAX(`idPost`) FROM `POST` WHERE `commentaire` = ?));
                    ");
                    $query->execute([$fileType, $uniquesavename . $nomFichier, $commentaire]);
                }
            } else {
                if ($etatTransaction) {
                    $db->rollback();

                    foreach ($tableauNomDonnee as $nom) {
                        unlink($nom);
                    }
                }
                $etatTransaction = false;
                //message d'erreur
                $message = '<div id="messageErreur" class="alert alert-danger">ERREUR : Image(s) trop grand(es) ou pas une image </div>';
            }
        }
        //verifier l'etat de la transaction
        if ($etatTransaction) {
            $db->commit();
        }

        //renvoie le message
        return $message;
    } catch (PDOException $e) {
        echo 'Exception reçue : ',  $e->getMessage(), "\n";
    }
}
