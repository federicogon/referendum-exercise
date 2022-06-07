<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Question;
use App\Models\Referendum as ReferendumModel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;


class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function voter() : View
    {
        $questions = Question::all();
        return view('voter',['questions' =>$questions ]);
    }

    /**
     * voter's page
     */
    public function vote(Request $request)
    {
        
        $validated = Validator::make($request->all(),
        [
            'username' => 'required',
            'referendums' => 'required|array'
        ],[
            'username.required' => 'Please enter your username',
            'referendums.required' => 'At least one question should be answered'
        ]
        );
        $validated->validated();

        try {
            foreach ($request->referendums ?? [] as $key => $value) {
                
                $data = [
                    'username' => $request->username,
                    'referendum_id' => $key,
                    
                ];
                $votes = $vote = [];
                foreach($value as $key => $value){
                    $vote['question_id'] = $key;
                    $vote['vote'] = $value['vote'];
                    $votes['votes'][] =  $vote;
                }
                
                $data =   array_merge($data,$votes);
                
               (new Referendum)->vote($request->merge($data));
                
                
            }

            return redirect('results');

        } catch (\Throwable $th) {

            return back()->with('message',$th->getMessage());
        }
    }

    /**
     * Result Page
     */
    public function results() : View
    {
        
        $referendum = ReferendumModel::all();
        $results = Helper::formatResult($referendum);

        return view('results', ['results'=>$results]);
    }
}
