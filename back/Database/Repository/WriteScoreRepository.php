<?php

namespace Memory\Database\Repository;

use Memory\Model\Score;
use Memory\Database\DatabaseConnection;

/**
 * Cette classe permet de réunir toutes les méthodes d'écriture des scores depuis la base de données.
 * Cela permet de centraliser les méthodes d'écriture dans une classe unique.
 */
class WriteScoreRepository
{
    /**
     * @param DatabaseConnection $database
     */
    public function __construct(private DatabaseConnection $database)
    {
    }

    /**
     * @param Score $score
     */
    public function createScore(Score $score): void
    {
        /**
         * Comme dans l'autre repository on ne bind pas le nom de la base de données.
         * Cependant ici on bind la valeur time car elle provient de l'utilisateur et pourrait être malicieuse.
         * On se souvient de l'adage "NTUI" => Never Trust User Input
         * Lorsque PDO va construire notre requete, il va automatiquement "nettoyer" les paramètres binder pour éviter
         * toute injection SQL.
         */
        $createScoreStatement = $this->database->prepare(
            'INSERT INTO ' . $this->database->getDatabaseName() . '.scores (time) VALUES (:time)'
        );
        $createScoreStatement->bindValue('time', $score->getTime());
        $createScoreStatement->execute();
    }
}
