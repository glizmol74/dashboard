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

			<h1> Relación Cuentas X Pagar </h1>

		     	<table class="table table-bordered table-responsive">
					<tr>
            <td>Proveedor Id</td>
            <td>Cuit</td>
						<td>Razón Social</td>
						<td>Total Deuda</td>
                        <td>Detalle</td>
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
            <td><a href="javascript:cambiarcont('DetalleCxP.php', <?php echo "'" . $sCod . "'" ?>,<?php echo "'" . $sName . "'" ?>)">Detalle</a></td>  
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