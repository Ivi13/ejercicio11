<?php
// app/Controladores/ControladorAlumnos.php

require_once __DIR__ . '/../Modelos/RepositorioAlumnos.php';

class ControladorAlumnos
{
    private $repositorio;

    function __construct()
    {
        $this->repositorio = new RepositorioAlumnos();
    }

    // LISTAR
    function listar()
    {
        try {
            $alumnos = $this->repositorio->obtenerTodos();
            $this->renderizar('alumnos/listar', ['alumnos' =>$alumnos]);
        } catch (Exception $e) {
            $this->registrarError("LISTAR", $e);
            $this->renderizar('alumnos/listar', [
                'alumnos' => [],
                'error' => 'No se pudieron cargar los alumnos. Revisa errores.log'
            ]);
        }
    }

    //MOSTRAR FORMULARIO
    function crear ()
    {
        $this->renderizar('alumnos/crear', ['antiguos' => ['nombre' => '', 'email' => '', 'edad' => '']
        ]);
    }


    function guardar()
    {
    // Solo POST
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header("Location: index.php?accion=listar");
            exit;
        }

        // Recoger datos con seguridad
        $nombre = trim($_POST['nombre'] ?? '');
        $email  = trim($_POST['email'] ?? '');
        $edad   = $_POST['edad'] ?? '';

        try {
            // Validar datos
            $this->validar($nombre, $email, $edad);

            // Crear objeto Alumno
            $alumno = new Alumno(
                null,
                $nombre,
                $email === '' ? null : $email, // Email opcional
                (int)$edad,
                date('Y-m-d H:i:s')
            );

            // Insertar en BD
            $this->repositorio->insertar($alumno);

            // Redirigir al listado
            header("Location: index.php?accion=listar");
            exit;

        } catch (Exception $e) {
            // Registrar error en log
            $this->registrarError("GUARDAR", $e);

            // Volver al formulario con datos antiguos y mensaje de error
            $this->renderizar('alumnos/crear', [
                'error' => $e->getMessage(),
                'antiguos' => [
                    'nombre' => $nombre,
                    'email'  => $email,
                    'edad'   => $edad
                ]
            ]);
        }
    }

    // BORRAR
    function borrar()
    {
        $id = $_GET['id'] ?? '';

        try {
            if ($id === '' || !ctype_digit($id)) {
                throw new Exception("Id inválido para borrar");
            }

            $this->repositorio->borrarPorId($id);
        } catch (Exception $e) {
            $this->registrarError("Borrar", $e);
        }

        header("Location: index.php?accion=listar");
        exit;
    }

    // VALIDACIONES
    function validar($nombre, $email, $edad)
    {
        if (strlen($nombre) < 3) {
            throw new Exception("El nombre debe tener al menos 3 caracteres");
        }

        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("El email no es válido");
        }

        if (!filter_var($edad, FILTER_VALIDATE_INT, [
            'options' => [
                'min_range' => 1,
                'max_range' => 120
            ]
        ])) {
            throw new Exception("La edad debe estar entre 1 y 120");
        }
    }

    // RENDERIZAR (layout + vista)
    function renderizar($vista, $datos = [])
    {
        extract($datos);

        $archivoVista = __DIR__ . '/../Vistas/' . $vista . '.php';
        if (!file_exists($archivoVista)) {
            echo "Vista no encontrada: " . $vista;
            return;
        }

        $vistacontenido = $archivoVista;

        require_once __DIR__ . '/../Vistas/Layout.php';
    }

    // LOG de errores en fichero
    function registrarError($contexto, $e)
    {
        $rutaLog = __DIR__ . '/../../storage/errores.log';
        $fecha = date('Y-m-d H:i:s');

        $linea = $fecha . " | " . $contexto . " | " . $e->getMessage() . " | " . $e->getFile() . " | " . $e->getLine() . "\n";
        file_put_contents($rutaLog, $linea, FILE_APPEND);
    }
}