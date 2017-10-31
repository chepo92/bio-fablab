<?php
 // dht11.php
 //Importamos la configuracion
 require("config.php");

if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

 // Leemos los valores que nos llegan por GET
 $temp = mysqli_real_escape_string($con, $_GET['temp']);
 $humedad = mysqli_real_escape_string($con, $_GET['humedad']);
 // E Insertamos los valores en la tabla
 $query = "INSERT INTO valores(temp, humedad) VALUES('$temp','$humedad')";
 // Ejecutamos la instruccion
 mysqli_query($con, $query);
 
 echo "<br />Pagina para subir los datos<br />";
 echo "<br />New record has id: " . mysqli_insert_id($con); 
 echo "<br />Temperatura = $temp ºC<br />";
 echo "<br />Humedad = $humedad %<br />";
 mysqli_close($con);
?>