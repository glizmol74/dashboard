<?php
 session_start();
  if($_SESSION["s_Usuario"] === null){
	  header("Location: ../../index.php");
	}
 else if($_SESSION["s_Nivel"] > 2 ){
	$Url = trim($_SESSION["s_Url"]);
	header("Location: ../php/dashboard.php");
	//$Mes_Actual = 'Mar-22';
  }
?>
<!DOCTYPE html>
<?php 
	include("conexion_sis.php");
	include ("../../config.php");

	$cad = 'Participación de Compras ' . $Mes_Actual ;
	$TituloPC =  "text: '$cad' ";
	$cad = "sqlsrv:Server=" . $ServerName . "; Database=" . $Base;
	$conex = new PDO($cad,$User,$Passw);
	$jDatosR = '';
	$i = 0;
	$Tp = "Comp-" .date("y-m");
	$Colors = ['#4dc9f6', '#f67019',	'#f53794', '#f1c40f', '#acc236', '#166a8f'];
	$consulta= "SET LANGUAGE Spanish
	set Dateformat YMD
	declare @FechaI datetime =  format(getdate(), 'yyyy-MM-01 00:00:00')
		select SUBSTRING(TipoReporte,6,5) as FechaN, 
			CONCAT(DATENAME(month, CONCAT('20',substring(tiporeporte,6,5),'-01 00:00:00')), '-20', SUBSTRING(tiporeporte,6,2)) as Fecha, 
			(CASE WHEN a.NombreClasifP != 'Sin Clasif' THEN a.NombreClasifP ELSE 'No Productiva' END ) Clase,
					sum(s1) as tCompras,
					(select sum(s1) from AuxPed where TipoReporte = '$Tp' ) as  Total
					from AuxPed A where TipoReporte = '$Tp' 
					group by TipoReporte, NombreClasifP
				order by 4 desc";
	try {
		$Rsql= $conex->prepare($consulta);
		$Rsql->execute();
		$dataG = '';
		$ClaseCp =  Array();
		$ValorCp =  Array();
		while($FilaR = $Rsql->fetch()){
			$TCompras = $FilaR['tCompras']+0;
			$Part = $FilaR['tCompras'] / $FilaR['Total'] * 100;
			$dataG = $dataG . "{ name: '" . $FilaR['Clase'] . "', y: " . $Part . " }, ";
			$ValorCp[$i] = $TCompras;
			$busqueda = strrpos($FilaR['Clase'], 'Articulos',0);
			if ( $busqueda === false) {
				$busqueda = strrpos($FilaR['Clase'], 'Productiva',0);
				if ( $busqueda === false) {
					$busqueda = strrpos($FilaR['Clase'], 'Materia', 0);
					if ( $busqueda === false) {
						$ClaseCp[$i] = 'Ins.';
					}else {
						$ClaseCp[$i] = 'M. P.';
					}
				}else {
					$ClaseCp[$i] = 'No P.';
				}
			}else {
				$ClaseCp[$i] = 'Art.';
			}
			$i++;
		}
	}catch(PDOException $e){
		echo "Error Conexión ". $e;
		$dataG = '';
	}
?>
<meta charset="UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">

<title>Dashboard</title>

  	<!-- Custom fonts for this template-->
  	<link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  	<link data-n-head="1" rel="icon" type="image/x-icon" href="../../favicon.ico">
  	<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  	<!-- Custom styles for this template-->
  	<link href="../css/sb-admin-2.min.css" rel="stylesheet">
  	<style type="text/css">
		  .highcharts-figure, .highcharts-data-table table {
			  min-width: 380px;
			  max-width: 960px;
			  margin: 1em auto;
		  }

		  .highcharts-data-table table {
			  font-family: Verdana,  sans-serif;
			  border-collapse: collapse;
			  border: 1px solid #ebebeb;
			  margin: 10px auto;
			  text-align: center;
			  width: 100%;
			  max-width: 700px;
		  }

		  .highcharts-data-table caption {
			  padding: 1em 0;
			  font-size: 1.2em;
			  color: #555;
		  }

		  .highcharts-data-table th {
			  font-weight: 600;
			  padding: 0.5em;
		  }

		  .highcharts-data-table td, .highcharts-data-table th .highcharts-data-table caption {
			  padding: 0.5em;
		  }

		  .highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
			  background: #f8f8f8;
		  }

		  .highcharts-data-table tr:hover {
			  background: #f1f7ff;
		  }

		  .highcharts-title {
			fill: #434348;
			font-weight: bold;
			font-size: 3em;
		  }
	</style>
