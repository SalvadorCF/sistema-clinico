<?php
session_start();
date_default_timezone_set('America/Mexico_City');
$NombreSecretaria = $_SESSION['NombreSecretaria'];
$ApellidoPSecretaria = $_SESSION['ApellidoPSecretaria'];
$ApellidoMSecretaria = $_SESSION['ApellidoMSecretaria'];
include_once '../conexion.php';
$banderaBuscar = 0;

if (!isset($NombreSecretaria)) {
    header("location: ../index.php");
}

//Fechas
$hoy = getdate();
$fechaA=$hoy['year']."-".$hoy['mon']."-".$hoy['mday'];


//Estado de cita O=ocupado P=pendiente C=CONSULTADA

//Buscar el horario del dia
if (isset($_POST['btnBuscar'])) {
    //Recuperar datos
    $fecha = new DateTime($_POST['Fecha']);
    $fecha = $fecha->format('Y-m-d');

    $fechaA=$fecha;

    $sql_leer = 'SELECT IdCita,FechaCita,Horario,CURP,Estado FROM Citas WHERE FechaCita=?';

    //LEER
    $gsent = $pdo->prepare($sql_leer);
    $gsent->execute([$fecha]);
    $resultado = $gsent->fetchAll();
    $banderaBuscar = 1;
}

if ($_GET) {
    $id = $_GET['id'];
    $sql_unico = 'SELECT * FROM Citas WHERE IdCita=?';
    $gsent_unico = $pdo->prepare($sql_unico);
    $gsent_unico->execute(array($id));
    $resultado_unico = $gsent_unico->fetch();
}
//Agendar Cita
if (isset($_POST['btnAgregar'])) {
    //Recuperar datos
    $idPaciente = $_POST['idPaciente'];
    $idCita = $_POST['idCitaA'];

    $estado = 'O';

    $sql_agregar = 'UPDATE Citas SET CURP=?,ESTADO=? WHERE IdCita=?';
    $sentencia_agregar = $pdo->prepare($sql_agregar);
    $sentencia_agregar->execute(array($idPaciente, $estado, $idCita));

    $pdo = null;
    $sentencia_editar = null;

    header('location:SCitas.php');
}

if (isset($_POST['btnCancelar'])) {
    //Recuperar datos
    $idCita = $_POST['idCitaC'];
    $estado = 'P';

    $sql_cancelar = 'UPDATE Citas SET CURP=NULL,ESTADO=? WHERE IdCita=?';
    $sentencia_cancelar = $pdo->prepare($sql_cancelar);
    $sentencia_cancelar->execute(array($estado, $idCita));

    $pdo = null;
    $sentencia_cancelar = null;

    header('location:SCitas.php');
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

    <title>Citas</title>
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
                    <li class="nav-item active">
                        <a class="nav-link active" aria-current="page" href="SCitas.php">Citas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="SAgendarHorario.php">Agendar Horario</a>
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
        <?php if (!$_GET) {  ?>
            <form method="POST">
                <h2>Panel de Citas</h2>
                <label for="fFecha" class="form-label">Seleccione la fecha</label>
                <input type="date" class="form-control mb-2" name="Fecha" placeholder="Fecha 2000-12-25" id="fFecha" value="<?php echo "$fechaA" ?>">
                <button class="btn btn-primary mt-3" name="btnBuscar">Buscar</button>
            </form>
        <?php } ?>

        <?php if ($_GET) : ?>
            <form method="POST" action="">
                <h2>Asignar Paciente</h2>
                <input type="text" class="form-control" name="idPaciente" placeholder="CURP del paciente">
                <input type="hidden" name="idCitaA" value="<?php echo $resultado_unico['IdCita'] ?>">
                <button class="btn btn-primary mt-3" name="btnAgregar">Asignar Cita</button>
            </form>

        <?php endif ?>

        <?php if ($banderaBuscar == 1) { ?>
            <?php if ($gsent->rowCount() > 0) { ?>

                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Fecha</th>
                            <th scope="col">Horario</th>
                            <th scope="col">Paciente</th>
                            <th scope="col">Estado</th>
                            <th scope="col" colspan="2">Acciones</th>
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
                                <?php if ($dato['Estado']=="P"){ ?>
                                    <a class="btn btn-secondary btn-sm" href="SCitas.php?id=<?php echo $dato['IdCita'] ?>">Agendar</a>
                                    <?php }?>
                                </td>

                                <td>
                                <?php if ($dato['Estado']=="P"||$dato['Estado']=="O"){ ?>
                                    <form method="POST" action="">

                                        <input type="hidden" name="idCitaC" value="<?php echo $dato['IdCita'] ?>">
                                        <button class="btn btn-danger btn-sm" name="btnCancelar">Cancelar</button>
                                    </form>
                                    <?php }?>
                                </td>
                            </tr>

                <?php }
                    }
                } ?>

                    </tbody>
                </table>




    </div>


    <script src="../libs/js/bootstrap.min.js"></script>

</body>

</html>