<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Quiz;

class Submission extends Model
{
    // use HasFactory;
    protected $table = 'submissions';

    protected $fillable = [
        'quiz_id',
        'user_id',
        'answer'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function quiz(){
        return $this->belongsTo(Quiz::class);
    }

    public static function getSubmissionsByQuizId($quizId){
        $submissions = self::with(['user', 'quiz'])
            ->where('quiz_id', $quizId)
            ->get();

        return $submissions->map(function ($submission) {
            return [
                'user_name' => $submission->user->name,
                'user_email' => $submission->user->email,
                'answer' => $submission->answer,
                'total_submissions' => $submission->quiz->total_submissions,
                'closes_at' => $submission->quiz->closes_at,
            ];
        });
    }

    public static function getSelfQuizSubmissions($quizId, $userId){
        $submissions = self::with(['user', 'quiz'])
            ->where('user_id', $userId)
            ->where('quiz_id', $quizId)
            ->get();

        return $submissions->map(function ($submission) {
            return [
                'answer' => $submission->answer,
            ];
        });
    }

    public static function insertIntoSubmissionTable($userId, $quizId, $firstAnswers, $answers){
        self::where('quiz_id', $quizId)->where('user_id', $userId)->delete();

        self::insert([
            'quiz_id' => $quizId,
            'user_id' => $userId,
            'answer' => $firstAnswers
        ]);

        $countSubmissions = 1;

        foreach ($answers as $answer) {
            if (!empty($answer) && $countSubmissions < 5) {
                self::create([
                    'quiz_id' => $quizId,
                    'user_id' => $userId,
                    'answer' => $answer,
                ]);
            }
            $countSubmissions++;
        }

        return true;
    }

    public static function getTop5MostFrequentAnswers($quizId){
        return self::select('answer', DB::raw('COUNT(answer) as count'))
            ->where('quiz_id', $quizId)
            ->groupBy('answer')
            ->orderByDesc('count')
            ->limit(5)
            ->get();
    }
}
