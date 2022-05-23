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
 <!-- <link href="../css/bootstrap.min.css" rel="stylesheet"> -->
  <!-- <link href="../css/bootstrap.min.css" rel="stylesheet"> -->
  <link href="../css/sb-admin-2.min.css" rel="stylesheet">
  <script type="text/javascript">
            function cambiarcontP(pagina, doc_id, doc_n, TipoD, Observ) {
                       $("#contenidoF").load(pagina,{detalle: doc_id, DocN: doc_n, TipoDoc: TipoD, ObservF: Observ});
            }
</script>
<div class="col-md-12 col-md-offset-0">
	    
		<form method="POST" action="">

			<h4> <?php echo  $ClienteId . '  |  '  . $ClienteName ?> </h4>

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
					  $consulta = " Select Dc.Fecha, DC.FechaEntrega FechaV,  DATEDIFF(day, getdate(), dc.fechaEntrega) Dias, DC.TipoDocumentoID TipoDoc, 
					  Dc.SucursalId SucID, Dc.Numero DocN, DC.Total Total, Dc.MontoAplicar TotalAplica, 
					  DC.SubTotal, DC.Iva, dc.Descripcion obs, Dc.DocumentoID DocID
					  From DocumentosCabecera DC
					  WHERE dc.EstadoDocumentoId = 1 and dc.Total >0 AND dc.Fecha>=CONVERT(DATETIME,'1900-1-1',101) 
						AND (dc.TipoDocumentoId LIKE 'FC%' OR dc.TipoDocumentoId LIKE 'ND%')   and clienteid = '$ClienteId'
					  order by fecha";

						$ejecutar = sqlsrv_query($con, $consulta);

						$j = 0;
						$BackColor = ['#FDFEFE','#FFFF66','#CC0000'];
						while($fila = sqlsrv_fetch_array($ejecutar)){
							$Fecha = $fila['Fecha'];
							$FechaV= $fila['FechaV'];
							$TipoD = $fila['TipoDoc'];
                            $SucID = $fila['SucID'];
							$DocN = $fila['DocN'];
							$DocId = $fila['DocID'];
							$SubTotal = $fila['SubTotal'];
							$Iva = $fila['Iva'];
							$TotalF = $fila['Total'];
							$TotalFA = $fila['TotalAplica'];
							$Obs = $fila['obs'];
							$Dias = $fila['Dias'];
							if ($Dias > 10) { 
								$Bkc = 0;
							 }else if ( $Dias > 0) {
								 $Bkc = 1;
							 } else {
								 $Bkc = 2;
							 }
					?>

					<tr align="center">
						<td bgcolor="<?php echo $BackColor[$Bkc]; ?>" style="color: #000"><?php echo $Fecha->format("d/m/y"); ?></td>
						<td bgcolor="<?php echo $BackColor[$Bkc]; ?>" style="color: #000"><?php echo $FechaV->format("d/m/y"); ?></td>
						<td bgcolor="<?php echo $BackColor[$Bkc]; ?>" style="color: #000"><?php echo $Dias ?></td>
						<td bgcolor="<?php echo $BackColor[$Bkc]; ?>" style="color: #000"><?php echo $TipoD ?></td>
						<td bgcolor="<?php echo $BackColor[$Bkc]; ?>" style="color: #000"><?php echo $SucID ?></td>
						<td bgcolor="<?php echo $BackColor[$Bkc]; ?>" style="color: #000"><?php echo $DocN ?></td>
						<td bgcolor="<?php echo $BackColor[$Bkc]; ?>" style="color: #000" align="right"><?php echo number_format($SubTotal,2,",","."); ?></td>
						<td bgcolor="<?php echo $BackColor[$Bkc]; ?>" style="color: #000" align="right"><?php echo number_format($Iva,2,",","."); ?></td>
						<td bgcolor="<?php echo $BackColor[$Bkc]; ?>" style="color: #000" align="right"><?php echo number_format($TotalF,2,",","."); ?></td>
                        <td bgcolor="<?php echo $BackColor[$Bkc]; ?>" style="color: #000" align="right"><?php echo number_format($TotalFA,2,",","."); ?></td>
						<td><a href="javascript:cambiarcontP('Producto.php', <?php echo $DocId ?>,<?php echo $DocN ?>,<?php echo "'". $TipoD ."'"?>, <?php echo "'". $Obs ."'"?>)">Detalle</a></td>  
						<!-- <td style="color: blue"><?php echo substr($Obs,0,15) ?></td> -->
					</tr>

					<?php } ?>

				</table>
		</form>
</div>

