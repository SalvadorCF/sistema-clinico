<?php session_start();

include_once '../conexion.php';

$NombreMedico = $_SESSION['NombreMedico'];
$ApellidoPMedico = $_SESSION['ApellidoPMedico'];
$ApellidoMMedico = $_SESSION['ApellidoMMedico'];

if (!isset($NombreMedico)) {
    header("location: ../index.php");
}
//Mostrar Datos de la tabla
$sql_leer = "SELECT * FROM Personal WHERE Nombre<>'" . $NombreMedico . "'";
$gsent = $pdo->prepare($sql_leer);
$gsent->execute();
$resultado = $gsent->fetchAll();

if (isset($_POST['btnRegistrar'])) {
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    $nombre = $_POST['nombre'];
    $apellidoP = $_POST['apellidoP'];
    $apellidoM = $_POST['apellidoM'];
    $direccion = $_POST['direccion'];
    $telefonoP = $_POST['telefonoP'];
    $telefonoS = $_POST['telefonoS'];
    $rGenero = $_POST['rGenero'];
    $fechaN = $_POST['fechaN'];
    $fechaI = $_POST['fechaI'];
    $cp = $_POST['cp'];


    $cargo = "SECRETARIA";

    //Registrar el usuario
    $sql_insertarU = 'INSERT INTO Usuarios (Correo,Password,Cargo) VALUES (?,?,?)';
    $sentencia_agregarU = $pdo->prepare($sql_insertarU);
    $sentencia_agregarU->execute(array($correo, $password, $cargo));

    //Busqueda del Usuario
    $sql_unico = 'SELECT IdUsuario FROM Usuarios WHERE Correo=?';
    $gsent_unico = $pdo->prepare($sql_unico);
    $gsent_unico->execute(array($correo));
    $resultado_unico = $gsent_unico->fetch();

    $idUsuario = $resultado_unico['IdUsuario'];

    //Agregar al personal
    $sql_insertarS = 'INSERT INTO Personal (FechaIngreso,FechaNacimiento,Nombre,ApellidoP,
        ApellidoM,Genero,CodigoPostal, Domicilio,TelefonoP,TelefonoS,IdUsuario) VALUES (?,?,?,?,?,?,?,?,?,?,?)';
    $sentencia_agregarS = $pdo->prepare($sql_insertarS);
    $sentencia_agregarS->execute(array(
        $fechaI, $fechaN, $nombre, $apellidoP, $apellidoM, $rGenero, $cp, $direccion, $telefonoP, $telefonoS,
        $idUsuario
    ));

    header('location:Personal.php');
}

if (isset($_POST['btnCancelar'])) {
    $idUsuario = $_POST['idUsuario'];


    $sql_eliminarP = 'DELETE FROM Personal  WHERE IdUsuario=?';
    $sentencia_eliminarP = $pdo->prepare($sql_eliminarP);
    $sentencia_eliminarP->execute(array($idUsuario));


    $sql_eliminarU = 'DELETE FROM Usuarios  WHERE IdUsuario=?';
    $sentencia_eliminarU  = $pdo->prepare($sql_eliminarU);
    $sentencia_eliminarU->execute(array($idUsuario));

    header('location:Personal.php');
}


