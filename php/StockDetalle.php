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
  if(isset($_GET['TipoRepo'])){
        $Reporte = $_GET['TipoRepo'];
        $TotalRepo = $_GET['TotalRepo'];
        $pagUrl = $_GET['Pagina'];
        $Titulo = $_GET['TRepo'];
    } else {
        $Reporte = '';
        $TotalRepo = 1;
        $Titulo = '';
    }

  	
?>
<meta charset="UTF-8">
<html> 	
	<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Stock</title>

 <link href="../html/pedido.css" rel="stylesheet">
 <link data-n-head="1" rel="icon" type="image/x-icon" href="../../favicon.ico">
 <link href="../css/bootstrap.min.css" rel="stylesheet">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
</head>
<body>
<div id="general">
 <!-- <div class="col-md-8 col-md-offset-2"> -->
	    
		<form method="POST" action="">

			<h2><?php echo $Titulo . '   Total = ' . number_format($TotalRepo,2,",","."); ?> </h1>

		     	<table class="table table-bordered table-responsive">
					<tr>
            <td>Codigo</td>
						<td>Producto</td>
						<td>Stock Actual</td>
						<td>Unid Med</td>
						<td>Costo</td>
            <td>Moneda</td>
            <td>Base</td>
						<td>Total</td>
            <td>Moneda</td>
						<td>Participación</td>
					</tr>


					<?php
            $TipoMone = array();
            $TipoMone[] = ' $ ';
            $TipoMone[] = 'Us$ ';
					  $consulta = "select ProductoId Cod, Descripcion, Stock Cant, 
                             UnidadMedidaID Unds, PrecioCompra Costo, CostoValor * Stock Total,
                             MonedaId Moneda, Cotiza
                        from Productos 
                        where Clasif1 = $Reporte  and stock > 0 order by Descripcion asc";

                    $ejecutar = sqlsrv_query($con, $consulta);

                    $i = 1;
                    while($fila = sqlsrv_fetch_array($ejecutar)){
                        $CodP = $fila['Cod'];
                        $ProdName= $fila['Descripcion'];
                        $Cant = $fila['Cant'];
                        $Unidades = $fila['Unds'];
                        $CostoL = $fila['Costo'];
                        $PrecioL = $fila['Total'];
                        $Part = $PrecioL / $TotalRepo * 100;
                        $Cotiza = $fila['Cotiza'];
                        $Moneda = $fila['Moneda'];
                        $CadMone = $TipoMone[$Moneda];
              ?>

                    <tr align="center">
						<td><?php echo $CodP ?></td>
						<td align="Left"><?php echo $ProdName; ?></td>
						<td align="right"><?php echo number_format($Cant,2,",","."); ?></td>
            <td align="center"><?php echo $Unidades; ?></td>
						<td align="right"><?php echo number_format($CostoL,2,",","."); ?></td>
            <td align="center"><?php echo $CadMone; ?></td>
            <td align="right"><?php echo number_format($Cotiza,2,",","."); ?></td>
						<td align="right"><?php echo number_format($PrecioL,2,",","."); ?></td>
            <td align="center"><?php echo $TipoMone[0]; ?></td>
            <td align="center"><?php echo number_format($Part,2,",",".") . ' % '; ?></td>
					</tr>

				<?php } ?>

				</table>
				
				
			<div class="form-group">
			<?php echo "<br />" ?>
				<input type="submit" name="Regresar" class="btn btn-warning" value="REGRESAR">
			</div>

			
		</form>
 <!-- </div> -->
<?php
 if (isset($_POST['Regresar'])){
 	echo "<script>window.open('Stock.php','_self')</script>";
 }

?>
</body>
</html>