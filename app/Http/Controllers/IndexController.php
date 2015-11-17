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
