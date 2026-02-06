<?php
// public/index.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluimmos el controlador
require_once __DIR__ . '/../app/Controladores/ControladorAlumnos.php';

// Creamos el controlador
$controlador = new ControladorAlumnos();

// Leemos la acción (si no viene, listar)
$accion = $_GET['accion'] ?? 'listar';

// Decidimos qué hacer
switch ($accion) { 
case 'listar': 
$controlador->listar(); 
break; 
case 'crear': 
$controlador->crear(); 
break;
case 'guardar': 
$controlador->guardar(); 
break;
case 'borrar': 
$controlador->borrar(); 
break;
default:
echo "Acción no válida";
}
?>