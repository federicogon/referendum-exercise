<?php

namespace App\Helpers;


class Helper 
{

    public static function formatResult($data) : array
    {
        $results = [];

        if(!isset($data->questions)){
            foreach ($data as $referendum) {
 
                foreach ($referendum->questions as $question) {
                    $totalVotes = 0;
                    $yesVotes = 0;
                    foreach ($question->votes as $vote) {
                        $totalVotes++;
                        if ($vote->in_support == 1) {
                            $yesVotes++;
                        }
                    }
                    $results[] = [
                        'question_id' => $question->id,
                        'question'    => $question->title,
                        'votes'       => $totalVotes,
                        'yesVotes'    => $yesVotes
                    ];
                }
            }
            return $results;
        }
        $referendum = $data;
        foreach ($referendum->questions as $question) {
            $totalVotes = 0;
            $yesVotes = 0;
            foreach ($question->votes as $vote) {
                $totalVotes++;
                if ($vote->in_support == 1) {
                    $yesVotes++;
                }
            }
            $results[] = [
                'question_id' => $question->id,
                'question'    => $question->title,
                'votes'       => $totalVotes,
                'yesVotes'    => $yesVotes
            ];
        }
        return $results;
    }
}

    