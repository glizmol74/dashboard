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
  if(isset($_POST['TipoR'])){
  	$TipoR = $_POST['TipoR'];
	  
  }else {
	$TipoR = 'X';
	
  }

?>
<meta charset="UTF-8">
<html> 	
	<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    

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
            <h3> Releción Artículos Vendidos </h3>
        </div>
        <div class="my-2">
            <button class="btn btn-success" onclick="exportTablaToExcel('Resumen Articulos','#IdResumen')">Exportar Excel</button>
        </div>
    </div>
    

    <table class="table table-bordered table-striped table-sm " id="IdResumen">
      <tr align="center" style="color: #000; font-weight: bold; background: #9c9c9c;">
        <td>Mes</td>
        <td>Unidades</td>
        <td>Costo</td>
        <td>Precio </td>
        <td>Costo Px</td>
        <td>Precio Px</td>
        <td>Detalle</td>
      </tr>


      <?php
          $consulta = "set dateformat YMD
            declare @FechaInio as datetime = format(getdate(),'yyyy-MM-01 00:00:00')
            declare @Ult as varchar(2) =  format(EOMONTH( @FechaInio ),'dd')
            declare @Antes as varchar(5) = format(EOMONTH( @FechaInio -1),'yy-MM')
            declare @FecFin as datetime = format(getdate(),'yyyy-MM-' + @Ult + ' 23:59:59')
            declare @FactMesActual varchar(10) = 'Fact-' + format(@fechaInio, 'yy-MM')
            declare @FechaA as datetime = format(EOMONTH( @FechaInio -1),'yyyy-MM-dd')
               
            SELECT  Fec1, Fec2, Cant Unds, Costo, Precio, (Precio/Tcant) PrecioPx, (Costo/Tcant) CostoPX
            from (  
                select distinct Fec1, Fec2, sum(Total) Cant, sum(s1) Precio, sum(s2) Costo,  (CASE WHEN sum(Total) > 0 THEN SUM(Total) ELSE 1 END) TCant, TipoReporte  
                from (
                    select SUBSTRING(A.tiporeporte,6,2) Fec1, SUBSTRING(A.tiporeporte,9,2) Fec2, a.ProductoId, a.Descripcion, 
                        (CASE WHEN A.TipoReporte = 'RemPxV' and d.CondV <> 10 and D.Estado <>3 THEN  sum(a.TotalCantidad ) else A.TotalCantidad END )  Total,
                        (CASE WHEN A.TipoReporte = 'RemPxV' and d.CondV <> 10 and D.Estado <>3 THEN  sum(s1) ELSE sum(s1) END )  S1, 
                        (CASE WHEN A.TipoReporte = 'RemPxV' and d.CondV <> 10 and D.Estado <>3 THEN  sum(s2) ELSE a.s2 END )  S2,
        
                        (CASE WHEN (A.TipoReporte  = @FactMesActual or ( A.TipoReporte = 'RemPxV' and A.FechaE between @FechaInio and @FecFin )) and a.ClasificacionProdID in (34) THEN sum(a.totalcantidad) ELSE 0 END) Dia0,
                        
                        (CASE WHEN (A.TipoReporte  = @FactMesActual or ( A.TipoReporte = 'RemPxV' and A.FechaE between @FechaInio and @FecFin )) and a.ClasificacionProdID in (34)  THEN sum(a.S1 ) ELSE 0 END) Precio,
                        (CASE WHEN (A.TipoReporte  = @FactMesActual or ( A.TipoReporte = 'RemPxV' and A.FechaE between @FechaInio and @FecFin )) and a.ClasificacionProdID in (34)  THEN sum(a.S2 ) ELSE 0 END) Costo,
                            A.TipoReporte
                    from AuxPed A left join Documento D on a.DocumentoId = d.DocumentoId 
                    where (A.TipoReporte like 'Fact-%' ) and a.ClasificacionProdID = 34
                    group by a.ProductoId, a.Descripcion, a.TipoReporte, d.CondV, d.Estado, d.FechaP, d.FechaE, a.TotalCantidad, a.s1, a.s2, a.FechaE,
                            a.ClasificacionProdID) T1 
                group by Fec1, Fec2, TipoReporte ) T2
                Order By Fec1 Desc, Fec2 Desc ";

          $ejecutar = sqlsrv_query($con, $consulta);

          $i = 1;
          $TotalVentas = 0;
          while($fila = sqlsrv_fetch_array($ejecutar)){
            $sYear = $fila['Fec1'];
            $sMes = $fila['Fec2'];
            $sUnds = $fila['Unds'];
            $nMes = intval($sMes,10);
            $MesL = $MesLetra[$nMes] . '-' . $sYear ;
            $Reporte = 'Fact-' . $sYear . '-' . $sMes;
            $Costo = $fila['Costo'];
            $Precio = $fila['Precio'];
            $CostoPx = $fila['CostoPX'];
            $PrecioPx = $fila['PrecioPx'];
            $TotalVentas+= $sUnds;
            $i++;
      ?>

      <tr align="center">
        <td><?php echo $MesL ?></td>
        <td align="right"><?php echo  '<span style="color:#040404">'.number_format($sUnds,0,",",".").'</span>'; ?></td>
        <td align="right"><?php echo  '<span style="color:#040404">'.number_format($Costo,2,",",".").'</span>'; ?></td>
        <td align="right"><?php echo  '<span style="color:#040404">'.number_format($Precio,2,",",".").'</span>'; ?></td>
        <td align="right"><?php echo  '<span style="color:#040404">'.number_format($CostoPx,2,",",".").'</span>'; ?></td>
        <td align="right"><?php echo  '<span style="color:#040404">'.number_format($PrecioPx,2,",",".").'</span>'; ?></td>
        
        <td style="color: blue;">
          <!-- <a href="javascript:cambiarcont('LitrosVentas.php', <?php echo "'" . $Reporte . "'" ?>,<?php echo "'" . $MesL . "'" ?>,<?php echo "'" . $sLitros . "'" ?>)">Detalle</a> -->
          <?php
            $scad = "('LitrosVentas.php', '" . $Reporte . "', '" . $MesL . "', '" . $TotalVentas . "',2)" ;
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
              
              <button class="btn btn-success" onclick="exportTablaToExcel('Articulos','#IdProductos')">Exportar Excel</button>
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
        TituloLts.innerHTML = 'Artículos ' 
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