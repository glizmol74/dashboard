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
  if(isset($_GET['detalle'])){
  	$Cliente_Id = $_GET['detalle'];
	  $ClienteName = $_GET['ClienteN'];
	  $MesCon = $_GET['MesC'];
	  $pagUrl = $_GET['Pagina'];
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
    
    <title>Relacion de Remitos Pendientes</title>

 <link href="../html/pedido.css" rel="stylesheet">
 <link data-n-head="1" rel="icon" type="image/x-icon" href="../../favicon.ico">
 <link href="../css/bootstrap.min.css" rel="stylesheet">
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

			<h1> <?php echo $Cliente_Id . '    ' . $ClienteName ?> </h1>

		     	<table class="table table-bordered table-responsive">
					<tr>
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
					  $consulta = "select FechaP, TipoDoc, SucId, DocumentoN, DocumentoId, ClienteId, 
					  ClienteName, SubTotal, CostoTotal, observacion, DocumentoA
					   from Documento 
                       where TipoReporte = 'REMPXV' and ClienteId = '$Cliente_Id' and CondV <> 10
                       order by  FechaP";



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
                            $CMg = $SubTotal - $CostoT;
                            $DocA = $fila['DocumentoA'];
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
 	echo "<script>window.open('RemitosPendienteXFacturar.php?detalleR=" . $MesCon . "&Pagina=" . $pagUrl . "', '_self')</script>";
 }

?>
</body>
</html>