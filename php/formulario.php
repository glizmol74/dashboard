<!DOCTYPE html> 
<?php 
	include("conexion_sis.php");
?>
<meta charset="UTF-8">
<html> 	
	<head>
		<?php 
         $self = $_SERVER['PHP_SELF'];

         header("refresh:120; url=$self");
		 ?>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Envasado</title>
    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">   			
	</head>
<body>

		<h1>PRODUCTOS A ENVASAR</h1>

	
	<div class="col-md-8 col-md-offset-2">
	<table class="table table-bordered table-responsive">
		<tr>
			<td>Cod</td>
			<td>Descripcion</td>
			<td><?php echo $tdia0; ?></td>
			<td><?php echo $tdia1; ?></td>
			<td><?php echo $tdia2; ?></td>
			<td>Pendiente</td>
			<td>Stock</td>
		</tr>

		<?php
			$consulta = "Select distinct ap.ProductoId Cod, ap.Descripcion Descripcion, sum(isnull(ap.dia0,0)) Dia_0, sum(isnull(ap.Dia1,0) ) Dia_1, sum(isnull(ap.dia2,0)) Dia_2, S4 StockActual, ap.ClasificacionProdId ClasP, ap.diferencia Diferencia,  
                 ap.s4 StockA from AuxPed Ap  
                  where ap.S2 <> 0 and ap.TipoReporte = 'PedPend2'  and ap.clasificacionProdID = 1 and ap.Diferencia < 0 and ap.prioridad <> 0
                  group by ap.ProductoId, ap.Descripcion, ap.ClasificacionProdId, ap.s4, ap.diferencia order by ClasificacionProdId, ap.Descripcion";

			$ejecutar = sqlsrv_query($con, $consulta);

			$i = 0;

			while($fila = sqlsrv_fetch_array($ejecutar)){
				$cod = $fila['Cod'];
				$Descripcion= $fila['Descripcion'];
				$sdia0 = $fila['Dia_0'];
				$sdia1 = $fila['Dia_1'];
				$sdia2 = $fila['Dia_2'];
				$StockA = $fila['StockA'];
				$i++;
			

		?>

		<tr align="center">
			<td><?php echo $cod; ?></td>
			<td align="left"><?php echo $Descripcion; ?></td>
			<td align="right"><?php echo  '<span style="color:red">'.number_format($sdia0,0,",",".").'</span>'; ?></td>
			<td align="right"><?php echo  '<span style="color:#FFA500">'.number_format($sdia1,0,",",".").'</span>'; ?></td>
			<td align="right"><?php echo  '<span style="color:#00FF00">'. number_format($sdia2,0,",",".").'</span>'; ?></td>
			<td align="right"><?php echo number_format($sdia2+$sdia1+$sdia0,0,",","."); ?></td>
			<td align="right"><?php echo number_format($StockA,0,",","."); ?></td>
		<!--	<td><a href="formulario.php?editar=<?php echo $id; ?>">Editar</a></td>
			<td><a href="formulario.php?borrar=<?php echo $id; ?>">Borrar</a></td>  -->
		</tr>

		<?php } ?>

	</table>
	</div>
</body>
</html>



