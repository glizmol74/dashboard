<?php



class scanDir {
    static private $directories, $files, $ext_filter, $recursive;

// ----------------------------------------------------------------------------------------------
    // scan(dirpath::string|array, extensions::string|array, recursive::true|false)
    static public function scan(){
        // Initialize defaults
        self::$recursive = false;
        self::$directories = array();
        self::$files = array();
        self::$ext_filter = false;

        // Check we have minimum parameters
        if(!$args = func_get_args()){
            die("Must provide a path string or array of path strings");
        }
        if(gettype($args[0]) != "string" && gettype($args[0]) != "array"){
            die("Must provide a path string or array of path strings");
        }

        // Check if recursive scan | default action: no sub-directories
        if(isset($args[2]) && $args[2] == true){self::$recursive = true;}

        // Was a filter on file extensions included? | default action: return all file types
        if(isset($args[1])){
            if(gettype($args[1]) == "array"){self::$ext_filter = array_map('strtolower', $args[1]);}
            else
            if(gettype($args[1]) == "string"){self::$ext_filter[] = strtolower($args[1]);}
        }

        // Grab path(s)
        self::verifyPaths($args[0]);
        return self::$files;
    }

    static private function verifyPaths($paths){
        $path_errors = array();
        if(gettype($paths) == "string"){$paths = array($paths);}

        foreach($paths as $path){
            if(is_dir($path)){
                self::$directories[] = $path;
                $dirContents = self::find_contents($path);
            } else {
                $path_errors[] = $path;
            }
        }

        if($path_errors){echo "The following directories do not exists<br />";die(var_dump($path_errors));}
    }

    // This is how we scan directories
    static private function find_contents($dir){
        $result = array();
        $root = scandir($dir);
        foreach($root as $value){
            if($value === '.' || $value === '..') {continue;}
            if(is_file($dir.DIRECTORY_SEPARATOR.$value)){
                if(!self::$ext_filter || in_array(strtolower(pathinfo($dir.DIRECTORY_SEPARATOR.$value, PATHINFO_EXTENSION)), self::$ext_filter)){
                    self::$files[] = $result[] = $dir.DIRECTORY_SEPARATOR.$value;
                }
                continue;
            }
            if(self::$recursive){
                foreach(self::find_contents($dir.DIRECTORY_SEPARATOR.$value) as $value) {
                    self::$files[] = $result[] = $value;
                }
            }
        }
        // Return required for recursive search
        return $result;
    }
}




function leer_fichero_completo($nombre_fichero){
   //abrimos el archivo de texto y obtenemos el identificador
   $fichero_texto = fopen ($nombre_fichero, "r");
   //obtenemos de una sola vez todo el contenido del fichero
   //OJO! Debido a filesize(), sólo funcionará con archivos de texto
   $contenido_fichero = fread($fichero_texto, filesize($nombre_fichero));
   return $contenido_fichero;
}

function BuscarArchivo($path){
    $f = scanDir::scan($path, "NUB");
    $Files = array();

    for ($i = 0; $i < count($f); $i++){
        $current = $f[$i];
        if( $current != "." && $current != ".."){
            $Files[] = $f[$i];
        }
    }

    $i = count( $Files);
    if ( $i > 0 ) {
        return $Files[0];
    }
    else {
        return "";
    }
}

