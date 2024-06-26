<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'mod_examhistory';

    public function exam()
    {
        return $this->hasOne(Exam::class, 'ID', 'ExamID');
    }

    public function resultPart()
    {
        return $this->hasMany(ResultPart::class, 'ExamHistoryID', 'ID');
    }
}
