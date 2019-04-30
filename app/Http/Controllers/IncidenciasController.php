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

        $result = app('db')->insert("insert into incidencia (titulo, descripcion, fecha, estatus, zona, id_tipo, id_region, id_centro) values ('$titulo', '$descripcion', curdate(), 'P', '$zona', '$id_tipo', '$id_region', '$id_centro')");

        return '1';

    }

    public function incidenciasRegion(Request $request){

        $id_region = $request["id_region"];

        $results = app('db')->select("
            select zona, sum(estatus = 'F') as finalizadas, sum(estatus = 'P') as pendientes 
            from incidencia 
            where id_region = $id_region group by zona
        ");

        return $results;

    }

    public function incidenciasFinalizadasZona(Request $request){

        $region = $request["region"];
        $zona = $request["zona"];

        $incidentes = app('db')->select(" 
            select t1.id_incidencia, t1.titulo, t2.descripcion
            from incidencia t1
            inner join tipo_incidencia t2
            on t1.id_tipo = t2.id_tipo
            where t1.zona = $zona and t1.id_region = $region and t1.estatus = 'F'");

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
            
        );

        $data = array(
            "datos_tabla" => $incidentes,
            "titulos_tabla" => $titulos
        );

        return response()->json(["code" => 200, "data" => $data]);

    }

    public function detallesIncidenciaFinalizada(Request $request){

        $id_incidencia = $request["id_incidencia"];

        $result = app('db')->select(" 
            select t1.id_incidencia, t1.titulo, t1.descripcion, date_format(t1.fecha, '%d/%m/%Y %r') as fecha, t1.zona, t1.estatus, t2.descripcion as tipo_incidencia, t3.nombre as centro, t1.id_contacto_logistica as id_responsable, t4.nombre as responsable 
            from incidencia t1
            inner join tipo_incidencia t2
            on t1.id_tipo = t2.id_tipo
            inner join centro_votacion t3
            on t1.id_centro = t3.id_centro
            left join contacto_logistica t4
            on t1.id_contacto_logistica = t4.id_contacto_logistica
            where t1.id_incidencia = $id_incidencia 
        ");

        $data = array(
            "detalles" => $result
        );

        return response()->json(["code" => 200, "data" => $data]);

    }

    public function incidenciasPendientesZona(Request $request){

        $region = $request["region"];
        $zona = $request["zona"];

        $pendientes_sin_asignar = app('db')->select(" 
            select t1.id_incidencia, t1.titulo, t2.descripcion as tipo
            from incidencia t1
            inner join tipo_incidencia t2
            on t1.id_tipo = t2.id_tipo
            where t1.zona = $zona and t1.id_region = $region and t1.estatus = 'P' and t1.id_contacto_logistica is null");

        $pendientes_asignadas = app('db')->select(" 
            select t1.id_incidencia, t1.titulo, t2.descripcion as tipo 
            from incidencia t1
            inner join tipo_incidencia t2
            on t1.id_tipo = t2.id_tipo
            where t1.zona = $zona and t1.id_region = $region and t1.estatus = 'P' and t1.id_contacto_logistica is not null");

        $titulos = array(
        
            array(
                "key" => 'titulo',
                "label" => "Título",
                "sortable" => true
            ),
            array(
                "key" => 'tipo',
                "label" => "Tipo",
                "sortable" => true
            ),
            
        );

        $data = array(
            "pendientes_sin_asignar" => $pendientes_sin_asignar,
            "pendientes_asignadas" => $pendientes_asignadas,
            "titulos" => $titulos
        );

        return response()->json(["code" => 200, "data" => $data]);

    }

    public function contactosLogistica(Request $request){

        $zona = $request["zona"];

        $contactos = app('db')->select(" 
            select id_contacto_logistica as value, concat(responsabilidad, ' - ', nombre) as text 
            from contacto_logistica 
            where zona = $zona 
        ");
        
        $contactos = (array) $contactos;

        array_unshift($contactos, array( "value" => null, "text" => "Seleccione un responsable" ));

        return $contactos;

    }

    public function asignarResponsable(Request $request){

        $id_incidencia = $request["id_incidencia"];
        $responsable = $request["id_responsable"];

        $result = app('db')->update(" update incidencia set asignada = 'S', id_contacto_logistica = $responsable where id_incidencia = $id_incidencia ");

        return $result;

    }

    public function cambiarEstadoIncidencia(Request $request){

        $id_incidencia = $request["id_incidencia"];
        $estatus = $request["estatus"];

        $result = app('db')->update(" update incidencia set estatus = '$estatus' where id_incidencia = $id_incidencia ");

        return $result;

    }

    public function impugnacionesRegion(Request $request){

        $id_region = $request["id_region"];

        $results = app('db')->select("
            select zona, sum(estatus = 'F') as finalizadas, sum(estatus = 'P') as pendientes 
            from incidencia 
            where id_region = $id_region and id_tipo = 3 group by zona
        ");

        return $results;

    }

    public function impugnacionesPendientes(Request $request){

        $zona = $request["zona"];

        $results = app('db')->select(" 
            select t1.id_incidencia, t1.titulo, t2.descripcion as tipo 
            from incidencia t1
            inner join tipo_incidencia t2
            on t1.id_tipo = t2.id_tipo
            where t1.zona = $zona and t1.id_tipo = 3 and t1.estatus = 'P'
        ");

        $titulos = array(
        
            array(
                "key" => 'titulo',
                "label" => "Título",
                "sortable" => true
            ),
            array(
                "key" => 'tipo',
                "label" => "Tipo",
                "sortable" => true
            ),
            
        );

        $data = array(
            "datos_tabla" => $results,
            "titulos" => $titulos
        );

        return response()->json(["code" => 200, "data" => $data]);

    }

}
