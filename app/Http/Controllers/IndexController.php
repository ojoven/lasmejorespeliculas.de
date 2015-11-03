<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;
use App\Http\Requests;

// Models

class IndexController extends Controller {

    public function index() {

        $resultModel = new Result();
        $result = $resultModel->getRandomResult();

        $data['result'] = $result;
        return view('index', $data);

    }

}
