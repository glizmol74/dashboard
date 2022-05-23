<?php
    include ("../../config.php");
	/*$serverName = "192.168.148.32"; 
	$serverName = "192.168.0.110\sqlexpress"; 
/*	$serverName = "VIT\TEMPO";*/

	$connectionInfo = array("Database"=>$Base, "UID"=>$User , "PWD"=>$Passw, "CharacterSet"=>"UTF-8");
	/*$connectionInfo = array("Database"=>"sysglmco_sistema", "UID"=>"sysglmco_gm", "PWD"=>"Glm260774$", "CharacterSet"=>"UTF-8"); */

	
	$con = sqlsrv_connect($ServerName, $connectionInfo);

	if($con){
	/*	echo "conexión exitosa 200.59.9.81"; */
	   $rSql = "Select d1, d2, d3 from " . $Base . "..sfecha ";
       $ejec = sqlsrv_query($con, $rSql);

       while($fila= sqlsrv_fetch_array($ejec)) {
   	      $tdia0 =$fila['d1'];
   	      $tdia1 =$fila['d2'];
   	     $tdia2 =$fila['d3'];
        }
	}else{
		echo "fallo en la conexión";
	}
	$MesLetra[0] = 'No Definido';
	$MesLetra[1] = 'Enero';
	$MesLetra[2] = 'Febrero';
	$MesLetra[3] = 'Marzo';
	$MesLetra[4] = 'Abril';
	$MesLetra[5] = 'Mayo';
	$MesLetra[6] = 'Junio';
	$MesLetra[7] = 'Julio';
	$MesLetra[8] = 'Agosto';
	$MesLetra[9] = 'Septiembre';
	$MesLetra[10] = 'Octubre';
	$MesLetra[11] = 'Noviembre';
	$MesLetra[12] = 'Diciembre';
	$Mes_Actual = strftime("%b-%y");

	$DiaSemana[0] = 'No definido';
	$DiaSemana[1] = 'Lunes';
	$DiaSemana[2] = 'Martes';
	$DiaSemana[3] = 'Miercoles';
	$DiaSemana[4] = 'Jueves';
	$DiaSemana[5] = 'Viernes';
	$DiaSemana[6] = 'Sabado';
	$DiaSemana[7] = 'Domingo';
?>