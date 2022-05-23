<?php
  include("conexion_sis.php");
  if(isset($_POST['ClienteID'])){
      $ClienteId = $_POST['ClienteID'];
      $ClienteName = $_POST['NameC'];
  } else {
    $ClienteID = '';
    $ClienteName = 'No Autorizado';
  }

?>
 <link data-n-head="1" rel="icon" type="image/x-icon" href="../../favicon.ico">
 <link href="../css/sb-admin-2.min.css" rel="stylesheet">
 <script type="text/javascript">
	 function cambiarcontPF(pagina, doc_id, doc_n, tipoD, Observ, doc_a) {
		contenidoFP.innerHTML = '567'
		 $('#contenidoFP').load(pagina, {detalle: doc_id, DocN: doc_n, TipoDoc: tipoD, ObservF: Observ, Doc_A: doc_a})
	 }
 </script>
<div class="col-md-12 col-md-offset-0">
	    
		<form method="POST" action="">

			<h4> <?php echo $ClienteId . '  |  '  . $ClienteName ?> </h4>

		     	<table class="table table-bordered">
					<tr align="center" style="font-weight: bold; color: #000; background: #9c9c9c;">
						<th>Fecha</th>
						<th>Fecha Venc.</th>
						<th>Días</th>
						<th>Tipo</th>
						<th>Suc.</th>
						<th>Doc. Nro.</th>
						<th>Sub Total</th>
						<th>Iva</th>
						<th>Total</th>
						<th>Total Aplica</th>
						<th>Acción</th>
					</tr>


					<?php
					  $consulta = " Select Dc.Fecha, DC.FechaVto FechaV, DATEDIFF(day, getdate(), dc.fechaVto) Dias, DC.TipoDocumentoID TipoDoc, 
					 	 Dc.SucursalId SucID, Dc.Numero DocN, DC.Total Total, Dc.MontoAplicar TotalAplica, 
					  	DC.SubTotal, DC.Iva, dc.cprasDocumentoID DocID, Dc.Descripcion Obs, 0 DocA
					  	From cprasDocumentosCabecera DC
					  	WHERE dc.EstadoDocumentoId = 1 and dc.MontoAplicar <>0 AND dc.Fecha>=CONVERT(DATETIME,'1900-1-1',101) 
							AND (dc.TipoDocumentoId LIKE 'FC%' OR dc.TipoDocumentoId LIKE 'ND%')   and proveedorid = '$ClienteId'
					  	order by fechavto";

						$ejecutar = sqlsrv_query($con, $consulta);

						$j = 0;
  						$BackColor = ['#FDFEFE','#FFFF66','#CC0000'];
						$Bkc = 0;
						while($fila = sqlsrv_fetch_array($ejecutar)){
							$Fecha = $fila['Fecha'];
							$FechaV= $fila['FechaV'];
							$TipoD = $fila['TipoDoc'];
                            $SucID = $fila['SucID'];
							$DocN = $fila['DocN'];
							$DocID = $fila['DocID'];
							$SubTotal = $fila['SubTotal'];
							$Iva = $fila['Iva'];
							$TotalF = $fila['Total'];
							$TotalFA = $fila['TotalAplica'];
							$Dias = $fila['Dias'];
							$Obs = $fila['Obs'];
							$DocA = $fila['DocA'];
							if ($Dias > 10) { 
								$Bkc = 0;
							 }else if ( $Dias > 0) {
								 $Bkc = 1;
							 } else {
								 $Bkc = 2;
							 }
					?>

					<tr align="center">
						<td bgcolor="<?php echo $BackColor[$Bkc]; ?>" style="color: #000;"><?php echo $Fecha->format("d/m/y"); ?></td>
						<td bgcolor="<?php echo $BackColor[$Bkc]; ?>" style="color: #000;"><?php echo $FechaV->format("d/m/y"); ?></td>
						<td bgcolor="<?php echo $BackColor[$Bkc]; ?>" style="color: #000;"><?php echo $Dias ?></td>
						<td bgcolor="<?php echo $BackColor[$Bkc]; ?>" style="color: #000;"><?php echo $TipoD ?></td>
						<td bgcolor="<?php echo $BackColor[$Bkc]; ?>" style="color: #000;"><?php echo $SucID ?></td>
						<td bgcolor="<?php echo $BackColor[$Bkc]; ?>" style="color: #000;"><?php echo $DocN ?></td>
						<td bgcolor="<?php echo $BackColor[$Bkc]; ?>" align="right" style="color: #000;" ><?php echo number_format($SubTotal,2,",","."); ?></td>
						<td bgcolor="<?php echo $BackColor[$Bkc]; ?>" align="right" style="color: #000;"><?php echo number_format($Iva,2,",","."); ?></td>
						<td bgcolor="<?php echo $BackColor[$Bkc]; ?>" align="right" style="color: #000;"><?php echo number_format($TotalF,2,",","."); ?></td>
                        <td bgcolor="<?php echo $BackColor[$Bkc]; ?>" align="right" style="color: #000;"><?php echo number_format($TotalFA,2,",","."); ?></td>
						<td><a href="javascript:cambiarcontPF('ProductoC.php', <?php echo $DocID .", " . $DocN . ", '" . $TipoD . "', '"  . $Obs . "', " . $DocA . ')'  ?> ">Detalle</a></td>
					</tr>

				<?php } ?>

				</table>
		</form>
</div>

