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

function NouveauPost($imageName, $type, $commentaire, $bool)
{
    try {
        $db = ConnexionBD();
        $db->beginTransaction();
        if ($bool) {
            $query = $db->prepare("
                INSERT INTO `POST`(`commentaire`) 
                VALUES (?);
                ");
            $query->execute([$commentaire]);
        }

        $query = $db->prepare("
            INSERT INTO `MEDIA`(`typeMedia`, `nomMedia`, `idPost`) 
            VALUES (?,?,(SELECT MAX(`idPost`) FROM `POST` WHERE `commentaire` = ?));
            ");
        $query->execute([$type, $imageName, $commentaire]);

        $db->commit();
    } catch (PDOException $e) {
        $db->rollback();
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

function RecupererImages($idPost)
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
