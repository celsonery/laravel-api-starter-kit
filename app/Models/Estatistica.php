<?php

namespace App\Models;

use Database\Factories\EstatisticaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estatistica extends Model
{
    /** @use HasFactory<EstatisticaFactory> */
    use HasFactory;

    protected $fillable = ['total'];
}
