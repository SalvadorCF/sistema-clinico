<?php
session_start();
$NombreSecretaria = $_SESSION['NombreSecretaria'];
$ApellidoPSecretaria = $_SESSION['ApellidoPSecretaria'];
$ApellidoMSecretaria = $_SESSION['ApellidoMSecretaria'];
include_once '../conexion.php';

if (!isset($NombreSecretaria)) {
  header("location: ../index.php");
}

if (isset($_POST['btnRegistrar'])) {
  $correo = $_POST['correo'];
  $password = $_POST['password'];
  $curp = $_POST['curp'];
  $nombre = $_POST['nombre'];
  $apellidoP = $_POST['apellidoP'];
  $apellidoM = $_POST['apellidoM'];
  $direccion = $_POST['direccion'];
  $cp = $_POST['cp'];
  $telefono = $_POST['telefono'];
  $rGenero = $_POST['rGenero'];
  $altura = $_POST['altura'];
  $peso = $_POST['peso'];
  $fecha = $_POST['fecha'];
  $tipoSangre = $_POST['tipoSangre'];
  $padecimientos = $_POST['padecimientos'];
  $alergias = $_POST['alergias'];

  $cargo = "PACIENTE";

  //Registrar el usuario
  $sql_insertarU = 'INSERT INTO Usuarios (Correo,Password,Cargo) VALUES (?,?,?)';
  $sentencia_agregarU = $pdo->prepare($sql_insertarU);
  $sentencia_agregarU->execute(array($correo, $password, $cargo));

  //Busqueda del Usuario
  $sql_unico = 'SELECT IdUsuario FROM Usuarios WHERE Correo=?';
  $gsent_unico = $pdo->prepare($sql_unico);
  $gsent_unico->execute(array($correo));
  $resultado_unico = $gsent_unico->fetch();

  $idUsuario=$resultado_unico['IdUsuario'];

  //Agregar al paciente
  $sql_insertarP = 'INSERT INTO Pacientes (CURP,Nombre,ApellidoP,
  ApellidoM,Sexo,Altura,Peso,FechaNacimiento,TipoSangre,CodigoPostal,
  Domicilio,Padecimientos,Alergias,Telefono,IdUsuario) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
  $sentencia_agregarP = $pdo->prepare($sql_insertarP);
  $sentencia_agregarP->execute(array($curp, $nombre, $apellidoP,
$apellidoM,$rGenero,$altura,$peso,$fecha,$tipoSangre,$cp,$direccion,
$padecimientos,$alergias,$telefono,$idUsuario));

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


  <title>Registrar Paciente</title>
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
          <li class="nav-item">
            <a class="nav-link" href="SAgendarHorario.php">Agendar Horario</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link active" aria-current="page" href="SRegistrarPaciente.php">Registrar Pacientes</a>
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

  <div class="container  bg-light text-dark">
    <form method="POST" action="">
      <div class="d-grid gap-2 col-6 mx-auto">
        <h2 class="mt-3">Registro de Pacientes</h2>
      </div>
      <h5>Datos para creacion del usuario</h4>
        <label for="correo" class="form-label">Correo electrónico:</label>
        <input type="text" class="form-control mb-3" name="correo" placeholder="correo@example.com" id="correo">

        <label for="password" class="form-label">Contraseña:</label>
        <input type="password" class="form-control mb-3" name="password" placeholder="Escriba su contraseña" id="password">

        <h5>Datos del paciente</h4>
          <label for="curp" class="form-label">CURP:</label>
          <input type="text" class="form-control mb-3" name="curp" placeholder="Escriba su CURP" id="curp">

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

          <label for="telefono" class="form-label">Telefono:</label>
          <input type="text" class="form-control mb-3" name="telefono" placeholder="Escriba su telefono" id="telefono">

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

          <div class="input-group mb-3">
            <label for="altura" class="form-label">Altura(mts):&nbsp;&nbsp;</label>
            <input type="text" class="form-control" name="altura" placeholder="Escriba su altura" id="altura">

            <label for="peso" class="form-label">&nbsp; Peso(kg):&nbsp;&nbsp;</label>
            <input type="text" class="form-control" name="peso" placeholder="Escriba su peso" id="peso">
          </div>
          <label for="fecha" class="form-label">Seleccione su fecha de nacimiento:</label>
          <input type="date" class="form-control mb-2" name="fecha" id="fecha">

          <label for="fecha" class="form-label" name="tipoSangre">Seleccione su tipo de sangre:</label>
          <select class="form-select mb-3" aria-label="Default select example" name="tipoSangre">
            <option selected>Tipo de sangre</option>
            <option value="O-">O negativo</option>
            <option value="O+">O positivo</option>
            <option value="A-">A negativo</option>
            <option value="A+">A positivo</option>
            <option value="B-">B negativo</option>
            <option value="B+">B positivo</option>
            <option value="AB-">AB negativo</option>
            <option value="AB+">AB positivo</option>
          </select>
          <label for="Escriba sus padecimientos" class="form-label">Padecimientos:</label>
          <input type="text" class="form-control mb-3" name="padecimientos" placeholder="Escriba sus padecimientos" id="Escriba sus padecimientos">

          <label for="alergias" class="form-label">Alergias:</label>
          <input type="text" class="form-control mb-3" name="alergias" placeholder="Escriba su telefono" id="alergias">

          <div class="d-grid gap-2 col-6 mx-auto">
            <button class="btn btn-primary" name="btnRegistrar">Registrar</button>
          </div>
    </form>
    <br>
    <br>

  </div>





  <script src="../libs/js/bootstrap.min.js"></script>

</body>

</html>