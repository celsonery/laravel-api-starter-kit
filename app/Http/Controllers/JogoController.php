<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJogoRequest;
use App\Models\Jogo;
use App\Models\Sorteio;
use CelsoNery\LoteriasApi\Facades\LoteriasApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class JogoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Jogo::paginate();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJogoRequest $request)
    {
        $validated = $request->validated();

        $data = [
            'jogo' => $validated['jogo'],
        ];

        foreach ($validated['numbers'] as $index => $number) {
            $data['n' . ($index + 1)] = $number;
        }

        if ($request->validated()) {
            $request->user()->jogos()->create($data);
        }

        return response()->json(['message' => 'successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jogo $jogo)
    {
        return $jogo->delete();
    }

    public function result(Request $id)
    {
        $result = LoteriasApi::megaSena();

        dd($result);
    }

    public function testdb()
    {
        $response = Http::get('https://loteriascaixa-api.herokuapp.com/api/megasena')->json();

        $resultados = collect($response)
            ->sortBy('concurso')
            ->values();

        foreach ($resultados as $resultado) {
            Sorteio::updateOrCreate(
                [
                    'number' => $resultado['concurso'],
                ],
                [
                    'data' => $resultado['data'],
                    'n1' => $resultado['dezenas'][0],
                    'n2' => $resultado['dezenas'][1],
                    'n3' => $resultado['dezenas'][2],
                    'n4' => $resultado['dezenas'][3],
                    'n5' => $resultado['dezenas'][4],
                    'n6' => $resultado['dezenas'][5],
                ]);
        }
        dd($resultados[0]);
    }
}
