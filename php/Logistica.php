<?php
  include("conexion_sis.php");
  $IdCol = 0;
  if(isset($_POST['ClienteID'])){
      $ClienteId = $_POST['ClienteID'];
      $ClienteName = $_POST['NameC'];
  } else {
    $ClienteID = '';
    $ClienteName = 'No Autorizado';
  }

  if(isset($_REQUEST['xPos'])) {
    $IdCol = $_REQUEST['xPos'];
  } else {
    $IdCol = 0;
  }

  function CeldaModal($id) {
    echo '<script>' , 'Pedido(' . $id . ', 1, "AAA")', '</script>';
    echo $id;
    return $id;
  }


  $i=0;
  $Max = 7;
  $tMax = -1;
  $lFecha =  date("Y-m-d");
  $Rfecha = new datetime($lFecha);
  $Titulo = array();
  $RwC = array(array());
  while ( $i < $Max) {
      
      $dia = $Rfecha->format("N");
      if ( $dia == 7) {

      } else {
        $nMes =  $Rfecha->format("n");
        $Titulo[$i] = substr($DiaSemana[$dia],0,3) . ' ' . $Rfecha->format("d") . '-' . substr($MesLetra[$nMes], 0, 3);
        $nDias[$i] = $Rfecha->format("Y-m-d");
        $Pos[$i] = -1;
        $i++;
      }
      $lFecha = $Rfecha->format("Y-m-d");
      $sFecha = date("Y-m-d",strtotime($lFecha. "+ 1 day"));
      
      $Rfecha = new datetime($sFecha);
  }
  $Ultimo = $Rfecha->format("Y-m-d");

?>
<!DOCTYPE html>
<meta charset="UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">

<title>Logistica</title>


 <link data-n-head="1" rel="icon" type="image/x-icon" href="../../favicon.ico">
 <!-- <link href="../css/bootstrap.min.css" rel="stylesheet"> -->
 <link href="../css/sb-admin-2.min.css" rel="stylesheet">
 <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
 
