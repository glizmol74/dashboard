<?php
  session_start();
  if($_SESSION["s_Usuario"] === null){
	  header("Location: ../../index.php");
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
		$FecMesConsula = '20' . substr($MesConsulta,-5,5) . '-01 00:00:00';
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
		
		<title>Relacion de Facturación</title>
		<link data-n-head="1" rel="icon" type="image/x-icon" href="../../favicon.ico">
		<!-- Bootstrap core CSS -->
		<link href="../html/pedido.css" rel="stylesheet">
		<link href="../css/sb-admin-2.min.css" rel="stylesheet">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
 	 	<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>     			
	</head>
	<body>
		<div id="content">
			<div class="continer-fluid">
				<div class="col-xl-12 col-md-12">
					<div class="card border-secundary shadown mb-3">
						<div class="card-header ma-0 py-2 d-flex flex-row align-items-center justify-content-between">
							<table class="table table-bordered table-striped mt-2" >
								<tr align="center">
									<td><a href="javascript:cambiarFacturaR('FacturacionV.php', 1, '<?php echo $MesConsulta ?>', 'dashboard.php')">Resumen Facturas X Clientes</a></td>
									<td><a href="javascript:cambiarFacturaR('FacturacionV.php', 2)">Facturación del Mes</a></td>
									<td bgcolor="#FF8C00"><a href="dashboard.php">Regresar</a></td>
								</tr>
							</table>
						</div>
						<div class="card-body text-dark ma-0 pa-0" id="IdFacturasR"></div>
					</div>
				</div>
			</div>
		</div>
		<?php
			if(isset($_GET['detalle'])){
				include("Consulta.php");
				echo "<script>window.open('Factura.php', '_self')</script>";
			}
			if (isset($_POST['Regresar'])){
				echo "<script>window.open('$pagUrl', '_self')</script>";
			}
		?>
		<script type="text/javascript">
			function cambiarFacturaR(pagina, Tipo, l_Mes, lPag) {
				$("#IdFacturasR").load(pagina, {TipoR: Tipo, DetalleR: l_Mes, Pagina: lPag})
			}
		</script>
		<script src="../js/jquery.min.js"></script>
		<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
		<script src="../js/jquery.table2excel.min.js"></script>
	</body>
</html>