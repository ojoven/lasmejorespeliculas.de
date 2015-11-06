<?php

namespace App\Http\Controllers;

use App\Lib\Functions;
use App\Models\Result;
use App\Models\Review;
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
        return view('index', $data);

    }

    public function director($name) {

        $type = 'director';
        $name = str_replace("_", " ", $name);
        $resultModel = new Result();
        $result = $resultModel->getSingleResult($type, $name);

        $data['result'] = $result;
        return view('index', $data);

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

    /** For the bot **/
    public function randombadreview() {

        $reviewModel = new Review();
        $review = $reviewModel->getRandomBadReview(4);

        $data['review'] = $review;
        return view('review', $data);
    }

}
