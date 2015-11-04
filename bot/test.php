<?php

// We'll test reviews
define('BOT_ROOT_PATH', __DIR__);
require_once 'config.php';
require_once 'lib/vendor/db.php';

numBadReviews();

function numBadReviews() {

    $db = new Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $numReviews = 0;
    for ($i=1; $i<=100; $i++) {

        // First we select a random film from the first 1000
        $db->connect();
        $result = $db->query("SELECT * FROM films WHERE position = " . $i);
        $film = $db->fetch_array_assoc($result);
        if (!$film) continue;

        echo $film['name'] . PHP_EOL;

        // Let's get film's bad reviews
        $db->connect();
        $reviews = $db->fetch_all_array("SELECT * FROM reviews WHERE id = '" . $film['id'] . "' AND rating <= 2");
        foreach ($reviews as $review) {
            echo $review['title'] . PHP_EOL;
        }

        $numReviews += count($reviews);
        echo "Num reviews: " . $numReviews . PHP_EOL;
    }

    echo "Total reviews: " . $numReviews;

}

function randomReview() {

    $db = new Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    while (true) {

        // First we select a random film from the first 1000
        $randIndex = rand(1, 1000);
        $db->connect();
        $result = $db->query("SELECT * FROM films WHERE position = " . $randIndex);
        $film = $db->fetch_array_assoc($result);
        if (!$film) continue;

        // Let's get film's bad review
        $db->connect();
        $result = $db->query("SELECT * FROM reviews WHERE id = '" . $film['id'] . "' AND rating <= 4 LIMIT 0, 1");
        $review = $db->fetch_array_assoc($result);

        if ($review) {

            echo $film['name'] . "(" . $film['rating'] . ")" . PHP_EOL . PHP_EOL;

            echo $review['title'] . PHP_EOL;
            echo $review['review'] . PHP_EOL;
            break;
        }

    }

}
