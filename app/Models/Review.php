<?php

namespace App\Models;

use App\Models\Film;
use App\Lib\Functions;
use Illuminate\Database\Eloquent\Model;

class Review extends Model {

    public function getRandomBadReview($minimumReview = 4) {

        while (true) {

            // First we select a random film from the first 1000
            $randIndex = rand(1, 1000);
            $film = Film::where('position', '=', $randIndex)->first()->toArray();
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

}