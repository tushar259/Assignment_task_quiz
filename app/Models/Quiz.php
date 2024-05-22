<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Submission;

class Quiz extends Model
{
    // use HasFactory;
    protected $table = 'quizzes';

    public function submissions(){
        return $this->hasMany(Submission::class);
    }

    public static function getQuizInfo($id){
        return self::find($id);
    }

    public static function getAllQuiz(){
    	return self::withCount('submissions')->orderBy('id', 'DESC')->get();
    }

    public static function insertIntoTable($image1Path, $image2Path, $expirationTime){
        self::insert([
		    'image1' => $image1Path,
		    'image2' => $image2Path,
            'closes_at' => $expirationTime
		]);
		return true;
    }

    
}
