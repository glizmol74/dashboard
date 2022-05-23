<?php
  session_start();
  if($_SESSION["s_Usuario"] === null){
	  header("Location: ./index.php");
  }else if($_SESSION["s_Nivel"] > 4 ){
	$Url = trim($_SESSION["s_Url"]);
	header("Location: ./$Url");
  }
?>
<?php
if(!isset($_GET['Ancho']) && !isset($_GET['Alto']) )
{
echo "<script language=\"JavaScript\">
<!-- 
document.location=\"$PHP_SELF?Ancho=\"+screen.width+\"&Alto=\"+screen.height;
//-->
</script>";
}
else {
if(isset($_GET['Ancho']) && isset($_GET['Alto'])) {
// Resolución de pantalla detectada

 // echo "Esta es tu resolucion de pantalla: Ancho= ".$_GET['Ancho']." y Alto= ".$_GET['Alto'];
 $Ancho = $_GET['Ancho'];
 $Alto = $_GET['Alto'];
 }
 else {
 //// error en la detección de resolución de pantalla
 echo "No se ha podido detectar la resolución de pantalla";
 }
}?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Produccion</title>
    <link data-n-head="1" rel="icon" type="image/x-icon" href="../../favicon.ico">
    <!-- Bootstrap core CSS -->
       <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
       <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="menuflex"  width= "<?php echo $Ancho-10?>">
        <div class="dropdown" id="menu">
            <button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php echo $_SESSION["s_Usuario"] ; ?>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="Pedidos.php">Pedidos</a>
                <a class="dropdown-item" href="prueba.html">Producción</a>
                <a class="dropdown-item" href="logout.php">Salida</a>
            </div>
        </div>
    </div>
    <div clase="prueba">
        <iframe width= "<?php echo $Ancho-20?>" height="<?php echo $Alto-100?>" src="../html/prueba.html" frameborder="0" allowfullscreen></iframe>
    </div>

<script src="../js/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
</body>

  </html>