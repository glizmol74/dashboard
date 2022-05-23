
<?php
  session_start();
  if($_SESSION["s_Usuario"] === null){
	  header("Location: ../../index.php");
  }else if($_SESSION["s_Nivel"] > 2 ){
	$Url = trim($_SESSION["s_Url"]);
	header("Location: ./$Url");
  }
  include("conexion_sis.php");
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
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../css/sb-admin-2.min.css" rel="stylesheet">
        
    </head>
    <body>
        <div class="col-md-12 col-md-offset-0">
            <form method="POST" action="">
                <h3><?php echo $TipoReporte ?></h3>

                <table class="table table-bordered table-striped">
                    <tr align="center" style="font-weight: bold; color: #000; background: #9c9c9c;">
                        <th>Proveedor Id</th>
                        <th>Cuit</th>
                        <th>Razón Social</th>
                        <th>Total Deuda</th>
                        <th>Detalle</th>
                    </tr>

                    <?php
                        $consulta = "select P.ProveedorId Cod, P.RazonSocial NameC, DC.Saldo Total, P.Cuit 
                        from Proveedores P join 
                          (select dc.ProveedorId as Prov, sum(dc.MontoAplicar) as Saldo
                          from cprasDocumentosCabecera DC 
                          where MontoAplicar <> 0 and (TipoDocumentoId like 'FC%' or TipoDocumentoId like 'ND%' )
                          group by dc.ProveedorId) DC on dc.Prov = p.ProveedorId
                              order by DC.Saldo desc";
                        $ejecutar = sqlsrv_query($con, $consulta);

                        $i = 1;
                        while($fila = sqlsrv_fetch_array($ejecutar)){
                            $sCod = $fila['Cod'];
                            $sName = $fila['NameC'];
                            $sTotal = $fila['Total'];
                            $sCuit = $fila['Cuit'];
                            $i++;
                    ?>

                            <tr align="center">
                                <td><?php echo $sCod ?></td>
                                <td><?php echo $sCuit ?></td>
                                <td align="Left"><?php echo $sName; ?></td>
                                <td align="right"><?php echo  '<span style="color:#040404">'.number_format($sTotal,2,",",".").'</span>'; ?></td>
                                <!-- <td><a href="javascript:cambiarcont('DetalleCxC.php', <?php echo "'" . $sCod . "'" ?>,<?php echo "'" . $sName . "'" ?>)">Detalle</a></td>   -->
                                <td style="color: blue;">
                                    <?php 
                                        $scad = "('DetalleCxP.php', '" . $sCod . "', '" . $sName . "' )";
                                        $cad = '<span><a onclick="DetalleCxPM' . $scad . '" id="'. $sCod .'" data-toggle="modal" data-target=".resumen-xl">Detalle</a></span>';
                                        echo '<span><a onclick="DetalleCxPM' . $scad . '" id="'. $sCod .'" data-toggle="modal" data-target="#x0">' . $cad . '</a></span>' ; 
                                    ?>
                                </td>  
                            </tr>

                    <?php } ?>

                </table>
            </form>
                
            <div class="modal fade" id="CxPModal" tabindex="-1" role="dialog" aria-labelledby="ZZxx" aria-hidden="true">
                <div class="modal-dialog modal-xl" >
                    <div class="modal-content"> 
                        <div class="modal-header text-center">
                            <h3>Relación Facturas a Pagar</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body ml-0 pl-0"><p  id="contenido2" class="ml-0 pl-0"></p></div>
                        <div class="modal-body ml-0 pl-0"><p  id="contenidoFP" class="ml-0 pl-0"></p></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            function cambiarcontC(pagina, ClienteID, ClienteN) {
                    $("#contenido2").load(pagina,{ClienteID: ClienteID, NameC: ClienteN});
                    return 'xxx';
            }

            function DetalleCxPM(Pag, Cod, Razon) {
                
                // $("#contenido2").load(pag,{ClienteID: Cod, NameC: Razon});
                var ModalPedido = new bootstrap.Modal(CxPModal, {}).show();
                contenidoFP.innerHTML = ''
                let lPag = Pag
                let lCod = Cod
                let lRazon = Razon
                let x = cambiarcontC(lPag, lCod, lRazon);
            }
        </script>
        <!-- Bootstrap core JavaScript-->
        <script src="../vendor/jquery/jquery.min.js"></script>
        <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    </body>
</html>