<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IncidenciasController extends Controller{

    public function __construct(){
        
    }

    public function incidenciasCentro(Request $request){

        $id_centro = $request["id_centro"];

        $results = app('db')->select('
            select t1.id_incidencia, t1.titulo, t2.descripcion, t1.estatus 
            from incidencia t1
            inner join tipo_incidencia t2
            on t1.id_tipo = t2.id_tipo
            where id_centro = ' .$id_centro . '
            order by t1.id_incidencia desc'
        );

        $titulos = array(
            
            array(
                "key" => 'titulo',
                "label" => "Título",
                "sortable" => true
            ),
            array(
                "key" => 'descripcion',
                "label" => "Tipo",
                "sortable" => true
            ),
            array(
                "key" => 'estatus',
                "label" => "Estado",
                "sortable" => true
            ),
        
        );

        $data = array(
            "datos_tabla" => $results,
            "titulos_tabla" => $titulos
        );

        return response()->json(["code" => 200, "data" => $data]);

    }

    public function registrarIncidencia(Request $request){

        $titulo = $request["titulo"];
        $descripcion = $request["descripcion"];
        $zona = $request["zona"];
        $id_tipo = $request["tipo"];
        $id_region = $request["id_region"];
        $id_centro = $request["id_centro"];

        $result = app('db')->insert("insert into incidencia (titulo, descripcion, estatus, zona, id_tipo, id_region, id_centro) values ('$titulo', '$descripcion', 'P', '$zona', '$id_tipo', '$id_region', '$id_centro')");

        return '1';

    }

}
