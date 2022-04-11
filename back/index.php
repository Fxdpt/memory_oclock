<?php

//On place notre index dans un namespace. Cela permet une meilleur portabilité du code.
namespace Memory;

//Grace aux namespace on peut importer très facilement et de manière plus lisible les classes.
use Memory\Kernel;

/**
 * Cette ligne est nécessaire afin de charger automatiquement les classes importées ci-dessus et dans les différents
 * fichiers.
 */
require __DIR__ . '/../vendor/autoload.php';


// On instancie notre Kernel et on démarre notre petite API.
$kernel = new Kernel();
$kernel->boot();
