<?php session_start();

include_once '../conexion.php';
date_default_timezone_set('America/Mexico_City');

$IdPaciente = $_SESSION['IdPaciente'];
$CURP = $_SESSION['CURP'];
$NombrePaciente = $_SESSION['NombrePaciente'];
$ApellidoPPaciente = $_SESSION['ApellidoPPaciente'];
$ApellidoMPaciente = $_SESSION['ApellidoMPaciente'];

//Fechas
$hoy = getdate();
$fechaA=$hoy['year']."-".$hoy['mon']."-".$hoy['mday'];

if (!isset($CURP)) {
    header("location: ../index.php");
}

$banderaBuscar = 0;

//Estado de cita O=ocupado P=pendiente C=CONSULTADA

//Buscar el horario del dia
if (isset($_POST['btnBuscar'])) {
    //Recuperar datos
    $fecha = new DateTime($_POST['Fecha']);
    $fecha = $fecha->format('Y-m-d');
    $fechaA=$fecha;

    $sql_leer = "SELECT IdCita,FechaCita,Horario,CURP,Estado FROM Citas WHERE FechaCita=? and Estado='P'";

    //LEER
    $gsent = $pdo->prepare($sql_leer);
    $gsent->execute([$fecha]);
    $resultado = $gsent->fetchAll();
    $banderaBuscar = 1;
}
if (isset($_POST['btnAgregar'])) {
    //Recuperar datos
    
    $idCita = $_POST['idCita'];
    $estado = 'O';
    $sql_agregar = 'UPDATE Citas SET CURP=?,ESTADO=? WHERE IdCita=?';
    $sentencia_agregar = $pdo->prepare($sql_agregar);
    $sentencia_agregar->execute(array($CURP, $estado, $idCita));

    $pdo = null;
    $sentencia_editar = null;

    header('location:PMisCitas.php');
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
    <link href="../css/login.css" rel="stylesheet">

    <title>Panel de Pacientes</title>
</head>

<body>

    <nav class="navbar navbar-expand-lg  navbar-dark bg-primary sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="PMisCitas.php">Consultorio Medico</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="PMisCitas.php">Mis Citas</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link active" href="PAgendarCita.php">Agendar Cita</a>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link" href="PConfiguracion.php">Configuración</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../CerrarSesion.php">Cerrar Sesión</a>
                    </li>


                </ul>

                <span class="navbar-text text-white">
                    <?php if (isset($NombrePaciente)) {
                        echo "$NombrePaciente $ApellidoPPaciente $ApellidoMPaciente";
                    } ?>
                </span>

            </div>
        </div>
    </nav>

    <div class="container mt-3">

        <form method="POST">
            <h2>Agendar Cita</h2>
            <label for="fFecha" class="form-label">Seleccione la fecha</label>
            <input type="date" class="form-control mb-2" name="Fecha" placeholder="Fecha 2000-12-25" id="fFecha" value="<?php echo "$fechaA" ?>">
            <button class="btn btn-primary mt-3" name="btnBuscar">Buscar</button>
        </form>


        <?php if ($banderaBuscar == 1) { ?>
            <?php if ($gsent->rowCount() > 0) { ?>

                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Fecha</th>
                            <th scope="col">Horario</th>
                            <th scope="col">Paciente</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultado as $dato) { ?>
                            <tr>
                                <td><?php echo $dato['FechaCita'] ?></td>
                                <th scope="row"><?php echo $dato['Horario'] ?></th>
                                <td><?php echo $dato['CURP'] ?></td>
                                <td><?php echo $dato['Estado'] ?></td>
                                <td>

                                    <form method="POST" action="">
                                        <input type="hidden" name="idCita" value="<?php echo $dato['IdCita'] ?>">
                                        <button class="btn btn-success btn-sm" name="btnAgregar">Agendar</button>
                                    </form>

                                </td>


                            </tr>

                <?php }
                    }
                }
                ?>

                    </tbody>
                </table>






    </div>

    <script src="../libs/js/bootstrap.min.js"></script>

</body>

</html>