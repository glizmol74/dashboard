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
  if(isset($_GET['detalle'])){
  	$Cliente_Id = $_GET['detalle'];
	  $ClienteName = $_GET['ClienteN'];
	  $MesCon = $_GET['MesC'];
	  $pagUrl = $_GET['Pagina'];
	  $FecMesConsula = '20' . substr($MesCon,-5,5) . '-01 00:00:00';
	  $Filtro = $_GET['Filtro'];
	  $tc = $_GET['TC'];
	  $tce = $_GET['TcE'];
		$tcn = $_GET['TcN'];
		$tcp = $_GET['TcP'];
		$tcnp = $_GET['TcNP'];
		$op = $_GET['Op'];
  }else {
	$Cliente_Id = '-';
	$ClienteName = '';
	$MesCon = '';
	$Filtro = '';
	$op = 0;
  }
  if ( $op == 1 ) {
	$fConsulta = ' Iva >= 0 ';
  } else if ( $op== 2 ) {
 	$fConsulta = ' Iva > 0 ';
  } else if ( $op == 3 ) {
	$fConsulta = ' Iva = 0 ';
  } else if ( $op == 4) {
	$fConsulta =  ' p.Clasif1 > 0 ';
  } else if ( $op == 5) {
	$fConsulta =  ' p.Clasif1 = 0 ';
  } else {
	$fConsulta = ' 1 = 1';
  }
?>
<meta charset="UTF-8">
<html> 	
	<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Relacion de Facturas</title>

 <link href="../html/pedido.css" rel="stylesheet">
 <link data-n-head="1" rel="icon" type="image/x-icon" href="../../favicon.ico">
 <link href="../css/sb-admin-2.min.css" rel="stylesheet">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript">
            function cambiarcont(pagina, doc_id, doc_n, mesC, TipoD, Observ, DocAf) {
                       $("#contenido2").load(pagina,{detalle: doc_id, DocN: doc_n, MesB: mesC, TipoDoc: TipoD, ObservF: Observ, Doc_A: DocAf});
            }
</script>
</head>
<body>
<div id="general">
 <!-- <div class="col-md-8 col-md-offset-2"> -->
	    
		<form method="POST" action="">

			<h3> <?php echo $Cliente_Id . ' |   ' . $ClienteName ?> </h3>

		     	<table class="table table-bordered table-striped">
					<tr align="center" style="font-weight: bold; color: #000; background: #9c9c9c;">
						<td>Fecha</td>
						<td>Tipo</td>
						<td>Sucursal</td>
						<td>Nro. Doc</td>
						<td>Sub Total</td>
						<td>Iva</td>
						<td>Total $</td>
						<td>Acción</td>
					</tr>


					<?php
						//echo $Filtro;
						$cad =  $MesCon . "&Pagina=" . $pagUrl . "&TC=" . $tc  . "&TcE=" . $tce . "&TcN=" . $tcn . "&TcP=" . $tcp;
						$cad = $cad . "&TcNP=" . $tcnp . "&Op=" . $op;
						

					  	$FechaFin = new datetime($FecMesConsula);
						$FechaFin->modify('last day of this month');
						$UltimoDia =  $FechaFin->format('Y/m/d') . ' 23:59:59';
                        $consulta = "set dateformat YMD
                        declare @FechaInio as datetime = '$FecMesConsula'
                        declare @FecFin as datetime = '$UltimoDia'
						select dc.Fecha  FechaP, dc.TipoDocumentoId TipoDoc,dc.SucursalId SucId, dc.Numero DocumentoN, dc.cprasDocumentoId DocumentoId, 
                        dc.ProveedorId ClienteId, c.RazonSocial ClienteName,dc.Subtotal SubTotal, 
                        dc.IVA, dc.Descripcion observacion, 0 DocumentoA
                          from cprasDocumentosCabecera DC join Proveedores C on dc.ProveedorId = c.ProveedorId
                          where dc.Fecha between  @FechaInio and  @FecFin
					    and dc.ProveedorId = '$Cliente_Id' and dc.EstadoDocumentoId <> 3
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
							$CostoT = $fila['IVA'];
                            $TotalC = $SubTotal + $CostoT + 0;
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
						<td align="right"><?php echo number_format($TotalC,2,",","."); ?></td>
						<td><a href="javascript:cambiarcont('ProductoC.php', <?php echo $DocId ?>,<?php echo $DocN ?>,<?php echo "'". $MesCon ."'"?>,<?php echo "'". $TipoD ."'"?>, <?php echo "'". $observ ."'"?>,<?php echo $DocA ?>)">Detalle</a></td>  
					</tr>

				<?php } ?>

				</table>
				
				
			<div class="form-group">
			<?php echo "<br />" ?>
				<input type="submit" name="Regresar" class="btn btn-warning" value="REGRESAR">
			</div>

			
		</form>
 <!-- </div> -->
  <div id="contenido2"></div>
</div>
<?php
 if (isset($_POST['Regresar'])){
	
 	//echo "<script>window.open('RankingCompras.php?detalleR=" . $MesCon . "&Pagina=" . $pagUrl . "&TC=" . $tc  . "&TcE=" . $tce . "', '_self')</script>";
	 echo "<script>window.open('RankingCompras.php?detalleR=" . $cad . "', '_self')</script>";
	
 }

?>
</body>
</html>