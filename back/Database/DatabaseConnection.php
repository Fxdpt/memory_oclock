<?php

namespace Memory\Database;

use Symfony\Component\Dotenv\Dotenv;

/**
 * Cette classe permet d'étendre le comportement de PDO et d'y ajouter nos propres modifications.
 * Ici cela nous permet de récupérer dynamiquement les informations de connexion de la base de données
 * depuis un fichier de configuration.
 */
class DatabaseConnection extends \PDO
{
    /**
     * @var string
     */
    private string $databaseName;

    /**
     * @var array<string>
     */
    public const MANDATORY_ENVIRONMENT_VARIABLES = [
        'DB_HOST',
        'DB_NAME',
        'DB_USER',
        'DB_PASSWORD',
    ];

    public function __construct()
    {
        //On charge les variables d'environnement
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__ . '/../../.env.local');

        //On vérifie que toutes les variables d'environnement nécessaires sont présentes
        foreach (self::MANDATORY_ENVIRONMENT_VARIABLES as $environmentVariable) {
            if (!array_key_exists($environmentVariable, $_ENV)) {
                throw new \Exception('Missing environment variable: ' . $environmentVariable);
            }
        }

        //Le nom de la base de données est stockées dans une propriété de notre classe afin de pouvoir l'appeler
        $this->databaseName = $_ENV['DB_NAME'];

        //On instancie notre connexion a la base de données en utilisant le constructeur du parent (ici PDO)
        parent::__construct(
            'mysql:host=' . $_ENV['DB_HOST'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASSWORD'],
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC]
        );
    }

    /**
     * Créer une base de données ainsi que ses tables si elles n'existent pas encore
     */
    public function createDatabaseIfNotExist(): void
    {
        $createDatabaseQuery = "CREATE DATABASE IF NOT EXISTS `" . $this->databaseName . "`";
        parent::query($createDatabaseQuery);
        $createTableQuery = "CREATE TABLE IF NOT EXISTS `" . $this->databaseName . "`.`scores` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `time` INT NOT NULL,
            PRIMARY KEY (`id`)
        )";
        parent::query($createTableQuery);
    }

    /**
     * Retourne le nom de la base de données
     *
     * @return string
     */
    public function getDatabaseName(): string
    {
        return $this->databaseName;
    }
}
