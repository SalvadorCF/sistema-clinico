<?php session_start();

include_once '../conexion.php';

$IdPersonal = $_SESSION['IdPersonal'];
$NombreMedico = $_SESSION['NombreMedico'];
$ApellidoPMedico = $_SESSION['ApellidoPMedico'];
$ApellidoMMedico = $_SESSION['ApellidoMMedico'];

if (!isset($IdPersonal)) {
    header("location: ../index.php");
}

$sql_unico = 'SELECT Nombre,ApellidoM,ApellidoP,Domicilio,TelefonoP,CodigoPostal,TelefonoS FROM Personal WHERE IdPersonal=?';
$gsent_unico = $pdo->prepare($sql_unico);
$gsent_unico->execute(array($IdPersonal));
$resultado_unico = $gsent_unico->fetch();

if (isset($_POST['btnActualizar'])) {

    $nombre = $_POST['nombre'];
    $apellidoP = $_POST['apellidoP'];
    $apellidoM = $_POST['apellidoM'];
    $domicilio = $_POST['domicilio'];
    $telefonop = $_POST['telefonop'];
    $telefonos = $_POST['telefonos'];
    $cp = $_POST['cp'];

    $sql_actualizar = 'UPDATE Personal SET Nombre=?,ApellidoM=?,ApellidoP=?,
    Domicilio=?,TelefonoP=?,TelefonoS=?,CodigoPostal=? WHERE IdPersonal=?';
    $sentencia_actualizar = $pdo->prepare($sql_actualizar);
    $sentencia_actualizar->execute(array($nombre,$apellidoM,$apellidoP,$domicilio,
    $telefonop,$telefonos,$cp,$IdPersonal));

    $pdo = null;
    $sentencia_actualizar = null;

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
                        <a class="nav-link" aria-current="page" href="Consultar.php">Consultar</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link " href="HistorialMedico.php">Historial Medico</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Personal.php">Personal</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link active" href="MConfiguracion.php">Configuración</a>
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

        <form method="POST">
            <h2>Medico</h2>

            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo $resultado_unico['Nombre'] ?>">

            <label for="apellidoP" class="form-label"> Apellido Paterno:</label>
            <input type="text" class="form-control" name="apellidoP" id="apellidoP" value="<?php echo $resultado_unico['ApellidoP'] ?>">

            <label for="apellidoM" class="form-label"> Apellido Materno:</label>
            <input type="text" class="form-control" name="apellidoM" id="apellidoM" value="<?php echo $resultado_unico['ApellidoM'] ?>">

    
            <label for="domicilio" class="form-label"> Domicilio:</label>
            <input type="text" class="form-control" name="domicilio" id="domicilio" value="<?php echo $resultado_unico['Domicilio'] ?>">

            <label for="cp" class="form-label"> Código Postal:</label>
            <input type="text" class="form-control" name="cp" id="cp" value="<?php echo $resultado_unico['CodigoPostal'] ?>">

            <label for="telefonop" class="form-label">Telefono Principal:</label>
            <input type="text" class="form-control" name="telefonop" id="telefonop" value="<?php echo $resultado_unico['TelefonoP'] ?>">

            <label for="telefonos" class="form-label">Telefono Secundario:</label>
            <input type="text" class="form-control" name="telefonos" id="telefonos" value="<?php echo $resultado_unico['TelefonoS'] ?>">

            <div class="d-grid gap-2 col-6 mx-auto">
                <button class="btn btn-primary mt-3" name="btnActualizar">Actualizar</button>
            </div>
        </form>






    </div>

    <script src="../libs/js/bootstrap.min.js"></script>

</body>

</html>