<?php
    include("conexion_sis.php");
    include ("../../config.php");
    session_start();
    if($_SESSION["s_Usuario"] === null){
	    header("Location: ../../index.php");
    }else if($_SESSION["s_Nivel"] > 2 ){
	    $Url = trim($_SESSION["s_Url"]);
	header("Location: ./$Url");
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link data-n-head="1" rel="icon" type="image/x-icon" href="../../favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
    <title>Productos Vendidos</title>

</head>
<body>
    <div id="content">
        <div class="continer-fluid">
            <div class="col-xl-12 col-md-12">
                <div class="card border-secundary shadown mb-3">
                    <div class="card-header ma-0 py-2 d-flex flex-row align-items-center justify-content-between">
                        <table class="table table-bordered table-striped mt-2" >
                            <tr align="center">
                                <td><a href="javascript:cambiarProdVend('VentasArticulos.php', 1)">Artículos</a></td>
                                <td><a href="javascript:cambiarProdVend('VentasXLitros.php', 3)">Líquidos</a></td>
                                <td bgcolor="#FF8C00"><a href="dashboard.php">Regresar</a></td>
                            </tr>
                        </table>
                    </div>
                    <div class="card-body text-dark ma-0 pa-0" id="ProductosVendidos"></div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function cambiarProdVend(pagina, Tipo) {
            $("#ProductosVendidos").load(pagina, {TipoR: Tipo})
        }
    </script>
    <script src="../js/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/jquery.table2excel.min.js"></script>
</body>
</html>