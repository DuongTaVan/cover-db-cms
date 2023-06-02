<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'mod_question';

    public function answer()
    {
        return $this->hasMany(Answer::class, 'QuestionID', 'ID');
    }
}
