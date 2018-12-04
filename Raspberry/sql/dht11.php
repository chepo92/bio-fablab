<?php
$password= "1234";
 //Importamos la configuracion
 require("PWR_config.php");
if ($_GET['pass'] == $password)
 {
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

 // Leemos los valores que nos llegan por GET
 $value0 = mysqli_real_escape_string($con, $_GET['sid']);
 $temp = mysqli_real_escape_string($con, $_GET['temp']);
 $humedad = mysqli_real_escape_string($con, $_GET['humedad']);
 // E Insertamos los valores en la tabla
 $query = "INSERT INTO valores(sensor,temp, humedad) VALUES('$value0', '$temp','$humedad')";
 // Ejecutamos la instruccion
 mysqli_query($con, $query);
 
 echo "<br />Pagina para subir los datos<br />";
 echo "<br />New record has id: " . mysqli_insert_id($con); 
 echo "<br />Sensor ID = $value0 <br />";
 echo "<br />Temperatura = $temp C<br />";
 echo "<br />Humedad = $humedad %<br />";
 mysqli_close($con);
 }
 else
 {
 echo "Acceso bloqueado. Necesitas la contrase?a para acceder a la base de datos."; 
 }
?>