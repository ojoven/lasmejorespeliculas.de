<?php
require_once __DIR__. '/../config.php';
require_once __DIR__. '/../lib/Functions.php';
require_once __DIR__. '/../lib/vendor/simple_html_dom.php';
require_once __DIR__. '/../lib/vendor/db.php';

class Scraper {

    /** FILM SINGLE **/
    public function scrapeFilmSingle() {

        $numPages = 1090; // This number we've got after having run scrapeFilmList()
        for ($page = 1; $page <= $numPages; $page++) {

            Functions::log(PHP_EOL . "Page " . $page);
            Functions::log("========================");
            $pageFilms = $this->_retrieveFilmListPage($page);
            $films = $this->_parseFilmListPage($pageFilms);

            foreach ($films as $film) {

                Functions::log($film['name']);

                $filmHtml = $this->_retrieveFilmSingleHtml($film['id']);
                $this->_storeHtmlFilmSingleHtml($filmHtml, $film['id']);
                $filmObject = $this->parseFilmSingle($filmHtml, $film['id'], $film['position']);

                // Let's store in the DB
                $db = new Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                $this->_saveFilmDB($db, $filmObject);

            }

        }

    }

    public function parseFilmSingle($filmHtml, $id, $position) {

        $html = str_get_html($filmHtml);

        $film['id'] = $id;
        $film['image'] = $html->find('#movie-main-image-container', 0)->find('img', 0)->getAttribute('src');
        $film['name'] = trim($html->find('#main-title', 0)->find('span', 0)->plaintext);
        $film['rating'] = floatval(str_replace(",", ".", trim($html->find('#movie-rat-avg', 0)->plaintext)));
        $film['num_votes'] = str_replace(".", "", trim($html->find('#movie-count-rat', 0)->find('span',0)->plaintext));
        $film['position'] = $position;

        $movieInfos = $html->find('.movie-info');

        foreach ($movieInfos as $movieInfo) {

            foreach ($movieInfo->find('dt') as $dt) {

                switch($dt->plaintext) {

                    case 'Título original':
                        $film['original_name'] = trim($dt->next_sibling()->plaintext);
                        break;

                    case 'AKA':
                        $film['akas'] = array();
                        foreach ($dt->next_sibling()->find('li') as $aka) {
                            array_push($film['akas'], $aka->plaintext);
                        }
                        break;

                    case 'Año':
                        $film['year'] = trim($dt->next_sibling()->plaintext);
                        break;

                    case 'Duración':
                        $film['duration'] = trim(str_replace("min.", "", $dt->next_sibling()->plaintext));
                        break;

                    case 'País':
                        $film['country'] = trim(str_replace("&nbsp;", "", $dt->next_sibling()->plaintext));
                        break;

                    case 'Director':
                        $film['directors'] = array();
                        foreach ($dt->next_sibling()->children() as $director) {
                            array_push($film['directors'], trim($director->find('span',0)->plaintext));
                        }
                        $film['director_string'] = implode(", ", $film['directors']);
                        break;

                    case 'Guión':
                        $film['script'] = trim($dt->next_sibling()->plaintext);
                        break;

                    case 'Música':
                        $film['music'] = trim($dt->next_sibling()->plaintext);
                        break;

                    case 'Fotografía':
                        $film['photo'] = trim($dt->next_sibling()->plaintext);
                        break;

                    case 'Reparto':
                        $film['cast'] = array();
                        foreach ($dt->next_sibling()->children() as $actor) {
                            array_push($film['cast'], trim($actor->plaintext));
                        }
                        $film['cast_string'] = implode(", ", $film['cast']);
                        break;

                    case 'Productora':
                        $film['producer'] = trim($dt->next_sibling()->plaintext);
                        break;

                    case 'Género':
                        $film['genres'] = array();
                        foreach ($dt->next_sibling()->find('a') as $genre) {
                            array_push($film['genres'], trim($genre->plaintext));
                        }
                        $film['genres_string'] = implode(", ", $film['genres']);
                        break;

                    case 'Sinopsis':
                        $film['synopsis'] = trim($dt->next_sibling()->plaintext);
                        break;

                    case 'Premios':
                        $film['prizes'] = array();
                        foreach ($dt->next_sibling()->find('div') as $prize) {
                            if (strpos($prize->plaintext, "Mostrar")===FALSE) {
                                array_push($film['prizes'], trim($prize->plaintext));
                            }
                        }
                        break;

                    case 'Críticas':
                        $film['reviews']['pro'] = array();
                        foreach ($dt->next_sibling()->find('li') as $review) {
                            if (!$review->first_child()->find('div', 0)) break;

                            // Review
                            $reviewFilm['review'] = trim(str_replace("&nbsp;", "", $review->first_child()->find('div', 0)->plaintext));

                            // Author and media
                            $author = trim($review->find('.pro-crit-med', 0)->plaintext);
                            $author = explode(":", $author);
                            if (isset($author[0])) $reviewFilm['author'] = trim($author[0]);
                            if (isset($author[1])) $reviewFilm['media'] = trim($author[1]);

                            // Rating
                            $ratingI = $review->first_child()->find('.pro-crit-med', 0)->find('i', 0)->getAttribute('alt');
                            if (strpos($ratingI, "no crítica")) $rating = 0;
                            if (strpos($ratingI, "negativa")) $rating = 1;
                            if (strpos($ratingI, "neutral")) $rating = 2;
                            if (strpos($ratingI, "positiva")) $rating = 3;
                            if (isset($rating)) $reviewFilm['rating'] = $rating;

                            array_push($film['reviews']['pro'], $reviewFilm);

                        }
                        break;
                }

            }

        }

        return $film;

    }