if ($_GET) {
    $idUsu = $_GET['id'];

    $sql_unicoU = 'SELECT Correo,Password FROM Usuarios WHERE IdUsuario=?';
    $gsent_unicoU  = $pdo->prepare($sql_unicoU);
    $gsent_unicoU->execute(array($idUsu));
    $resultado_unicoU  = $gsent_unicoU->fetch();
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

    <title>Personal</title>
    <script language="javascript" type="text/javascript">
        function closed() {
            window.open('', '_parent', '');
            window.close();
        }
    </script>
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
                    <li class="nav-item active">
                        <a class="nav-link active" href="Personal.php">Personal</a>
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
                <h2>Datos de Recuperación</h2>

                    <label for="altura" class="form-label">Usuario:&nbsp;&nbsp;</label>
                    <input type="text" class="form-control" name="altura" id="altura" value="<?php echo $resultado_unicoU['Correo'] ?>" disabled>

                    <label for="peso" class="form-label">&nbsp; Contraseña:&nbsp;&nbsp;</label>
                    <input type="text" class="form-control" name="peso" id="peso" value="<?php echo $resultado_unicoU['Password'] ?>" disabled>      
                    <div class="d-grid gap-2 col-6 mx-auto">
                    <a class="btn btn-primary mt-3" href="javascript:closed();">Cerrar</a>
        </div>      
            </form>

        <?php endif ?>

        <?php if (!$_GET) { ?>
            <h2>Personal del Consultorio</h2>


            <div class="table-responsive-sm">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellido Paterno</th>
                            <th scope="col">Apellido Materno</th>
                            <th scope="col">Genero</th>
                            <th scope="col">Domicilio</th>
                            <th scope="col">Codigo Postal</th>
                            <th scope="col">Telefono Principal</th>
                            <th scope="col">Telefono Secundario</th>
                            <th scope="col" colspan="2">Acciones</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultado as $dato) { ?>
                            <tr>
                                <td><?php echo $dato['IdPersonal']; ?></td>
                                <th><?php echo $dato['Nombre']; ?></th>
                                <td><?php echo $dato['ApellidoP']; ?></td>
                                <td><?php echo $dato['ApellidoM']; ?></td>
                                <td><?php echo $dato['Genero']; ?></td>
                                <td><?php echo $dato['Domicilio']; ?></td>
                                <td><?php echo $dato['CodigoPostal']; ?></td>
                                <td><?php echo $dato['TelefonoP']; ?></td>
                                <td><?php echo $dato['TelefonoS']; ?></td>
                                <td>

                                    <a class="btn btn-success btn-sm" target="_blank" href="Personal.php?id=<?php echo $dato['IdUsuario'] ?>">Datos</a>


                                    <form method="POST" action="">

                                        <input type="hidden" name="idUsuario" value="<?php echo $dato['IdUsuario'] ?>">
                                        <button class="btn btn-danger btn-sm" name="btnCancelar">Eliminar</button>
                                    </form>
                                </td>




                            </tr>

                        <?php
                        }
                        ?>

                </table>
            </div>

            <div class="container  bg-light text-dark">
                <form method="POST" action="">
                    <div class="d-grid gap-2 col-6 mx-auto">
                        <h2 class="mt-3">Registro de Personal</h2>
                    </div>
                    <h5>Datos para creacion del usuario</h4>
                        <label for="correo" class="form-label">Correo electrónico:</label>
                        <input type="text" class="form-control mb-3" name="correo" placeholder="correo@example.com" id="correo">

                        <label for="password" class="form-label">Contraseña:</label>
                        <input type="password" class="form-control mb-3" name="password" placeholder="Escriba su contraseña" id="password">

                        <h5>Datos de la secretaria</h4>
                            <label for="fechaI" class="form-label">Fecha de Ingreso:</label>
                            <input type="date" class="form-control mb-3" name="fechaI" id="fechaI">

                            <label for="cufechaNrp" class="form-label">Fecha de FechaNacimiento:</label>
                            <input type="date" class="form-control mb-3" name="fechaN" id="fechaN">


                            <label for="nombre" class="form-label">Nombre:</label>
                            <input type="text" class="form-control mb-3" name="nombre" placeholder="Escriba su nombre" id="nombre">

                            <label for="apellidop" class="form-label">Apellido Paterno:</label>
                            <input type="text" class="form-control mb-3" name="apellidoP" placeholder="Escriba su apellido paterno" id="apellidop">

                            <label for="apellidom" class="form-label">Apellido Materno:</label>
                            <input type="text" class="form-control mb-3" name="apellidoM" placeholder="Escriba su apellido materno" id="apellidom">

                            <label for="direccion" class="form-label">Domicilio:</label>
                            <input type="text" class="form-control mb-3" name="direccion" placeholder="Escriba su direecion" id="direccion">

                            <label for="cp" class="form-label">Código Postal:</label>
                            <input type="text" class="form-control mb-3" name="cp" placeholder="Escriba su código postal" id="cp">

                            <label for="telefonoP" class="form-label">Telefono Principal:</label>
                            <input type="text" class="form-control mb-3" name="telefonoP" placeholder="Escriba su telefono" id="telefonoP">
                            <label for="telefonoS" class="form-label">Telefono Secundario:</label>
                            <input type="text" class="form-control mb-3" name="telefonoS" placeholder="Escriba su telefono" id="telefonoS">

                            <label for="rmasculino" class="form-label">Genero:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rGenero" id="rmasculino" value="M">
                                <label class="form-check-label" for="rmasculino">
                                    Masculino
                                </label>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" name="rGenero" id="rfemenino" value="F">
                                <label class="form-check-label mb-3" for="rfemenino">
                                    Femenino
                                </label>
                            </div>
                            <div class="d-grid gap-2 col-6 mx-auto">
                                <button class="btn btn-primary" name="btnRegistrar">Registrar</button>
                            </div>
                </form>
                <br>
                <br>

            </div>

        <?php
        }
        ?>
    <script src="../libs/js/bootstrap.min.js"></script>

</body>

</html>