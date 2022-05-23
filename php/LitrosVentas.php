<?php
  include("../php/conexion_sis.php");
  $TituloLitros = '';
  $In = '(1000)';
  if(isset($_POST['TipoRepo'])){
      $Reporte = $_POST['TipoRepo'];
      $MesRepo = $_POST['MesR'];
      $TmLitros = $_POST['TLitrosM'];
	  $TipoP = $_POST['Reporte'];
  } else {
    $Reporte = '';
    $MesRepo = 'No Autorizado';
    $TmLitros = 1;
	$TipoP = 0;
  }

  if ( $TipoP == '1' ) {
	$TituloLitros = 'Relación Detallada Litros / Unidades Vendidas en : ';
	$In = '(12,16,17)';
  } else if ($TipoP == '2') {
	  $TituloLitros = 'Relación Detallada Artículos Vendidos en : ';
	  $In = '(34)';
  }

?>
 <link data-n-head="1" rel="icon" type="image/x-icon" href="../../favicon.ico">
 <link href="../css/sb-admin-2.min.css" rel="stylesheet">
 <script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<script src="../js/exportarExcel.js"></script>
 
<div class="col-md-12 col-md-offset-0">
	    
		<form method="POST" action="">

			<h4> <?php echo $TituloLitros  . $MesRepo  ?> </h4>

		     	<table class="table table-bordered table-striped table-sm" id="IdProductos">
					<tr align="center" style="color: #000; font-weight: bold; background: #9c9c9c;">
						<td>Codigo</td>
						<td>Producto</td>
						<?php if ( $TipoP == '1') { ?>
						<td>Litros</td>
						<?php } ?>
						<td>Unidades</td>
						<td>Costo</td>
						<td>Precio</td>
						<td>Costo Px</td>
						<td>Precio Px</td>
						<td>% Part.</td>
					</tr>


					<?php
					  $consulta = "set dateformat YMD
					  	SELECT * From (
					  	SELECT A.ProductoId Cod, A.Descripcion, sum (case when a.ClasificacionProdID =17 THEN (f.Cantidad*a.TotalCantidad) else a.TotalCantidad END) Cant, 
					  		sum(A.TotalCantidad) Unds, avg(A.S2) CostoLitros, avg(A.S1) PrecioLitros
						from AuxPed A left join DesgloseFormula F on f.FormulaID = a.FormulaID and f.FormSemi <> 0
						where ((A.TipoReporte = '$Reporte' ) or 
						       (A.TipoReporte = 'RemPxV' and  format(fechaE, 'yy-MM') = substring('$Reporte',6,5) )) 
							   and A.ClasificacionProdID in $In
							   group by a.ProductoId, a.Descripcion) T1
							   where Cant <> 0
							   order by 3 desc, Descripcion asc";

						$ejecutar = sqlsrv_query($con, $consulta);

						$j = 0;
			
						while($fila = sqlsrv_fetch_array($ejecutar)){
							$CodP = $fila['Cod'];
							$ProdName= $fila['Descripcion'];
							$Cant = $fila['Cant'];
                            $Unidades = $fila['Unds'];
							$CostoL = $fila['CostoLitros'];
							$PrecioL = $fila['PrecioLitros'];
							if ($Cant > 0 ){
								$CostoPx = $CostoL / $Cant ;
								$PrecioPx = $PrecioL / $Cant;
							}
							$Part = $Cant / $TmLitros * 100;
					?>

					<tr align="center">
						<td><?php echo "'" . $CodP   ?></td>
						<td align="Left"><?php echo $ProdName; ?></td>
						<?php if ( $TipoP ==  '1') { ?>
							<td align="right"><?php echo number_format($Cant,2,",","."); ?></td>
						<?php } ?>
                        <td align="right"><?php echo number_format($Unidades,0,",","."); ?></td>
						<td align="right"><?php echo number_format($CostoL,2,",","."); ?></td>
						<td align="right"><?php echo number_format($PrecioL,2,",","."); ?></td>
						<td align="right"><?php echo number_format($CostoPx,2,",","."); ?></td>
						<td align="right"><?php echo number_format($PrecioPx,2,",","."); ?></td>
						<td align="center"><?php echo number_format($Part,2,",",".") . ' % '; ?></td>
					</tr>

				<?php } ?>

				</table>
		</form>
</div>

