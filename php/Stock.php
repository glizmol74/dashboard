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
	if(isset($_GET['detalleR'])){
		$MesConsulta = $_GET['detalleR'];
		$MesN = intval(substr($MesConsulta,-2,2));
		$pagUrl = $_GET['Pagina'];
		$MesConsulta = 'REMPXV';
	}else{
		$MesConsulta = '';
	    $MesN = 0;
	}
?>
<meta charset="UTF-8">
<html> 	
	<head>
		<?php 
         $self = $_SERVER['PHP_SELF'];

       //  header("refresh:250; url=$self");
		 ?>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
	<title>Stock Valorizado</title>
	<link data-n-head="1" rel="icon" type="image/x-icon" href="../../favicon.ico">
    <!-- Bootstrap core CSS -->
    <link href="../html/pedido.css" rel="stylesheet">
    <link href="../css/bootstrap.min.css" rel="stylesheet">     			
	</head>
<body>
		<div id="general2">
		  <form method="POST" action="">
			<div class="row">
			    <div class="alert alert-info" role="alert">
					Stock Valorizado Por Clasificacion
				</div>
			</div>
		     	<!-- <div class="col-md-8 col-md-offset-2"> -->
					<table class="table table-bordered table-responsive">
		
						<?php
						  $consulta = "select Clasif1 Clasif, NameC1 Descp, sum (stock * CostoValor) Total from Productos
						  where Stock > 0 and Clasif1 > 0
						  group by Clasif1, NameC1 order by 1";
						  $ejecutar = sqlsrv_query($con, $consulta);

						  $i = 0;
						  ?>
						  <table class="table table-bordered table-responsive">
						    <tr>
							<?php
							   $Valores = array();
							   $ClasiF = array();
						       while($fila = sqlsrv_fetch_array($ejecutar)){
							     $CadTitulo = $fila['Descp'];
								 $Valores[$i] = $fila['Total'];
								 $ClasiF[$i] = $fila['Clasif'];
						    ?>

							<td><a href="StockDetalle.php?TipoRepo=<?php echo $ClasiF[$i] ; ?>&TotalRepo=<?php echo $Valores[$i] ; ?>&TRepo=<?php echo $CadTitulo ; ?>&Pagina=Stock.php"><?php echo $CadTitulo , ' :  ', number_format($Valores[$i],2,",",".") ?></a></td>
							<?php
							  $i = $i + 1;
							   }
							?>
							</tr>
							
                            <!--<td><a href="Remitos.php?detalle=<?php echo $ClienteID ; ?>&MesC=<?php echo $MesConsulta ; ?>&ClienteN=<?php echo $ClienteN ; ?>&Pagina=<?php echo $pagUrl ; ?>">Detalle</a></td>  -->

						
						<div class="form-group">
							<input type="submit" name="Regresar" class="btn btn-warning" value="REGRESAR">
						</div>
					</table>
				<!-- </div> -->
		     
				<div class="row">

					<!-- Area Chart -->
	  				<div class="chart-pie pt-10">
						<canvas id="idGrafStock"></canvas>
	  				</div>
				</div>
    	  </form>
		</div>
	<?php
	 /* if(isset($_GET['detalle'])){
	  	 include("Consulta.php");
	  	 echo "<script>window.open('Factura.php', '_self')</script>";
	  }*/
	  if (isset($_POST['Regresar'])){
		echo "<script>window.open('dashboard.php', '_self')</script>";
	}
	?>

	<!-- Bootstrap core JavaScript-->
	<script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>

  <!-- Custom scripts for all pages-->
  <script src="../js/sb-admin-2.min.js"></script>
  <script src= "../js/index3.js"></script>

</body>
</html>