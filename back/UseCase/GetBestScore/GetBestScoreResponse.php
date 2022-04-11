<?php

namespace Memory\UseCase\GetBestScore;

use Memory\Model\Score;

/**
 * Cette classe permet de mettre en forme et de générer la réponse renvoyé.
 */
class GetBestScoreResponse
{
    public function __construct(?Score $score)
    {
        http_response_code(200);
        header('Content-Type: application/json');
        $response = null;
        if ($score !== null) {
            $response = ['score' => [
                'id' => $score->getId(),
                'time' => $score->getTime(),
            ]];
        }
        echo json_encode($response);
    }
}
