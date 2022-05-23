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
		$tc = $_GET['TC'];
		$tce = $_GET['TcE'];
		$tcn = $_GET['TcN'];
		$tcp = $_GET['TcP'];
		$tcnp = $_GET['TcNP'];
		$op = $_GET['Op'];
		$Filtro = 'detalleR=' . $MesConsulta . '&Pagina=' . $pagUrl . '&TC=' . $tc . '&TcE=' . $tce . '&TcN=' . $tcn;
		$Filtro = $Filtro . '&TcP=' . $tcp . '&TcNP=' . $tcnp;
		$Filtro1 = '?' . $Filtro . '&Op=1';
		$Filtro2 = '?' . $Filtro . '&Op=2';
		$Filtro3 = '?' . $Filtro . '&Op=3';
		$Filtro4 = '?' . $Filtro . '&Op=4';
		$Filtro5 = '?' . $Filtro . '&Op=5';
	}else{
		$MesConsulta = '';
	    $MesN = 0;
		$tc = 0;
		$tce = 0;
		$tcn = 0;
		$op = 0;
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
    
	<title>Ranking de Compras</title>
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
					Compras del Mes de <?php echo $MesLetra[$MesN] ?> del 20<?php echo substr($MesConsulta,-5,2); ?> 
					  
				</div>
				<div class="row">
					<table class="table table-responsive">
						<tr align="center">
							<td><?php echo " <a href= 'RankingCompras.php$Filtro1'  > " ?> <?php echo ' Total Compras = ' . number_format($tc,2,",",".")   ?> </a> </td>
							<td><?php echo " <a href= 'RankingCompras.php$Filtro2'  > " ?> <?php echo ' Compras E = ' . number_format($tce,2,",",".")   ?> </a> </td>
							<td><?php echo " <a href= 'RankingCompras.php$Filtro3'  > " ?> <?php echo ' Compras N = ' . number_format($tcn,2,",",".")   ?> </a> </td>
							<td><?php echo " <a href= 'RankingCompras.php$Filtro4'  > " ?> <?php echo ' Compras P = ' . number_format($tcp,2,",",".")   ?> </a> </td>
							<td><?php echo " <a href= 'RankingCompras.php$Filtro5'  > " ?> <?php echo ' Compras NP = ' . number_format($tcnp,2,",",".")   ?> </a> </td>
						</tr>
					</table>
				</div>
				
			</div>
			
		     	<!-- <div class="col-md-8 col-md-offset-2"> -->
					<table class="table table-bordered table-responsive">
						<tr>
							<td>Cod Proveedor</td>
							<td>Nombre o Razón Social</td>
							<td>Sub Total</td>
							<td>Iva</td>
							<td>Total Compras</td>
                            <td>PartC</td>
                            <td>Detalle</td>
						</tr>

						<?php
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

						 $FechaFin = new datetime($FecMesConsula);
						 $FechaFin->modify('last day of this month');
						 $UltimoDia =  $FechaFin->format('Y/m/d') . ' 23:59:59';
                         $consulta = "set dateformat YMD
						  declare @FechaInio as datetime = '$FecMesConsula'
						  declare @FecFin as datetime = '$UltimoDia'
						  select  idc ClienteId, c.RazonSocial ClienteName, sum(subtotal) ValorFac, sum(iva) Iva,
								(select sum( (case when t.TipoDocumentoID like 'NC%' then -1 else 1 end ) * t.Subtotal) from cprasDocumentosCabecera T  
								  where t.Fecha between  @FechaInio and  @FecFin and t.EstadoDocumentoId <> 3 
									   AND (t.TipoDocumentoId like 'FC%' or t.TipoDocumentoId like 'NC%')) TotalC
							from (
						  select dc.ProveedorId Idc, (case when dc.TipoDocumentoID like 'NC%' then -1 else 1 end ) * dc.Subtotal SubTotal, 
								sum( (case when dc.TipoDocumentoID like 'NC%' then -1 else 1 end ) * zz.Unitario * zz.Cantidad * zz.AlicuotaIVA /100 ) iva
						  from cprasDocumentosCabecera DC join cprasDocumentosDetalle zz on dc.cprasDocumentoId = zz.cprasDocumentoId
						       left join Productos P on P.ProductoID = zz.ProductoId collate Modern_Spanish_CI_AS
						  where dc.Fecha between  @FechaInio and  @FecFin AND (dc.TipoDocumentoId like 'FC%' or dc.TipoDocumentoId like 'NC%')
								and dc.EstadoDocumentoId <> 3
								and $fConsulta 
						  group by dc.ProveedorId, dc.Subtotal, dc.TipoDocumentoId, dc.numero ) T1 join Proveedores C on t1.Idc = c.ProveedorId
						  
						  group by idc, c.RazonSocial
						  order by 3 desc";

						  $ejecutar = sqlsrv_query($con, $consulta);

						  

						  $i = 0;
						  $TotalGeneral = 0;
						  while($fila = sqlsrv_fetch_array($ejecutar)){
						   	$ClienteID = $fila['ClienteId'];
						  	$ClienteN = $fila['ClienteName'];
						  	$ValorFac = $fila['ValorFac'] + 0;
							$Iva = $fila['Iva'] + 0;
							$TotalC = $ValorFac + $Iva + 0;
							$Partc = $ValorFac / $fila['TotalC'] * 100;
						  	$i++;
						?>

					

						<tr align="center">
							
							<td><?php echo $ClienteID ?></td>
                            <td align="left"><?php echo $ClienteN  ?></td>
                            <td align="right"><?php echo  '<span style="color:#040404">'.number_format($ValorFac,2,",",".").'</span>'; ?></td>
                            <td align="right"><?php echo  '<span style="color:#040404">'.number_format($Iva,2,",",".").'</span>'; ?></td>
							<td align="right"><?php echo  '<span style="color:#040404">'.number_format($TotalC,2,",",".").'</span>'; ?></td>
                            <td align="right"><?php echo  '<span style="color:#040404">'.number_format($Partc,2,",",".").'</span>'; ?></td>
                            <td><a href="FacturaC.php?detalle=<?php echo $ClienteID ; ?>&MesC=<?php echo $MesConsulta ; ?>&ClienteN=<?php echo $ClienteN ; ?>&Filtro=<?php echo "$Filtro&Op=$op" ; ?>">Detalle</a></td>
						</tr>
						
						<?php
							$TotalGeneral = $TotalGeneral + $ValorFac;
						 } ?>
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
	  	 echo "<script>window.open('FacturaC.php', '_self')</script>";
	  }
	  if (isset($_POST['Regresar'])){
		
		echo "<script>window.open('$pagUrl', '_self')</script>";
	}
	?>
</body>
</html>