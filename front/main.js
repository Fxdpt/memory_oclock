/******************************************************************************
 *                                                                            *
 * Ce fichier contient toutes les fonctions appelées dans notre fichier index *
 *                                                                            *
 *****************************************************************************/

/**
 * Permet de mélanger les cartes
 *
 * @param imagesPairs
 */
function shuffle(imagesPairs) {
  //Extrait une image aléatoire du tableau.
  const shuffledPairs = imagesPairs.splice(
    Math.floor(Math.random() * (IMAGES.length + 1)),
    1
  );
  //Réintroduit l'image a un emplacement aléatoire dans le tableau.
  imagesPairs.splice(
    Math.floor(Math.random() * (IMAGES.length + 1)),
    0,
    shuffledPairs[0]
  );
}

/**
 * Récupère le nombre de paires nécessaires de manière aléatoire.
 *
 * @return imagesPairs
 */
function selectAndRandomizeFruits() {
  // Récupère le nombre d'images nécessaires.
  while (IMAGES.length > PAIR_NUMBER) {
    IMAGES.splice(Math.floor(Math.random() * (IMAGES.length + 1)), 1);
  }
  // Double le tableau afin de générer des paires
  const imagesPairs = [...IMAGES, ...IMAGES];

  /**
   * On éxecute la méthode de mélange plusieurs fois afin d'avoir un tableau le
   * plus mélangé possible.
   **/
  for (let i = 0; i < 1000; i++) {
    shuffle(imagesPairs);
  }

  return imagesPairs;
}

/**
 * Compare les cartes sélectionnées.
 */
function resolveCards() {
  /**
   * Si les classe représentant le nom de nos fruits correspondent on attribue
   * la classe "matched"
   */
  if (cardsSelected[0].classList[2] === cardsSelected[1].classList[2]) {
    cardsSelected.forEach((card) => {
      card.classList.add("matched");
    });
    // On vide le tableau de cartes sélectionnées pour pouvoir recommencer.
    cardsSelected = [];
    cardsResolved = false;

    /**
     * On incrémente le nombre de paires trouvées et on les compares au nombre
     * de points à atteindre.
     */
    if (++points === PAIR_NUMBER) {
      alert("Vous avez gagnééééééééééééé");
      /**
       * On execute une requete XHR afin de soumettre le score.
       * fetch est une méthode asynchrone. pour pouvoir correctement traité la
       * réponse, nous devons attendre que la requete soit terminée.
       * C'est pour cela que nous utilisons la méthode .then() qui s'éxécutera
       * une fois la promesse résolue.
       */
      fetch("http://localhost:8080/back/index.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        /**
         * On sérialise le score en JSON afin qu'il soit correctement
         * interprété par le serveur.
         */
        body: JSON.stringify({ time }),
      })
        .then(() => {
          document.location = "./index.html";
        })
        .catch((err) => console.log(err));

      // Equivalent jQuery
      // $.ajax({
      //   contentType: "application/json",
      //   method: "POST",
      //   url: "http://localhost:8080/back/index.php",
      //   data: {
      //     time,
      //   },
      //   success: function () {
      //     document.location = "./index.html";
      //   },
      // });
    }
  } else {
    /**
     * Si les cartes ne sont pas une paire on les laisse afficher 1 seconde afin
     * que le joueur puisse mémoriser leurs emplacements.
     */
    setTimeout(() => {
      cardsSelected.forEach((card) => {
        card.classList.remove("flipped");
      });
      cardsSelected = [];
      cardsResolved = false;
    }, 1000);
  }
}

/**
 * Retourne les cartes et les compares.
 */
function resolveCardOnClick(e) {
  /**
   * Si les cartes sont déjà sélectionnées, on ne fait rien.
   */
  if (!cardsResolved) {
    /**
     * Sinon on ajoute la classe "flipped" à la carte cliquée.
     * puis on ajoute la carte a notre tableau de carte sélectionnées.
     */
    if (!e.target.classList.contains("flipped")) {
      e.target.classList.add("flipped");
      cardsSelected.push(e.target);
    }
    /**
     * Lorsque 2 cartes ont été selectionnées, on les compare.
     */
    if (cardsSelected.length === 2) {
      cardsResolved = true;
      resolveCards();
    }
  }
}

/**
 * Initialise les cartes
 *
 * @param tile
 * @param index
 * @param images
 */
function setupTile(tile, index, images) {
  //On récupère l'image correspondant au fruit.
  let fruit = images[index];

  //On ajoute la classe correspondant a l'index du fruit dans le tableau.
  tile.classList.add("tile_" + index);

  /**
   * On ajoute la classe avec le nom du fruit, qui nous servira a comparer les
   * cartes
   */
  tile.classList.add(fruit);
  /**
   * Equivalent jQuery
   * $(tile).addClass("tile_" + index + " " + fruit);
   */

  // On ajoute un event listener sur notre carte afin de pouvoir la cliquer.
  tile.addEventListener("click", resolveCardOnClick);
  /**
   * Equivalent jQuery
   * $(tile).on("click", resolveCardOnClick);
   */
}

