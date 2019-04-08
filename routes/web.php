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

// Incidencias
$router->post('/incidencias_centro', 'IncidenciasController@incidenciasCentro');
$router->post('/registrar_incidencia', 'IncidenciasController@registrarIncidencia');

//Ingreso de Resultados
$router->post('/obtener_mesas', 'IngresoController@obtenerMesas');
$router->post('/partidos_ingreso', 'IngresoController@partidosIngreso');
$router->post('/registrar_resultados', 'IngresoController@registrarResultados');