<?php

namespace App\Http\Controllers;

use App\Lib\Functions;
use App\Models\Result;
use App\Models\Review;
use App\Models\Film;
use Illuminate\Http\Request;
use App\Http\Requests;

// Models

class IndexController extends Controller {

    public function index() {

        return view('index');

    }

    public function actor($name) {

        $type = 'actor';
        $name = str_replace("_", " ", $name);
        $resultModel = new Result();
        $result = $resultModel->getSingleResult($type, $name);

        $data['result'] = $result;
        $view = (isset($_GET['profile'])) ? "profile" : "index";
        return view($view, $data);

    }

    public function director($name) {

        $type = 'director';
        $name = str_replace("_", " ", $name);
        $resultModel = new Result();
        $result = $resultModel->getSingleResult($type, $name);

        $data['result'] = $result;
        $view = (isset($_GET['profile'])) ? "profile" : "index";
        return view($view, $data);

    }

    public function search($query) {

        $results = array();

        if (trim($query)!="") {
            $resultModel = new Result();
            $results = $resultModel->searchResults($query);
        }

        $data['results'] = $results;
        return view('index', $data);

    }

    /** Screenshots **/
    public function generateprofile($type, $name) {

        $profilePath = base_path() . "/public/img/profiles/" . $type . "_" . $name . ".jpg";
        if (!file_exists($profilePath)) {

            // We generate the screenshot if it doesn't exist yet
            $url = "http://filmaffinity.local.host/" . $type . "/" . $name . "?profile";
            $pathToPhantomJs = app_path() . "/Lib/phantomjs/renderpanel.js";
            $extension = "jpg";

            $command = "phantomjs --ssl-protocol=any " . $pathToPhantomJs .  " " . $url . " " . $profilePath . " " . $extension;
            $return = shell_exec($command);

            // Permissions
            chmod($profilePath, 0755);

        }

        // We serve the image
        header('Content-Type: image/jpeg');
        if (!file_exists($profilePath)) throw new Exception("We couldn't generate the picture, sorry");
        $img = imagecreatefromjpeg($profilePath);
        imagejpeg($img);
        die();

    }

    /** For the bot **/
    public function review($reviewId) {

        $reviewModel = new Review();
        $filmModel = new Film();

        $review['review'] = $reviewModel->where('review_id', '=', $reviewId)->first()->toArray();
        $review['film'] = $filmModel->where('id', '=', $review['review']['id'])->first()->toArray();

        $data['review'] = $review;
        return view('review', $data);
    }

    public function randombadreview() {

        $reviewModel = new Review();
        $review = $reviewModel->getRandomBadReview(3);

        // we return a json
        return json_encode($review);
    }

}
