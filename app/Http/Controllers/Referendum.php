<?php

namespace App\Http\Controllers;

use App\Models\Question as QuestionModel;
use App\Models\QuestionVoters as QVModel;
use App\Models\QuestionVotes;
use App\Models\Referendum as ReferendumModel;
use App\Models\Voter as VoterModel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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

        if (!$referendum->save()) {
            return response()->json([
                'success' => false,
                'message' => 'An error occured'
            ]);
        }

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
     * --data-raw '{"question_id": 1, "username": "user1", "in_support": true}'
     */
    public function vote(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'question_id' => 'required|integer',
            'username' => 'required|string',
            'in_support' => 'required|boolean'
        ]);

        $voter = VoterModel::where('username', $validated['username'])->first();

        if (empty($voter)) {
            return response()->json(['success' => false, 'message' => 'Voter doesn\'t exist'], 400);
        }

        $userVoted = QVModel::where('voter_id', $voter->id)
                                ->where('question_id', $validated['question_id'])
                                ->first();

        if (!empty($userVoted)) {
            return response()->json([
                'success' => false,
                'message' => 'You have already voted'
            ]);
        }

        $vote = new QuestionVotes();
        $vote->question_id = $validated['question_id'];
        $vote->in_support = $validated['in_support'];
        $vote->save();

        $voted = new QVModel();
        $voted->voter_id = $voter->id;
        $voted->question_id = $validated['question_id'];
        $voted->save();

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
        $referendums = ReferendumModel::orderBy('order')->get();
        $response = [];

        foreach ($referendums as $referendum) {
            $orderToString = (string) $referendum->order;
            $response[$orderToString] = $referendum->only(['id', 'title', 'description']);
        }

        return response()->json(['success' => true, 'list' => array_values($response)]);
    }
}
