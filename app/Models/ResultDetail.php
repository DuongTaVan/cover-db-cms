<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultDetail extends Model
{
    use HasFactory;

    protected $connection = 'mysql';
    protected $table = 'exam_results_detail';
    public $timestamps = false;
}
