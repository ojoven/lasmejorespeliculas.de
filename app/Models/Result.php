<?php

namespace App\Models;

use App\Lib\Functions;
use App\Models\Director;
use App\Models\Cast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Result extends Model {

    const MAX_RESULTS = 20;

    /** SINGLE **/
    public function getSingleResult($type, $name) {

        switch ($type) {

            case 'director':
                $filmIds = Director::where('name', '=', $name)->select('id')->lists('id')->toArray();
                break;
            case 'actor':
                $filmIds = Cast::where('name', '=', $name)->select('id')->lists('id')->toArray();
                break;

        }

        $result = $this->_buildResultSingle($type, $name, $filmIds);

        return $result;

    }

    /** SEARCH **/
    public function searchResults($query) {

        // Search directors
        $directors = Director::whereRaw('LOWER(name) LIKE \'%' . strtolower($query) . '%\'')->select('name', DB::raw('count(*) as total'))->groupBy('name')->get()->toArray();
        $directors = array_slice($directors, 0, self::MAX_RESULTS);
        $directors = $this->_parseResults($directors, 'director');

        // Search cast
        $cast = Cast::whereRaw('LOWER(name) LIKE \'%' . strtolower($query) . '%\'')->select('name', DB::raw('count(*) as total'))->groupBy('name')->get()->toArray();
        $cast = array_slice($cast, 0, self::MAX_RESULTS);
        $cast = $this->_parseResults($cast, 'actor');

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
            $parsedResult['type_string'] = $this->_getStringFromType($type);
            array_push($parsedResults, $parsedResult);

        }

        return $parsedResults;

    }

    /** GET RANDOM RESULTS **/
    public function getRandomResultHome() {

        $numTotalFilms = 1000;

        // Get the first 1000 films
        $filmIds = Film::orderBy('rating', 'desc')->limit($numTotalFilms)->select('id')->lists('id')->toArray();

        // Random retrieve 1 of them
        $offset = rand(0, $numTotalFilms - 1);
        $filmId = array_slice($filmIds, $offset, 1);

        // 5 of 6 times, we'll show director, instead of actor
        $value = rand(1,6);
        if ($value==1) { // 1 of 4 possibilities: actor/actress
            $name = Cast::where('id', '=', $filmId)->select('name')->lists('name')->first();
            $filmIds = Cast::where('name', '=', $name)->select('id')->lists('id')->toArray();
            $type = 'actor';
        } else {
            $name = Director::where('id', '=', $filmId)->select('name')->lists('name')->first();
            $filmIds = Director::where('name', '=', $name)->select('id')->lists('id')->toArray();
            $type = 'director';
        }

        $result = $this->_buildResultSingle($type, $name, $filmIds);

        return $result;

    }


    private function _getStringFromType($type) {

        switch ($type) {
            case 'director': $typeString = "Director"; break;
            case 'actor': $typeString = "Actor / Actress"; break;
        }
        return $typeString;
    }

    private function _buildResultSingle($type, $name, $filmIds) {

        $result['type'] = $type;
        $result['type_string'] = $this->_getStringFromType($type);
        $result['name'] = $name;

        if (isset($filmIds) && $filmIds) {
            $result['films'] = Film::orderBy('rating', 'desc')->find($filmIds)->toArray();
        }

        return $result;

    }


}