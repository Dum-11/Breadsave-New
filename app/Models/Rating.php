<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_rating';
    protected $fillable = [
        'id_pesanan', 
        'bintang', 
        'ulasan'
    ];
}