/**
 * Lance le chronomètre
 */
function stopwatch() {
  //On initialise nos supers musique d'ambiance
  const START_MUSIC = new Audio("./assets/persona1.mp3");
  const MIDDLE_MUSIC = new Audio("./assets/persona2.mp3");
  const END_MUSIC = new Audio("./assets/persona3.mp3");

  //On récupère la div du chronomètre
  const PROGRESSION_BAR = document.querySelector("#progress-bar");

  //On génère une nouvelle div qui sera le chronomètre
  const TIME_SPAN = document.createElement("div");
  PROGRESSION_BAR.appendChild(TIME_SPAN);

  const INTERVAL = 100;
  const WIDTH_PER_INTERVAL = 0.1;
  let width = 0;
  /**
   * Pour gérer le chronomètre, on utilise la largeur de notre barre
   * de progression.
   * La function setInterval() permet de lancer une fonction toutes
   * les n millisecondes.
   * Ici on met volontairement des millisecondes très basse afin d'avoir
   * une barre de progression qui évolue de manière fluide.
   */
  setInterval(() => {
    /**
     * On incrémente le temps de 100 millisecondes.
     */
    time += INTERVAL;

    /**
     * On incrémente la largeur de la barre de progression.
     */
    width += WIDTH_PER_INTERVAL;
    TIME_SPAN.style.width = width + "%";

    /**
     * La structure switch true permet d'obtenir le même résultat qu'une
     * condition en if elseif else. Mais cette structure est à mon sens
     * beaucoup plus lisible.
     * Dans des cas ou l'on pourrait avoir beaucoup de elseif, le switch est
     * a privilégié pour plus de clareté
     */
    switch (true) {
      case width >= 30 && width <= 70:
        TIME_SPAN.style.backgroundColor = "orange";
        START_MUSIC.pause();
        START_MUSIC.currentTime = 0;
        MIDDLE_MUSIC.play();
        break;
      case width >= 70:
        TIME_SPAN.style.backgroundColor = "red";
        MIDDLE_MUSIC.pause();
        MIDDLE_MUSIC.currentTime = 0;
        END_MUSIC.play();
        break;
      default:
        TIME_SPAN.style.backgroundColor = "green";
        START_MUSIC.play();
        break;
    }
    /**
     * Lorsque la barre est rempli, cela signifie que le temps est écoulé
     * Le joueur à donc perdu :'(
     * On arrête la musique, on lui affiche un message et on réinitialise le
     * jeu en raffraichissant la page.
     */
    if (Math.floor(width) === 100) {
      END_MUSIC.pause();
      MIDDLE_MUSIC.currentTime = 0;
      alert("Vous avez perduuuuuuuuuuu :'(");
      document.location = "./index.html";
    }
  }, INTERVAL);
}

/**
 * Récupère le meilleur score depuis la base de données et l'affiche.
 */
function initializeScore() {
  /**
   * On utilise une fonction asynchrone pour récupérer les meilleurs scores.
   * Dans le cas d'une première utilisation ou si personne n'a réussi a terminer
   * le jeu, on doit se préparer à la récupération de la valeur null,
   * et donc n'afficher aucun meilleur score.
   */
  fetch("http://localhost:8080/back/index.php", {
    method: "GET",
  })
    .then((res) => {
      return res.json();
    })
    .then((scores) => {
      if (scores !== null) {
        listContainer = document.createElement("ol");
        console.log(scores);
        scores.forEach((score) => {
          var minutes = Math.floor(score.time / 60000);
          var seconds = ((score.time % 60000) / 1000).toFixed(0);
          const bestTime = `${minutes}min  ${seconds < 10 ? "0" : ""}${seconds}s`;
          const scoreItem = document.createElement("li");
          scoreItem.innerHTML = bestTime;
          listContainer.appendChild(scoreItem);
        })
        SCORE_CONTAINER.appendChild(listContainer);
      }
    });

  // Equivalent jQuery
  // $.ajax({
  //   url: "http://localhost:8080/back/index.php",
  //   method: "GET",
  //   dataType: "json",
  //   success: function (data) {
  //     if (data !== null && data.hasOwnProperty("score")) {
  //       score = data.score;
  //       var minutes = Math.floor(score.time / 60000);
  //       var seconds = ((score.time % 60000) / 1000).toFixed(0);
  //       const bestTime =
  //         minutes + "min" + (seconds < 10 ? "0" : "") + seconds + "s";
  //       SCORE_CONTAINER.innerHTML =
  //         "<span>Meilleur score: " + bestTime + " </span>";
  //     }
  //   }
  // })
}
