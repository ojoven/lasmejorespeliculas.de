<?php
define('SCRAPER_ROOT_PATH', __DIR__);
require_once 'models/Scraper.php';

// Let's retrieve and save the list of films
// Taken from Filmaffinity TOP FA
$scraper = new Scraper();
$numPages = $scraper->scrapeFilmList();
$scraper->scrapeFilmSingle($numPages);
//$scraper->scrapeFilmUserReviews();
//$scraper->scrapeLastFilms();

echo "Scraping already done!" . PHP_EOL;