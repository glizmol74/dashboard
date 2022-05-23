<?php
  include("conexion_sis.php");
  if(isset($_GET['detalle'])){
  	$Pedido_Id = $_GET['detalle'];
	  $ClienteName = $_GET['ClienteN'];
	  $ObsD = $_GET['ObsD'];
	  $Pagina = $_GET['Pagina'];
	  $TipoR = $_GET['TipoR'];
     echo  $Pedido_Id;
  }else{
	$Pedido_Id = '';
	$ClienteName = '';
	$ObsD = '';
	$TipoR = 0;
  }

?>
 <link data-n-head="1" rel="icon" type="image/x-icon" href="../../favicon.ico">
 <link href="../css/sb-admin-2.min.css" rel="stylesheet">
 <script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
 <script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script> 
 <script type="text/javascript">
		function exportReportToExcel(id) {
          let filen = ""
          let Tabla = ""
          if ( id == 1){
            filen = "Articulos"
            Tabla = "#IdProductos"
          } else if ( id == 2) {
            filen = "Detalle Pedidos"
            Tabla = "#IdDetalleP"
          }

          $(Tabla).table2excel({
            exclude: ".excludeThisClass",
            name: "Productos",
            filename: filen,
            fileext: ".xlsx",
            sheet: {
                name: 'Productos'
            },
            preserveColors: true
          })

        }
	</script>

<div class="col-md-12">
	    
		<form method="POST" action="">

			<h3> <?php echo $ClienteName ?> </h3>

			<div class="d-flex justify-content-between">
					<div class="my-2">
						<!-- <a class="btn btn-warning" href="dashboard.php">Regresar</a> -->
						<input type="submit" name="Regresar" class="btn btn-warning" value="Regresar">
					</div>
					<div class="my-2">
						<button class="btn btn-success" onclick="exportReportToExcel(2)">Exportar Excel</button>
					</div>
			</div>

			<table class="table table-bordered table-sm table-striped" id="IdDetalleP">
				<tr align="center" style="color: #000; font-weight: bold; background: #9c9c9c;" >
					<td>Cod</td>
					<td>Descripcion</td>
					<td>Pedidos</td>
					<td>Entregado</td>
					<td>Pendiente</td>
					<td>Stock</td>
					<td>Costo</td>
					<td>Precio</td>
					<td>Total Costo</td>
					<td>Total Pendiente</td>
					<td>Iva</td>
					<td>CMg</td>
					<td>Cmg %</td>
				</tr>

				<?php
					$consulta = "Select distinct ap.ProductoId Cod, ap.Descripcion Descripcion, TotalCantidad Ped, S3 CantFact, s2 Pend, S4 Stock, 
							ap.ClasificacionProdId, ap.Iva, Ap.Costo, Ap.Unitario, s2 * Unitario TotalPed, s2 * Costo TotalCosto
						from AuxPed Ap  
							where  ap.TipoReporte = 'PedPend'  and  ap.DocumentoId = $Pedido_Id
							
							order by ClasificacionProdId, ap.Descripcion";



					$ejecutar = sqlsrv_query($con, $consulta);

					$j = 0;
		
					while($fila = sqlsrv_fetch_array($ejecutar)){
						$cod = $fila['Cod'];
						$Descripcion= $fila['Descripcion'];
						$Ped = $fila['Ped'];
						$CantFact = $fila['CantFact'];
						$Pend = $fila['Pend'];
						$Stock = $fila['Stock'];
						$Costo = $fila['Costo'];
						$Unitario = $fila['Unitario'];
						$TotalCosto = $fila['TotalCosto'];
						$TotalPed = $fila['TotalPed'];
						$Iva = $fila['Iva'];

						if ($TotalPed > 0){
							$CMg = ($TotalPed - $TotalCosto);
							$CmgP = $CMg / $TotalPed * 100;
						}else{
							$CMg = 0;
							$CmgP = 0;
						}
						$j++;
					

				?>

				<tr align="center">
					<td><?php echo "'" . $cod; ?></td>
					<td align="left"><?php echo $Descripcion; ?></td>
					<td align="right"><?php echo  '<span style="color:red">'.number_format($Ped,0,",",".").'</span>'; ?></td>
					<td align="right"><?php echo  '<span style="color:#FFA500">'.number_format($CantFact,0,",",".").'</span>'; ?></td>
					<td align="right"><?php echo  '<span style="color:#00FF00">'. number_format($Pend,0,",",".").'</span>'; ?></td>
					<td align="right"><?php echo number_format($Stock,0,",","."); ?></td>
					<td align="right"><?php echo number_format($Costo,2,",","."); ?></td>
					<td align="right"><?php echo number_format($Unitario,2,",","."); ?></td>
					<td align="right"><?php echo number_format($TotalCosto,2,",","."); ?></td>
					<td align="right"><?php echo number_format($TotalPed,2,",","."); ?></td>
					<td align="right"><?php echo number_format($Iva,2,",","."); ?></td>
					<td align="right"><?php echo number_format($CMg,2,",","."); ?></td>
					<td align="center"><?php echo number_format($CmgP,2,",",".") . ' %'; ?></td>
				</tr>

				<?php } ?>

			</table>
				
				
			<div class="form-group">
			<?php echo $ObsD."<br />"."<br />" ?>
				
			</div>
		</form>
</div>

<?php
 if (isset($_POST['Regresar'])){
 	echo "<script>window.open('$Pagina?detalleR=$TipoR&Pagina=$Pagina', '_self')</script>";
 }
?>