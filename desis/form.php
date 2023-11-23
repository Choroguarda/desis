<?php
require_once 'database.php';

if (isset($_GET['accion'])) {
    $accion = $_GET['accion'];

    switch ($accion) {
        case 'obtenerRegiones':
            obtenerRegiones();
            break;

        case 'obtenerProvincias':
            if (isset($_GET['idRegion'])) {
                $idRegion = $_GET['idRegion'];
                obtenerProvincias($idRegion);
            } else {
                // Manejar el caso de falta de parámetros
                echo json_encode(array('error' => 'Falta el parámetro idRegion.'));
            }
            break;
        case 'obtenerComunas':
            if (isset($_GET['idProvincia'])) {
                $idProvincia = $_GET['idProvincia'];
                obtenerComunas($idProvincia);
            } else {
                // Manejar el caso de falta de parámetros
                echo json_encode(array('error' => 'Falta el parámetro idProvincia.'));
            }
            break;
        case 'obtenerCandidatos':
            obtenerCandidatos();
            break;
        case 'insertarVotante':
            // Verifica si se enviaron datos del formulario
            if (isset($_POST['nombre']) && isset($_POST['alias']) && isset($_POST['rut']) && isset($_POST['email']) && isset($_POST['region']) && isset($_POST['provincia']) && isset($_POST['comuna']) && isset($_POST['candidato'])) {
                // Obtén los datos del formulario
                $nombre = $_POST['nombre'];
                $alias = $_POST['alias'];
                $rut = $_POST['rut'];
                $email = $_POST['email'];
                $region = $_POST['region'];
                $provincia = $_POST['provincia'];
                $comuna = $_POST['comuna'];
                $candidato = $_POST['candidato'];

                // Inserta los datos en la base de datos
                insertarVotante($nombre, $alias, $rut, $email, $region, $provincia, $comuna, $candidato);

                // Responde con un mensaje de éxito o cualquier otro tipo de respuesta que desees
                echo json_encode(array('mensaje' => 'Datos del votante insertados correctamente.'));
            } else {
                // Maneja el caso de falta de parámetros
                echo json_encode(array('error' => 'Faltan parámetros del formulario.'));
            }
            break;
            // Agrega más casos según sea necesario para otras acciones

        default:
            // Manejar el caso de acción no válida
            echo json_encode(array('error' => 'Acción no válida.'));
            break;
    }
} else {
    // Manejar el caso de falta de acción
    echo json_encode(array('error' => 'Falta el parámetro acción.'));
}
function insertarVotante($nombre, $alias, $rut, $email, $region, $provincia, $comuna, $candidato)
{
    $db = new Database();
    $stmt = $db->getConn()->prepare("INSERT INTO Votante (nombre, alias, rut, email, region, provincia, comuna, candidato) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $nombre, $alias, $rut, $email, $region, $provincia, $comuna, $candidato);

    if ($stmt->execute()) {
        echo json_encode(array('mensaje' => 'Datos del votante insertados correctamente.'));
    } else {
        echo json_encode(array('error' => 'Error al insertar datos del votante.'));
    }

    $stmt->close();
    $db->closeConnection();
}
function obtenerRegiones()
{
    $db = new Database();
    $sql = "SELECT * FROM Regiones";
    $result = $db->getConn()->query($sql);

    $regiones = array();
    while ($row = $result->fetch_assoc()) {
        $regiones[] = $row;
    }

    $db->closeConnection();

    echo json_encode($regiones);
}

function obtenerProvincias($idRegion)
{
    $db = new Database();
    $sql = "SELECT * FROM Provincias WHERE region_id = $idRegion";
    $result = $db->getConn()->query($sql);

    $provincias = array();
    while ($row = $result->fetch_assoc()) {
        $provincias[] = $row;
    }

    $db->closeConnection();


    header('Content-Type: application/json; charset=utf-8');

    echo json_encode($provincias);
}

function obtenerComunas($idProvincia)
{
    $db = new Database();
    $sql = "SELECT * FROM Comunas WHERE provincia_id = $idProvincia";
    $result = $db->getConn()->query($sql);

    $comunas = array();
    while ($row = $result->fetch_assoc()) {
        $comunas[] = $row;
    }

    $db->closeConnection();


    header('Content-Type: application/json; charset=utf-8');

    echo json_encode($comunas);
}


function obtenerCandidatos()
{
    $db = new Database();
    $sql = "SELECT * FROM Candidatos";
    $result = $db->getConn()->query($sql);

    $candidatos = array();
    while ($row = $result->fetch_assoc()) {
        $candidatos[] = $row;
    }

    $db->closeConnection();

    echo json_encode($candidatos);
}
