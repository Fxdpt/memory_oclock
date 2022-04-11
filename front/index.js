/**
Declare all variables required for the game
 */

const TILES = document.querySelectorAll('.tile');
/**
 * Equivalent jQuery
 * const TILES = $('.tile');
 */

const PAIR_NUMBER = TILES.length / 2;
let time = 0;
let points = 0;
const IMAGES = [
    'red_apple',
    'banana',
    'orange',
    'green_lemon',
    'berry',
    'apricot',
    'lemon',
    'strawberry',
    'green_apple',
    'peach',
    'grape',
    'watermelon',
    'plums',
    'pear',
    'cherry',
    'raspberry',
    'mango',
    'yellow_cherry',
];
let gameStarted = false;
let cardsSelected = [];
let cardsResolved = false;

const imagesPairs = selectAndRandomizeFruits();
TILES.forEach((tile, index) => {
    setupTile(tile, index, imagesPairs);
})
// Equivalent jQuery
// $('.tile').each((index, tile) => {
//   setupTile(tile, index, imagesPairs);
//   })

initializeScore();
stopwatch()