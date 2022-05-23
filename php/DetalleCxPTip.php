<?php
  include("conexion_sis.php");
  include("../config.php");
  $Cond = '1 = 0';
  $TipoReporte = '';
  $lFecha = date("Y-m-d");
  $sFecha = date("d/m/Y",strtotime($lFecha. "+ 11 day"));
  if(isset($_POST['TipoR'])){
      $Report = $_POST['TipoR'];
      if ( $Report == 1 ){ 
        $Cond = ' Dias <= 0';
		$TipoReporte = 'Relación de Facturas Vencidas';
	  }
      else if ( $Report == 3) {
        $Cond = ' Dias > 10';
		
		$TipoReporte = 'Relación de Facturas no Vencidas';
	  }
      else if ( $Report == 2) {
        $Cond = ' Dias > 0 and Dias <= 10';
		$TipoReporte = 'Relación de Facturas x Vencer antes del ' . $sFecha;
	  }
      else if ( $Report == 4) {
        $Cond = ' 1 = 1';
		$TipoReporte = 'Resumen de Cuentas x Pagar x Proveedor';
	  }
  } else {
    $Report = 0;
	$TipoReporte = 'xxx';
  }

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link data-n-head="1" rel="icon" type="image/x-icon" href="../../favicon.ico">
	 <!-- <link href="../css/bootstrap.min.css" rel="stylesheet"> -->
	 <link href="../css/sb-admin-2.min.css" rel="stylesheet">
	 <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

	<title>Cuentas x Pagar</title>
