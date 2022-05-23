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
    
	<title>Remitos Pendientes X Facturar</title>
	<link data-n-head="1" rel="icon" type="image/x-icon" href="../../favicon.ico">
    <!-- Bootstrap core CSS -->
    <link href="../html/pedido.css" rel="stylesheet">
    <link href="../css/bootstrap.min.css" rel="stylesheet">     			
	</head>
<body>
		<div id="general">
		<form method="POST" action="">
			<div class="row">
			    <div class="alert alert-info" role="alert">
					Resumen de Remitos Pendientes X Facturar
				</div>
			</div>
		     	<!-- <div class="col-md-8 col-md-offset-2"> -->
					<table class="table table-bordered table-responsive">
						<tr>
							<td>Cod Cliente</td>
							<td>Nombre o Razón Social</td>
							<td>Costo</td>
							<td>Total </td>
							<td>CMg  $</td>
							<td>CMg %</td>
              <td>PartC</td>
              <td>Detalle</td>
						</tr>

						<?php
					
                        
                          $consulta = "select ClienteId, ClienteName, sum(SubTotal) ValorFac, sum(CostoTotal) CostoFac,
                           (select sum(subtotal) from Documento where TipoReporte = 'REMPXV' and CondV <> 10 ) TotalV
                          from Documento 
                          where TipoReporte = 'REMPXV' and CondV <>10
                          group by  ClienteId, ClienteName
                          order by 3 desc";
						  $ejecutar = sqlsrv_query($con, $consulta);

						  $i = 0;
						  while($fila = sqlsrv_fetch_array($ejecutar)){
						   	$ClienteID = $fila['ClienteId'];
						  	$ClienteN = $fila['ClienteName'];
						  	$ValorFac = $fila['ValorFac'];
						  	$CostoFac = $fila['CostoFac'];
							$CMg = $ValorFac - $CostoFac;
							if ( $ValorFac == 0){
								$CMgPorc = 0;
							}else {  
								$CMgPorc = $CMg / $ValorFac * 100;
							}
							$Partc = $ValorFac / $fila['TotalV'] * 100;
						  	$i++;
						?>

					

						<tr align="center">
							
							<td><?php echo $ClienteID ?></td>
                            <td align="left"><?php echo $ClienteN ?></td>
                            <td align="right"><?php echo  '<span style="color:#040404">'.number_format($CostoFac,2,",",".").'</span>'; ?></td>
                            <td align="right"><?php echo  '<span style="color:#040404">'.number_format($ValorFac,2,",",".").'</span>'; ?></td>
                            <td align="right"><?php echo  '<span style="color:#040404">'.number_format($CMg,2,",",".").'</span>'; ?></td>
                            <td align="right"><?php echo  '<span style="color:#040404">'.number_format($CMgPorc,2,",",".").'</span>'; ?></td>
                            <td align="right"><?php echo  '<span style="color:#040404">'.number_format($Partc,2,",",".").'</span>'; ?></td>
                            <td><a href="Remitos.php?detalle=<?php echo $ClienteID ; ?>&MesC=<?php echo $MesConsulta ; ?>&ClienteN=<?php echo $ClienteN ; ?>&Pagina=<?php echo $pagUrl ; ?>">Detalle</a></td>
						</tr>

						<?php } ?>
						<div class="form-group">
						<input type="submit" name="Regresar" class="btn btn-warning" value="REGRESAR">
					</div>
					</table>
				<!-- </div> -->
		     
			</div>
			

	</form>
	<?php
	  if(isset($_GET['detalle'])){
	  	 include("Consulta.php");
	  	 echo "<script>window.open('Factura.php', '_self')</script>";
	  }
	  if (isset($_POST['Regresar'])){
		echo "<script>window.open('$pagUrl', '_self')</script>";
	}
	?>
</body>
</html>