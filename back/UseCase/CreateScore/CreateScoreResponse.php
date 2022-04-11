<?php

namespace Memory\UseCase\CreateScore;

/**
 * Cette classe permet de formater et de générer la réponse renvoyé.
 */
class CreateScoreResponse
{
    //Dans les déclarations des méthodes, on précise toujours les valeurs qui ont des paramètres par défaut a la fin.
    public function __construct(private int $statusCode, private ?string $message = null)
    {
        //On définis le code HTTP
        http_response_code($statusCode);

        //On précise qu'on renvoit du JSON
        header('Content-Type: application/json');

        //Si un message est présent, on le sérialize (on transforme le tableau en JSON)
        if ($message !== null) {
            echo json_encode(['message' => $message]);
        }
    }
}
