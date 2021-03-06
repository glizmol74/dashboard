<?php
  session_start();
  if($_SESSION["s_Usuario"] === null){
	  header("Location: ../../index.php");
  }else if($_SESSION["s_Nivel"] > 2 ){
	$Url = trim($_SESSION["s_Url"]);
	header("Location: ./$Url");
  }
?>
<!DOCTYPE html> 
<?php
  include("conexion_sis.php");
  include ("../../config.php");
  

  if(isset($_GET['detalle'])){
  	$Cliente_Id = $_GET['detalle'];
	  $pagUrl = $_GET['Pagina'];
  }else {
	$Cliente_Id = '-';
	$ClienteName = '';
	$MesCon = '';
  }
    $Report = 0;
    $cad = "sqlsrv:Server=" . $ServerName . "; Database=" . $Base;
    $conex = new PDO($cad,$User,$Passw);

    $consulta = "select sum(case when Dias <=0 then TotalAplica else 0 end) Vencida,
        sum(case when Dias > 0 and Dias <=10 then TotalAplica else 0 end) ProxVencer,
        sum(case when Dias > 10 then TotalAplica else 0 end) PorVencer 

        from (
            Select Dc.Fecha, DC.FechaEntrega FechaV,  DATEDIFF(day, getdate(), dc.fechaEntrega) Dias, DC.TipoDocumentoID TipoDoc, 
            Dc.SucursalId SucID, Dc.Numero DocN, DC.Total Total, Dc.MontoAplicar TotalAplica, 
            DC.SubTotal, DC.Iva, dc.Descripcion obs
            From DocumentosCabecera DC
            WHERE dc.EstadoDocumentoId = 1 and dc.Total >0 AND dc.Fecha>=CONVERT(DATETIME,'1900-1-1',101) 
            AND (dc.TipoDocumentoId LIKE 'FC%' OR dc.TipoDocumentoId LIKE 'ND%')  ) T1";

    $rSql = $conex->prepare($consulta);
    $rSql->execute();
        
    if($FilaR = $rSql->fetch()) {
        $Vencida = $FilaR['Vencida'] + 0;
        $PorVencer = $FilaR['PorVencer']+0;
        $ProxVencer = $FilaR['ProxVencer']+0;
        $TotalCxC = $Vencida + $PorVencer + $ProxVencer;
    }
    $lFecha = date("Y-m-d");
    $sFecha1 = date("d/m/Y",strtotime($lFecha. "+ 10 day"));
    $sFecha = date("d/m/Y",strtotime($lFecha. "+ 11 day"));
?>
<html> 	
	<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title>Releción Cuentas X Cobrar</title>

        <link href="../html/pedido.css" rel="stylesheet">
        
        <link data-n-head="1" rel="icon" type="image/x-icon" href="../../favicon.ico">
        <!-- <link href="../css/bootstrap.min.css" rel="stylesheet"> -->
        <link href="../css/sb-admin-2.min.css" rel="stylesheet">
         <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
        <script type="text/javascript">
                function cambiarcont(pagina, ClienteID, ClienteN) {
                        $("#contenido2").load(pagina,{ClienteID: ClienteID, NameC: ClienteN});
                }
                function cambiarReporteCxC(pagina, Tipo) {
                    $("#CuentasXcobrar").load(pagina, {TipoR: Tipo})
                }
        </script>
    </head>
    <body>
        <div id="content">
            <div class="continer-fluid">
                <div class="col-xl-12 col-md-12">
                    <div class="card border-success shadown mb-3" >
                        <div class="card-header ma-0 py-2 d-flex flex-row align-items-center justify-content-between">
                            <table class="table table-bordered table-striped mt-2">
                                <tr align="center">
                                    <td>Total CxC </td>
                                    <td>CxC Vencida</td>
                                    <td>CxC X Vencer Hasta el <?php echo $sFecha1?> </td>
                                    <td>CxC X Vencer A Partir del <?php echo $sFecha ?></td>
                                </tr>
                                <tr align="center">
                                    <td><a href="javascript:cambiarReporteCxC('ResumenCxC.php', 4)"><?php echo number_format($TotalCxC,2,",",".") ?></a></td>
                                    <td><a href="javascript:cambiarReporteCxC('DetalleCxCTip.php', 1)"> <?php echo number_format($Vencida,2,",",".") ?></a></td>
                                    <td><a href="javascript:cambiarReporteCxC('DetalleCxCTip.php', 2)"><?php echo number_format($ProxVencer,2,",",".") ?></a></td>
                                    <td><a href="javascript:cambiarReporteCxC('DetalleCxCTip.php', 3)"><?php echo number_format($PorVencer,2,",",".") ?></a></td>
                                    
                                </tr>
                            </table>
                             
                        </div>
                        <a type="button" class="btn btn-warning" onclick="window.open('dashboard.php','_self')">Regresar</a>
                        <div class="card-body text-dark ma-0 pa-0" id="CuentasXcobrar"></div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>