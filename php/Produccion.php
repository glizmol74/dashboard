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
	<title>Produccion</title>
	<link href="../css/bootstrap.min.css" rel="stylesheet"> 
</head>
<body>
     <h1>PRODUCCION</h1>
     <div class="col-md-8 col-md-offset-2">
	    <table class="table table-bordered table-responsive">
     		<tr>
     			<td>Cod</td>
				<td>Descripcion </td>
			    <td><?php echo $tdia0; ?></td>
			    <td><?php echo $tdia1; ?></td>
			    <td><?php echo $tdia2; ?></td>
			    <td>Pendiente</td>
				<td>Stock</td>
				<td>Pedido</td>
     		</tr>


     		<?php
			  $consulta = "select Cod, Descripcion, sum(Cantidad) Cantidad, sum(dia_0) Dia_0, sum(dia_1) Dia_1, sum(dia_2) Dia_2, Stock from
(Select  CodIsumo Cod, NombreInsumo Descripcion, sum(Cantidad) Cantidad, sum(dia0) Dia_0, sum(dia1) Dia_1, sum(dia2) Dia_2, Stock
 from InsumosPendiente
         where FormulaSE <> 0 and TipoReporte = 'PedPend2'  and Stock - Cantidad < 0
		 group by CodIsumo, NombreInsumo, Stock 

		union 
 Select distinct a.ProductoId Cod , a.Descripcion Descripcion, AVG ( iif(a.diferencia < 0, a.diferencia * -1,0)) Cantidad, sum ( a.dia0) Dia_0, sum(a.Dia1) Dia_1, sum(a.dia2) Dia_2, s4 Stock
 from AuxPed a
        where a.Equipo = 'SERVER' and a.TipoReporte = 'PedPend2' and a.FormulaID <> 0 and a.s2 >0 and a.ClasificacionProdID = 1 and a.Diferencia < 0
		and a.ProductoId like 'ISE%'
       group by a.ProductoId, a.Descripcion, a.s4) tabla
 group by Cod, Descripcion, Stock
	   order by 2";

			   $ejecutar = sqlsrv_query($con, $consulta);

			   $i = 0;

			  while($fila = sqlsrv_fetch_array($ejecutar)){
			  	$cod = $fila['Cod'];
				$Descripcion= $fila['Descripcion'];
				$sdia0 = $fila['Dia_0'];
				$sdia1 = $fila['Dia_1'];
				$sdia2 = $fila['Dia_2'];
				$pend =  $fila['Cantidad'];
				$pend = $sdia0+$sdia1+$sdia2;
				$stockA = $fila['Stock'];
				$diff = $stockA - $pend;
				$StockD = $stockA;
				if ($diff < 0 ){
					$diff = $diff * -1;
				}

				if ($sdia0-$StockD >= 0){
					$sdia0 = $sdia0-$StockD;
				}
				$i++;
		?>

	<tr align="center">
			<td><?php echo $cod; ?></td>
			<td align="left"><?php echo $Descripcion; ?></td>
			<td align="right"><?php echo  '<span style="color:red">'.number_format($sdia0,0,",",".").'</span>'; ?></td>
			<td align="right"><?php echo  '<span style="color:#FFA500">'.number_format($sdia1,0,",",".").'</span>'; ?></td>
			<td align="right"><?php echo  '<span style="color:#00FF00">'. number_format($sdia2,0,",",".").'</span>'; ?></td>
			<td align="right"><?php echo number_format($diff,0,",","."); ?></td>
			<td align="right"><?php echo number_format($stockA,0,",","."); ?></td>
			<td align="right"><?php echo  number_format($pend,0,",","."); ?></td>
		<!--	<td><a href="formulario.php?editar=<?php echo $id; ?>">Editar</a></td>
			<td><a href="formulario.php?borrar=<?php echo $id; ?>">Borrar</a></td>  -->
		</tr>

		<?php } ?>
     	</table>
     </div>


</body>
</html>