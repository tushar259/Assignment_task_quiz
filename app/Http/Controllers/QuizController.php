<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Quiz;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Carbon;

class QuizController extends Controller
{
    public function uploadQuiz(Request $request){
        $request->validate([
            'image1' => 'required|image',
            'image2' => 'required|image',
            'time' => 'required'
        ]);

        $image1Path = $this->storeImage($request->file('image1'), 'image1');
        $image2Path = $this->storeImage($request->file('image2'), 'image2');

        $timeString = $request->input('time');
        $expirationTime = $this->calculateExpirationTime($timeString);

        Quiz::insertIntoTable($image1Path, $image2Path, $expirationTime);

        return redirect()->route('dashboard')->with('success', 'Images uploaded successfully!');
    }

    private function storeImage($image, $imageName){
        $destinationPath = public_path('images/quiz');
        $fileName = $imageName.'_'.time().'.'.$image->getClientOriginalExtension();
        $image->move($destinationPath, $fileName);

        return 'images/quiz/'.$fileName;
    }

    private function calculateExpirationTime($timeString){
        $now = Carbon::now();

        switch ($timeString) {
            case '1 min':
                return $now->addMinute();
            case '5 min':
                return $now->addMinutes(5);
            case '10 min':
                return $now->addMinutes(10);
            case '20 min':
                return $now->addMinutes(20);
            case '30 min':
                return $now->addMinutes(30);
            case '1 Hour':
                return $now->addHour();
            case '1 Day':
                return $now->addDay();
            case '2 Days':
                return $now->addDays(2);
            default:
                return $now;
        }
    }

    public function showQuizList(){
    	$listOfQuiz = Quiz::getAllQuiz();
        // return $listOfQuiz;
    	return view("quiz-list", ['listOfQuiz' => $listOfQuiz]);
    }

    public function quizSubmissionInfo($id){
        $quizInfo = Quiz::getQuizInfo($id);
        $submissions = Submission::getSubmissionsByQuizId($id);
    	$answersCount = Submission::getTop5MostFrequentAnswers($id);
    	return view("quiz", ['quizInfo' => $quizInfo, 'listOfSubmissions' => $submissions, 'answersCount' => $answersCount]);
    }

    public function quizInfoToAnswer($id){
        $quizInfo = Quiz::getQuizInfo($id);

        $currentTime = Carbon::now();
        $closesAt = Carbon::parse($quizInfo->closes_at);


        if ($currentTime->lessThan($closesAt)){
            $validTime = "valid";    
        } 
        else{
            $validTime = "invalid";
        }
        
        $userId = Auth::id();
        // $user = Auth::user();
        $quizSubmissions = Submission::getSelfQuizSubmissions($id, $userId);

        return view("quiz", ['quizInfo' => $quizInfo, 'quizSelfSubmissions' => $quizSubmissions, 'validTime' => $validTime]);
    }

    public function addSubmission(Request $request){
        $request->validate([
            'quizId' => 'required',
            'newAnswer' => 'required|max:255',
            'answers' => 'array',
            'answers.*' => 'string|max:255',
        ]);

        $userId = Auth::id();
        $quizId = $request->input('quizId');
        $firstAnswers = $request->input('newAnswer');
        $answers = $request->input('answers', []);

        Submission::insertIntoSubmissionTable($userId, $quizId, $firstAnswers, $answers);

        return redirect()->route('dashboard');

    }
}
