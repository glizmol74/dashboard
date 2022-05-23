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
			<td>Cantidad</td>
			<td>Und Med</td>
            <td>En Caja</td>
	
            <td>Estado</td>
		</tr>

		<?php
		    $hoy = date("Y-m-d");
			$consulta = "select CodProducto Cod, pp.Descripcion, sum(pp.Cantidad) Cantidad, P.Stock, Prioridad, PP.STATUS, pp.Caja, PP.Unidad 
			from Produccion PP join Productos P on pp.CodProducto = p.ProductoID
           where Clasificacion = 17 and fecha <= CONVERT(DATETIME,'$hoy 23:59:59',101)  AND PP.STATUS <> 2
           group by CodProducto, pp.Descripcion, P.Stock, Prioridad, PP.STATUS, pp.Caja, PP.Unidad
           order by Prioridad asc";

			$ejecutar = sqlsrv_query($con, $consulta);

			$i = 0;
            $Estado = ['','Envasando','Terminado','Etiquetando'];
            $Cajas = ['No','Si'];
			while($fila = sqlsrv_fetch_array($ejecutar)){
                $cod = $fila['Cod'];
				$Descripcion= $fila['Descripcion'];
				$pend =  $fila['Cantidad'];
				$stockA = $fila['Stock'];
				$Unidad = $fila['Unidad'];
                $st = $fila['STATUS'];
                $Cj = $Cajas[ $fila['Caja']];
				$i++;
			

		?>

		<tr align="center">
			<td><?php echo $cod; ?></td>
			<td align="left"><?php echo $Descripcion; ?></td>
			<td align="right"><?php echo number_format($pend,0,",","."); ?></td>
			<td ><?php echo $Unidad; ?></td>
            <td align="left"><?php echo $Cj; ?></td>
			<td align="left"><?php echo $Estado[$st]; ?></td>
		</tr>

		<?php } ?>

	</table>
	</div>
</body>
</html>



