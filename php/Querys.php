<?php
    

    class DataResult
    {
        public $Col = '';
        public $Valor = '';
        public $Tipo = '';
        public $Witch = 0;
    }

    
    
    
    
    function ConsultaExport($id, $sql)
    {
        include ("../../config.php");
        $cad = "sqlsrv:Server=" . $ServerName . "; Database=" . $Base;
        $conex = new PDO($cad,$User,$Passw);
        $rowData = array();

        try {
            $Rsql = $conex->prepare($sql);
            $Rsql->execute();
            while($FilaR = $Rsql->fetch()) {
                $Data = new DataResult;
                switch ($id) {
                    case 1: // Procucto
                        $Data->Col = 'Fecha';
                        $Data->Valor = $FilaR[''];
                        $Data->Tipo = 'Date';
                        $Data->Witch = 20;

                        
                        break;
                    case 2:
                        break;
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        

    }
?>