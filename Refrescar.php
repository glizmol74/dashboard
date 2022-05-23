<?php
include ("./refrescarData.php");
if(isset($_GET['Op'])){
    $Op = $_GET['Op'];
}else{
    $Op = 0;
}


if ($Op == 1){
    $x =  DataToBaseDB();
}elseif ($Op == 2) {
    $x = ejecutarUpdateDB();
}else {
    $x = 0;
} 

return $x ;
?>