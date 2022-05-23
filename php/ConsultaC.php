<?php
  include("conexion_sis.php");
  if(isset($_GET['Clientes'])){
  	$Pedido_Id = $_GET['Clientes'];
  	$ClienteName = $_GET['Producto'];
     echo  $Pedido_Id;
  }else{
	$Pedido_Id = '';
	$ClienteName = '';
  }

?>
 <link data-n-head="1" rel="icon" type="image/x-icon" href="../../favicon.ico">
 <link href="../css/bootstrap.min.css" rel="stylesheet">

<div class="col-md-8 col-md-offset-2">
	    
		<form method="POST" action="">

			<?php echo $ClienteName ?>

		     	<table class="table table-bordered table-responsive">
					<tr>
						<td>Cod Cliente</td>
						<td>Fecha P</td>
						<td>Suc</td>
						<td>Numero</td>
						<td>Dia R</td>
						<td>Razon</td>
						<td>Cant Pend</td>
						<td>Stock - Pend</td>
					</tr>


					<?php
			          $consulta = "Select distinct dc.Fecha Fecha, dc.SucursalId Suc, dc.numero NumD, dc.clienteID Cod, c.razonsocial Razon, dc.documentoID DocID,dc.EstadoDocumentoID, ap.ProductoId , ap.Descripcion, ap.s2 Pend, ap.unitario, ap.unitario * ap.s2, ap.documentoid , ap.observacion, ap.prioridad, DATEDIFF(day, dc.fecha, getdate()) DiaR, ap.s4 Stock 
                            from AuxPed Ap join  DocumentosCabecera dc on ap.documentoID = dc.documentoID JOIN EMP001.dbo.Clientes c ON c.ClienteId = dc.ClienteId  
                              where ap.S2 <> 0  and ap.TipoReporte = 'PedPend' and ap.ProductoId = '$Pedido_Id' 
                              order by DiaR desc, c.razonsocial";

                           

						$ejecutar = sqlsrv_query($con, $consulta);

						$j = 0;
			
						while($fila = sqlsrv_fetch_array($ejecutar)){
							if ($j == 0){
								$StockA = $fila['Stock'];
							}
							$cod = $fila['Cod'];
							$Descripcion= $fila['Razon'];
							$FechaP = $fila['Fecha'];
							$Suc = $fila['Suc'];
							$Pend = $fila['Pend'];
							$NumD = $fila['NumD'];
							$DiaR = $fila['DiaR'];
							$Stock = $fila['Stock'];
							$j++;
						

					?>

					<tr align="center">
						<td><?php echo $cod; ?></td>
						<td><?php echo $FechaP->format("d/m/y"); ?></td>
						<td><?php echo $Suc; ?></td>
						<td align="right"><?php echo  number_format($NumD,0,",","."); ?></td>
						<td align="center"><?php echo  number_format($DiaR,0,",","."); ?></td>
						<td align="left"><?php echo $Descripcion; ?></td>
						<td align="right"><?php echo  '<span style="color:red">'.number_format($Pend,0,",",".").'</span>'; ?></td>
						<?php  $StockA = $StockA - $Pend ?>
						<td align="right"><?php echo  '<span style="color:black">'.number_format($StockA,0,",",".").'</span>'; ?></td>
					<!--	<td><a href="formulario.php?borrar=<?php echo $id; ?>">Borrar</a></td>  -->
					</tr>

				<?php } ?>

				</table>
			<div class="form-group">
				<input type="submit" name="Regresar" class="btn btn-warning" value="REGRESAR">
			</div>
		</form>
</div>

<?php
 if (isset($_POST['Regresar'])){
 	echo "<script>window.open('Pedidos.php', '_self')</script>";
 }
?>