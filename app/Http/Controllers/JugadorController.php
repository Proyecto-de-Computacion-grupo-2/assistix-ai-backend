<?php

namespace App\Http\Controllers;

use App\Models\jugador;
use Illuminate\Http\Request;

class JugadorController extends Controller
{
    public function getAll(Request $request)
    {
        $jugadores = jugador::get();
        return json_encode($jugadores);
    }

    public function get(Request $request, jugador  $id){
        return json_encode($id);
    }
}
