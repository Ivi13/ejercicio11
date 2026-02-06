<?php
// app/Modelos/ConexionBD.php

class ConexionBD
{
    private static $conexion = null;

    public static function obtenerConexion()
    {
        if (self::$conexion === null) {

            $host = "localhost";
            $baseDatos = "centro";
            $usuario = "root";
            $password = "root123";

            try {
                $dsn = "mysql:host=$host;dbname=$baseDatos;charset=utf8mb4";
                self::$conexion = NEW PDO($dsn, $usuario, $password);

                // Hacemos que PDO lance excepciones si hay errores
                self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                // En un proyecto real no mostraríamos detalles
                die("Error de conexión con la base de datos.");
            }
        }

        return self::$conexion;
    }
}