    private function _saveFilmDB($db, $film) {

        $db->connect();

        // Films table
        $filmDb = $film;
        $relations = array('reviews', 'prizes', 'cast', 'directors', 'genres', 'akas');
        foreach ($relations as $relation) {
            unset($filmDb[$relation]);
        }
        Functions::insertDB($db, 'films', $filmDb);

        // AKAs
        if (isset($film['akas'])) {

            foreach ($film['akas'] as $aka) {
                $akaDb = array(
                    'id' => $film['id'],
                    'aka' => $aka
                );
                Functions::insertDB($db, 'akas', $akaDb);
            }

        }

        // Genres
        if (isset($film['genres'])) {

            $genresData = array();
            $indexes = array('id', 'genre');

            foreach ($film['genres'] as $genre) {
                $genreDb = array(
                    'id' => $film['id'],
                    'genre' => $genre
                );
                array_push($genresData, $genreDb);
            }

            Functions::insertMultipleDB($db, 'genres', $indexes, $genresData);

        }

        // Cast
        if (isset($film['cast'])) {

            $castData = array();
            $indexes = array('id', 'name');

            foreach ($film['cast'] as $actor) {
                $actorDb = array(
                    'id' => $film['id'],
                    'name' => $actor
                );
                array_push($castData, $actorDb);
            }

            Functions::insertMultipleDB($db, 'cast', $indexes, $castData);

        }

        // Directors
        if (isset($film['directors'])) {

            foreach ($film['directors'] as $director) {
                $directorDb = array(
                    'id' => $film['id'],
                    'name' => $director
                );
                Functions::insertDB($db, 'directors', $directorDb);
            }

        }

        // Prizes
        if (isset($film['prizes'])) {

            $prizesData = array();
            $indexes = array('id', 'prize');

            foreach ($film['prizes'] as $prize) {
                $prizeDb = array(
                    'id' => $film['id'],
                    'prize' => $prize
                );
                array_push($prizesData, $prizeDb);
            }

            Functions::insertMultipleDB($db, 'prizes', $indexes, $prizesData);

        }

        // Pro Reviews
        if (isset($film['reviews']['pro'])) {

            $reviewsData = array();
            $indexes = array('id', 'review', 'rating', 'author', 'media');

            foreach ($film['reviews']['pro'] as $review) {
                $reviewDb = array(
                    'id' => $film['id'],
                    'review' => $review['review'],
                    'rating' => $review['rating'],
                    'author' => (isset($review['author'])) ?$review['author'] : null,
                    'media' => (isset($review['media'])) ? $review['media'] : null
                );
                array_push($reviewsData, $reviewDb);
            }

            Functions::insertMultipleDB($db, 'proreviews', $indexes, $reviewsData);

        }

    }

