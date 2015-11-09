<?php

namespace App\Models;

use App\Models\Film;
use App\Lib\Functions;
use Illuminate\Database\Eloquent\Model;

class Review extends Model {

    public function getRandomBadReview($minimumReview = 3) {

        while (true) {

            $film = $this->_getFilmForReview();
            if (!$film) continue;

            // Let's get film's bad review
            $reviews = $this->where('id', '=', $film['id'])->where('rating', '<=', $minimumReview)->get()->toArray();

            if ($reviews) {

                $randReview = rand(1, count($reviews));
                $review = $reviews[$randReview-1];

                if (strlen($review['review'])>2000) continue; // To avoid too long review

                $complete['film'] = $film;
                $complete['review'] = $review;
                return $complete;
            }

        }

    }

    private function _getFilmForReview() {

        // We'll retrieve popular films (based on the num of votes)
        $randIndex = rand(1,500);
        $film = Film::where('rating', '>=', '7')->orderBy('num_votes', 'desc')->skip($randIndex)->first();
        return $film;

    }

}