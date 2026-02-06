<?php
// app/Modelos/Alumno.php

class Alumno
{
    public $id;
    public $nombre;
    public $email;
    public $edad;
    public $fechaCreacion;

    function __construct($id, $nombre, $email, $edad, $fechaCreacion)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->edad = $edad;
        $this->fechaCreacion = $fechaCreacion;
    }
}