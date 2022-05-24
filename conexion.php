<?php 

$link= 'mysql:host=localhost;dbname=consultorio';
$usuario='root';
$pass='';

try{
    $pdo= new PDO($link,$usuario,$pass);
    //echo 'Conectado';

    //foreach($pdo->query('Select * from colores')as $fila){
     //   print_r($fila);

   // }


}catch(PDOException $e){
    print "Error ".$e->getMesasge()."<br/>";
    die();
}
?>