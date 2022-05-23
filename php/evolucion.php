<?php
  session_start();
  if($_SESSION["s_Usuario"] === null){
	  header("Location: ./index.php");
  }else if($_SESSION["s_Nivel"] > 2 ){
	$Url = trim($_SESSION["s_Url"]);
	header("Location: ./$Url");
  }
?>

<!DOCTYPE html>
<?php 
	include("conexion_sis.php");
?>
<meta charset="UTF-8">
<html> 	
	<head>
		<?<?php 
         $self = $_SERVER['PHP_SELF'];

         header("refresh:360; url=$self");
		 ?>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
	<title>Resumen de Ventas</title>
	<link data-n-head="1" rel="icon" type="image/x-icon" href="favicon.ico">
    <!-- Bootstrap core CSS -->
	 <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	   <!-- <link href="css/bootstrap.min.css" rel="stylesheet"> -->
	   <link rel="stylesheet" href="css/styles.css">
	 
	   </head>
<body>
	<div class="container" id="resumen">	
		<div class="row">
			<div class="col-md-3 m-1">
				<div class="card bg-success text-white text-center m-1" style="max-width: 18rem;">
				  	<div class="card-header">Total Facturado</div>
						 <div class="card-body">
						  <h7 class="card-title"><span id="idVentaE">0</span></h7>
						  <h7 class="card-title"><span id="idVentaN">0</span></h7>
						  <h5 class="card-title"><span id="idVentas">0</span></h5>
						  <h6 class="card-title"><span id="idPartV">0</span></h6>						  
					     </div>
				</div>
			</div>
<!--			<div class="col-md-0 m-2">
				   <div class="card  text-center m-1" style="max-width: 2rem;">
				      <h2><br>+<br><br></h2>
				   </div>
			 </div> -->
			<div class="col-md-2">
				<div class="card bg-warning text-white text-center m-1" style="max-width: 16rem;">
				  	<div class="card-header">Total Pedidos</div>
					  <div class="card-body">
						<h5 class="card-title"><span id="idPedido">0</span></h5>
						<h6 class="card-title"><span id="idRemPxV">0</span></h5>
					</div>
				</div>			
			</div>
<!--			<div class="col-md-0 m-2">
			    <div class="card  text-center m-1" style="max-width: 2rem;">
			        <h2><br>=<br><br></h2>
			    </div>
			</div> -->
			<div class="col-md-3">
				<div class="card bg-info text-white text-center m-1" style="max-width: 14rem;">
				  	<div class="card-header">Total Ventas</div>
					     <div class="card-body">
						   <h5 class="card-title"><span id="idTotal">0</span></h5>
						   <h6 class="card-title"><span id="idIVA">0</span></h6>
					     </div>
				</div>			
			</div>
			<div class="col-md-3">
				<div class="card bg-primary text-white text-center m-1" style="max-width: 16rem;">
				  	<div class="card-header">Cuentas x Cobrar</div>
					     <div class="card-body">
						   <h5 class="card-title"><span id="idCxC">0</span></h5>
						   <h6 class="card-title"><span id="idCobMes">0</span> </h6>
						   <h6 class="card-title"><span id="idCobDia">0</span> </h6>
					     </div>
				</div>			
			</div>
			<div class="dropdown">
				<button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<?php echo $_SESSION["s_Usuario"] ; ?>
		  		</button>
		  		<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
					<a class="dropdown-item" href="Pedidos.php">Pedidos</a>
					<a class="dropdown-item" href="prueba.html">Producción</a>
					<a class="dropdown-item" href="dashboard.php">Tablero</a>
					<a class="dropdown-item" href="logout.php">Salida</a>
		  		</div>
			</div>
		</div>

		<div class="row my3">
			<div class="col-md-12 text-center">
				<h2>Evolucion de Ventas</h2>
				<canvas id="idGrafica" class="grafica" height="80%"></canvas>
			</div>
        </div>	
		<div class="row my3">
			<div id="idConTabla"></div>
		</div>
		<div class="row my2">
			<div class="col-md-12 text-center">
				<h2>CMg</h2>
				<canvas id="idGraficaCMg" class="grafica" height="70%"></canvas>
			</div>
		</div>
					
	</div>		


	

	<script src="js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
	<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
    
    <script src= "js/index.js"></script>
<body>


</html>