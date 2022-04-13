<?php

namespace Memory\UseCase\GetBestScore;

use Memory\Model\Score;

/**
 * Cette classe permet de mettre en forme et de générer la réponse renvoyé.
 */
class GetBestScoreResponse
{
    /**
     * @param array<Score> $scores
     */
    public function __construct(array $scores)
    {
        http_response_code(200);
        header('Content-Type: application/json');
        $response = [];
        if (!empty($scores)) {
            /**
             * Pour uniformiser nos différents objets, on utilise la fonction array_map afin de créer un tableau
             * contenant les propriétés de nos objets. on retourne ensuite notre tableau encodé en JSON pour qu'il soit
             * exploitable par la partie client de l'application.
             */
            $response = array_map(
                fn (Score $score) => [
                    'id' => $score->getId(),
                    'time' => $score->getTime(),
                ],
                $scores
            );
        }

        echo json_encode($response);
    }
}
