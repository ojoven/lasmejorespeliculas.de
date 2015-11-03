<?php
define('SCRAPER_ROOT_PATH', __DIR__);
require_once 'models/Scraper.php';

// Let's retrieve and save the list of films
// Taken from Filmaffinity TOP FA (just films from this year)
$scraper = new Scraper();
$scraper->scrapeLastFilms();