function DataToBaseDB(){
    include ("../config.php");
    $Resultado = -1;

    try {
        //code...
        $connectionInfo = array("Database"=>$Base, "UID"=>$User , "PWD"=>$Passw, "CharacterSet"=>"UTF-8");
        $con = sqlsrv_connect($ServerName, $connectionInfo);
        $Resultado = 1;
        if($con){
            $N2 = BuscarArchivo('./Data');
            while( $N2 <> "" ){
                $NombreF = str_replace('\\', '/', $N2);
                $sql = leer_fichero_completo($NombreF);
                
                $Tama = strlen( $sql);
                $Cant = $Tama;
                //echo $NombreF . '  ==>> ' . $Tama;
                $MaxTama = 4096 * 10;
        
                $Param = array();
        
                $Param[] = $NombreF;
                $j = 1;
                
                $PosIni = 0;

                while ($Cant > 0){
                    if ($Cant > 4095){
                        $Cad = substr ($sql, $PosIni, 4094);
                        $Param[] = $Cad;
                        $Cant = $Cant - 4094;
                        $j = $j + 1;
                        $PosIni = $PosIni + 4094;
                    }else {
                        $Param[]= substr ($sql, $PosIni, $Cant);
                        $j = $j + 1;
                        $Cant = 0;
                    }
                } // fin while Cantidad

                if ( $j < 12){
                    for ( $x = $j; $x < 12; $x++ ){
                        $Param[]= " ";
                    }
                } // fin if j

                if ( sqlsrv_begin_transaction( $con ) ){
                    $Comand = "if exists( select top 1 * from nube where NameF = '" . $Param[0] . "' )
                       Update Nube set NameF = '" . $Param[0] . "' where NameF = '" . $Param[0] . "' Else
                        Insert Into Nube (NameF, S1, S2, S3, S4, S5, S6, S7, S8, S9, S10 ) Values ( ?, 
                        ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ) ";
                     
                     $stm1 = sqlsrv_query( $con, $Comand, $Param );
                     if ( $stm1 ){
                        sqlsrv_commit( $con );
                        //echo "\nOk\n";
                         unlink($NombreF);
                         $Resultado = 1;
                     } else {
                        sqlsrv_rollback( $con );
                        //echo "\nError\n";
                        $N2 = "";
                        return -4;
                     }

                }else{ // fin si transaccion
                    return -3;
                }
                $N2 = BuscarArchivo('./Data');
            } // Fin while N2
        }else{
            return -2;
        }
    } catch (\Throwable $th) {
        return $Resultado . $th;
        //throw $th;
    }
    return 1;
}



function ejecutarUpdateDB(){
    include ("../config.php");
    $File = array();
    $CadSql = array();
    $Resultado = 0;
  
    $cad = "sqlsrv:Server=" . $ServerName . "; Database=" . $Base;
    $conex = new PDO($cad,$User,$Passw);
    $consulta = "SELECT NameF, S1+S2+S3+S4+S5+S6+S7+S8+S9+S10 AS Operacion 
                        from Nube order by NameF";
    
    try {
        $Rsql = $conex->prepare($consulta);
				$Rsql->execute();
				while($FilaR = $Rsql->fetch()) {
                    $File[]= $FilaR['NameF'];
                    $CadSql[] = $FilaR['Operacion'];
                }
    } catch(PDOException $e){
       // echo "Error Conexión ". $e;
        return -1;
    }

    $NroFile = count( $File );
    $x = 0;

     //  echo ' Registros Por Operar ===>>  ' . $NroFile ;
     $connectionInfo = array("Database"=>$Base, "UID"=>$User , "PWD"=>$Passw, "CharacterSet"=>"UTF-8");
     $con = sqlsrv_connect($ServerName, $connectionInfo);
  
    while ($x < $NroFile){
        try {

            if($con){
                if ( sqlsrv_begin_transaction( $con ) ){
                    $Param = array();

                    $Param[] = $File[$x]; 
                    $Comand = $CadSql[$x]  . "   Delete Nube where NameF = '" . $Param[0] . "' ";
                    //echo $Comand;

                    $stm1 = sqlsrv_query( $con, $Comand, $Param );
                    if ( $stm1 ){
                        sqlsrv_commit( $con );
                        $x =  $x + 1;
                        //echo "  Ok ";
                        $Resultado = 1;
                     } else {
                        sqlsrv_rollback( $con );
                       // echo " Error ";
                        $x = $NroFile;
                        return -4;
                     }
                }else{
                    return -3;
                } // Fin de Transaction Sql
        
            } // Fin de  if Conexion
            return -2;
        } catch (\Throwable $th) {
            //throw $th;
            return  -2;
            $x = $NroFile;
    //        echo " Errro  ==>>>  " . $th;
        } 
    } // fin de while $x 

return 1;
} // Fin de la Function



?>