<?php
  session_start();
  if($_SESSION["s_Usuario"] === null){
	  header("Location: ./index.php");
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
	  $ClienteName = $_GET['ClienteN'];
	  $MesCon = $_GET['MesC'];
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
    
    <title>Releción Litros / Unidades</title>

 <link data-n-head="1" rel="icon" type="image/x-icon" href="../../favicon.ico">
 <link href="../css/sb-admin-2.min.css" rel="stylesheet">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
 <script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
 <script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>

</head>
<body>
<div id="general">
  <form method="POST" action="">
    <div class="d-flex justify-content-between">
        <div >
            <h3> Relación Litros / Unidades Vendidas  </h3>
        </div>
        <div class="my-2">
            <button class="btn btn-success" onclick="exportTablaToExcel('Resumen Liquidos','#IdResumen')">Exportar Excel</button>
        </div>
    </div>

    <table class="table table-bordered table-striped table-sm " id="IdResumen">
      <tr align="center" style="color: #000; font-weight: bold; background: #9c9c9c;">
        <td>Mes</td>
        <td>Litros</td>
        <td>Unidades</td>
        <td>Costo</td>
        <td>Precio </td>
        <td>Costo Px</td>
        <td>Precio Px</td>
        <td>Detalle</td>
      </tr>


      <?php
          $consulta = "select Fec1, Fec2, isnull(sum(Unds),0) Unds, isnull(sum(litros),0) Litros, 
            ISNULL(sum(CostoL), 0) CostoL, ISNULL(sum(PrecioL) , 0) PrecioL 
            from ( SELECT (CASE WHEN TipoReporte like 'Fact-%' THEN SUBSTRING(A.tiporeporte,6,2) ELSE format(FechaE,'yy') END ) Fec1,
                (CASE WHEN TipoReporte like 'Fact-%' THEN SUBSTRING(A.tiporeporte,9,2) ELSE format(FechaE,'MM') END ) Fec2,
                  (CASE WHEN A.ClasificacionProdID in (12,16,17) and f.FormSemi <> 0 THEN case when a.ClasificacionProdID =17 THEN (f.Cantidad*a.TotalCantidad) else a.TotalCantidad END ELSE 0 END) Litros,
                  (CASE WHEN A.ClasificacionProdID in (12,16,17) and f.FormSemi <> 0 THEN (A.TotalCantidad) ELSE 0 END) Unds,
                  (CASE WHEN A.ClasificacionProdID in (12,16,17) and f.FormSemi <> 0 THEN (A.S2) ELSE 0 END) CostoL,
                  (CASE WHEN A.ClasificacionProdID in (12,16,17) and f.FormSemi <> 0 THEN (A.s1) ELSE 0 END) PrecioL 
                      from AuxPed A left join DesgloseFormula F on f.FormulaID = a.FormulaID and f.FormSemi <> 0
            where  TipoReporte like 'Fact-%' or TipoReporte = 'RemPxV' ) T1
            group by Fec1, Fec2
            order by Fec1 desc, Fec2 desc ";

          $ejecutar = sqlsrv_query($con, $consulta);

          $i = 1;
          while($fila = sqlsrv_fetch_array($ejecutar)){
            $sYear = $fila['Fec1'];
            $sMes = $fila['Fec2'];
            $sLitros = $fila['Litros'];
            $sUnds = $fila['Unds'];
            $nMes = intval($sMes,10);
            $MesL = $MesLetra[$nMes] . '-' . $sYear ;
            $Reporte = 'Fact-' . $sYear . '-' . $sMes;
            $CostoL = $fila['CostoL'];
            $PrecioL = $fila['PrecioL'];
            $CostoPx = $CostoL / $sLitros ;
            $PrecioPx = $PrecioL / $sLitros ;
            $i++;
      ?>

      <tr align="center">
        <td><?php echo $MesL ?></td>
        <td align="right"><?php echo  '<span style="color:#040404">'.number_format($sLitros,2,",",".").'</span>'; ?></td>
        <td align="right"><?php echo  '<span style="color:#040404">'.number_format($sUnds,0,",",".").'</span>'; ?></td>
        <td align="right"><?php echo  '<span style="color:#040404">'.number_format($CostoL,2,",",".").'</span>'; ?></td>
        <td align="right"><?php echo  '<span style="color:#040404">'.number_format($PrecioL,2,",",".").'</span>'; ?></td>
        <td align="right"><?php echo  '<span style="color:#040404">'.number_format($CostoPx,2,",",".").'</span>'; ?></td>
        <td align="right"><?php echo  '<span style="color:#040404">'.number_format($PrecioPx,2,",",".").'</span>'; ?></td>
        
        <td style="color: blue;">
          <!-- <a href="javascript:cambiarcont('LitrosVentas.php', <?php echo "'" . $Reporte . "'" ?>,<?php echo "'" . $MesL . "'" ?>,<?php echo "'" . $sLitros . "'" ?>)">Detalle</a> -->
          <?php
            $scad = "('LitrosVentas.php', '" . $Reporte . "', '" . $MesL . "', '" . $sLitros . "', 1)" ;
            $cad = "Detalle";
            echo '<span><a onclick="DetalleLitros' . $scad . '" id="'. $MesL .'" data-toggle="modal" data-target="#Facturas">' . $cad . '</a></span>';
          ?>
        </td>  
      </tr>

      <?php } ?>

    </table>
  </form>
 
  <div class="modal fade" id="LitrosModal" tabindex="-1" role="dialog" aria-labelledby="Litros" aria-hidden="true">
    <div class="modal-dialog modal-xl" >
        <div class="modal-content"> 
          <div class="modal-header text-center">
              <h2 id="TituloLts">Documento :</h2>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body ml-0 pl-0"><p  id="contenido2" class="ml-0 pl-0"></p></div>
          <div class="modal-footer">
              <button class="btn btn-success" onclick="exportTablaToExcel('Liquidos','#IdProductos')">Exportar Excel</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
    </div>
  </div>
  <div id="contenido3"></div>
</div>
  <script type="text/javascript">
      function cambiarcontLitros(pagina, TipoR, MesRepo, Tlitros, Report) {
                  $("#contenido2").load(pagina,{TipoRepo: TipoR, MesR: MesRepo, TLitrosM: Tlitros, Reporte: Report});
      }

      function DetalleLitros(Pag, Tipo_R, Mes_R, tLitros, Reporte) {
        var ModalLitros = new bootstrap.Modal(LitrosModal, {}).show();
        let lPagF = Pag
        let lMes_Repo = Mes_R
        TituloLts.innerHTML = 'Líquidos ' 
        let lTotalLitros = tLitros
        let lTipo_R = Tipo_R
        let lReporte = Reporte
        
        let xx = cambiarcontLitros(lPagF, lTipo_R, lMes_Repo, lTotalLitros, lReporte);
          
      }
      
  </script>
  <script src="../js/exportarExcel.js"></script>
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>