<?php
include_once 'conexion.php';
session_start();

$usuario = $_POST['correo'];
$clave = $_POST['clave'];

//Ver que sus datos son correctos
$sql_validar = 'SELECT COUNT(*) as contar FROM Usuarios WHERE Correo=? and Password=?';
$gsent_validar = $pdo->prepare($sql_validar);
$gsent_validar->execute(array($usuario, $clave));
$resultado_validar = $gsent_validar->fetch();

if ($resultado_validar['contar'] > 0) {
    //Determinar el cargo
    $sql_cargo = 'SELECT IdUsuario, Cargo FROM Usuarios WHERE Correo=? and Password=?';
    $gsent_cargo = $pdo->prepare($sql_cargo);
    $gsent_cargo->execute(array($usuario, $clave));
    $resultado_cargo = $gsent_cargo->fetch();

    if ($resultado_cargo['Cargo'] == "PACIENTE") {
        //Determinar el Nombre del paciente
        $sql_paciente = 'SELECT CURP,Nombre,ApellidoP,ApellidoM FROM Pacientes WHERE IdUsuario=? ';
        $gsent_paciente = $pdo->prepare($sql_paciente);
        $gsent_paciente->execute(array($resultado_cargo['IdUsuario']));
        $resultado_paciente = $gsent_paciente->fetch();

        $_SESSION['IdPaciente'] = $resultado_cargo['IdUsuario'];
        $_SESSION['CURP'] = $resultado_paciente['CURP'];
        $_SESSION['NombrePaciente'] = $resultado_paciente['Nombre'];
        $_SESSION['ApellidoPPaciente'] = $resultado_paciente['ApellidoP'];
        $_SESSION['ApellidoMPaciente'] = $resultado_paciente['ApellidoM'];
        
        header("location:Paciente/PMisCitas.php");
    }
    if ($resultado_cargo['Cargo'] == "SECRETARIA") {
        //Determinar el Nombre de la secretaria
        $sql_secretaria = 'SELECT IdPersonal,Nombre,ApellidoP,ApellidoM FROM Personal WHERE IdUsuario=? ';
        $gsent_secretaria = $pdo->prepare($sql_secretaria);
        $gsent_secretaria->execute(array($resultado_cargo['IdUsuario']));
        $resultado_secretaria = $gsent_secretaria->fetch();

        $_SESSION['IdPersonalS'] = $resultado_secretaria['IdPersonal'];
        $_SESSION['NombreSecretaria'] = $resultado_secretaria['Nombre'];
        $_SESSION['ApellidoPSecretaria'] = $resultado_secretaria['ApellidoP'];
        $_SESSION['ApellidoMSecretaria'] = $resultado_secretaria['ApellidoM'];
        
        header("location:Secretaria/SCitas.php");
    }
    if ($resultado_cargo['Cargo'] == "MEDICO") {
        //Determinar el Nombre del Medico
        $sql_medico = 'SELECT IdPersonal,Nombre,ApellidoP,ApellidoM FROM Personal WHERE IdUsuario=? ';
        $gsent_medico = $pdo->prepare($sql_medico);
        $gsent_medico->execute(array($resultado_cargo['IdUsuario']));
        $resultado_medico = $gsent_medico->fetch();

        $_SESSION['IdPersonal'] = $resultado_medico['IdPersonal'];
        $_SESSION['NombreMedico'] = $resultado_medico['Nombre'];
        $_SESSION['ApellidoPMedico'] = $resultado_medico['ApellidoP'];
        $_SESSION['ApellidoMMedico'] = $resultado_medico['ApellidoM'];
        
        header("location:Medico/Consultar.php");
    }
} else {
    header("location:index.php");
    
}
