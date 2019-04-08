<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuController extends Controller
{

    public function __construct(){
        
    }

    public function obtenerMenu(Request $request){

        // Centro de Votacion al que pertenece
        $id_persona = $request["id_persona"];

        $centro_votacion = app('db')->select('select * from centro_votacion where id_coordinador = ' . $id_persona . ' or id_subcoordinador = ' . $id_persona);

        // Datos del menu
        $id_perfil = $request["id_perfil"];

        $menu = app('db')->select('
            select * 
            from permisos t1
            inner join menu  t2
            on t1.id_menu = t2.id_menu 
            where id_perfil = ' . $id_perfil);

        $datos = array(

            "centro_votacion" => $centro_votacion,
            "menu" => $menu

        );

        return response()->json(["code" => 200, "data" => $datos]);

    }

}
