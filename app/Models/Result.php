<?php

namespace App\Models;

use App\Lib\Functions;
use App\Models\Director;
use App\Models\Cast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Result extends Model {

    const MAX_RESULTS = 20;

    public function searchResults($query) {

        // Search directors
        $directors = Director::whereRaw('LOWER(name) LIKE \'%' . strtolower($query) . '%\'')->select('name', DB::raw('count(*) as total'))->groupBy('name')->get()->toArray();
        $directors = array_slice($directors, 0, self::MAX_RESULTS);
        $directors = $this->_parseResults($directors, 'Director');

        // Search cast
        $cast = Cast::whereRaw('LOWER(name) LIKE \'%' . strtolower($query) . '%\'')->select('name', DB::raw('count(*) as total'))->groupBy('name')->get()->toArray();
        $cast = array_slice($cast, 0, self::MAX_RESULTS);
        $cast = $this->_parseResults($cast, 'Actor/Actress');

        // Mix
        $results = array_merge($directors, $cast);

        // Limit
        $results = array_slice($results, 0, self::MAX_RESULTS);

        return $results;

    }

    private function _parseResults($nonParsedResults, $type) {

        $parsedResults = array();

        foreach ($nonParsedResults as $result) {

            $parsedResult['name'] = $result['name'];
            $parsedResult['total'] = $result['total'];
            $parsedResult['type'] = $type;
            array_push($parsedResults, $parsedResult);

        }

        return $parsedResults;

    }


}