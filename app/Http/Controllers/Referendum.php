<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
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
        $voter = new VoterModel();
        $voter->username = $request->username;
        $voter->save();
        }

        foreach($request->votes ?? [] as $vote){
            
            $question = QuestionModel::whereId($vote['question_id'])->whereReferendumId($request->referendum_id)->first();

            if($question){
               
                $qv = new QuestionVoters();
    
    
                $check = $qv->where('voter_id',$voter->id)
                ->where('question_id', $vote['question_id'])->first();

                if(!$check)
                {
                    $qv->voter_id = $voter->id;
                    $qv->question_id = $vote['question_id'];
                    $qv->save();
    
                    $qvs = new QuestionVotes();
                    $qvs->question_id = $vote['question_id'];
                    $qvs->in_support =  $vote['vote'] == 'true' ? true : false;
                    $qvs->save();
                }
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

        $results = Helper::formatResult($referendum);
        
        return response()->json([
            'success'    => true,
            'referendum' => $referendum->only(['id', 'title', 'description']),
            'results'    => $results
        ]);
    }

    /**
     * /api/referendum/results/
     */
    public function allResults(Request $request): JsonResponse
    {
        $default_sort = 'desc';
        $referendums = ReferendumModel::orderBy('order', $request->sort ?? $default_sort )->get();

        $response = [];
        foreach ($referendums as $referendum) {
            $response[$referendum->order] = $referendum->only(['id', 'title', 'order']);
            $response[$referendum->order]['results'] = Helper::formatResult($referendum);
        }
        return response()->json(['success' => true, 'list' => array_values($response)]);
    }

    public function questions(): JsonResponse
    {
        $referendums = ReferendumModel::all();
        $response = [];
        foreach ($referendums as $referendum) {
            $response[] = [
                'id' => $referendum->id,
                'questions' => $referendum->questions->toArray()
            ];
        }
        return response()->json(['success' => true, 'referendums' => $response]);
    }
}
