<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {

    //return $router->app->version();

    $personas = App\Persona::all();

    return $personas;

});

$router->post('/login', 'LoginController@login');

// Menu Principal
$router->post('/obtener_menu', 'MenuController@obtenerMenu');

// Seccion de Logistica
$router->post('/obtener_lista_logistica', 'LogisticaController@obtenerLista');
$router->post('/cambiar_estado_logistica', 'LogisticaController@cambiarEstado');
$router->post('/logistica_region', 'LogisticaController@resultadosLogisticaRegion');
$router->post('/logistica_detalles_zona', 'LogisticaController@detallesZonaLogisica');
$router->post('/logistica_general', 'LogisticaController@resultadosLogisticaGeneral');
$router->post('/resumen_logistica_region', 'LogisticaController@resumenLogisticaRegion');
$router->post('/logistica_zona', 'LogisticaController@resultadosLogisticaZona');

// Incidencias
$router->post('/incidencias_centro', 'IncidenciasController@incidenciasCentro');
$router->post('/registrar_incidencia', 'IncidenciasController@registrarIncidencia');
$router->post('/incidencias_region', 'IncidenciasController@incidenciasRegion');
$router->post('/incidencias_finalizadas_zona', 'IncidenciasController@incidenciasFinalizadasZona');
$router->post('/detalles_incidencia_finalizada', 'IncidenciasController@detallesIncidenciaFinalizada');
$router->post('/incidencias_pendientes_zona', 'IncidenciasController@incidenciasPendientesZona');
$router->post('/contactos_logistica', 'IncidenciasController@contactosLogistica');
$router->post('/asignar_responsable', 'IncidenciasController@asignarResponsable');
$router->post('/cambiar_estado_incidencia', 'IncidenciasController@cambiarEstadoIncidencia');
$router->post('/impugnaciones_region', 'IncidenciasController@impugnacionesRegion');
$router->post('/impugnaciones_pendientes', 'IncidenciasController@impugnacionesPendientes');

//Ingreso de Resultados
$router->post('/obtener_mesas', 'IngresoController@obtenerMesas');
$router->post('/partidos_ingreso', 'IngresoController@partidosIngreso');
$router->post('/registrar_resultados', 'IngresoController@registrarResultados');