</head>
<body>
	<div class="col-md-12 col-md-offset-0">
			
			<form method="POST" action="">
  					<h3><?php echo $TipoReporte ?></h3>
					<table class="table table-bordered table-sm ma-0">
						<tr align="center" style="font-weight: bold; color: #000; background: #9c9c9c;">
							<th>Fecha</th>
							<th>Fecha Venc.</th>
							<th>Días</th>
							<th>Tipo</th>
							<th>Suc.</th>
							<th>Doc. Nro.</th>
							<th>Nombre ó Razón Social</th>
							<th>Sub Total</th>
							<th>Iva</th>
							<th>Total</th>
							<th>Total Aplica</th>
							<th>Acción</th>
						</tr>


						<?php
						    $consulta = " SELECT * from ( Select format(Dc.Fecha,'dd/MM/yy') Fecha, format(DC.FechaVto,'dd/MM/yy') FechaV,  DATEDIFF(day, getdate(), dc.fechaVto) Dias, DC.TipoDocumentoID TipoDoc, 
                                Dc.SucursalId SucID, Dc.Numero DocN, c.RazonSocial, DC.Total Total, Dc.MontoAplicar TotalAplica, 
                                DC.SubTotal, DC.Iva, dc.Descripcion obs, DC.cprasDocumentoId DocID
                                From cprasDocumentosCabecera DC left join Proveedores C on dc.ProveedorId = c.ProveedorId
                                WHERE dc.EstadoDocumentoId = 1 and dc.Total >0 AND dc.Fecha>=CONVERT(DATETIME,'1900-1-1',101) 
                                    AND (dc.TipoDocumentoId LIKE 'FC%' OR dc.TipoDocumentoId LIKE 'ND%')  ) T1
							    where " . $Cond . " order by Dias asc";

                                $cad = "sqlsrv:Server=" . $ServerName . "; Database=" . $Base;
                                $conex = new PDO($cad,$User,$Passw);

                                $rSql = $conex->prepare($consulta);
                                $rSql->execute();

							//$ejecutar = sqlsrv_query($con, $consulta);
  							
							$j = 0;
							$BackColor = ['#FDFEFE','#FFFF66','#CC0000'];
							while($fila = $rSql->fetch()) {
								$Fecha = $fila['Fecha'];
								$FechaV= $fila['FechaV'];
								$TipoD = $fila['TipoDoc'];
								$SucID = $fila['SucID'];
								$DocN = $fila['DocN'];
								$DocID = $fila['DocID'];
								$Razon = $fila['RazonSocial'];
								$SubTotal = $fila['SubTotal'];
								$Iva = $fila['Iva'];
								$TotalF = $fila['Total'];
								$TotalFA = $fila['TotalAplica'];
								$Obs = $fila['obs'].'';
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
							<td bgcolor="<?php echo $BackColor[$Bkc]; ?>" style="color: #000" ><?php echo $Fecha; ?></td>
							<td bgcolor="<?php echo $BackColor[$Bkc]; ?>" style="color: #000"><?php echo $FechaV?></td>
							<td bgcolor="<?php echo $BackColor[$Bkc]; ?>" style="color: #000"><?php echo $Dias ?></td>
							<td bgcolor="<?php echo $BackColor[$Bkc]; ?>" style="color: #000"><?php echo $TipoD ?></td>
							<td bgcolor="<?php echo $BackColor[$Bkc]; ?>" style="color: #000"><?php echo $SucID ?></td>
							<td bgcolor="<?php echo $BackColor[$Bkc]; ?>" style="color: #000"><?php echo $DocN ?></td>
							<td bgcolor="<?php echo $BackColor[$Bkc]; ?>" style="color: #000" align="left"><?php echo $Razon ?></td>
							<td bgcolor="<?php echo $BackColor[$Bkc]; ?>" style="color: #000" align="right"><?php echo number_format($SubTotal,2,",","."); ?></td>
							<td bgcolor="<?php echo $BackColor[$Bkc]; ?>" style="color: #000" align="right"><?php echo number_format($Iva,2,",","."); ?></td>
							<td bgcolor="<?php echo $BackColor[$Bkc]; ?>" style="color: #000" align="right"><?php echo number_format($TotalF,2,",","."); ?></td>
							<td bgcolor="<?php echo $BackColor[$Bkc]; ?>" style="color: #000" align="right"><?php echo number_format($TotalFA,2,",","."); ?></td>
							<td style="color: blue;">
								<?php 
									$scad = "('ProductoC.php', " . $DocID . ", " . $DocN . ", '" . $TipoD . "', '" . $Obs . "', '"  . $Razon ."')";
									$cad = 'Detalle';
									echo '<span><a onclick="DetalleFactP' . $scad . '" id="'. $DocID .'" data-toggle="modal" data-target="#Facturas">' . $cad . '</a></span>' ; 
								?>
							</td> 
						</tr>

						<?php } ?>

					</table>
			</form>

			<div class="modal fade" id="DxFPModal" tabindex="-1" role="dialog" aria-labelledby="Facturas" aria-hidden="true">
                <div class="modal-dialog modal-xl" >
                    <div class="modal-content"> 
                        <div class="modal-header text-center">
                            <h2 id="TituloDoc">Documento :</h2>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body ml-0 pl-0"><p  id="contenidoFact" class="ml-0 pl-0"></p></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
	</div>
	
	<script type="text/javascript">
            function cambiarcontFact(pagina, doc_id, doc_n, TipoD, Observ, DocA) {
                       $("#contenidoFact").load(pagina,{detalle: doc_id, DocN: doc_n, TipoDoc: TipoD, ObservF: Observ, Doc_A: DocA});
            }

            function DetalleFactP(Pag, Doc_ID, Doc_N, Tipo_D, Obs, Razon) {
                 
                // $("#contenido2").load(pag,{ClienteID: Cod, NameC: Razon});
                var ModalFactura = new bootstrap.Modal(DxFPModal, {}).show();
		        let lPagF = Pag
		        let lDocID = Doc_ID
				TituloDoc.innerHTML = 'Proveedor : ' + Razon
                let lDoc_N = Doc_N
				let lTipo_D = Tipo_D
				let lObs = Obs
			
                let xx = cambiarcontFact(lPagF, lDocID, lDoc_N, lTipo_D, lObs, 0);
				
            }
        </script>
		<script src="../vendor/jquery/jquery.min.js"></script>
        <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>


 


