<?php
  include("conexion_sis.php");
  if(isset($_POST['detalle'])){
      $Doc_Id = $_POST['detalle'];
      $Doc_N = $_POST['DocN'];
      //$MesCon = $_POST['MesB'];
      $Tipo_Doc = $_POST['TipoDoc'];
	  $ObservacionF = $_POST['ObservF'];
	  //$DocumentoA = $_POST['Doc_A'];
  } else {
	$Doc_Id = '';
	$Doc_N = '';
	//$MesCon = '';
	$Tipo_Doc = '';
	$ObservacionF = '';
	//$DocumentoA = '';
  }

?>
 <link data-n-head="1" rel="icon" type="image/x-icon" href="../../favicon.ico">
 <!-- <link href="../css/bootstrap.min.css" rel="stylesheet"> -->
 <link href="../css/sb-admin-2.min.css" rel="stylesheet">
 
<div class="col-md-12 col-md-offset-0">
	    
		<form method="POST" action="">

			<h3> <?php echo 'Documento : '. $Tipo_Doc . '  Nro.: '. $Doc_N . '    '  ?> </h3>

		     	<table class="table table-bordered table-sm table-striped">
					<tr align="center" style="font-weight: bold; color: #000; background: #9c9c9c;">
						<td>Codigo</td>
						<td>Producto</td>
						<td>Cantidad</td>
						<td>Unitario</td>
						<td>Costo</td>
                        <td>Sub Total</td>
						<td>Iva</td>
                        <td>Total Costo</td>
						<td>CMg $</td>
						<td>CMg %</td>
					
					</tr>

					<?php
					  if ( substr($Tipo_Doc,0,2)== 'NC'){
						$Sig = -1;
					  }else{
						  $Sig = 1;
					  }
					  $consulta = "select ProductoId Cod, Descripcion, Cantidad Cant, Unitario, GmPrecioCompra Costo, 
					             Cantidad*Unitario * '$Sig' SubTotal, Cantidad * GmPrecioCompra * '$Sig' CostoTotal,
								 (AlicuotaIVA * Cantidad * Unitario / 100 * '$Sig') Iva
					   from zzDocumentosDetalle
                        where DocumentoId = $Doc_Id";

						$ejecutar = sqlsrv_query($con, $consulta);

						$j = 0;
						$Tcost = 0;
						$Tcmg = 0;
						$Tcmgp = 0;
						$Ttot = 0;
						$Tiva = 0;
			
						while($fila = sqlsrv_fetch_array($ejecutar)){
							$CodP = $fila['Cod'];
							$ProdName= $fila['Descripcion'];
							$Cant = $fila['Cant'];
                            $Unitario = $fila['Unitario'];
                            $Costo = $fila['Costo'];
							$SubTotal = $fila['SubTotal'];
							$CostoT = $fila['CostoTotal'];
							$Iva = $fila['Iva'];
							$CMg = $SubTotal - $CostoT;
							if ( $SubTotal == 0 ){
								$CMgP = 0;
							}else {
							 	$CMgP = ($CMg / $SubTotal * 100) * $Sig;
							}
							$Tcost+= $CostoT;
							$Tcmg+= $CMg;
							$Ttot+= $SubTotal;
							$Tiva+= $Iva;

							if ($Ttot > 0)
								$Tcmgp = ($Tcmg / $Ttot * 100);
							$j++;
						

					?>

					<tr align="center">
						<td><?php echo $CodP ?></td>
						<td align="Left"><?php echo substr($ProdName,0,40); ?></td>
						<td align="right"><?php echo number_format($Cant,0,",","."); ?></td>
                        <td align="right"><?php echo number_format($Unitario,2,",","."); ?></td>
                        <td align="right"><?php echo number_format($Costo,2,",","."); ?></td>
						<td align="right"><?php echo number_format($SubTotal,2,",","."); ?></td>
						<td align="right"><?php echo number_format($Iva,2,",","."); ?></td>
						<td align="right"><?php echo number_format($CostoT,2,",","."); ?></td>
						<td align="right"><?php echo number_format($CMg,2,",","."); ?></td>
						<td align="right"><?php echo number_format($CMgP,2,",",".").' %'; ?></td>
					</tr>

				<?php } ?>
					<tr align="center" style="font-weight: bold; color:#000; background:#FFF">
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td style="background-color: #9c9c9c;" >Sub Total</td>
						<td align="right" style="background-color: #9c9c9c;"><?php echo number_format($Ttot,2,",","."); ?></td>
						<td align="right" style="background-color: #9c9c9c;"><?php echo number_format($Tiva,2,",","."); ?></td>
						<td align="right" style="background-color: #9c9c9c;"><?php echo number_format($Tcost,2,",","."); ?></td>
						<td align="right" style="background-color: #9c9c9c;"><?php echo number_format($Tcmg,2,",","."); ?></td>
						<td align="right" style="background-color: #9c9c9c;"><?php echo number_format($Tcmgp,2,",",".") . ' %'; ?></td>
					</tr>
				</table>
				<div class="row justify-content-end">
					<div class="col-xl-3 col-md-3">
						<table class="table table-bordered table-sm">
							<tr align="right" style="color: #000; font-weight: bold;">
								<td bgcolor="#9c9c9c">Total Costo</td>
								<td ><?php echo number_format($Tcost,2,",","."); ?></td>
							</tr>
							<tr align="right" style="color: #000; font-weight: bold;">
								<td bgcolor="#9c9c9c" >Sub Total </td>
								<td ><?php echo number_format($Ttot,2,",","."); ?></td>
							</tr>
							<tr align="right" style="color: #000; font-weight: bold;">
								<td bgcolor="#9c9c9c" >Iva </td>
								<td ><?php echo number_format($Tiva,2,",","."); ?></td>
							</tr>
							<tr align="right" style="color: #000; font-weight: bold;">
								<td bgcolor="#9c9c9c" >Total <?php echo $Tipo_Doc ?> </td>
								<td ><?php echo number_format($Ttot + $Tiva,2,",","."); ?></td>
							</tr>
						</table>
					</div>
				</div>
				<?php echo 'Observacion : ' . $ObservacionF ?>
				<?php
				   if ( $Sig == -1 ){
						$consulta = "select DISTINCT dc.Fecha FechaP, dc.TipoDocumentoId TipoDoc, dc.SucursalId SucId,dc.Numero DocumentoN, 
							dc.DocumentoId , dc.ClienteId ClienteId, 
							C.RazonSocial ClienteName, Dc.Subtotal SubTotal, sum(zz.Cantidad * zz.GmPrecioCompra ) CostoTotal, dc.Descripcion observacion, 
							af.Afectado DocumentoA
		
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
							<h2> <?php echo '<br />Documento Afectado : ' . $TipoD . '  ' . $DocN ; ?> </h2>
							<table class="table table-bordered table-sm table-striped">
								<tr align="center" style="font-weight: bold; color:#000; background:#9c9c9c">
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
							<table class="table table-bordered table-sm table-striped">
								<tr align="center" style="font-weight: bold; color:#000; background:#9c9c9c">
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
							  where DocumentoId = $DocA";
	  
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

