<?php

namespace Memory\UseCase\GetBestScore;

/**
 * On créer une réponse dédié pour les erreurs.
 * Cela permet de ne pas avoir de code trop chargé entre la gestion des erreurs
 * et la serialisation de la réponse du meilleur score.
 */
class GetBestScoreErrorResponse
{
    /**
     * @param integer $statusCode
     * @param string $message
     */
    public function __construct(private int $statusCode, private string $message)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode(['message' => $message]);
    }
}