<html>
    <body>
        <div id="content">
            <div class="col-md-11 col-md-offset-0 pa-0">
                    
                <form method="POST" action="">

                    <h3> <?php echo 'Detalle de Relación Cobranza de  : '. $MesLetra[$nMes] . '  del  '  . $Rfecha->format("Y") ?> </h3>

                    <table class="table table-bordered table-striped rows table-responsibe">
                        <tr>
                            <?php 
                                $i= 0;
                                while( $i < $Max) {
                                    ?>
                                    <td align="center" style="font-weight: bold;font-size: 1.2em; background: #9c9c9c; color:#FFFFFF"><?php echo $Titulo[$i++]; ?></td>
                                    
                                <?php } ?>
                        </tr>
                        <?php
                            $consulta = " set dateformat YMD
                            declare @Hoy as datetime = format(getdate(), 'yyyy-MM-dd')
                            declare @Ultimo as datetime = '$Ultimo'
                            select Id, Fecha, Codigo, RazonSocial, Tipo, Orden, Estado, Suc, Numero 
                            from logistica
                            where ( Fecha >= @Hoy and Fecha < @Ultimo ) or (Fecha < @Hoy and Estado = 0 ) 
                            order by Fecha, Orden";

                            $ejecutar = sqlsrv_query($con, $consulta);

                            $j = 0;
                           
                            $y = 0;
                            $Cont = $Pos;
                            $RwC[$j] = $Pos;
                            while($fila = sqlsrv_fetch_array($ejecutar)){
                                $Id[$j] = $fila['Id'];
                                $Fecha[$j] = $fila['Fecha'];
                                $Codigo[$j]= $fila['Codigo'];
                                $Razon[$j] = $fila['RazonSocial'];
                                $Tipo[$j] = $fila['Tipo'];
                                $Orden[$j] = $fila['Orden'];
                                $St[$j] = $fila['Estado'];
                                $SucID[$j] = $fila['Suc'];
                                $DocN[$j] = $fila['Numero'];
                                $sFecha = $Fecha[$j]->format("Y-m-d");
                                
                                if ( $sFecha <= $nDias[0]) {
                                    $y = $Pos[0] + 1;
                                    $RwC[$y][0] = $j;
                                    $Pos[0] = $y;
                                    #echo $sFecha . ' <<< ' . $nDias[0] . ' ';
                                } else {
                                    $sFecha = $Fecha[$j]->format("Y-m-d");
                                    for ($x=1; $x < $Max; $x++) { 
                                        
                                        if ( $sFecha == $nDias[$x]) {
                                            #echo $sFecha . ' == ' . $nDias[$x] . ' ';
                                            $y = $Pos[$x] + 1;
                                            $RwC[$y][$x] = $j;
                                            $Pos[$x] = $y;
                                            $x = $Max;
                                        }
                                    }

                                }
                                #echo $y . ' ';
                                if ($y > $tMax) {
                                    $tMax = $y;
                                    $RwC[$y+1] = $Cont;
                                }
                                $j++;
                            }
                            $y = 0;
                            while($y <= $tMax) {
                                $cad = ' - ';
                                $Tip = 1;
                                $Cod = ' ';
                                
                                ?>

                                <tr align="center"> <?PHP 
                                    for ($p=0; $p < $Max ; $p++) {
                                        $cad = ' - ';
                                        $Tip = 1;
                                        $Cod = ' '; 
                                        $z =   $RwC[$y][$p];
                                        if ( $z >= 0) {
                                            $cad = $Razon[$z];
                                            $Tip = $Tipo[$z];

                                            if ($Tip == 1 ) {
                                                ?>
                                                <td>
                                                    <?php 
                                                        //echo '<span style="font-weight: bold; color:#27AE60; "><a  onclick="Pedido(this.id,1,"$Razon[$z]")" id="'. $z .'" data-toggle="modal" data-target="#x0"><font color="#27AE60">' . $cad .'</font></a></span>'; 
                                                        $scad = "document.write('<?php $IdCol= CeldaModal( " . $z . "); ?>');";
                                                        $scad = "(this.id, 1, '" . $Razon[$z] . "')";
                                                        
                                                        echo '<span style="font-weight: bold; color:#27AE60; "><a  onclick="Pedido' .$scad . '" id="'. $z .'" data-toggle="modal" data-target="#x0"><font color="#27AE60">' . $cad .'</font></a></span>'; 
                                                        //echo '<span style="font-weight: bold; color:#27AE60; "><a  onclick="' .$scad . '" id="'. $z .'" data-toggle="modal" data-target="#x0"><font color="#27AE60">' . $cad .'</font></a></span>'; 
                                                        
                                                    ?>
                                                </td>
                                                <?php
                                            } else {
                                                ?>
                                                <td>
                                                    <?php 
                                                        echo '<span style="font-weight: bold; color:#FF0033">' . $cad .'</span>'; 
                                                    ?>
                                                </td>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <td><?php echo $cad; ?></td>
                                        <?php } 
                                    } $y++; ?>
                            
                                </tr>

                            <?php }
                            
                              ?>

                    </table>

                </form>
            </div>
            
            <!-- Modal  -->
            <div class="modal fade" id="PedidoModal" tabindex="-1" role="dialog" aria-labelledby="ZZxx" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content"> 
                        <?php 
                            $dom = new DOMDocument();
                            $dom->loadHTML('Logistica.php');
                           //$atr = $dom->getElementById('cadena').innerText;
                            $tMax = '9';
                        ?>
                        <div class="modal-header text-center">
                            <font color="#27AE60" ><h5 class="modal-title text-center" id="ModalTitulo"></h5></font>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body"><p id="cadena">3</p></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close <?php echo $tMax; ?></button>
                        </div>
                    </div>
                </div>
            </div>
          
            <script type="text/javascript">
                var xPos =0;
                function Pedido(Id, Col, Razon) {
                    cadena.innerHTML =  Id;
                    xPos = $('#cadena').text();
                    
                    var ModalPedido = new bootstrap.Modal(PedidoModal, {}).show();
                    
                    ModalTitulo.innerHTML = Razon + xPos;
                                   
                }
            </script>

                 

            <!-- Bootstrap core JavaScript-->
            <script src="../vendor/jquery/jquery.min.js"></script>
            <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        </div>
    </body>
</html>