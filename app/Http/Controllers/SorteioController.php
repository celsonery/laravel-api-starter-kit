<?php

namespace App\Http\Controllers;

use App\Models\Estatistica;
use App\Models\Sorteio;
use Illuminate\Support\Facades\DB;

class SorteioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $numbers = [];

        for ($i = 1; $i <= 60; $i++) {
            $numbers[$i] = Sorteio::where('n1', $i)
                ->orWhere('n2', $i)
                ->orWhere('n3', $i)
                ->orWhere('n4', $i)
                ->orWhere('n5', $i)
                ->orWhere('n6', $i)
                ->count();

            Estatistica::updateOrCreate(['id', $i], ['total' => $numbers[$i]]);
        }

        return response()->json(['numbers' => $numbers]);
    }


}
