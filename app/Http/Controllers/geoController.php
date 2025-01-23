<?php

namespace App\Http\Controllers;

use App\Http\Controllers\GlobalController as Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class geoController extends Controller
{
    public function geoController(Request $request)
    {
        // Aquí definimos la URL externa a la que se hará la solicitud
        $url = 'https://astiango.co/api/assets/geoip';

        // Hacemos la solicitud GET o POST dependiendo de lo que necesites
        $response = Http::withHeaders([
            'Accept' => 'application/json',  // Asegúrate de que estás indicando que aceptas JSON
            'Content-Type' => 'application/json',
        ])->get($url, $request->query());

        // Retornar la respuesta tal cual
        return response()->json($response->json());
        //return response()->json(['message' => 'La ruta fue encontrada!']);
    }
}
