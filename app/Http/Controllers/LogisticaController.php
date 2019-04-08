<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogisticaController extends Controller{

    public function __construct(){
        
    }

    public function obtenerLista(Request $request){

        $id_persona = $request["id_persona"];

        $result = app('db')->select('select fiscales, abierto, acreditados, desayuno, asistencia_am, almuerzo, asistencia_pm, refaccion_pm, cerrado from centro_votacion where id_coordinador = ' . $id_persona  . ' or id_subcoordinador = ' . $id_persona);

        $lista = array(
            array (
                "name" => "Fiscales Completos", "id" => 1, "estado" => $result[0]->fiscales, "icono" => "./teamwork.png", "campo" => "fiscales"
            ),
            array(
                "name" => "Apertura de Centro", "id" => 2, "estado" => $result[0]->abierto, "icono" => "./doorway.png", "campo" => "abierto"
            ),
            array(
                "name" => "Fiscales Acreditados", "id" => 3, "estado" => $result[0]->acreditados, "icono" => "./clipboard.png", "campo" => "acreditados"
            ),
            array(
                "name" => "Desayuno", "id" => 4, "estado" => $result[0]->desayuno, "icono" => "./breakfast.png", "campo" => "desayuno"
            ),
            array(
                "name" => "Afluencia AM", "id" => 5, "estado" => $result[0]->asistencia_am, "icono" => "./sunrise.png", "campo" => "asistencia_am"
            ),
            array(
                "name" => "Almuerzo", "id" => 6, "estado" => $result[0]->almuerzo, "icono" => "./lunch-box.png", "campo" => "almuerzo"
            ),
            array(
                "name" => "Afluencia PM", "id" => 7, "estado" => $result[0]->asistencia_pm, "icono" => "./sunset.png", "campo" => "asistencia_pm"
            ),
            array(
                "name" => "Cena", "id" => 8, "estado" => $result[0]->refaccion_pm, "icono" => "./coffee-cup.png", "campo" => "refaccion_pm"
            ),
            array(
                "name" => "Cierre de Centro", "id" => 9, "estado" => $result[0]->cerrado, "icono" => "./padlock.png", "campo" => "cerrado"
            ),
        );
        
        return response()->json(["code" => 200, "data" => $lista]);

    }

    public function cambiarEstado(Request $request){

        $id_centro = $request["id_centro"];
        $estado = $request["estado"];
        $campo = $request["campo"];

        $result = app('db')->update("update centro_votacion set " . $campo . " = '$estado' where id_centro = " . $id_centro);

        return $result;

    }
}
