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

$sql_unico = 'SELECT Nombre,ApellidoM,ApellidoP,Domicilio,Telefono,CodigoPostal,Padecimientos,Alergias FROM Pacientes WHERE CURP=?';
$gsent_unico = $pdo->prepare($sql_unico);
$gsent_unico->execute(array($CURP));
$resultado_unico = $gsent_unico->fetch();

if (isset($_POST['btnActualizar'])) {

    $nombre = $_POST['nombre'];
    $apellidoP = $_POST['apellidoP'];
    $apellidoM = $_POST['apellidoM'];
    $padecimientos = $_POST['padecimientos'];
    $alergias = $_POST['alergias'];
    $domicilio = $_POST['domicilio'];
    $telefono = $_POST['telefono'];
    $cp = $_POST['cp'];

    $sql_actualizar = 'UPDATE Pacientes SET Nombre=?,ApellidoM=?,ApellidoP=?,Padecimientos=?,Alergias=?,
    Domicilio=?,Telefono=?,CodigoPostal=? WHERE CURP=?';
    $sentencia_actualizar = $pdo->prepare($sql_actualizar);
    $sentencia_actualizar->execute(array($nombre,$apellidoM,$apellidoP,$padecimientos,$alergias,$domicilio,
    $telefono,$cp,$CURP));

    $pdo = null;
    $sentencia_actualizar = null;

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
                    <li class="nav-item">
                        <a class="nav-link" href="PAgendarCita.php">Agendar Citas</a>
                    </li>

                    <li class="nav-item active">
                        <a class="nav-link active" href="PConfiguracion.php">Configuración</a>
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
            <h2>Paciente</h2>

            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo $resultado_unico['Nombre'] ?>">

            <label for="apellidoP" class="form-label"> Apellido Paterno:</label>
            <input type="text" class="form-control" name="apellidoP" id="apellidoP" value="<?php echo $resultado_unico['ApellidoP'] ?>">

            <label for="apellidoM" class="form-label"> Apellido Materno:</label>
            <input type="text" class="form-control" name="apellidoM" id="apellidoM" value="<?php echo $resultado_unico['ApellidoM'] ?>">

            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Padecimientos:</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" name="padecimientos"><?php echo $resultado_unico['Padecimientos'] ?></textarea>
            </div>
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Alergias:</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" name="alergias"><?php echo $resultado_unico['Alergias'] ?></textarea>
            </div>
            <label for="domicilio" class="form-label"> Domicilio:</label>
            <input type="text" class="form-control" name="domicilio" id="domicilio" value="<?php echo $resultado_unico['Domicilio'] ?>">

            <label for="cp" class="form-label"> Código Postal:</label>
            <input type="text" class="form-control" name="cp" id="cp" value="<?php echo $resultado_unico['CodigoPostal'] ?>">

            <label for="telefono" class="form-label">Telefono:</label>
            <input type="text" class="form-control" name="telefono" id="telefono" value="<?php echo $resultado_unico['Telefono'] ?>">

            <div class="d-grid gap-2 col-6 mx-auto">
                <button class="btn btn-primary mt-3" name="btnActualizar">Actualizar</button>
            </div>
        </form>






    </div>

    <script src="../libs/js/bootstrap.min.js"></script>

</body>

</html>