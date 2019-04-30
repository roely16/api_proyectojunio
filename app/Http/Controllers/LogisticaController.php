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

    public function resultadosLogisticaRegion(Request $request){

        $id_region = $request["id_region"];

        $zonas = app('db')->select("select zona, count(*) as total from centro_votacion where id_region = $id_region group by zona");

        $lista = array(
            array (
                "name" => "Fiscales Completos", "id" => "1", "icono" => "./teamwork.png", "campo" => "fiscales"
            ),
            array(
                "name" => "Apertura de Centro", "id" => "2", "icono" => "./doorway.png", "campo" => "abierto"
            ),
            array(
                "name" => "Fiscales Acreditados", "id" => "3", "icono" => "./clipboard.png", "campo" => "acreditados"
            ),
            array(
                "name" => "Desayuno", "id" => "4", "icono" => "./breakfast.png", "campo" => "desayuno"
            ),
            array(
                "name" => "Afluencia AM", "id" => "5", "icono" => "./sunrise.png", "campo" => "asistencia_am"
            ),
            array(
                "name" => "Almuerzo", "id" => "6", "icono" => "./lunch-box.png", "campo" => "almuerzo"
            ),
            array(
                "name" => "Afluencia PM", "id" => "7", "icono" => "./sunset.png", "campo" => "asistencia_pm"
            ),
            array(
                "name" => "Cena", "id" => "8", "icono" => "./coffee-cup.png", "campo" => "refaccion_pm"
            ),
            array(
                "name" => "Cierre de Centro", "id" => "9", "icono" => "./padlock.png", "campo" => "cerrado"
            ),
        );

        $datos = array();
        
        foreach ($lista as $item) {
            
            $zonas_array = array();
            $field = $item["campo"];

            $suma_centros = 0;
            $suma_centros_ok = 0;

            foreach ($zonas as $zona) {

                $total = $zona->total;

                $query = "select count(*) as total_finalizados from centro_votacion where $field = 'S' and zona = $zona->zona and id_region = $id_region ";

                $resultados = app('db')->select($query);

                $zona->total_finalizados = $resultados[0]->total_finalizados;
                $zona->campo = $field;

                $suma_centros = $suma_centros + $zona->total;
                $suma_centros_ok = $suma_centros_ok + $resultados[0]->total_finalizados;

                $zonas_array [] = (array) $zona;

            }

            $item["zonas"] = $zonas_array;

            $item["porcentaje"] = round(($suma_centros_ok / $suma_centros) * 100);

            $datos[] = $item;

        }

        $titulos = array(
            
            array(
                "key" => 'zonas',
                "label" => "Zona"
            ),
            array(
                "key" => 'total',
                "label" => "Total",
                "class" => "text-right"
            ),
            
        
        );

        $data = array(
            "datos_tabla" => $datos,
            "titulos_tabla" => $titulos
        );

        return response()->json(["code" => 200, "data" => $data]);

    }

    public function detallesZonaLogisica(Request $request){

        $campo = $request["campo"];
        $region = $request["region"];
        $zona = $request["zona"];

        $results = app('db')->select(" select nombre, $campo as estado from centro_votacion where zona = $zona and id_region = $region ");

        return $results;

    }

    public function resultadosLogisticaGeneral(){

        $regiones = app('db')->select("
            select t2.id_region, t2.descripcion, count(*) as total 
            from centro_votacion t1
            inner join region t2
            on t1.id_region = t2.id_region
            group by t2.id_region, t2.descripcion");

        $lista = array(
            array (
                "name" => "Fiscales Completos", "id" => "1", "icono" => "./teamwork.png", "campo" => "fiscales"
            ),
            array(
                "name" => "Apertura de Centro", "id" => "2", "icono" => "./doorway.png", "campo" => "abierto"
            ),
            array(
                "name" => "Fiscales Acreditados", "id" => "3", "icono" => "./clipboard.png", "campo" => "acreditados"
            ),
            array(
                "name" => "Desayuno", "id" => "4", "icono" => "./breakfast.png", "campo" => "desayuno"
            ),
            array(
                "name" => "Afluencia AM", "id" => "5", "icono" => "./sunrise.png", "campo" => "asistencia_am"
            ),
            array(
                "name" => "Almuerzo", "id" => "6", "icono" => "./lunch-box.png", "campo" => "almuerzo"
            ),
            array(
                "name" => "Afluencia PM", "id" => "7", "icono" => "./sunset.png", "campo" => "asistencia_pm"
            ),
            array(
                "name" => "Cena", "id" => "8", "icono" => "./coffee-cup.png", "campo" => "refaccion_pm"
            ),
            array(
                "name" => "Cierre de Centro", "id" => "9", "icono" => "./padlock.png", "campo" => "cerrado"
            ),
        );

        $datos = array();
        
        foreach ($lista as $item) {
            
            $regiones_array = array();
            $field = $item["campo"];
            $nombre = $item["name"];

            $suma_regiones = 0;
            $suma_regiones_ok = 0;

            foreach ($regiones as $region) {

                $total = $region->total;
                $id_region = $region->id_region;

                $query = "select count(*) as total_finalizados from centro_votacion where $field = 'S' and id_region = $id_region ";

                $resultados = app('db')->select($query);

                $region->total_finalizados = $resultados[0]->total_finalizados;
                $region->campo = $field;
                $region->nombre = $nombre;

                $suma_regiones = $suma_regiones + $region->total;
                $suma_regiones_ok = $suma_regiones_ok + $resultados[0]->total_finalizados;

                $regiones_array [] = (array) $region;

            }

            $item["regiones"] = $regiones_array;

            $item["porcentaje"] = round(($suma_regiones_ok / $suma_regiones) * 100);

            $datos[] = $item;

        }

        $titulos = array(
            
            array(
                "key" => 'regiones',
                "label" => "Región"
            ),
            array(
                "key" => 'total',
                "label" => "Total",
                "class" => "text-right"
            ),
            
        
        );

        $data = array(
            "datos_tabla" => $datos,
            "titulos_tabla" => $titulos
        );

        return response()->json(["code" => 200, "data" => $data]);

        //return $datos;

    }

    public function resumenLogisticaRegion(Request $request){

        $id_region = $request["region"];
        $campo = $request["campo"];

        $zonas = app('db')->select("select zona, count(*) as total, sum($campo = 'S') as total_ok from centro_votacion where id_region = $id_region group by zona");

        $titulos = array(
            
            array(
                "key" => 'zona',
                "label" => "Zona"
            ),
            array(
                "key" => 'total',
                "label" => "Total",
                "class" => "text-right"
            ),
            array(
                "key" => 'porcentaje',
                "label" => "Porcentaje",
                "class" => "text-right"
            ),
            
        );

        $data = array(
            "datos_tabla" => $zonas,
            "titulos_tabla" => $titulos
        );

        return response()->json(["code" => 200, "data" => $data]);

    }

    public function resultadosLogisticaZona(Request $request){

        $zona = $request["zona"];

        // $zonas = app('db')->select("select zona, count(*) as total from centro_votacion where id_region = $id_region group by zona");

        $lista = array(
            array (
                "name" => "Fiscales Completos", "id" => "1", "icono" => "./teamwork.png", "campo" => "fiscales"
            ),
            array(
                "name" => "Apertura de Centro", "id" => "2", "icono" => "./doorway.png", "campo" => "abierto"
            ),
            array(
                "name" => "Fiscales Acreditados", "id" => "3", "icono" => "./clipboard.png", "campo" => "acreditados"
            ),
            array(
                "name" => "Desayuno", "id" => "4", "icono" => "./breakfast.png", "campo" => "desayuno"
            ),
            array(
                "name" => "Afluencia AM", "id" => "5", "icono" => "./sunrise.png", "campo" => "asistencia_am"
            ),
            array(
                "name" => "Almuerzo", "id" => "6", "icono" => "./lunch-box.png", "campo" => "almuerzo"
            ),
            array(
                "name" => "Afluencia PM", "id" => "7", "icono" => "./sunset.png", "campo" => "asistencia_pm"
            ),
            array(
                "name" => "Cena", "id" => "8", "icono" => "./coffee-cup.png", "campo" => "refaccion_pm"
            ),
            array(
                "name" => "Cierre de Centro", "id" => "9", "icono" => "./padlock.png", "campo" => "cerrado"
            ),
        );

        $datos = array();

        foreach ($lista as $item) {
            
            $campo = $item["campo"];

            $result = app('db')->select(" select nombre, $campo as estado from centro_votacion where zona = $zona ");

            $totales = app('db')->select(" select count(*) as total, sum($campo = 'S') as total_ok from centro_votacion where zona = $zona ");

            $item["centros"] = (array) $result;
            $item["porcentaje"] = round(((int) $totales[0]->total_ok / $totales[0]->total) * 100);

            $datos[] = $item;

        }

        $data = array(
            "datos_tabla" => $datos
        );

        return response()->json(["code" => 200, "data" => $data]);

    }
}
