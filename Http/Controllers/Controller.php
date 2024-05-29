<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
class Controller extends BaseController
{
    public function getJugadores(Request $request) {
        return json_encode('Hola');
    }

}
