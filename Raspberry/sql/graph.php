<!DOCTYPE html>
<html>
<title>Log Biofablab</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="./w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Karma">
<style>
body,h1,h2,h3,h4,h5,h6 {font-family: "Karma", sans-serif}
.w3-bar-block .w3-bar-item {padding:20px}
</style>

<body>

<?php  
	require("config.php");
	date_default_timezone_set("America/Santiago");
	if (mysqli_connect_errno())
	  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }
	 $fechahora = isset($_POST ['fecha']) ? $_POST ['fecha'] : date("Y-m-d H:i:s");     
?>

<?php

function temperatura_diaria ($con,$chipId,$variable,$ano,$mes,$dia) {

$query = "SELECT UNIX_TIMESTAMP(tiempo), $variable FROM valores WHERE year(`tiempo`) = '$ano' AND month(`tiempo`) = '$mes' AND day(`tiempo`) = '$dia' AND `sensor`= '$chipId'";
$resultado=mysqli_query($con, $query);

 while ($row=mysqli_fetch_array($resultado))
 {
  echo "[";
  echo ($row[0]*1000)-14400000; //le resto 4 horas = 14400000 mill
  echo ",";
  echo $row[1];
  echo "],";
 }
}
?>


<!-- Sidebar (hidden by default) -->
<nav class="w3-sidebar w3-bar-block w3-card w3-top w3-xlarge w3-animate-left" style="display:none;z-index:2;width:40%;min-width:300px" id="mySidebar">
  <a href="javascript:void(0)" onclick="w3_close()"
  class="w3-bar-item w3-button">Cerrar Menu</a>
  <a href="#food" onclick="w3_close()" class="w3-bar-item w3-button">Hoy</a>
  <a href="#about" onclick="w3_close()" class="w3-bar-item w3-button">Consulta</a>
</nav>

<!-- Top menu -->
<div class="w3-top">
  <div class="w3-white w3-xlarge" style="max-width:1200px;margin:auto">
    <div class="w3-button w3-padding-16 w3-left" onclick="w3_open()">☰</div>
	<div class="w3-right w3-padding-16">Mail</div>
	<div class="w3-center w3-padding-16"><?php echo "Fecha: ".$fechahora; ?></div>

  </div>
</div>
  
<!-- !PAGE CONTENT! -->
<div class="w3-main w3-content w3-padding" style="max-width:1200px;margin-top:100px">

  <!-- First Photo Grid-->
  <div class="w3-row-padding w3-padding-16 w3-center" id="food">
    <div class="w3-half">
	  <div id="graph1"></div>
    </div>
    <div class="w3-half">
	  <div id="graph2"></div>
    </div>
  </div>
  
  <!-- Second Photo Grid-->
  <div class="w3-row-padding w3-padding-16 w3-center">
    <div class="w3-half">
	  <div id="graph3"></div>
    </div>
    <div class="w3-half">
	  <div id="graph4"></div>
    </div>

  </div>

  <!-- Pagination -->
  <div class="w3-center w3-padding-32">
    <div class="w3-bar">
      <a href="#" class="w3-bar-item w3-button w3-hover-black">«</a>
      <a href="#" class="w3-bar-item w3-black w3-button">1</a>
      <a href="#" class="w3-bar-item w3-button w3-hover-black">2</a>
      <a href="#" class="w3-bar-item w3-button w3-hover-black">3</a>
      <a href="#" class="w3-bar-item w3-button w3-hover-black">4</a>
      <a href="#" class="w3-bar-item w3-button w3-hover-black">»</a>
    </div>
  </div>
  
  <hr id="about">

  <!-- About Section -->
  <div class="w3-container w3-padding-32 w3-center">  
    <h3>About BioMateriales</h3><br>
    <img src="/w3images/chef.jpg" alt="Smartgrids" class="w3-image" style="display:block;margin:auto" width="800" height="533">
    <div class="w3-padding-32">
      <h4><b>I am all of me!</b></h4>
      <h6><i>Monitoreo de sistemas</i></h6>
      <p>Herramienta para el monitoreo de los sistemas biologicos y criaderos </p>
    </div>
  </div>
  <hr>
  
  <!-- Footer -->
  <footer class="w3-row-padding w3-padding-32">
    <div class="w3-third">
      <h3>CONTACTO</h3>
      <p>info@domain.cl</p>
      <p>Powered by <a href="https://www.w3schools.com/w3css/default.asp" target="_blank">w3.css</a></p>
    </div>
  
    <div class="w3-third">
      <h3>Redes Sociales</h3>
      <ul class="w3-ul w3-hoverable">
        <li class="w3-padding-16">
          <img src="/w3images/workshop.jpg" class="w3-left w3-margin-right" style="width:50px">
          <span class="w3-large">Lorem</span><br>
          <span>Sed mattis nunc</span>
        </li>
        <li class="w3-padding-16">
          <img src="/w3images/gondol.jpg" class="w3-left w3-margin-right" style="width:50px">
          <span class="w3-large">Ipsum</span><br>
          <span>Praes tinci sed</span>
        </li> 
      </ul>
    </div>

    <div class="w3-third w3-serif">
      <h3>Consultar Fecha</h3>
	  
	  <form action="graph.php" method="POST">
		<input type="date" name="fecha" ><br>
		<br>
		<input type="submit" name="Enviar" >
		</form>
	  
    </div>
  </footer>

<!-- End page content -->
</div>

<script>
// Script to open and close sidebar
function w3_open() {
    document.getElementById("mySidebar").style.display = "block";
}
 
function w3_close() {
    document.getElementById("mySidebar").style.display = "none";
}
</script>

