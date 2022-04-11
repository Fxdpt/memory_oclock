<?php

namespace Memory;

use Memory\Database\DatabaseConnection;
use Memory\UseCase\CreateScore\CreateScore;
use Memory\UseCase\GetBestScore\GetBestScore;

class Kernel
{
    private DatabaseConnection $database;

    /**
     * Démarre l'application côté serveur.
     *
     * @return void
     * @throws \Exception
     */
    public function boot(): void
    {
        $this->checkPhpVersion();
        $this->defineHeaders();

        // On instancie notre connexion a la base de données.
        $this->database = new DatabaseConnection();

        // On créer notre base de données si elle n'existe pas.
        $this->database->createDatabaseIfNotExist();

        // On traite les requêtes HTTP reçues.
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $this->createScore();
                break;
            case 'GET':
                $this->getBestScore();
                break;
            default:
                if ($_SERVER['REQUEST_METHOD'] !== 'OPTIONS') {
                    throw new \Exception('Method not allowed : ' . $_SERVER['REQUEST_METHOD']);
                }
                break;
        }
    }

    /**
     * Pour des raisons de compatibilités on vérifie que l'utilisateur à une version suffisante de PHP.
     *
     * @return void
     */
    private function checkPhpVersion(): void
    {
        if (version_compare(phpversion(), '8.0.0', '<')) {
            echo 'You need PHP 8.0 or higher to run this application.';
            exit(1);
        }
    }

    /**
     * On définis ces headers car la partie front et la partie back sont sur un même serveur
     * mais sur des ports différents.
     * Cela peut parfois amener à des problèmes de sécurité bloqué automatiquement par les navigateurs.
     * On appelle cela les CORS
     * https://developer.mozilla.org/fr/docs/Web/HTTP/CORS
     * Ici nous sommes dans un cadre restreint d'utilisation et pouvons nous permettre d'appliquer ces headers.
     * Dans une situation professionnel, il convient de mesurer les risques avant d'entreprendre cette action.
     *
     * @return void
     */
    private function defineHeaders(): void
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
    }

    /**
     * Execution du use case pour créer un score.
     *
     * @return void
     */
    private function createScore(): void
    {
        // On récupère le corps de notre requête qui a été envoyé en JSON.
        $request = file_get_contents('php://input');

        //On transforme notre JSON en tableau.
        $data = json_decode($request, true);

        /**
         * On instancie notre useCase.
         *
         * La méthode "magique" __invoke() présente dans notre useCase permet de transformer
         * notre instance en "callable"
         * c'est à dire que je peut l'appeler comme une fonction.
         *
         * https://www.php.net/manual/fr/language.types.callable.php
         * https://www.php.net/manual/fr/language.oop5.magic.php#object.invoke
         */
        $createScoreUseCase = new CreateScore($this->database);
        $createScoreUseCase($data);
    }

    /**
     * Execution du use case pour récupérer le meilleur score.
     *
     * @return void
     */
    private function getBestScore(): void
    {
        $getBestScoreUseCase = new GetBestScore($this->database);
        $getBestScoreUseCase();
    }
}
