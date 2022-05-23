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
 <link href="../css/bootstrap.min.css" rel="stylesheet">
 
<div class="col-md-11 col-md-offset-0">
	    
		<form method="POST" action="">

			<h3> <?php echo 'Relación Facturas a Pagar de  : '. $ClienteId . '  |  '  . $ClienteName ?> </h3>

		     	<table class="table table-bordered table-responsive">
					<tr>
						<td>Fecha</td>
						<td>Fecha Venc.</td>
						<td>Tipo</td>
						<td>Suc.</td>
						<td>Doc. Nro.</td>
						<td>Sub Total</td>
						<td>Iva</td>
						<td>Total</td>
						<td>Total Aplica</td>
						<td>Observación</td>
					</tr>


					<?php
					  $consulta = " Select Dc.Fecha, DC.FechaEntrega FechaV, DC.TipoDocumentoID TipoDoc, 
					  Dc.SucursalId SucID, Dc.Numero DocN, DC.Total Total, Dc.MontoAplicar TotalAplica, 
					  DC.SubTotal, DC.Iva, dc.Descripcion obs
					  From DocumentosCabecera DC
					  WHERE dc.EstadoDocumentoId = 1 and dc.Total >0 AND dc.Fecha>=CONVERT(DATETIME,'1900-1-1',101) 
		AND (dc.TipoDocumentoId LIKE 'FC%' OR dc.TipoDocumentoId LIKE 'ND%')   and clienteid = '$ClienteId'
					  order by fecha";

						$ejecutar = sqlsrv_query($con, $consulta);

						$j = 0;
			
						while($fila = sqlsrv_fetch_array($ejecutar)){
							$Fecha = $fila['Fecha'];
							$FechaV= $fila['FechaV'];
							$TipoD = $fila['TipoDoc'];
                            $SucID = $fila['SucID'];
							$DocN = $fila['DocN'];
							$SubTotal = $fila['SubTotal'];
							$Iva = $fila['Iva'];
							$TotalF = $fila['Total'];
							$TotalFA = $fila['TotalAplica'];
							$Obs = $fila['obs'];
					?>

					<tr align="center">
						<td><?php echo $Fecha->format("d/m/y"); ?></td>
						<td><?php echo $FechaV->format("d/m/y"); ?></td>
						<td><?php echo $TipoD ?></td>
						<td><?php echo $SucID ?></td>
						<td><?php echo $DocN ?></td>
						<td align="right"><?php echo number_format($SubTotal,2,",","."); ?></td>
						<td align="right"><?php echo number_format($Iva,2,",","."); ?></td>
						<td align="right"><?php echo number_format($TotalF,2,",","."); ?></td>
                        <td align="right"><?php echo number_format($TotalFA,2,",","."); ?></td>
						<td><?php echo $Obs ?></td>
					</tr>

				<?php } ?>

				</table>
		</form>
</div>

