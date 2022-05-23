<?php 
    include("conexion_sis.php");
    session_start();
    if($_SESSION["s_Usuario"] === null){
        header("Location: ../../index.php");
    }else if($_SESSION["s_Nivel"] > 2 ){
        $Url = trim($_SESSION["s_Url"]);
        header("Location: ./$Url");
    }
    if(isset($_POST['DetalleR'])){
		$MesConsulta = $_POST['DetalleR'];
		$MesN = intval(substr($MesConsulta,-2,2));
		$FecMesConsula = '20' . substr($MesConsulta,-5,5) . '-01 00:00:00';
        $pagUrl = $_POST['Pagina'];
	}else {
        $MesConsulta = '';
        $MesN = 0;
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
    </head>
    <body>
        <div id="content">
            <form method="POST" action="">
                <div class="d-flex justify-content-between">
                    <div class="alert alert-info h4 font-weight-bold" role="alert">
                        Resumen de Facturación X Cliente del Mes de <?php echo $MesLetra[$MesN] ?> del 20<?php echo substr($MesConsulta,-5,2); ?> 
                    </div>
                    <div class="my-2">
                        <button class="btn btn-success" onclick="exportTablaToExcel('Resumen Facturacion X Cliente <?php echo $MesLetra[$MesN] ?> del 20<?php echo substr($MesConsulta,-5,2); ?>','#IDResumenFV')">Exportar Excel</button>
                    </div>
                </div>
                    <table class="table table-bordered table-striped table-sm" id="IDResumenFV">
                        <tr align="center" style="font-weight: bold; color: #000; background: #9c9c9c;" >
                            <td>Cod Cliente</td>
                            <td>Nombre o Razón Social</td>
                            <td>Facturado</td>
                            <td>Costo</td>
                            <td>CMg  $</td>
                            <td>CMg %</td>
                            <td>PartC</td>
                            <td>Detalle</td>
                        </tr>

                        <?php
                    
                            $FechaFin = new datetime($FecMesConsula);
                            $FechaFin->modify('last day of this month');
                            $UltimoDia =  $FechaFin->format('Y/m/d') . ' 23:59:59';
                            $consulta = "set dateformat YMD
                                declare @FechaInio as datetime = '$FecMesConsula'
                                declare @FecFin as datetime = '$UltimoDia'
                                select  idc ClienteId, c.RazonSocial ClienteName, sum(subtotal) ValorFac, sum(costoFac) CostoFac,
                                    (select sum( (case when t.TipoDocumentoID like 'NC%' then -1 else 1 end ) * t.Subtotal) from DocumentosCabecera T  
                                        where t.Fecha between  @FechaInio and  @FecFin and t.EstadoDocumentoId <> 3 
                                            AND (t.TipoDocumentoId like 'FC%' or t.TipoDocumentoId like 'NC%')) TotalV
                                from (
                                    select dc.ClienteId Idc, (case when dc.TipoDocumentoID like 'NC%' then -1 else 1 end ) * dc.Subtotal SubTotal, 
                                        sum( (case when dc.TipoDocumentoID like 'NC%' then -1 else 1 end ) * zz.gmPrecioCompra * zz.Cantidad ) CostoFac
                                    from DocumentosCabecera DC join ZZDocumentosDetalle zz on dc.DocumentoId = zz.DocumentoId
                                    where dc.Fecha between  @FechaInio and  @FecFin AND (dc.TipoDocumentoId like 'FC%' or dc.TipoDocumentoId like 'NC%')
                                        and dc.EstadoDocumentoId <> 3
                                    group by dc.ClienteId, dc.Subtotal, dc.TipoDocumentoId, dc.numero ) T1 join Clientes C on t1.Idc = c.ClienteID
                            
                                group by idc , c.RazonSocial
                                order by 3 desc";

                            $ejecutar = sqlsrv_query($con, $consulta);

                            $i = 0;
                            while($fila = sqlsrv_fetch_array($ejecutar)){
                            $ClienteID = $fila['ClienteId'];
                            $ClienteN = $fila['ClienteName'];
                            $ValorFac = $fila['ValorFac'];
                            $CostoFac = $fila['CostoFac'];
                            $CMg = $ValorFac - $CostoFac;
                            if ( $ValorFac == 0){
                                $CMgPorc = 0;
                            }else {  
                                $CMgPorc = $CMg / $ValorFac * 100;
                            }
                            $Partc = $ValorFac / $fila['TotalV'] * 100;
                            $i++;
                        ?>

                        <tr align="center">
                            
                            <td><?php echo "'" . $ClienteID ?></td>
                            <td align="left"><?php echo $ClienteN ?></td>
                            <td align="right"><?php echo  '<span style="color:#040404">'.number_format($ValorFac,2,",",".").'</span>'; ?></td>
                            <td align="right"><?php echo  '<span style="color:#040404">'.number_format($CostoFac,2,",",".").'</span>'; ?></td>
                            <td align="right"><?php echo  '<span style="color:#040404">'.number_format($CMg,2,",",".").'</span>'; ?></td>
                            <td align="right"><?php echo  '<span style="color:#040404">'.number_format($CMgPorc,2,",",".").'</span>'; ?></td>
                            <td align="right"><?php echo  '<span style="color:#040404">'.number_format($Partc,2,",",".").'</span>'; ?></td>
                            <td style="color: blue;"> 
                                <?php
                                    $scad = "('Factura.php', '" . $ClienteID . "', '" . $MesConsulta . "', '" . $ClienteN . "', '" . $pagUrl . "')";
                                    $cad = 'Detalle';
                                    echo '<span><a onclick="DetalleFact' . $scad . '" id="' . $ClienteID . '" data-toggle="modal" data-target="#FacturasV">' . $cad . '</a></span>' 
                                ?>
                                <!-- <a href="Factura.php?detalle=<?php echo $ClienteID ; ?>&MesC=<?php echo $MesConsulta ; ?>&ClienteN=<?php echo $ClienteN ; ?>&Pagina=<?php echo $pagUrl ; ?>">Detalle</a> -->
                            </td>
                        </tr>

                        <?php } ?>
                    </table>
                </div>
            </form>

            <div class="modal fade" id="FactModal" tabindex="-1" role="dialog" aria-labelledby="FacturasV" aria-hidden="true">
                <div class="modal-dialog modal-xl" >
                    <div class="modal-content"> 
                        <div class="modal-header text-center">
                            <h2 id="TituloDoc">Documento :</h2>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body ml-0 pl-0"><p  id="IdcontenidoFact" class="ml-2 pl-2"></p></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
                function cambiarcontFactV(pagina, doc_id, mes_n, Razon, pag) {
                        $("#IdcontenidoFact").load(pagina,{detalle: doc_id, MesC: mes_n, ClienteN: Razon, Pagina: pag});
                }

                function DetalleFact(Pag, Doc_ID, Mes_N, Razon, PagG) {
                    
                    // $("#contenido2").load(pag,{ClienteID: Cod, NameC: Razon});
                    var ModalFactura = new bootstrap.Modal(FactModal, {}).show();
                    let lPagF = Pag
                    let lDocID = Doc_ID
                    TituloDoc.innerHTML = 'Cliente : ' + Doc_ID + ' | ' + Razon
                    let lMes = Mes_N
                    let lRazon = Razon
                    let lPagG = PagG
                
                    let xx = cambiarcontFactV(lPagF, lDocID, lMes, lRazon, lPagG);
                    
                }
        </script>
        <script src="../js/jquery.min.js"></script>
        <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="../js/jquery.table2excel.min.js"></script>
        <script src="../js/exportarExcel.js"></script>
    </body>
</html>