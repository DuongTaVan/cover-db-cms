<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $connection = 'mysql2';
    protected $table = 'mod_order';

    /**
     * @var array $fillable
     */
    protected $fillable = [
        'name', 'url', 'size', 'type'
    ];
}
