<?php
session_start();
$NombreSecretaria = $_SESSION['NombreSecretaria'];
$ApellidoPSecretaria = $_SESSION['ApellidoPSecretaria'];
$ApellidoMSecretaria = $_SESSION['ApellidoMSecretaria'];
include_once '../conexion.php';

if (!isset($NombreSecretaria)) {
    header("location: ../index.php");
}


//Agregar
if ($_POST) {
    //Recuperar datos
    $fecha = new DateTime($_POST['Fecha']);
    $horai = new DateTime($_POST['HoraInicio']);
    $horaf = new DateTime($_POST['HoraFin']);
    $intervalo = $_POST['Intervalo'];
    $estado = 'P';

    $fecha = $fecha->format('Y-m-d');
    $horai = $horai->format('H:i:s');
    $horaf = $horaf->format('H:i:s');

    //Conversion a segundos
    $segundos_horaInicial = strtotime($horai);
    $segundos_horaFinal = strtotime($horaf);
    $segundos_intervalo = $intervalo * 60;

    $sql_insertar = 'INSERT INTO Citas (FechaCita,HoraI,HoraF,Intervalo,Horario,Estado) VALUES (?,?,?,?,?,?)';
    for ($i = $segundos_horaInicial; $i < $segundos_horaFinal; $i = $i + $segundos_intervalo) {
        $nuevaHora = date("H:i:s", $i);
        $sentencia_agregar = $pdo->prepare($sql_insertar);
        $sentencia_agregar->execute(array($fecha, $horai, $horaf, $intervalo, $nuevaHora, $estado));
    }
    //$nuevaHora=date("H:i:s",$segundos_horaInicial+$segundos_intervalo);
    $pdo = null;
    $sentencia_agregar = null;

    header('location:SAgendarHorario.php');
}

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="../libs/css/bootstrap.min.css" rel="stylesheet">


    <title>Agendar Horario</title>
</head>

<body>

    <nav class="navbar navbar-expand-lg  navbar-dark bg-primary sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="SCitas.php">Consultorio Medico</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="SCitas.php">Citas</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link active" aria-current="page" href="SAgendarHorario.php">Agendar Horario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="SRegistrarPaciente.php">Registrar Pacientes</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="SConfiguracion.php">Configuración</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../CerrarSesion.php">Cerrar Sesión</a>
                    </li>

                </ul>
                <span class="navbar-text text-white">
                    <?php if (isset($NombreSecretaria)) {
                        echo "$NombreSecretaria $ApellidoPSecretaria $ApellidoMSecretaria";
                    } ?>
                </span>
            </div>
        </div>
    </nav>
    <div class="container mt-3">
        <form method="POST">
            <h2>Agendar Horario</h2>
            <label for="fFecha" class="form-label">Seleccione la fecha</label>
            <input type="date" class="form-control mb-2" name="Fecha" placeholder="Fecha 2000-12-25" id="fFecha">
            <label for="fFecha" class="form-label">Hora de Inicio</label>
            <input type="time" class="form-control mb-2" name="HoraInicio" placeholder="Hora de Inicio 20:00:00" id="fHoraI">
            <label for="fHoraF" class="form-label">Hora Final</label>
            <input type="time" class="form-control" name="HoraFin" placeholder="Hora de Inicio 20:00:00" id="fHoraF">
            <input type="text" class="form-control mt-3" name="Intervalo" placeholder="Duracion (minutos)">
            <button class="btn btn-primary mt-3">Agregar Horario</button>

        </form>

    </div>

    <script src="../libs/js/bootstrap.min.js"></script>

</body>

</html>