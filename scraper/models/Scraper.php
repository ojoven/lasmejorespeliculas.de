<?php
require_once __DIR__. '/../lib/Functions.php';
require_once __DIR__. '/../lib/vendor/simple_html_dom.php';

class Scraper {

    public function scrapeFilmList() {

        $page = 1;

        while (true) {

            Functions::log("Retrieving films for page " . $page);
            $pageFilms = $this->_retrieveFilmListPage($page);

            // Exit the loop when there's empty return
            if (trim($pageFilms)=="") break;

            $this->_storeHtmlFilmListPage($pageFilms, $page);
            $this->_parseFilmListPage($pageFilms, $page);

            //if ($page>=12) break; // Limit for initial testing

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
        file_put_contents($pageFilePath, $pageFilms);

    }

    private function _parseFilmListPage($pageFilms, $page) {

        $html = str_get_html($pageFilms);
        foreach($html->find('.fa-shadow') as $filmHtml) {

            $film = array();
            $film['position'] = $filmHtml->find('.position', 0)->plaintext;
            $film['id'] = $filmHtml->find('.movie-card', 0)->getAttribute('data-movie-id');
            $film['image'] = $filmHtml->find('.mc-poster', 0)->find('img', 0)->getAttribute('src');
            $film['name'] = trim($filmHtml->find('.mc-title', 0)->find('a', 0)->plaintext);
            $film['link'] = $filmHtml->find('.mc-title', 0)->find('a', 0)->getAttribute('href');

            Functions::log($film['link']);

        }

    }


}