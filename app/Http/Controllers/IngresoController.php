<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IngresoController extends Controller{

    public function __construct(){
    
    }

    public function  obtenerMesas(Request $request){

        $id_centro = $request["id_centro"];

        $results = app('db')->select('select id_mesa, tendencia, estatus from mesa_x_centro where id_centro = ' . $id_centro);

        //Por cada mesa buscar ingreso de alcade y presidente
        foreach ($results as $mesa) {

            //Presidente
            $presidente = app('db')->select('select * from conteo_x_mesa where id_mesa = ' . $mesa->id_mesa . ' and id_tipo = 1');

            //Alcalde
            $alcalde = app('db')->select('select * from conteo_x_mesa where id_mesa = ' . $mesa->id_mesa . ' and id_tipo = 2');

            if ($presidente) {
                $mesa->presidente = true;
            }

            if ($alcalde) {
                $mesa->alcalde = true;
            }

        }

        $titulos = array(
            array(
                "key" => "id_mesa",
                "label" => "Mesa"
            ),
            // array(
            //     "key" => "estatus",
            //     "label" => "Estado"
            // ),
            array(
                "key" => "p",
                "label" => "P"
            ),
            array(
                "key" => "a",
                "label" => "A"
            )
        );

        $data = array(
            "mesas" => $results,
            "titulos" => $titulos
        );

        return response()->json(["code" => 200, "data" => $data]);

    }

    public function partidosIngreso(Request $request){

        $id_centro = $request["id_centro"];
        $id_mesa = $request["id_mesa"];

        //Partidos para presidente
        $presidente = app('db')->select("select * from partido where ingreso_presidente = 'S' ");

        //Por cada partido buscar registro de resultados
        foreach ($presidente as $partido_presidente) {
            
            $total = app('db')->select('select cantidad, id_tipo from conteo_x_mesa where id_mesa = ' . $id_mesa . ' and id_centro = ' .$id_centro. ' and id_tipo = 1 and id_partido = ' .$partido_presidente->id_partido);

            if ($total) {

                $partido_presidente->total_votos = $total[0]->cantidad;
                $partido_presidente->id_tipo = $total[0]->id_tipo;

            }else{

                // $partido_presidente->total_votos = null;
                $partido_presidente->id_tipo = 1;

            }

        }   

        //Partidos para alcalde
        $alcalde = app('db')->select("select * from partido where ingreso_alcalde = 'S' ");

        //Por cada partido buscar registro de resultados
        foreach ($alcalde as $partido_alcalde) {
    
            $total = app('db')->select('select cantidad, id_tipo from conteo_x_mesa where id_mesa = ' . $id_mesa . ' and id_centro = ' .$id_centro. ' and id_tipo = 2 and id_partido = ' .$partido_alcalde->id_partido);

            if ($total) {

                $partido_alcalde->total_votos = $total[0]->cantidad;
                $partido_alcalde->id_tipo = $total[0]->id_tipo;

            }else{

                $partido_alcalde->total_votos = null;
                $partido_alcalde->id_tipo = 2;

            }

        }   

        $data = array(
            "presidente" => $presidente,
            "alcalde" => $alcalde
        );

        return response()->json(["code" => 200, "data" => $data]);

    }

    public function registrarResultados(Request $request){
        
        $id_centro = $request["id_centro"];
        $id_mesa = $request["id_mesa"];
        $partidos = $request["partidos"];

        foreach ($partidos as $partido) {
            
            $id_partido = $partido["id_partido"];
            $id_tipo = $partido["id_tipo"];
            $total_votos = $partido["total_votos"];

            $result = app('db')->insert("insert into conteo_x_mesa (id_mesa, id_centro, id_partido, id_tipo, cantidad) values ('$id_mesa', '$id_centro', '$id_partido', '$id_tipo', '$total_votos') on duplicate key update cantidad = '$total_votos'");

        }        

        return $request;

    }

}
