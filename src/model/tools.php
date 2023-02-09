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

function NouvellePost($imageName, $type, $commentaire, $bool)
{
    if ($bool) {
        try {
            $query = ConnexionBD()->prepare("
            INSERT INTO `POST`(`commentaire`) 
            VALUES (?);
            ");
            $query->execute([$commentaire]);
        } catch (PDOException $e) {
            echo 'Exception reçue : ',  $e->getMessage(), "\n";
        }
    }
    try {
        $query = ConnexionBD()->prepare("
        INSERT INTO `MEDIA`(`typeMedia`, `nomMedia`, `idPost`) 
        VALUES (?,?,(SELECT `idPost` FROM `POST` WHERE `commentaire` = ?));
        ");
        $query->execute([$type, $imageName, $commentaire]);
    } catch (PDOException $e) {
        echo 'Exception reçue : ',  $e->getMessage(), "\n";
    }
}

function RecupererPosts()
{
    try {
        $query = ConnexionBD()->prepare("
        SELECT `commentaire`, `nomMedia` 
        FROM `POST`,`MEDIA` 
        WHERE `POST`.`idPost` = `MEDIA`.`idPost` 
        ");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Exception reçue : ',  $e->getMessage(), "\n";
    }
}