    private function _storeHtmlFilmSingleHtml($filmHtml, $id) {

        // We'll save the HTMLs for if future additional parsing is needed
        $firstChar = $id[0];
        $pageSingleFilmPath = SCRAPER_ROOT_PATH . "/htmls/films/" . $firstChar . "/" . $id . ".html";
        if (!file_exists($pageSingleFilmPath)) {
            file_put_contents($pageSingleFilmPath, $filmHtml);
        }

    }

    private function _retrieveFilmSingleHtml($id) {

        // First char (to avoid having all films stored in the same folder)
        $firstChar = $id[0];

        // No documentaries nor TV series
        $url = "http://www.filmaffinity.com/es/film" . $id . ".html";

        $pageSingleFilmPath = SCRAPER_ROOT_PATH . "/htmls/films/" . $firstChar . "/" . $id . ".html";
        if (file_exists($pageSingleFilmPath)) {
            $filmHtml = file_get_contents($pageSingleFilmPath);
        } else {
            $filmHtml = Functions::getURL($url, array());
        }

        return $filmHtml;

    }


    /** FILM LIST **/
    public function scrapeFilmList() {

        $page = 1;

        while (true) {

            $pageFilms = $this->_retrieveFilmListPage($page);

            // Exit the loop when there's empty return
            if (trim($pageFilms)=="") break;

            $this->_storeHtmlFilmListPage($pageFilms, $page);
            $this->_parseFilmListPage($pageFilms, $page);

            $page++;

        }

    }

    private function _retrieveFilmListPage($page) {

        // No documentaries nor TV series
        $baseUrl = "http://www.filmaffinity.com/es/topgen.php?nodoc&notvse";
        $numElementsPerPage = 30;

        $pageFilePath = SCRAPER_ROOT_PATH . "/htmls/pages/page_" . $page . ".html";
        if (file_exists($pageFilePath)) {
            $pageFilms = file_get_contents($pageFilePath);
        } else {
            $paramsPost['from'] = ($page - 1) * $numElementsPerPage;
            $pageFilms = Functions::postURL($baseUrl, $paramsPost);
        }

        return $pageFilms;

    }

    private function _storeHtmlFilmListPage($pageFilms, $page) {

        // We'll save the HTMLs for if future additional parsing is needed
        $pageFilePath = SCRAPER_ROOT_PATH . "/htmls/pages/page_" . $page . ".html";
        if (!file_exists($pageFilePath)) {
            file_put_contents($pageFilePath, $pageFilms);
        }

    }

    private function _parseFilmListPage($pageFilms) {

        $filmsPage = array();
        $html = str_get_html($pageFilms);
        foreach($html->find('.fa-shadow') as $filmHtml) {

            $film = array();
            $film['position'] = $filmHtml->find('.position', 0)->plaintext;
            $film['id'] = $filmHtml->find('.movie-card', 0)->getAttribute('data-movie-id');
            $film['image'] = $filmHtml->find('.mc-poster', 0)->find('img', 0)->getAttribute('src');
            $film['name'] = trim($filmHtml->find('.mc-title', 0)->find('a', 0)->plaintext);
            $film['link'] = $filmHtml->find('.mc-title', 0)->find('a', 0)->getAttribute('href');

            array_push($filmsPage, $film);

        }

        return $filmsPage;

    }


}