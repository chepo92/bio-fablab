<html>
    <head>
    
<meta http-equiv="refresh" content="10" > 
    </head>
    <body>




<?php
 // dht11.php
 //Importamos la configuracion
 require("config.php");

if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

 
 $query = "SELECT id,temp,humedad FROM valores ORDER BY id DESC";
 // Ejecutamos la instruccion
 $result=mysqli_query($con, $query);
 

// Associative array
$row=mysqli_fetch_assoc($result);


echo "<br />Pagina para visualizar los datos<br />";
printf ("New record has id: %s \n Temperatura = %s ºC  \n Humedad =  %s%% \n",$row["id"],$row["temp"],$row["humedad"]);

// Free result set


 mysqli_free_result($result);
 mysqli_close($con);
?>

</body>
</html>