<html> 	
	<head>
		<?php 
		$self = $_SERVER['PHP_SELF'];
		header("refresh:350; url=$self");
		$hyear = date('Y');
		?>
	</head>
	<body id="page-top">

		  <script src="../code/highcharts.js"></script>
		  <script src="../code/modules/data.js"></script>
		  <script src="../code/modules/drilldown.js"></script>
		  <script src="../code/modules/exporting.js"></script>
		  <script src="../code/modules/export-data.js"></script>
		  <script src="../code/modules/accessibility.js"></script>

		<!-- Page Wrapper -->
		<div id="wrapper">
			<!-- Content Wrapper -->
			<div id="content-wrapper" class="d-flex flex-column">

				<!-- Main Content -->
				<div id="content">

					<!-- Topbar -->
					<nav class="navbar navbar-expand navbar-light bg-white topbar mb-2 static-top shadow">

						<!-- Sidebar Toggle (Topbar) -->
						<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-5">
							<i class="fa fa-bars"></i>
						</button>

						<!-- Topbar Navbar -->
						<ul class="navbar-nav ml-auto">
								
							<div class="topbar-divider d-none d-sm-block"></div>

							<!-- Nav Item - User Information -->
							<li class="nav-item dropdown no-arrow">
							<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION["s_Usuario"] ; ?></span>
								<img class="img-profile rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/60x60">
							</a>
							<!-- Dropdown - User Information -->
							<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
								<a class="dropdown-item" href="Pedidos.php">
								<i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
								Pedidos
								</a>
								<a class="dropdown-item" href="../html/prueba.html">
								<i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
								Producción
								</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
								<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
								Logout
								</a>
							</div>
							</li>

						</ul>

					</nav>
					<!-- End of Topbar -->

					<!-- Begin Page Content -->
					<div class="container-fluid">

						<!-- Page Heading -->
					
						<!-- Content Row -->
						<h3 class="text-center font-weight-bold text-success">Resumen de Ventas </h3>
						<div class="row">
							
							<!-- Total Pedidos (Pendientes) -->
							<div class="col-xl-4 col-md-6 mb-4">
								<div class="card border-left-success shadow h-100 py-1 pb-0">
									<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-0">
											<div class="h4 font-weight-bold text-success text-uppercase mb-1 text-center"><font color="#27AE60">Total Pedidos</font></div>
											<div class="h4 mb-0 font-weight-bold text-gray-800 text-center"><a id="IdPedPen"  href="PedidosN.php?detalleR=2&Pagina=dashboard.php"
														data-toggle="tooltip" data-placement="top" title="Total de Pedidos Pendientes"><span id="idPedido">0</span></a></div>
											<div class="h6 mb-0 font-weight-bold text-gray-800 text-center"><span id="idPedCMG">0</span></div>
											<div class="h6 mb-0 font-weight-bold text-gray-800 text-center"> <a id="idPed_pen" href="PedidosN.php?detalleR=3&Pagina=dashboard.php"
														data-toggle="tooltip" data-placement="top" title="Pedidos Pendientes Meses Anteriores"><span id="idPedP">P=0</span></a> + <a id="idPedNewP" href="PedidosN.php?detalleR=4&Pagina=dashboard.php" 
														data-toggle="tooltip" data-placement="top" title="Pedidos Nuevos Pendientes del Mes"><span id="idPedNP">PNP=0</span></a></div>
											<div class="h4 mb-0 font-weight-bold text-gray-800 text-center"> <a id="idPedNew" href="PedidosN.php?detalleR=1&Pagina=dashboard.php"
														data-toggle="tooltip" data-placement="top" title="Pedidos Nuevos del Mes"><span id="idPedA">N=0</span></a></div>
											<div class="h6 mb-0 font-weight-bold text-gray-800 text-center"><span id="idPorcPed">0</span></div>
											<div class="row no-gutters align-items-center">
												<div class="col">
													<button type="button" class="btn btn-light btn-sm font-weight-bold text-primary" data-toggle="tooltip" data-placement="top" title="Pedidos Anteriores Pendientes" disabled>PP=</button>
												</div>
												<div class="col">
													<button type="button" class="btn btn-light btn-sm ml-5 font-weight-bold text-primary" data-toggle="tooltip" data-placement="top" title="Pedidos Nuevos del Mes" disabled>N=</button>
												</div>
												<div class="col">
													<button type="button" class="btn btn-light btn-sm ml-5 font-weight-bold text-primary" data-toggle="tooltip" data-placement="top" title="Pedidos Nuevos Pendientes del Mes" disabled>PNP=</button>
												</div>
											</div>
										</div>
										<!-- <div class="col-auto">
										<i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
										</div> -->
									</div>
									</div>
								</div>
							</div>

							<!-- Total Ventas Proyectada -->
							<div class="col-xl-4 col-md-5 mb-4">
								<div class="card border-left-success shadow h-100 py-1">
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-0">
												<div class="h4 font-weight-bold text-info text-uppercase mb-1 text-center"><font color="#27AE60">Fact. Est. - <?php echo $Mes_Actual;?></font></div>
											
												<div class="col mr-0">
													<div class="text-center mt-1">
														<button type="button" class="btn btn-link btn-lg font-weight-bold" data-toggle="modal" data-target="#estimada"
															data-toggle="tooltip" data-placement="top" title="Presiones Click Izq. para ver Detalle"
															style="font-size: 24px; font-weight: bold; border:#ebebeb;"><span id="idTotal">0</span></button>
													</div>
													<!-- <div class="h4 mb-0 mr-3 font-weight-bold text-primary text-center"><span id="idTotal">0</span></div> -->
													<div class="h6 mb-0 font-weight-bold text-gray-800 text-center  mt-3 mb-1"><a id="IdRem" href="RemitosPendienteXFacturar.php?detalleR=''&Pagina=dashboard.php" 
																data-toggle="tooltip" data-placement="top" title="Total de Remitos Pendientes por Emitir Factura de Ventas "><span id="idRemPxV">0</span></a></div>
													<div class="h6 mb-0 font-weight-bold text-gray-800 text-center my-2"><span id="idRemCMG">0</span></div>
												</div>
											</div>
											<!-- <div class="col-auto">
											<i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
											</div> -->
										</div>
									</div>
								</div>
							</div>

							<!-- Modal Estimada -->
							<div class="modal fade" id="estimada" tabindex="-1" role="dialog" aria-labelledby="Estimada" aria-hidden="true">
								<div class="modal-dialog modal-dialog-centered" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title"><font color="#27AE60">FACTURACION ESTIMADA - <?php echo $Mes_Actual;?></font></h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>
										<div class="modal-body">
											<div class="row">
												<div class="col-auto">
													<h6 class="modal-body font-weight-bold mb-0 mt-0 pb-0">Pedidos Pendientes </h6>
												</div>
												<div class="col">
													<h6 class="modal-body font-weight-bold text-primary mb-0 mt-0 pb-0 text-right"><span id="modalPedPend">0</span> </h6>
												</div>
											</div>
											<div class="row">
												<div class="col-auto">
													<h6 class="modal-body font-weight-bold mb-0 mt-0 pb-0 py-0 pa-1 ">Remitos Pendiente x Facturar </h6>
												</div>
												<div class="col">
													<h6 class="modal-body font-weight-bold text-primary mb-0 mt-0 pb-0 py-0 text-right"><span id="modalRemPend">0</span> </h6>
												</div>
											</div>
											<div class="row">
												<div class="col-auto">
													<h6 class="modal-body font-weight-bold mb-0 mt-0 pb-0 py-0">Facturado en el Mes </h6>
												</div>
												<div class="col">
													<h6 class="modal-body font-weight-bold text-primary mb-0 mt-0 pb-0 py-0 text-right"><span id="modalFacturado"></span> </h6>
												</div>
											</div>
											<div class="row">
												<div class="col-auto">
													<h6 class="modal-body font-weight-bold mb-0 mt-0 pb-0 py-0">Facturación Estimada -  <?php echo $Mes_Actual;?> </h6>
												</div>
												<div class="col">
													<h5 class="modal-body font-weight-bold text-primary mb-0 mt-0 pb-0 py-0 text-right"><span id="modalEstimada"></span> </h5>
												</div>
											</div>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
										</div>
									</div>
								</div>
							</div>

							<!-- Total Facturado (Mes Actual) -->
							<div class="col-xl-4 col-md-6 mb-4">
								<div class="card border-left-success shadow h-100 py-1">
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-0">
												<div class="h4 font-weight-bold text-success text-uppercase mb-1 text-center"><a id="FactV" href="#"><font color="#27AE60"><span id="idFactV">Total Fact. - <?php echo $Mes_Actual;?></span></font> </a></div>
											
												<div class="col mr-0">
													<div class="h4 mb-0 font-weight-bold text-primary text-center"><span id="idVentas">0</span></div>
													<div class="h6 mb-0 font-weight-bold text-gray-800 text-center my-2"><span id="idVentaE">0</span></div>
													<div class="h6 mb-0 mr-3 font-weight-bold text-primary text-center my-2"><span id="idIVA">0</span></div>
													<div class="h6 mb-0 font-weight-bold text-gray-800 text-center"><span id="idPartV">0</span></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>	
						</div>
						
						<div class="row row-cols-1 row-cols-md-2">
							<!-- Total Cuentas X Cobrar -->
							<div class="col  mb-4">
								<div class="card border-left-success text-center shadow h-100 py-0 pb-0">
									<div class="card-header py-2  h4 font-weight-bold  text-uppercase">
										<a id="CxC" href = "CuentasXCobrarN.php"> <font color="#27AE60"> <span id="idCxC"> 0</span></font></a>
									</div>
									<div class="card-body">
										<p class="card-title h5 font-weight-bold mb-0 text-left"><font color="#27AE60">Resumen Cobranza del Mes</font></p>
										<div class="row no-gutters align-items-center">
											<div class="col-xl-12">
												
												<div class="row my-0 mt-3">
													<div id="idTablaCxC" class="col-xl-12"></div>
												</div>
											</div>
										
										</div>
									</div>
								</div>
							</div>

							<!--Litros Vendidos -->
							<div class="col  mb-4">
								<div class="card border-left-success text-center shadow h-100 py-0 pb-0">
									<div class="card-header py-2  h4 font-weight-bold  text-uppercase">
										<a href="ProductosVendidos.php"><font color="#27AE60">Productos Vendidos - <?php echo $Mes_Actual;?></font></a>
									</div>
									<div class="card-body">
									<p class="card-title h5 font-weight-bold mb-4"><font color="#27AE60"></font></p>
										<div class="row no-gutters align-items-center">
											<div class="col-xl-12 ">
		  										<div class="row my-0 mt-3">
													  <div id="idUnidad" class="col-xl-12"></div>
												  </div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Grafica de Ventas -->
						<div class="row">

							<!-- Colummn Chart -->
							<div class="col-xl-7 col-lg-8">
								<div class="card shadown mb-3 h-100">
									<div class="card-header py-2 d-flex flex-row align-items-center justify-content-between">
										<div class="row no-gutters align-items-center justify-content-between">
											<div class="col-auto px-2">
												<h4 class="font-weight-bold text-uppercase"><font color="#27AE60">Facturación :</font></h4>
											</div>
											<div class="col-auto px-3">
												<h5><a href="#">Año <?php echo $hyear-2 ?></a></h5>
											</div>
											<div class="col-auto px-3">
												<h5><a href="#">Año <?php echo $hyear-1 ?></a></h5>
											</div>
											<div class="col-auto px-3">
												<h5><a href="#">Año <?php echo $hyear ?></a></h5>
											</div>
										</div>
									</div>
									<div class="card-body">
										<!-- highchats pie chart -->
										<figure class="highcharts-figure">
											<div id="GraficaVentaEvol"></div>
										</figure>
									</div>
									<div class="card-footer">
										<div id="idConTabla" class="ma-1"></div>
									</div>
								</div>
							</div>
							
							<!-- Pie Chart -->
							<div class="col-xl-5 col-lg-8">
								<div class="card shadown mb-3 h-100" >
									<!-- Card Header - Dropdown -->
									<div class="card-header py-2 d-flex flex-row align-items-center justify-content-between">
										<h4 class="font-weight-bold text-success"><font color="#27AE60">CMg %</font></h4>
									</div>
									<!-- Card Body -->
									<div class="card-body">
										<figure class="highcharts-figure">
											<div id="idGraficaCMg"v></div>
										</figure>
									</div>
								</div>
							</div>
						</div>

						<!-- Resumen de Compras -->
						<div class="text-center">
							<h3 class="my-3 text-center text-danger font-weight-bold">Resumen de Compras</h3>
						</div>
						
						<div class="row">
							<!-- Total Cuentas X Pagar -->
							<div class="col-xl-7 col-md-6 mb-4">
								<div class="card border-left-danger shadow h-100 py-0 pb-0 text-center">
									<div class="card-header py-2  h4 font-weight-bold  text-uppercase">
										
											<a href="CuentasXPagarN.php"> <font color="#FF0033"> <span id="idCxP">0</span></font> </a>
										
									</div>
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col-xl-12">
												<div class="row my-0 mt-3">
													<div id="idTablaCxP" class="col-xl-12"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- Total compras -->
							<div class="col-xl-5 col-md-5 mb-4">
								<div class="card border-left-danger shadow h-100 py-0 pb-0 text-center">
									<div class="card-header py-2  h4 font-weight-bold  text-uppercase">
										<p class="m-0" ><font color="#FF0033">Total Compras - <?php echo $Mes_Actual;?></font></p>
									</div>
									<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
										
										<div class="row no-gutters align-items-center">
											<div class="col">
												<div class="h4 font-weight-bold text-primary text-center mt-3"><span id="idRTotal">0</span></div>
												<div class="h6 mb-0 mr-3 font-weight-bold text-gray-800 text-center mt-2"><span id="idTotalC">0</span></div>
												<div class="h6 mb-0 mr-3 font-weight-bold text-primary text-center mt-2"><span id="idIVAC">0</span></div>
												
												<div class="h6 mb-0 font-weight-bold text-gray-800 text-center mt-4 mb-0"><a id="idRemC" href="RemitosPendienteXFacturarC.php?detalleR=''&Pagina=dashboard.php" ><span id="idRemPxC">0</span></a></div>
											</div>
										</div>
										</div>
										<!-- <div class="col-auto">
										<i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
										</div> -->
									</div>
									</div>
								</div>
							</div>
							
						</div>

  						<!-- Grafica de Compras -->
						<div class="row my-3 row-cols-1 row-cols-md-2">
							
								<!-- Colummn Chart -->
								<div class="col-xl-7 col-lg-8">
									<div class="card shadown mb-3 h-100">
										<!-- <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
											<h4>..</h4>
										</div> -->
										<div class="card-body">
											<!-- highchats pie chart -->
											<figure class="highcharts-figure">
												<div id="GraficaComprasEvol"></div>
											</figure>
										</div>
										<div class="card-footer mr-1 m-1 p-0">
											<div id="idConTablaC" class="ma-1"></div>
										</div>
									</div>
								</div>

								<!-- Pie Chart -->
								<div class="col-xl-5 col-lg-8">
									<div class="card shadown mb-3 h-100">
										<!-- <div class="card-header py-0 d-flex flex-row align-items-center justify-content-between px-0">
											
										</div> -->
										<div class="card-body">
											<!-- highchats pie chart -->
											<figure class="highcharts-figure">
												<div id="GraficaCompra"></div>
											</figure>
										</div>
										<div class="card-footer m-0 p-0">
											<table class="table table-bordered table-striped table-sm m-0 p-0">
												<tr align="center" >
													<?php 
														$j=0;
														while ($j < $i) { ?>
															<td style="color: #000;" bgcolor="<?php echo $Colors[$j] ?>" ><?php echo $ClaseCp[$j]; ?></td>
													<?php
															$j = $j+1;		
														}
													?>
												</tr>
												<tr align="center" >
													<?php 
														$j=0;
														while ($j < $i) { ?>
															<td style="color: #000;" ><?php echo number_format($ValorCp[$j],0,".",","); ?></td>
													<?php
															$j = $j+1;		
														}
													?>
												</tr>
											</table>
										</div>
									</div>
								</div>
							
						</div>

  						
						<h3 class="my-3 text-center text-secondary font-weight-bold">Resumen de Stock</h3>
						<div class="row my-1 justify-content-center">
							<!-- Stock Valorizado -->
							<div class="col-xl-7 col-md-6 mb-4">
								<div class="card border-left-warning shadow h-100 py-0 text-center">
									<div class="card-header py-2  h4 font-weight-bold  text-uppercase">
										<div class="h4 font-weight-bold text-warning text-uppercase mb-1"><a id="SxV" href = "Stock.php?detalleR='R'&Pagina=dashboard.php"><span id="idSxV"> 0</span></a></div>
									</div>
									<div class="card-body">
										<div class="row no-gutters align-items-center">
											<div class="col mr-2">
												
												<div class="row my-3">
													<div id="idTablaSxV" class="col-xl-12"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
					<!-- /.container-fluid -->
				</div>

				<!-- End of Main Content -->

				<!-- Footer -->
				<footer class="sticky-footer bg-white">
					<div class="container my-auto">
					<div class="copyright text-center my-auto">
						<span>Copyright &copy; Your Website 2020</span>
					</div>
					</div>
				</footer>
				<!-- End of Footer -->

			</div>
			<!-- End of Content Wrapper -->
		</div>
		<!-- End of Page Wrapper -->

		<!-- Scroll to Top Button-->
		<a class="scroll-to-top rounded" href="#page-top">
			<i class="fas fa-angle-up"></i>
		</a>

		<!-- Logout Modal-->
		<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
						<button class="close" type="button" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
					<div class="modal-footer">
						<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
						<a class="btn btn-primary" href="../php/logout.php">Logout</a>
					</div>
				</div>
			</div>
		</div>

		<!-- Bootstrap core JavaScript-->
		<script src="../vendor/jquery/jquery.min.js"></script>
		<script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

		<!-- Core plugin JavaScript-->
		<script src="../vendor/jquery-easing/jquery.easing.min.js"></script>

		<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
		<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>

		<!-- Custom scripts for all pages-->
		<!-- <script src="../js/sb-admin-2.min.js"></script> -->
		<script src= "../js/index2.js"></script>
		<script type="text/javascript">
			Highcharts.chart('GraficaCompra', {
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false,
					type: 'pie'
				},

				title: { 
					<?php echo $TituloPC ?>,
					style: {
						color: '#FF0033',
						fontSize: '24px'
					}
				},

				colors: ['#4dc9f6',
						'#f67019',
						'#f53794',
						'#f1c40f',
						'#acc236',
						'#166a8f'],

				legend: {
					align: 'center',
					verticalAlign: 'top',
					padding: 0,
					layout: 'horizontal',
					alignColumns: false,
					y:5,
					enabled: true,
				},

				tooltip: {
					headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
					pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f} %</b>'
				},

				accessibility: {
					point: {
						valueSuffix: '%'
					}
				},

				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						dataLabels: {
							enabled: true,
							format: '<b>{point.name}</b> <br>{point.percentage: .2f} %'
						},
						showInLegend: true,
					}
				},

				series: [{
					name: 'Compras',
					colorByPoint: true,
					data: [ <?php echo $dataG; ?>]
				}]
			});
		</script>
		<script src="../vendor/jquery/jquery.min.js"></script>
        <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	</body>
</html>