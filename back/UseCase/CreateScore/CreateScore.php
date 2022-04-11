<?php

namespace Memory\UseCase\CreateScore;

use Memory\Database\DatabaseConnection;
use Memory\Database\Repository\WriteScoreRepository;
use Memory\Model\Score;

/**
 * Cette classe est un "UseCase" qui permet de créer un score.
 * Un UseCase représente un cas précis et défini de notre application.
 * Lors de la planification d'un projet, les Use Case représente ce qu'on appelle des "Users Stories".
 * Tout le code métier lier à la création d'un score doit se retrouver dans cette classe.
 */
class CreateScore
{
    /**
     * @var WriteScoreRepository
     */
    private WriteScoreRepository $repository;

    /**
     * @param DatabaseConnection $database
     */
    public function __construct(private DatabaseConnection $database)
    {
        // On instancie notre classe qui nous permettras d'interagir avec la base de données.
        $this->repository = new WriteScoreRepository($database);
    }

    /**
     * @param array $receivedScore
     */
    public function __invoke(array $receivedScore): CreateScoreResponse
    {
        /**
         * Le block try catch nous permet "d'essayer" d'éxécuter un bloc de code, "d'attraper" les exceptions générées.
         * On peut enchainer les catch, en faisant attention d'aller du plus précis au plus générique.
         * ex: (PDOException hérite d'Exception qui hérite de Throwable)
         * Si je catch Throwable en premier, je récupèrerais donc toutes les exceptions qui en hérite.
         *
         * Cela nous permet d'affiner nos messages d'erreur en fonction des cas.
         * Typiquement lors d'une erreur de Type PDOException, on ne veut pas forcément afficher la structure de la base
         * de données qui pourrait être renvoyé dans le message d'erreur.
         */
        try {
            $this->validateDataOrFail($receivedScore);

            /**
             * On instancie notre score. Vu que c'est une nouvelle instance qui n'existe pas encore en base de données,
             * on ne lui donne pas d'id
             */
            $score = new Score(null, $receivedScore['time']);

            /**
             * On fait persister nos données en base de données grâce au repository.
             */
            $this->repository->createScore($score);

            return new CreateScoreResponse(201);
        } catch (\PDOException) {
            return new CreateScoreResponse(
                500,
                'Quelque chose c\'est mal déroulé lors de la création du score dans le stockage de données.'
            );
        } catch (\InvalidArgumentException $ex) {
            return new CreateScoreResponse(400, $ex->getMessage());
        } catch (\Exception $ex) {
            return new CreateScoreResponse(500, $ex->getMessage());
        }
    }

    /**
     * @param array<string,int>
     */
    private function validateDataOrFail(array $data): void
    {
        /**
         * On controle ici que les données reçues sont valides.
         * Si nous n'avons pas de temps, ce n'est pas normal.
         * Si le temps n'est pas un chiffre, ça n'est pas normal.
         * Si le temps est négatif, le joueur est surement un tricheur :)
         * Dans tous les cas on génère une exception pour indiquer à l'utilisateur que les données sont invalides.
         */
        if (!isset($data['time']) || !is_numeric($data['time']) || $data['time'] < 0) {
            throw new \InvalidArgumentException("Propriété 'time' manquante ou invalide.");
        }
    }
}
