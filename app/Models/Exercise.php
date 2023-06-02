<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'mod_test';

    public function question(){
        return $this->hasMany(Question::class, 'TestID', 'ID');
    }
}
