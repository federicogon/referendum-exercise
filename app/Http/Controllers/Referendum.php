<?php

namespace App\Http\Controllers;

use App\Models\Question as QuestionModel;
use App\Models\QuestionVoters as QVModel;
use App\Models\QuestionVoters;
use App\Models\QuestionVotes;
use App\Models\Referendum as ReferendumModel;
use App\Models\Voter as VoterModel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class Referendum extends Controller
{
    /**
     * Example call
     * curl --location \
     * --request POST 'http://localhost:8000/api/referendum/create' \
     * --header 'Content-Type: application/json' \
     * --data-raw '{"title": "Referendum 1","description": "Referendum 1 Description","order": 100.5,"questions": ["Question number 1","Question number 2","Question number 3"]}'
     */
    public function create(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'       => 'required',
            'description' => 'required',
            'order'       => 'required|numeric',
            'questions'   => 'required|array'
        ]);

        // $voters = VoterModel::all();

        $referendum = new ReferendumModel();
        $referendum->title = $validated['title'];
        $referendum->description = $validated['description'];
        $referendum->order = $validated['order'];
        $referendum->save();

        foreach ($validated['questions'] as $questionTitle) {
            if (!empty($questionTitle)) {
                $question = new QuestionModel();
                $question->title = $questionTitle;
                $question->referendum_id = $referendum->id;
                $question->save();
                // foreach ($voters as $voter) {

                //     $qv = new QVModel();

                //     $qv->voter_id = $voter->id;
                //     $qv->question_id = $question->id;
                //     $qv->save();
                // }
            }
        }

        return response()->json(['success' => true, 'referendum_id' => $referendum->id]);
    }

    /**
     * Example call
     * curl --location \
     * --request POST 'http://localhost:8000/api/referendum/vote' \
     * --header 'Content-Type: application/json' \
     * --data-raw '{"referendum_id": 1, "username": "user1", "votes": [{"question_id": "1", "vote": true},...]}'
     */
    public function vote(Request $request): JsonResponse
    {
        
        $validated = Validator::make($request->all(),
        [
            'username' => 'required',
            'votes.*' => 'required|array',
            'referendum_id' => 'required|exists:referendums,id'
        ]
        );
        $validated->validated();

        $voter = VoterModel::where('username', $request->username)->first();
        if(!$voter){
        $voter->username = $request->username;
        $voter->save();
        }
        foreach($request->votes as $vote){
            $qvs = new QuestionVotes();
            $qvs->question_id = $vote['question_id'];
            $qvs->in_support =  $vote['vote'] == true ? true : false;
            $qvs->save();

            $qv = new QuestionVoters();
            if($qv->where('voter_id', $voter->id)->first()){

                $qv->voter_id = $voter->id;
                $qv->question_id = $vote['question_id'];
                $qv->save();

            }


        }
        
        return response()->json(['success' => true]);
    }

    /**
     * /api/referendum/results/<id>
     */
    public function results(int $id): JsonResponse
    {
        $referendum = ReferendumModel::findOrFail($id);

        $results = [];
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
        return response()->json([
            'success'    => true,
            'referendum' => $referendum->only(['id', 'title', 'description']),
            'results'    => $results
        ]);
    }

    /**
     * /api/referendum/results/
     */
    public function allResults(): JsonResponse
    {
        $referendums = ReferendumModel::orderByDesc('order')->get();
 
        $response = [];
        foreach ($referendums as $referendum) {
            $response[$referendum->order] = $referendum->only(['id', 'title', 'order']);
        }
        return response()->json(['success' => true, 'list' => array_values($response)]);
    }
}
