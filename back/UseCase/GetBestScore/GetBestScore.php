<?php

namespace Memory\UseCase\GetBestScore;

use Memory\Database\DatabaseConnection;
use Memory\Database\Repository\ReadScoreRepository;

/**
 * Cette classe est un "UseCase" qui permet de récupérer le meilleur score.
 * Un UseCase représente un cas précis et défini de notre application.
 * Lors de la planification d'un projet, les Use Case représente ce qu'on appelle des "Users Stories".
 * Tout le code métier lier à la récupération du meilleur score doit se retrouver dans cette classe.
 */
class GetBestScore
{
    /**
     * @var ReadScoreRepository
     */
    private ReadScoreRepository $repository;

    /**
     * @param DatabaseConnection $database
     */
    public function __construct(DatabaseConnection $database)
    {
        // On instancie notre classe qui nous permettras d'interagir avec la base de données.
        $this->repository = new ReadScoreRepository($database);
    }

    public function __invoke(): GetBestScoreResponse|GetBestScoreErrorResponse
    {
        try {
            $score = $this->repository->findBestScore();

            return new GetBestScoreResponse($score);
        } catch (\PDOException $ex) {
            return new GetBestScoreErrorResponse(
                500,
                'Quelque chose c\'est mal déroulé lors de la lecture du score depuis le stockage de données.'
            );
        } catch (\Exception $ex) {
            return new GetBestScoreErrorResponse(500, $ex->getMessage());
        }
    }
}
