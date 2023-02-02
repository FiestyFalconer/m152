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

function NouvellePost($imageName, $type, $commentaire)
{
    $query = ConnexionBD()->prepare("
        INSERT INTO `POST`(`commentaire`) 
        VALUES (?);
    ");
    $query->execute([$commentaire]);
    $query = ConnexionBD()->prepare("
        INSERT INTO `MEDIA`(`typeMedia`, `nomMedia`, `idPost`) 
        VALUES (?,?,LAST_INSERT_ID());
    ");
    $query->execute([$type, $imageName]);
    
}
