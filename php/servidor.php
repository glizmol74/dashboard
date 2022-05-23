<?php

   include ("./mysql.php");
   
  $oMySql = new MySQL();

  $response = "";
  $rq = $_POST['rq'];

   if($rq == 1){
   	 $response = $oMySql->getResumen();
   }else if ($rq == 2){
      $response = $oMySql->getPedidos();
   }else if ($rq == 3){
      $response = $oMySql->getStock();
   }else if ($rq == 4){
      $response = $oMySql->getEvolucionV();
   }else if ($rq == 5){
      $response = $oMySql->getEvolucionVH();
   }else if ($rq == 0){
    // ingreso al sistema consulta de usuario
    $User_db = (isset($_POST['Usuario'])) ? $_POST['Usuario'] : '';
    $Pwd_db = (isset($_POST['Password'])) ? $_POST['Password'] : '';

    $passw = md5($Pwd_db); //encripta la clave
    $response = $oMySql->getUsuario($User_db,$Pwd_db);
   }else if ($rq == 6){
    $response = $oMySql->getCuentaXCobrar();
   }else if ($rq == 7){
    $response = $oMySql->getDetalleCxC();
   }else if ($rq == 8){
    $response = $oMySql->getCuentaXPagar();
   }else if ($rq == 9){
    $response = $oMySql->getDetalleCxP();
   }else if ($rq == 10) {
      $response = $oMySql->getEvolucionC();
   }else if ($rq == 11) {
      $response = $oMySql->getResumenCompra();
   }else if ($rq == 12) {
      $response = $oMySql->getGraficaPartCompra();
   }else if ($rq == 13) {
      $response = $oMySql->getUnidades();
   }

echo $response;
?>