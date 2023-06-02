<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'mod_exam';

    public function result()
    {
        return $this->hasMany(Result::class, 'ExamID', 'ID');
    }

    public function resultPart()
    {
        return $this->hasMany(ResultPart::class, 'ExamHistoryID', 'mod_examhistory_id');
    }
}
