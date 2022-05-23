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
		$TipoR = $_GET['detalleR'];
		//$MesN = intval(substr($MesConsulta,-2,2));
		$pagUrl = $_GET['Pagina'];
	}else{
		$TipoR = '0';
	    $MesN = 0;
	}
	$MesN = 04;
	$MesN = date("n");
	if ( $TipoR == 1 ){
		$Titulo = 'Pedidos Nuevos del Mes de  ' . $MesLetra[$MesN] ;
		$consulta = "set dateformat YMD
				declare @FechaM datetime = format(getdate(), 'yyyy-MM-01 00:00:00')
				Select D.FechaP, D.FechaE, D.DiaRetraso, D.ClienteName, D.SucId, D.DocumentoN, D.DocumentoID,
					 D.Observacion, D.SubTotal, D.Estado, D.CostoTotal CostoP, D.Iva,
					 (select sum(A.s2*A.Unitario) from AuxPed A where A.DocumentoId = D.DocumentoId) Saldo, D.CondV
	  			from documento D
	  			where TipoReporte = 'PedPend'  and FechaP >= @FechaM
	  			order by FechaP";

	}else if ( $TipoR == 2) {
		$Titulo = 'Pedidos Pendientes   ' ;
		$consulta = "set dateformat YMD
		declare @FechaM datetime = format(getdate(), 'yyyy-MM-01 00:00:00')
		select * from(
			 Select D.FechaP, D.FechaE, D.DiaRetraso, D.ClienteName, D.SucId, D.DocumentoN, D.DocumentoID,
					Observacion, SubTotal, Estado, CostoTotal CostoP, Iva,
				(select sum(A.s2*A.Unitario) from AuxPed A where A.DocumentoId = D.DocumentoId) Saldo, D.CondV
			   from documento D 
			   where TipoReporte = 'PedPend'  and Estado = 1 ) T1
		where saldo <> 0
		  order by FechaP";

	} else if ( $TipoR == 3) {
		$Titulo = 'Pedidos Pendientes de Meses Anteriores a  ' . $MesLetra[$MesN] ;
		$consulta = "set dateformat YMD
		declare @FechaM datetime = format(getdate(), 'yyyy-MM-01 00:00:00')
		select * from(
			 Select D.FechaP, D.FechaE, D.DiaRetraso, D.ClienteName, D.SucId, D.DocumentoN, D.DocumentoID,
					Observacion, SubTotal, Estado, CostoTotal CostoP, Iva,
				(select sum(A.s2*A.Unitario) from AuxPed A where A.DocumentoId = D.DocumentoId) Saldo, D.CondV
			   from documento D 
			   where TipoReporte = 'PedPend'  and Estado = 1 and FechaP < @FechaM ) T1
		where saldo <> 0
		  order by FechaP";

	} else {
		$Titulo = 'Pedidos Nuevos Pendientes del mes ' . $MesLetra[$MesN] ;
		$consulta = "set dateformat YMD
		declare @FechaM datetime = format(getdate(), 'yyyy-MM-01 00:00:00')
		select * from(
			 Select D.FechaP, D.FechaE, D.DiaRetraso, D.ClienteName, D.SucId, D.DocumentoN, D.DocumentoID,
					Observacion, SubTotal, Estado, CostoTotal CostoP, Iva,
				(select sum(A.s2*A.Unitario) from AuxPed A where A.DocumentoId = D.DocumentoId) Saldo, D.CondV
			   from documento D 
			   where TipoReporte = 'PedPend'  and Estado = 1 and FechaP >= @FechaM ) T1
		where saldo <> 0
		  order by FechaP";

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
		
		<title>Pedidos</title>
		<link data-n-head="1" rel="icon" type="image/x-icon" href="../../favicon.ico">
		<!-- Bootstrap core CSS -->
		<link href="../html/pedido.css" rel="stylesheet">
		<link href="../css/sb-admin-2.min.css" rel="stylesheet">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
 		<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>     			
	</head>
	<body>
		<div id="general">
			<form method="POST" action="">
				<div class="d-block  bg-primary text-white font-weight-bold h3 text-center p-2">
					<?php echo $Titulo ?>
				</div>
				<div class="d-flex justify-content-between">
					<div class="my-2">
						<a class="btn btn-warning" href="dashboard.php">Regresar</a>
					</div>
					<div class="my-2">
						<button class="btn btn-success" onclick="exportTablaToExcel('<?php echo $Titulo ?>','#IdPedidosN')">Exportar Excel</button>
					</div>
				</div>
				<table class="table table-bordered table-sm table-striped" id="IdPedidosN">
					<tr align="center" style="color: #000; font-weight: bold; background: #9c9c9c;">
						<td>FechaP</td>
						<td>FechaE</td>
						<td>Svc</td>
						<td>Suc</td>
						<td>Numero</td>
						<td>Cliente</td>
						<td>Total Costo</td>
						<td>Total Pedido</td>
						<td>Saldo Pedido</td>
						<td>Iva</td>
						<td>CMg %</td>
						<td>Estado</td>
						<td>Accion</td>
					</tr>

					<?php

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
							$Saldo = $fila['Saldo']+0;
							$SubTotal = $fila['SubTotal'];
							$Iva = $fila['Iva'];
							$Costo = $fila['CostoP'];
							$St = $fila['Estado']+0;
							$CondV = $fila['CondV'];
							
							if ($SubTotal > 0){
								$CMgP = ($SubTotal - $Costo) / $SubTotal * 100;
								$PorcPen = 100 - (( $SubTotal - $Saldo) / $SubTotal * 100);
							} else{
								$CMgP = 0;
								$PorcPen = 0;
							}
							if ( $St == 1 and $Saldo > 0 and $PorcPen > 0) {
								$Estado = number_format($PorcPen,2,",",".") . "% Pend..";
								$St = 1;

							}
							else  {
								$Estado = "Completado";
								$St = 2;
							
							}
							$i++;

							if ( $CondV != 10 ) {
								$DiaR = 0;

					?>

					<tr align="center">
						
						<td><?php echo $FechaP->format("d/m/y"); ?></td>
						<td><?php echo $FechaE->format("d/m/y"); ?></td>
						<td><?php echo $DiaR; ?></td>
						<td><?php echo $SucId; ?></td>
						<td><?php echo $DocN; ?></td>
						<td align="left"><?php echo $Cliente; ?></td>
						<td align="right"><?php echo number_format($Costo,2,",","."); ?></td>
						<td align="right"><?php echo number_format($SubTotal,2,",","."); ?></td>
						<td align="right"><?php echo number_format($Saldo,2,",","."); ?></td>
						<td align="right"><?php echo number_format($Iva,2,",","."); ?></td>
						<td align="right"><?php echo number_format($CMgP,2,",","."); ?></td>
						<td><?php if ($St == 1) { echo '<span style="color:red">'.$Estado.'</span>'; } else if ($St == 2) { echo '<span style="color:#00FF00">'.$Estado.'</span>'; } ?></td>
						<td><a href="Consulta.php?detalle=<?php echo $DocID ; ?>&ClienteN=<?php echo $Cliente ; ?>&ObsD=<?php echo $Observ ; ?>&Pagina=PedidosN.php&TipoR=<?php echo $TipoR ; ?>">Detalle</a></td>
					</tr>

					<?php } else { $DiaR = 1; ?>

						<tr align="center">
						
							<td bgcolor="yellow"><?php echo  $FechaP->format("d/m/y"); ?></td>
							<td bgcolor="yellow"><?php echo $FechaE->format("d/m/y"); ?></td>
							<td bgcolor="yellow"><?php echo $DiaR; ?></td>
							<td bgcolor="yellow"><?php echo $SucId; ?></td>
							<td bgcolor="yellow"><?php echo $DocN; ?></td>
							<td bgcolor="yellow" align="left"><?php echo $Cliente; ?></td>
							<td bgcolor="yellow" align="right"><?php echo number_format($Costo,2,",","."); ?></td>
							<td bgcolor="yellow" align="right"><?php echo number_format($SubTotal,2,",","."); ?></td>
							<td bgcolor="yellow" align="right"><?php echo number_format($Saldo,2,",","."); ?></td>
							<td bgcolor="yellow" align="right"><?php echo number_format($Iva,2,",","."); ?></td>
							<td bgcolor="yellow" align="right"><?php echo number_format($CMgP,2,",","."); ?></td>
							<td bgcolor="yellow"><?php if ($St == 1) { echo '<span style="color:red">'.$Estado.'</span>'; } else if ($St == 2) { echo '<span style="color:#00FF00">'.$Estado.'</span>'; } ?></td>
							<td><a href="Consulta.php?detalle=<?php echo $DocID ; ?>&ClienteN=<?php echo $Cliente ; ?>&ObsD=<?php echo $Observ ; ?>&Pagina=PedidosN.php&TipoR=<?php echo $TipoR ; ?>">Detalle</a></td>
						</tr>

					<?php } } ?>
					
				</table>
				</div>
			</form>
			<?php
			if(isset($_GET['detalle'])){
				include("Consulta.php");
				echo "<script>window.open('Factura.php', '_self')</script>";
			}
			
			?>
	<script src="../js/exportarExcel.js"></script>
	</body>
</html>