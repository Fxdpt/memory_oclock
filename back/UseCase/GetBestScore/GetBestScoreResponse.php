<?php

namespace Memory\UseCase\GetBestScore;

use Memory\Model\Score;

/**
 * Cette classe permet de mettre en forme et de générer la réponse renvoyé.
 */
class GetBestScoreResponse
{
    public function __construct(array $scores)
    {
        http_response_code(200);
        header('Content-Type: application/json');
        $response = [];
        if (!empty($scores)) {
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
