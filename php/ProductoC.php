<?php
  include("conexion_sis.php");
  if(isset($_POST['detalle'])){
      $Doc_Id = $_POST['detalle'];
      $Doc_N = $_POST['DocN'];
      $Tipo_Doc = $_POST['TipoDoc'];
	  $ObservacionF = $_POST['ObservF'];
	  $DocumentoA = $_POST['Doc_A'];
  } else {
	$Doc_Id = '';
	$Doc_N = '';
	$Tipo_Doc = '';
	$ObservacionF = '';
	$DocumentoA = '';
  }

?>
 <link data-n-head="1" rel="icon" type="image/x-icon" href="../../favicon.ico">
 <link href="../css/sb-admin-2.min.css" rel="stylesheet">
 
<div class="col-md-12 col-md-offset-0">
	    
		<form method="POST" action="">

			<h4> <?php echo 'Documento : '. $Tipo_Doc . '  Nro.: '. $Doc_N . '    '  ?> </h4>

		     	<table class="table table-bordered table-sm table-striped">
					<tr align="center" style="font-weight: bold; color: #000; background: #9c9c9c;">
						<th>Codigo</th>
						<th>Producto</th>
						<th>Cantidad</th>
						<th>Unitario</th>
                        <th>Sub Total</th>
						<th>Iva</th>
                        <th>Total Prod</th>
					</tr>


					<?php
					  if ( substr($Tipo_Doc,0,2)== 'NC'){
						$Sig = -1;
					  }else{
						  $Sig = 1;
					  }
					  $consulta = "select ProductoId Cod, Descripcion, Cantidad Cant, Unitario, AlicuotaIVA Iva, 
					             Cantidad*Unitario * '$Sig' SubTotal
					   from cprasDocumentosDetalle
                        where cprasDocumentoId = $Doc_Id";

						$ejecutar = sqlsrv_query($con, $consulta);

						$j = 0;
						$Tiva = 0;
						$Tsub = 0;
						$Ttot = 0;
			
						while($fila = sqlsrv_fetch_array($ejecutar)){
							$CodP = $fila['Cod'];
							$ProdName= $fila['Descripcion'];
							$Cant = $fila['Cant'];
                            $Unitario = $fila['Unitario'];
                            $SubTotal = $Cant * $Unitario * $Sig;
                            $Iva = ( $fila['Iva'] * $SubTotal / 100 ) + 0;
							$Total = $SubTotal + $Iva;
							$Tiva+= $Iva;
							$Tsub+= $SubTotal;
							$Ttot+= $Total;
							$j++;
						

					?>

					<tr align="center">
						<td><?php echo $CodP ?></td>
						<td align="Left"><?php echo $ProdName; ?></td>
						<td align="center"><?php echo number_format($Cant,0,",","."); ?></td>
                        <td align="right"><?php echo number_format($Unitario,2,",","."); ?></td>
                        <td align="right"><?php echo number_format($SubTotal,2,",","."); ?></td>
						<td align="right"><?php echo number_format($Iva,2,",","."); ?></td>
						<td align="right"><?php echo number_format($Total,2,",","."); ?></td>
					</tr>

					

				<?php } ?>

				<tr align="center" style="font-weight: bold; color:#000; background:#FFF">
						<td></td>
						<td></td>
						<td></td>
						<td style="background-color: #9c9c9c;" >Total</td>
						<td align="right" style="background-color: #9c9c9c;"><?php echo number_format($Tsub,2,",","."); ?></td>
						<td align="right" style="background-color: #9c9c9c;"><?php echo number_format($Tiva,2,",","."); ?></td>
						<td align="right" style="background-color: #9c9c9c;"><?php echo number_format($Ttot,2,",","."); ?></td>
					</tr>

				</table>
				<?php echo 'Observacion : ' . $ObservacionF ?>
				<?php
				   if ( $Sig == -1 ){
						$consulta = "select DISTINCT dc.Fecha FechaP, dc.TipoDocumentoId TipoDoc, dc.SucursalId SucId,dc.Numero DocumentoN, 
							dc.DocumentoId , dc.ClienteId ClienteId, 
							C.RazonSocial ClienteName, Dc.Subtotal SubTotal, sum(zz.Cantidad * zz.GmPrecioCompra ) CostoTotal, dc.Descripcion observacion, 
							0 DocumentoA
		
						from DocumentosCabecera DC join Clientes C on dc.ClienteId = C.ClienteID
						join ( select da.DocumentoIdAfectado Afectado from DocumentosAfectaciones da where da.DocumentoIdAfectante = $Doc_Id ) Af on Afectado = dc.DocumentoId
						join ZZDocumentosDetalle zz on zz.DocumentoId = Afectado
						group by dc.Fecha, dc.TipoDocumentoId, dc.SucursalId, dc.Numero, dc.DocumentoId, dc.ClienteId, 
						c.RazonSocial, dc.Subtotal, af.Afectado, dc.Descripcion ";

						$ejecutar = sqlsrv_query($con, $consulta);
						if($fila = sqlsrv_fetch_array($ejecutar)){
							$FechaF = $fila['FechaP'];
							$TipoD = $fila['TipoDoc'];
							$SucId= $fila['SucId'];
							$DocN = $fila['DocumentoN'];
							$DocId = $fila['DocumentoId'];
							$SubTotal = $fila['SubTotal'];
							$CostoT = $fila['CostoTotal'];
							$observ = $fila['observacion'];
							$DocA = $fila['DocumentoA'];
							$CMg = $SubTotal - $CostoT;
							if ( $SubTotal == 0 ){
								$CMgP = 0;
							}else {
							 	$CMgP = ($CMg / $SubTotal * 100);
							}
							 ?>
							<h5> <?php echo '<br />Documento Afectado : ' . $TipoD . '  ' . $DocN ; ?> </h5>
							<table class="table table-bordered table-responsive">
								<tr>
									<td>Fecha</td>
									<td>Tipo</td>
									<td>Sucursal</td>
									<td>Nro. Doc</td>
									<td>Sub Total</td>
									<td>Costo</td>
									<td>CMg $</td>
									<td>CMg %</td>
								</tr>

								<tr align="center">
									<td><?php echo $FechaF->format("d/m/y"); ?></td>
									<td><?php echo $TipoD; ?></td>
									<td><?php echo $SucId; ?></td>
									<td><?php echo $DocN;  ?></td>
									<td align="right"><?php echo number_format($SubTotal,2,",","."); ?></td>
									<td align="right"><?php echo number_format($CostoT,2,",","."); ?></td>
									<td align="right"><?php echo number_format($CMg,2,",","."); ?></td>
									<td align="right"><?php echo number_format($CMgP,2,",",".").' %'; ?></td>
								</tr>
							</table> 
							<h3>Detalle  </h3>
							<table class="table table-bordered table-responsive">
								<tr>
									<td>Codigo</td>
									<td>Producto</td>
									<td>Cantidad</td>
									<td>Unitario</td>
									<td>Costo</td>
									<td>Total Prod</td>
									<td>Total Costo</td>
									<td>CMg $</td>
									<td>CMg %</td>
								
								</tr>
							
							<?php
							 $consulta = "select DISTINCT zz.ProductoId Cod, zz.Descripcion, zz.Cantidad Cant, zz.Unitario,
							       gmPrecioCompra Costo, Cantidad*Unitario  SubTotal, Cantidad * GmPrecioCompra   CostoTotal
							 from zzDocumentosDetalle zz
							  where DocumentoId = $DocA and 1 = 0";
	  
							  $ejecutar = sqlsrv_query($con, $consulta);
	  
							  $j = 0;
				  
							  while($fila = sqlsrv_fetch_array($ejecutar)){
								  $CodP = $fila['Cod'];
								  $ProdName= $fila['Descripcion'];
								  $Cant = $fila['Cant'];
								  $Unitario = $fila['Unitario'];
								  $Costo = $fila['Costo'];
								  $SubTotal = $fila['SubTotal'];
								  $CostoT = $fila['CostoTotal'];
								  $CMg = $SubTotal - $CostoT;
								  if ( $SubTotal == 0 ){
									$CMgP = 0;
								}else {
									 $CMgP = ($CMg / $SubTotal * 100);
								}
								  $j++;
								?>
			
								<tr align="center">
									<td><?php echo $CodP ?></td>
									<td align="Left"><?php echo $ProdName; ?></td>
									<td align="right"><?php echo number_format($Cant,0,",","."); ?></td>
									<td align="right"><?php echo number_format($Unitario,2,",","."); ?></td>
									<td align="right"><?php echo number_format($Costo,2,",","."); ?></td>
									<td align="right"><?php echo number_format($SubTotal,2,",","."); ?></td>
									<td align="right"><?php echo number_format($CostoT,2,",","."); ?></td>
									<td align="right"><?php echo number_format($CMg,2,",","."); ?></td>
									<td align="right"><?php echo number_format($CMgP,2,",",".").' %'; ?></td>
								</tr>
			
								<?php } ?>
			
							</table>

							<?php
						}
						
				   }
				?>
		</form>
</div>

