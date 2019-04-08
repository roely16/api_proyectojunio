<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Persona;

class LoginController extends Controller
{

    public function __construct(){
        
    }

    public function login(Request $request){

        $usuario = $request["user"];

        $persona = Persona::where('usuario', '=', $usuario)->get();

        if (sizeof($peresona) <= 0) {
        
            return response()->json(["code" => 100]);

        }

        return response()->json(["code" => 200, "data" => $persona]);

    }

}
