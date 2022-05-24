<?php session_start();

include_once '../conexion.php';

$IdPaciente = $_SESSION['IdPaciente'];
$CURP = $_SESSION['CURP'];
$NombrePaciente = $_SESSION['NombrePaciente'];
$ApellidoPPaciente = $_SESSION['ApellidoPPaciente'];
$ApellidoMPaciente = $_SESSION['ApellidoMPaciente'];

if (!isset($CURP)) {
    header("location: ../index.php");
}

//Estado de cita O=ocupado P=pendiente C=CONSULTADA

if (isset($IdPaciente)) {
    $sql_leer = 'SELECT IdCita,FechaCita,Horario,CURP,Estado FROM Citas WHERE CURP=? ORDER BY FechaCita DESC, Horario DESC;';
    $gsent = $pdo->prepare($sql_leer);
    $gsent->execute(array($CURP));
    $resultado = $gsent->fetchAll();
}
if (isset($_POST['btnCancelar'])) {
    $idCita = $_POST['idCitaC'];
    $estado = 'P';

    $sql_cancelar = 'UPDATE Citas SET CURP=NULL,ESTADO=? WHERE IdCita=?';
    $sentencia_cancelar = $pdo->prepare($sql_cancelar);
    $sentencia_cancelar->execute(array($estado, $idCita));

    $pdo = null;
    $sentencia_cancelar = null;

    header('location:PMisCitas.php');
}

if ($_GET) {
    $id = $_GET['id'];
   

    $sql_unico = 'SELECT Estatura,Peso,Diagnostico,Tratamiento FROM Consultas WHERE IdCita=?';
    $gsent_unico = $pdo->prepare($sql_unico);
    $gsent_unico->execute(array($id));
    $resultado_unico = $gsent_unico->fetch();
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
                    <li class="nav-item active">
                        <a class="nav-link active" aria-current="page" href="PMisCitas.php">Mis Citas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="PAgendarCita.php">Agendar Citas</a>
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
    <?php if ($_GET) : ?>
            <form method="POST">
                <h2>Expediente</h2>

                <input type="hidden" name="idCitaC" value="<?php echo $id ?>">

                <div class="input-group mb-3 mt-3">
                    <label for="altura" class="form-label">Altura(mts):&nbsp;&nbsp;</label>
                    <input type="text" class="form-control" name="altura" id="altura" value="<?php echo $resultado_unico['Estatura'] ?>" disabled>

                    <label for="peso" class="form-label">&nbsp; Peso(kg):&nbsp;&nbsp;</label>
                    <input type="text" class="form-control" name="peso"  id="peso" value="<?php echo $resultado_unico['Peso'] ?>" disabled>
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Diagnostico:</label>
                    <textarea class="form-control" id="exampleFormControlTextarea1" name="diagnostico"  disabled><?php echo $resultado_unico['Diagnostico'] ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Tratamiento:</label>
                    <textarea class="form-control" id="exampleFormControlTextarea1" name="tratamiento"  disabled><?php echo $resultado_unico['Tratamiento'] ?></textarea>
                </div>
                <div class="d-grid gap-2 col-6 mx-auto">
                    <a class="btn btn-primary mt-3" href="PMisCitas.php">Regresar</a>
                </div>
            </form>

        <?php endif ?>
        <?php if (!$_GET) : ?>
        <h2>Historial de Citas</h2>

        <?php if ($gsent->rowCount() > 0) { ?>

<table class="table align-middle">
    <thead>
        <tr>
            <th scope="col">Fecha</th>
            <th scope="col">Horario</th>
            <th scope="col">Paciente</th>
            <th scope="col">Estado</th>
            <th scope="col" >Acciones</th>
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
                <?php if ($dato['Estado']=="C"){ ?>
                    <a class="btn btn-success btn-sm" href="PMisCitas.php?id=<?php echo $dato['IdCita'] ?>">Revisar</a>
                    <?php } ?>
                    <?php if ($dato['Estado']=="O"){ ?>
                    <form method="POST" action="">

                        <input type="hidden" name="idCitaC" value="<?php echo $dato['IdCita'] ?>">
                        <button class="btn btn-danger btn-sm" name="btnCancelar" >Cancelar</button>
                    </form>
                    <?php } ?>
                </td>

                
            </tr>

<?php }
    }
 ?>

<?php endif ?>



    </div>

    <script src="../libs/js/bootstrap.min.js"></script>

</body>

</html>