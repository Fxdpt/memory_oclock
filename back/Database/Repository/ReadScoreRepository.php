<?php

namespace Memory\Database\Repository;

use Memory\Model\Score;
use Memory\Database\DatabaseConnection;

/**
 * Cette classe permet de réunir toutes les méthodes de lecture des scores depuis la base de données.
 * Cela permet de centraliser les méthodes de lecture dans une classe unique.
 */
class ReadScoreRepository
{
    /**
     * @param DatabaseConnection $database
     */
    public function __construct(private DatabaseConnection $database)
    {
    }

    /**
     * Cette méthode permet de récupérer le meilleur score en base de données.
     * Lors d'une première utilisation notre base de données sera logiquement vide.
     * Ceci n'est pas une erreur, et doit être interprété comme un résultat valide.
     * C'est pourquoi on peut également retourner null, si aucun résultat n'a été trouvé.
     *
     * @return array<Score>
     */
    public function findBestScores(): array
    {
        /**
         * On se permet ici de ne pas binder le nom de la base de données
         * car cette valeur provient d'un fichier de configuration défini par le développeur
         *
         * Si on traduit litérallement cette ligne de code en SQL, on obtient:
         * Selectionne toute les colonnes depuis la table databaseName.scores, trié par temps, dans un ordre croissant,
         * et seulement les 5 premier résultats trouvé.
         */
        $statement = $this->database->query(
            'SELECT * FROM ' . $this->database->getDatabaseName() . '.scores ORDER BY time ASC LIMIT 5'
        );

        //On vérifie toujours que notre requête c'est bien éxécuté avant de construire notre objet.
        $scores = [];
        if ($statement !== false && ($result = $statement->fetchAll(\PDO::FETCH_ASSOC)) !== false) {
            foreach ($result as $score) {
                $scores[] = new Score((int) $score['id'], (int) $score['time']);
            }

            return $scores;
        }
        return $scores;
    }
}
