<?php session_start();

include_once '../conexion.php';

$NombreMedico = $_SESSION['NombreMedico'];
$ApellidoPMedico = $_SESSION['ApellidoPMedico'];
$ApellidoMMedico = $_SESSION['ApellidoMMedico'];

date_default_timezone_set('America/Mexico_City');


if (!isset($NombreMedico)) {
    header("location: ../index.php");
}
$banderaBuscar = 0;

//Fechas
$hoy = getdate();
$fechaA = $hoy['year'] . "-" . $hoy['mon'] . "-" . $hoy['mday'];

//Estado de cita O=ocupado P=pendiente C=CONSULTADA

//Buscar el horario del dia
if (isset($_POST['btnBuscar'])) {
    //Recuperar datos
    $fecha = new DateTime($_POST['Fecha']);
    $fecha = $fecha->format('Y-m-d');

    $fechaA = $fecha;

    $sql_leer = "SELECT IdCita,FechaCita,Horario,CURP,Estado FROM Citas WHERE FechaCita=? ORDER BY FechaCita DESC, Horario ASC ";

    //LEER
    $gsent = $pdo->prepare($sql_leer);
    $gsent->execute([$fecha]);
    $resultado = $gsent->fetchAll();
    $banderaBuscar = 1;
}


if ($_GET) {
    $id = $_GET['id'];
    $curp = $_GET['curp'];

    $sql_unico = 'SELECT Nombre,ApellidoP,ApellidoM FROM Pacientes WHERE CURP=?';
    $gsent_unico = $pdo->prepare($sql_unico);
    $gsent_unico->execute(array($curp));
    $resultado_unico = $gsent_unico->fetch();
}

if (isset($_POST['btnFinalizar'])) {
    //Recuperar datos

    $idCita = $_POST['idCitaC'];
    $altura = $_POST['altura'];
    $peso = $_POST['peso'];
    $diagnostico = $_POST['diagnostico'];
    $tratamiento = $_POST['tratamiento'];

    $sql_agregar = 'INSERT Consultas (IdCita,Estatura,Peso,Diagnostico,Tratamiento) VALUES (?,?,?,?,?)';
    $sentencia_agregar = $pdo->prepare($sql_agregar);
    $sentencia_agregar->execute(array($idCita, $altura, $peso,$diagnostico,$tratamiento));

    $estado = 'C';

    $sql_agregarC = 'UPDATE Citas SET ESTADO=? WHERE IdCita=?';
    $sentencia_agregarC = $pdo->prepare($sql_agregarC);
    $sentencia_agregarC->execute(array($estado, $idCita));

    $pdo = null;
    $sentencia_editar = null;

    header('location:Consultar.php');
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

    <title>Panel del Medico</title>
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
                        <a class="nav-link active" aria-current="page" href="Consultar.php">Consultar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="HistorialMedico.php">Historial Medico</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Personal.php">Personal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="MConfiguracion.php">Configuración</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../CerrarSesion.php">Cerrar Sesión</a>
                    </li>


                </ul>

                <span class="navbar-text text-white">
                    <?php if (isset($NombreMedico)) {
                        echo "$NombreMedico $ApellidoPMedico $ApellidoMMedico";
                    } ?>
                </span>

            </div>
        </div>
    </nav>

    <div class="container mt-3">

        <?php if ($_GET) : ?>
            <form method="POST">
                <h2>Consultando a <?php echo $resultado_unico['Nombre'] . " " . $resultado_unico['ApellidoP'] . " " . $resultado_unico['ApellidoM']; ?></h2>

                <input type="hidden" name="idCitaC" value="<?php echo $id ?>">

                <div class="input-group mb-3 mt-3">
                    <label for="altura" class="form-label">Altura(mts):&nbsp;&nbsp;</label>
                    <input type="text" class="form-control" name="altura" placeholder="Escriba su altura" id="altura">

                    <label for="peso" class="form-label">&nbsp; Peso(kg):&nbsp;&nbsp;</label>
                    <input type="text" class="form-control" name="peso" placeholder="Escriba su peso" id="peso">
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Diagnostico:</label>
                    <textarea class="form-control" id="exampleFormControlTextarea1" name="diagnostico"></textarea>
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Tratamiento:</label>
                    <textarea class="form-control" id="exampleFormControlTextarea1" name="tratamiento"></textarea>
                </div>
                <div class="d-grid gap-2 col-6 mx-auto">
                    <button class="btn btn-primary mt-3" name="btnFinalizar">Registrar Consulta</button>
                </div>
            </form>

        <?php endif ?>


        <?php if (!$_GET) {  ?>
            <form method="POST">
                <h2>Agenda de Hoy</h2>
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

                                        <?php if ($dato['Estado'] == "O") { ?>
                                            <a class="btn btn-primary btn-sm" href="Consultar.php?id=<?php echo $dato['IdCita'] ?>&curp=<?php echo $dato['CURP'] ?>">Consultar</a>
                                        <?php } ?>
                                    </td>

                                    </td>


                                </tr>

                    <?php }
                        }
                    }
                    ?>

                        </tbody>
                    </table>

                <?php } ?>






    </div>

    <script src="../libs/js/bootstrap.min.js"></script>

</body>

</html>