<?php

namespace App\Http\Controllers;

use App\Models\Result;

use Illuminate\Http\Request;
use App\Http\Requests;

class HtmlController extends Controller {

    public function getSearch() {

        $query = $_GET['query'];
        $results = array();

        if (trim($query)!="") {
            $resultModel = new Result();
            $results = $resultModel->searchResults($query);
        }

        $data['results'] = $results;
        return view('results', $data);
    }

    public function getRandom() {

        $resultModel = new Result();
        $result = $resultModel->getRandomResult();

        $data['result'] = $result;
        return view('single', $data);

    }

    public function getSingle() {

        $type = $_GET['type'];
        $name = $_GET['name'];

        $resultModel = new Result();
        $result = $resultModel->getSingleResult($type, $name);

        $data['result'] = $result;
        return view('single', $data);
    }


}
