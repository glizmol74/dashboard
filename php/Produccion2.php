<!DOCTYPE html>
<?php 
	include ("conexion_sis.php");
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
				<td>Cantidad</td>
				<td>Und Med</td>
				<td>Estado</td>
     		</tr>


			 <?php
			 $hoy = date("Y-m-d");
			  $consulta = "Set Dateformat YMD
			  declare @Hoy as datetime = format(getdate(), 'yyyy-MM-dd 23:59:59')
			  select CodProducto Cod, pp.Descripcion, sum(pp.Cantidad) Cantidad, Prioridad, PP.STATUS, PP.Unidad
			  from Produccion PP 
             where Clasificacion = 16 and fecha <= @Hoy AND PP.STATUS <> 2
             group by CodProducto, PP.Descripcion, Prioridad, PP.STATUS, PP.Unidad
             order by Prioridad asc";

			   $ejecutar = sqlsrv_query($con, $consulta);
			 
			   $i = 0;
			   $Estado = ['','En Proceso','Terminado','Ll'];

			  while($fila = sqlsrv_fetch_array($ejecutar)){
			  	$cod = $fila['Cod'];
				$Descripcion= $fila['Descripcion'];
				$pend =  $fila['Cantidad'];
				$Unidad = $fila['Unidad'];
				$st = $fila['STATUS'];
				$i++;
		?>

	<tr align="center">
			<td><?php echo $cod; ?></td>
			    <td align="left"><?php echo $Descripcion; ?></td>
				<td align="right"><?php echo number_format($pend,0,",","."); ?></td>
				<td align="right"><?php echo $Unidad; ?></td>
				<td align="left"><?php echo $Estado[$st]; ?></td>
			</tr>

		<?php } ?>
     	</table>
     </div>


</body>
</html>