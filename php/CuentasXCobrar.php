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
  if(isset($_GET['detalle'])){
  	$Cliente_Id = $_GET['detalle'];
	  $pagUrl = $_GET['Pagina'];
  }else {
	$Cliente_Id = '-';
	$ClienteName = '';
	$MesCon = '';
  }

?>
<meta charset="UTF-8">
<html> 	
	<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Releción Cuentas X Cobrar</title>

 <link href="../html/pedido.css" rel="stylesheet">
 <link data-n-head="1" rel="icon" type="image/x-icon" href="../favicon.ico">
 <link href="../css/bootstrap.min.css" rel="stylesheet">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript">
            function cambiarcont(pagina, ClienteID, ClienteN) {
                       $("#contenido2").load(pagina,{ClienteID: ClienteID, NameC: ClienteN});
            }
</script>
</head>
<body>
<div id="general">
 <!-- <div class="col-md-8 col-md-offset-2"> -->
	    
		<form method="POST" action="">

			<h1> Relación Cuentas X Cobrar </h1>

		     	<table class="table table-bordered table-responsive">
					<tr>
                        <td>Cliente Id</td>
						<td>Razón Social</td>
						<td>Total Deuda</td>
                        <td>Detalle</td>
					</tr>


					<?php
            include ("../../config.php");
            $cad = "sqlsrv:Server=" . $ServerName . "; Database=" . $Base;
            $conex = new PDO($cad,$User,$Passw);

            $consulta = "select sum(case when Dias <=0 then TotalAplica else 0 end) Vencida,
             sum(case when Dias > 0 and Dias <10 then TotalAplica else 0 end) ProxVencer,
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
              $TotalCxC = $Vencida + $ProxVencer + $ProxVencer;
            }

            
                   $consulta = "select ClienteId Cod, RazonSocial NameC, Saldo Total 
                         from Clientes
                         where saldo > 0
                         order by Saldo desc";
                    $ejecutar = sqlsrv_query($con, $consulta);

                    $i = 1;
                    while($fila = sqlsrv_fetch_array($ejecutar)){
                         $sCod = $fila['Cod'];
                         $sName = $fila['NameC'];
                         $sTotal = $fila['Total'];
                         $i++;
                  ?>

					<tr align="center">
                      <td><?php echo $sCod ?></td>
                      <td align="Left"><?php echo $sName; ?></td>
                      <td align="right"><?php echo  '<span style="color:#040404">'.number_format($sTotal,2,",",".").'</span>'; ?></td>
                      <td><a href="javascript:cambiarcont('DetalleCxC.php', <?php echo "'" . $sCod . "'" ?>,<?php echo "'" . $sName . "'" ?>)">Detalle</a></td>  
					</tr>

				<?php } ?>

				</table>
				
				
			<div class="form-group">
			<?php echo "<br />" ?>
				<input type="submit" name="Regresar" class="btn btn-warning" value="REGRESAR">
			</div>

			
		</form>
 <!-- </div> -->
  <div id="contenido2"></div>
</div>
<?php
 if (isset($_POST['Regresar'])){
 	echo "<script>window.open('dashboard.php','_self')</script>";
 }

?>
</body>
</html>