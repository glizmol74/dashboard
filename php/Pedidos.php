<?php 
	session_start();
  	if($_SESSION["s_Usuario"] === null){
	  	header("Location: ./index.php");
  	}else if($_SESSION["s_Nivel"] > 5 ){
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
		<?php 
         $self = $_SERVER['PHP_SELF'];

         header("refresh:360; url=$self");
		 ?>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
	<title>Pedidos</title>
	<link data-n-head="1" rel="icon" type="image/x-icon" href="../../favicon.ico">
    <!-- Bootstrap core CSS -->
    <link href="pedido.css" rel="stylesheet">
	<link rel="stylesheet" href="../css/bootstrap.min.css" 
	</head>
<body>
	<div class="container3">	
		<div id="general">
		  <div>
			<div class="row">
		  		<div class="col-md-7">
					 <h2>PEDIDOS PENDIENTES</h2>
				 </div>
			 	<div class="dropdown">
				 	<button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					 <?php echo $_SESSION["s_Usuario"] ; ?>
				   </button>
				   <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						<a class="dropdown-item" href="prueba.html">Producción</a>
						 <a class="dropdown-item" href="<?php echo $_SESSION['s_Url'] ?>">Inicio</a>	
						 <a class="dropdown-item" href="dashboard.php">Dashboard</a>
				 		<a class="dropdown-item" href="logout.php">Salida</a>
			   		</div>
		 		</div>
			</div>
		  </div>
		     <div id="pedido">
		     	<!-- <div class="col-md-10 col-md-offset-2"> -->
					<table class="table table-bordered table-responsive">
						<tr>
							<td>FechaP</td>
							<td>FechaE</td>
							<td>Ret</td>
							<td>Suc</td>
							<td>Numero</td>
							<td>Cliente</td>
							<td>Accion</td>
						</tr>

						<?php
						  $consulta = "Select FechaP, FechaE, DiaRetraso, ClienteName, SucId, DocumentoN, DocumentoID, Observacion 
										from documento 
										where TipoReporte = 'PedPend2' and subtotal <> 0";
						  $ejecutar = sqlsrv_query($con, $consulta);

						  $i = 0;
						  while($fila = sqlsrv_fetch_array($ejecutar)){
						  	$format = "m/d/y";
						  	$FechaP = $fila['FechaP'];

						  	$FechaE = $fila['FechaE'];


						  	$DiaR = $fila['DiaRetraso'];
						  	$Cliente = $fila['ClienteName'];
						  	$SucId = $fila['SucId'];
						  	$DocN = $fila['DocumentoN'];
							$DocID = $fila['DocumentoID'];
							$Observ = $fila['Observacion'];
						  	$i++;
						?>

					

						<tr align="center">
							<td><?php echo $FechaP->format("d/m/y"); ?></td>
							<td><?php echo $FechaE->format("d/m/y"); ?></td>
							<td><?php echo $DiaR; ?></td>
							<td><?php echo $SucId; ?></td>
							<td><?php echo $DocN; ?></td>
							<td align="left"><?php echo $Cliente; ?></td>
							<td><a href="Consulta.php?detalle=<?php echo $DocID ; ?>&ClienteN=<?php echo $Cliente ; ?>&ObsD=<?php echo $Observ ; ?>&Pagina=Pedidos.php">Detalle</a></td>
						<!--	<td align="right"><?php echo  '<span style="color:red">'.number_format($sdia0*-1,0,",",".").'</span>'; ?></td>
							<td align="right"><?php echo  '<span style="color:#FFA500">'.number_format($sdia1,0,",",".").'</span>'; ?></td>
							<td align="right"><?php echo  '<span style="color:#00FF00">'. number_format($sdia2,0,",",".").'</span>'; ?></td>
							<td align="right"><?php echo number_format($sdia2+$sdia1+$sdia0*-1,0,",","."); ?></td>
							<td><a href="formulario.php?editar=<?php echo $id; ?>">Editar</a></td>
							<td><a href="formulario.php?borrar=<?php echo $id; ?>">Borrar</a></td>  -->
						</tr>

						<?php } ?>
					</table>
				<!-- </div> -->
		     </div>
		     <div id="articulos">
		     	<table class="table table-bordered table-responsive">
					<tr>
						<td>Cod</td>
						<td>Descripcion</td>
						<td>Pedidos</td>
						<td>Entregado</td>
						<td>Pendiente</td>
						<td>Stock</td>
						<td>Diferencia</td>
						<td>Acción</td>
					</tr>
				
					<?php
			          $consulta = "Select distinct ap.ProductoId Cod, ap.Descripcion Descripcion, sum(TotalCantidad) Ped, sum(S3) CantFact, sum(s2) Pend, S4 Stock, ap.ClasificacionProdId, ap.diferencia Diff  
                            from AuxPed Ap  
                              where ap.S2 <> 0  and ap.TipoReporte = 'PedPend'  and ap.clasificacionProdID in (1,34)
                              
                              group by ap.ProductoId, ap.Descripcion, ap.ClasificacionProdId, ap.s4, ap.diferencia  order by ClasificacionProdId, ap.Descripcion";

						$ejecutar = sqlsrv_query($con, $consulta);

						$j = 0;
			
						while($fila = sqlsrv_fetch_array($ejecutar)){
							$cod = $fila['Cod'];
							$Descripcion= $fila['Descripcion'];
							$Ped = $fila['Ped'];
							$CantFact = $fila['CantFact'];
							$Pend = $fila['Pend'];
							$Stock = $fila['Stock'];
							$Diff = $fila['Diff'];
							$j++;
						

					?>

					<tr align="center">
						<td><?php echo $cod; ?></td>
						<td align="left"><?php echo $Descripcion; ?></td>
						<td align="right"><?php echo  '<span style="color:red">'.number_format($Ped,0,",",".").'</span>'; ?></td>
						<td align="right"><?php echo  '<span style="color:#FFA500">'.number_format($CantFact,0,",",".").'</span>'; ?></td>
						<td align="right"><?php echo  '<span style="color:#00FF00">'. number_format($Pend,0,",",".").'</span>'; ?></td>
						<td align="right"><?php echo number_format($Stock,0,",","."); ?></td>
						<td align="right"><?php echo number_format($Diff,0,",","."); ?></td>
						<td><a href="ConsultaC.php?Clientes=<?php echo $cod; ?> & Producto=<?php echo $Descripcion; ?>">Clientes</a></td>
					<!--	<td><a href="formulario.php?borrar=<?php echo $id; ?>">Borrar</a></td>  -->
					</tr>

				<?php } ?>

				</table>
		     </div>
		 </div>
	</div>
		<?php
		if(isset($_GET['detalle'])){
			include("Consulta.php");
			echo "<script>window.open('Consulta.php', '_self')</script>";
		}

		if(isset($_GET['Clientes'])){
			include("ConsultaC.php");
			echo "<script>window.open('ConsultaC.php', '_self')</script>";
		}
		?>
		<script src="../js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
	<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
    
</body>
</html>