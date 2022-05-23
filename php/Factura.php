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
  include("./conexion_sis.php");
  if(isset($_POST['detalle'])){
  	$Cliente_Id = $_POST['detalle'];
	  $ClienteName = $_POST['ClienteN'];
	  $MesCon = $_POST['MesC'];
	  $pagUrl = $_POST['Pagina'];
	  $FecMesConsula = '20' . substr($MesCon,-5,5) . '-01 00:00:00';
  }else {
	$Cliente_Id = '-';
	$ClienteName = '';
	$MesCon = '';
  }

?>
<meta charset="UTF-8">
<html> 	
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link href="../html/pedido.css" rel="stylesheet">
		<link data-n-head="1" rel="icon" type="image/x-icon" href="../favicon.ico">
		<link href="../css/sb-admin-2.min.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
		<script type="text/javascript">
				function cambiarcont(pagina, doc_id, doc_n, mesC, TipoD, Observ, DocAf) {
						$("#contenido2").load(pagina,{detalle: doc_id, DocN: doc_n, MesB: mesC, TipoDoc: TipoD, ObservF: Observ, Doc_A: DocAf});
				}
		</script>
	</head>
	<body>
		<div id="content">
			<form method="POST" action="">

				<h3> <?php echo $Cliente_Id . '    ' . $ClienteName ?> </h3>

					<table class="table table-bordered table-sm table-striped">
						<tr align="center" style="font-weight: bold; color: #000; background: #9c9c9c;">
							<td>Fecha</td>
							<td>Tipo</td>
							<td>Sucursal</td>
							<td>Nro. Doc</td>
							<td>Sub Total</td>
							<td>Costo</td>
							<td>CMg $</td>
							<td>CMg %</td>
							<td>Acción</td>
						</tr>


						<?php

							$FechaFin = new datetime($FecMesConsula);
							$FechaFin->modify('last day of this month');
							$UltimoDia =  $FechaFin->format('Y/m/d') . ' 23:59:59';
							$consulta = "set dateformat YMD
							declare @FechaInio as datetime = '$FecMesConsula'
							declare @FecFin as datetime = '$UltimoDia'
							select dc.Fecha  FechaP, dc.TipoDocumentoId TipoDoc,dc.SucursalId SucId, dc.Numero DocumentoN, dc.DocumentoId DocumentoId, 
							dc.ClienteId ClienteId, c.RazonSocial ClienteName,dc.Subtotal SubTotal, 
							(select sum(z.GmPrecioCompra * z.Cantidad) from ZZDocumentosDetalle Z where z.DocumentoId = dc.DocumentoId) CostoTotal,
							dc.Descripcion observacion, 0 DocumentoA
							from DocumentosCabecera DC join Clientes C on dc.ClienteId = c.ClienteID
							where dc.Fecha between  @FechaInio and  @FecFin
							and dc.ClienteId = '$Cliente_Id' and dc.EstadoDocumentoId <> 3
							and ( dc.TipoDocumentoId like 'FC%' or dc.TipoDocumentoId like 'NC%') 
							order by  Fecha; ";



							$ejecutar = sqlsrv_query($con, $consulta);

							$j = 0;
				
							while($fila = sqlsrv_fetch_array($ejecutar)){
								$FechaF = $fila['FechaP'];
								$TipoD = $fila['TipoDoc'];
								$SucId= $fila['SucId'];
								$DocN = $fila['DocumentoN'];
								$DocId = $fila['DocumentoId'];
								$SubTotal = $fila['SubTotal'];
								$CostoT = $fila['CostoTotal'];
								$observ = $fila['observacion'];
								$DocA = $fila['DocumentoA'];
								$CMg = $SubTotal - $CostoT;
								if ( substr($TipoD,0,2) == 'NC') {
									$Sig = -1;
								} else {
									$Sig= 1;
								}
								$CMgP = ( $CMg / $SubTotal * 100 ) * $Sig;
								$j++;
							

						?>

						<tr align="center">
							<td><?php echo $FechaF->format("d/m/y"); ?></td>
							<td><?php echo $TipoD; ?></td>
							<td><?php echo $SucId; ?></td>
							<td><?php echo $DocN;  ?></td>
							<td align="right"><?php echo number_format($SubTotal,2,",","."); ?></td>
							<td align="right"><?php echo number_format($CostoT,2,",","."); ?></td>
							<td align="right"><?php echo number_format($CMg,2,",","."); ?></td>
							<td align="right"><?php echo number_format($CMgP,2,",",".").' %'; ?></td>
							<td><a href="javascript:cambiarcont('Producto.php', <?php echo $DocId ?>,<?php echo $DocN ?>,<?php echo "'". $MesCon ."'"?>,<?php echo "'". $TipoD ."'"?>, <?php echo "'". $observ ."'"?>,<?php echo $DocA ?>)">Detalle</a></td>  
						</tr>

					<?php } ?>

					</table>
				
			</form>
 
  			<div id="contenido2"></div>
		</div>

</body>
</html>