# MEMORY

## Setup du projet
- Créer un fichier .env.local a la racine du projet.
- Copier les clefs du fichier .env et renseigner les valeurs de connexions à la base de données.
- La base de données n'a pas besoin d'être créer en amont, cette fonctionnalité est gérée directement par le serveur.
- PHP 8.0 ou supérieur est nécessaire au bon fonctionnement de l'application
    - des features 8.0 sont utilisés dans le code (notamment l'initialisation des propriétés des classes directement dans le constructeur)
- Lancer un `composer install` pour installer la dépendance nécessaire (Dotenv) et charger l'autoload.
- Depuis le dossier racine lancer PHP sur le port 8080 `php -S localhost:8080`

### Note
- l'application a un meilleur fonctionnement si elle est lancée avec LiveServer depuis VSCode. Le comportement restera le même mais selon les navigateurs, des erreurs peuvent s'afficher pour la lecture des musiques (bien que cela n'empeche pas le fonctionnement et la lecture).

    Le fichier HTML est situé dans front/index.html
- J'ai volontairement commenté du code jQuery, j'ai fais quelques exemples d'implémentation en jQuery pour montrer que je maitrise cette librairie et qu'elle est utilisé pendant la formation.

    Cependant je préfère utiliser du Javascript natif que je trouve beaucoup plus lisible, et qui aujourd'hui couvre énormément de fonctionnalités qui rendait jQuery presque indispensable il y a encore quelque temps (requete XHR principalement)