<script src="js/jquery.min.js"></script>
<script src="js/highcharts.js"></script>
<script src="js/exporting.js"></script>

<script>
$(function () {
    $('#graph1').highcharts({
        chart: {
            type: 'line',
            zoomType: 'x'
        },
        colors: ['#337ab7', '#cc3c1a'],
        title: {
            text: 'Temperatura'
        },
        xAxis: {
             type: 'datetime',         
        },
        yAxis: {
            title: {
                text: 'Escala'
            }
        },
      
        series: [{
            name: 'Sensor 1',
            data: [<?php
                $chipId = 270824;
				$variable = temp;
				$fecha = isset($_POST ['fecha']) ? $_POST ['fecha'] : date("Y-m-d");             
                $ano = substr("$fecha", 0, 4); // 2017/07/16
                $mes = substr("$fecha", 5, 2);
                $dia = substr("$fecha", 8, 2);
                temperatura_diaria($con,$chipId, $variable, $ano , $mes, $dia);
             ?>]
	}, {
            name: 'Sensor 2',
            data: [<?php
                $chipId = 270846;
				$variable = temp;
                $fecha = isset($_POST ['fecha']) ? $_POST ['fecha'] : date("Y-m-d");             
                $ano = substr("$fecha", 0, 4); // 2017/07/16
                $mes = substr("$fecha", 5, 2);
                $dia = substr("$fecha", 8, 2);
                temperatura_diaria($con,$chipId, $variable, $ano , $mes, $dia);
             ?>]
	}],
    });
});
</script>

<script>
$(function () {
    $('#graph2').highcharts({
        chart: {
            type: 'line',
            zoomType: 'x'
        },
        colors: ['#337ab7', '#cc3c1a'],
        title: {
            text: 'Humedad'
        },
        xAxis: {
             type: 'datetime',         
        },
        yAxis: {
            title: {
                text: 'Escala'
            }
        },
      
        series: [{
            name: 'Sensor 1',
            data: [<?php
                $chipId = 270824;
				$variable = humedad;
                $fecha = isset($_POST ['fecha']) ? $_POST ['fecha'] : date("Y-m-d");             
                $ano = substr("$fecha", 0, 4); // 2017/07/16
                $mes = substr("$fecha", 5, 2);
                $dia = substr("$fecha", 8, 2);
                temperatura_diaria($con,$chipId, $variable, $ano , $mes, $dia);
             ?>]
	}, {
            name: 'Sensor 2',
            data: [<?php
                $chipId = 270846;
				$variable= humedad;
                $fecha = isset($_POST ['fecha']) ? $_POST ['fecha'] : date("Y-m-d");             
                $ano = substr("$fecha", 0, 4); // 2017/07/16
                $mes = substr("$fecha", 5, 2);
                $dia = substr("$fecha", 8, 2);
                temperatura_diaria($con,$chipId,$variable, $ano , $mes, $dia);
             ?>]
	}],
    });
});
</script>

<script>
$(function () {
    $('#graph3').highcharts({
        chart: {
            type: 'line',
            zoomType: 'x'
        },
        colors: ['#337ab7', '#cc3c1a'],
        title: {
            text: 'Free'
        },
        xAxis: {
             type: 'datetime',         
        },
        yAxis: {
            title: {
                text: 'Escala'
            }
        },
      
        series: [{
            name: 'Sensor 1',
            data: [<?php
                $chipId = 270824;
				$variable = temp;
                $fecha = isset($_POST ['fecha']) ? $_POST ['fecha'] : date("Y-m-d");             
                $ano = substr("$fecha", 0, 4); // 2017/07/16
                $mes = substr("$fecha", 5, 2);
                $dia = substr("$fecha", 8, 2);
                temperatura_diaria($con,$chipId, $variable, $ano , $mes, $dia);
             ?>]
	}, {
            name: 'Sensor 2',
            data: [<?php
                $chipId = 270846;
				$variable= temp;
                $fecha = isset($_POST ['fecha']) ? $_POST ['fecha'] : date("Y-m-d");             
                $ano = substr("$fecha", 0, 4); // 2017/07/16
                $mes = substr("$fecha", 5, 2);
                $dia = substr("$fecha", 8, 2);
                temperatura_diaria($con,$chipId,$variable, $ano , $mes, $dia);
             ?>]
	}],
    });
});
</script>

<script>
$(function () {
    $('#graph4').highcharts({
        chart: {
            type: 'line',
            zoomType: 'x'
        },
        colors: ['#337ab7', '#cc3c1a'],
        title: {
            text: 'Free'
        },
        xAxis: {
             type: 'datetime',         
        },
        yAxis: {
            title: {
                text: 'Escala'
            }
        },
      
        series: [{
            name: 'Sensor 1',
            data: [<?php
                $chipId = 270824;
				$variable = humedad;
                $fecha = isset($_POST ['fecha']) ? $_POST ['fecha'] : date("Y-m-d");             
                $ano = substr("$fecha", 0, 4); // 2017/07/16
                $mes = substr("$fecha", 5, 2);
                $dia = substr("$fecha", 8, 2);
                temperatura_diaria($con,$chipId, $variable, $ano , $mes, $dia);
             ?>]
	}, {
            name: 'Sensor 2',
            data: [<?php
                $chipId = 270846;
				$variable= humedad;
                $fecha = isset($_POST ['fecha']) ? $_POST ['fecha'] : date("Y-m-d");             
                $ano = substr("$fecha", 0, 4); // 2017/07/16
                $mes = substr("$fecha", 5, 2);
                $dia = substr("$fecha", 8, 2);
                temperatura_diaria($con,$chipId,$variable, $ano , $mes, $dia);
             ?>]
	}],
    });
});
</script>

</body>
</html>
