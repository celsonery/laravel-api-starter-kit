<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Sorteio extends Model
{
    /** @use HasFactory<\Database\Factories\SorteioFactory> */
    use HasFactory;

    protected $fillable = ['number', 'data', 'n1', 'n2', 'n3', 'n4', 'n5', 'n6', 'n7', 'n8', 'n9', 'n10', 'n11', 'n12'];
}
