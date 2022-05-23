<?php
  include ("../../config.php");

	class MySql{
		var $BaseDB, $ServerN, $UserDB, $PasswDB;
		public function __construct()
		{
			include ("../../config.php");
			$this->BaseDB = $Base;
			$this->ServerN = $ServerName;
			$this->UserDB = $User;
			$this->PasswDB = $Passw;
		}

		
		public function getResumen() 
		{
			include ("../../config.php");
			$cad = "sqlsrv:Server=" . $this->ServerN . "; Database=" . $this->BaseDB;
			$cad = "sqlsrv:Server=" . $ServerName . "; Database=" . $Base;
			$conex = new PDO($cad,$User,$Passw);
			$FechaActual = date("Y-m-d HH:mm:ss") ;
			$FechaMesAnt = date("Y-m-d");
			$FecActual = date("Y-m");
			$MesAnt = "Fact-" . date("y-m", strtotime($FechaMesAnt. "- 1 month"));
			$FactMesActual = "Fact-" .date("y-m");
			$PrimerDia = date("Y-m") . '-01 00:00:00';
			$FechaFin = new datetime($PrimerDia);
			$FechaFin->modify('last day of this month');
			$UltimoDia =  $FechaFin->format('Y-m-d') . ' 23:59:59';
			$PrimerDia = date("Y-m") . '-01 00:00:00';
			

			$consulta = "set dateformat YMD
				declare @FechaInio as datetime = format(getdate(),'yyyy-MM-01 00:00:00')
				declare @Ult as varchar(2) =  format(EOMONTH( @FechaInio ),'dd')
				declare @Antes as varchar(5) = format(EOMONTH( @FechaInio -1),'yy-MM')
				declare @FecFin as datetime = format(getdate(),'yyyy-MM-' + @Ult + ' 23:59:59')
				declare @MesAnt as varchar(10) = 'Fact-' + @antes
				declare @FactMesActual varchar(10) = 'Fact-' + format(@fechaInio, 'yy-MM')
				declare @FechaA as datetime = format(EOMONTH( @FechaInio -1),'yyyy-MM-dd')
				declare @MesPart as varchar(6) = substring(DATENAME (MONTH, @fechaA),1,3)  + '-' + substring(DATENAME (YEAR, @FechaA),3,2)
				SELECT isnull(Ventas,0) Ventas, SUM(VentaMa) VentaMa, sum(Tpedidos) Tpedidos, sum(TcostoP) TCostoP, sum(PedA) PedA, sum(PedPNP) PedPNP,
						sum(TotalRem) TotalRem, sum(CostoTRem) CostoTRem, isnull(VentaE,0) VentaE, 
						sum(Litros) Litros, sum(Unds) Unds, sum(Costo)  Costo, 
						sum(Precio) Precio, isnull( ( select Porc  from PedidosN where Fecha = '$FecActual') , 0) PorcPed, 
						isnull(IVAF,0) IVA, @MesPart MesPart 
					FROM (
					select  isnull(( select sum((case when dc.TipoDocumentoID like 'NC%' then -1 else 1 end) * dc.Subtotal ) 
							from DocumentosCabecera DC where ( dc.TipoDocumentoId like 'FC%' or dc.TipoDocumentoId like 'NC%' ) and 
							dc.Fecha between @FechaInio and @FecFin  and dc.EstadoDocumentoId <> 3),0) Ventas,
							(CASE WHEN  T2.TipoReporte =  @MesAnt THEN T2.S1 ELSE 0 END) VentaMa,
							(CASE WHEN  T2.TipoReporte = 'PedPend' THEN T2.S1 ELSE 0 END) Tpedidos,
							(CASE WHEN  T2.TipoReporte = 'PedPend' THEN T2.S2 ELSE 0 END) TcostoP,
							(CASE WHEN  T2.TipoReporte = 'PedPend' THEN T2.S3 ELSE 0 END) PedA,
							(CASE WHEN  T2.TipoReporte = 'PedPend'  THEN T2.S4 ELSE 0 END) PedPNP,
							(CASE WHEN  T2.TipoReporte = 'RemPxV'  THEN T2.S1 ELSE 0 END) TotalRem,
							(CASE WHEN  T2.TipoReporte = 'RemPxV'  THEN T2.S2 ELSE 0 END) CostoTRem,
							(CASE WHEN  T2.TipoReporte = @FactMesActual or T2.TipoReporte = 'RemPxV' THEN isnull(T2.Dia0,0) ELSE 0 END ) Unds,
							(CASE WHEN  T2.TipoReporte = @FactMesActual or T2.TipoReporte = 'RemPxV' THEN isnull(T2.Dia1,0) ELSE 0 END ) Litros,
							(CASE WHEN  T2.TipoReporte = @FactMesActual or T2.TipoReporte = 'RemPxV' THEN isnull(T2.Precio,0) ELSE 0 END ) Precio,
							(CASE WHEN  T2.TipoReporte = @FactMesActual or T2.TipoReporte = 'RemPxV' THEN isnull(T2.Costo,0) ELSE 0 END ) Costo,
							( select sum((case when dc.TipoDocumentoID like 'NC%' then -1 else 1 end) * dc.IVA ) 
					from DocumentosCabecera DC where ( dc.TipoDocumentoId like 'FC%' or dc.TipoDocumentoId like 'NC%' ) and 
							dc.Fecha between @FechaInio and @FecFin  and dc.EstadoDocumentoId <> 3) IVAF,
							( select sum((case when dc.TipoDocumentoID like 'NC%' then -1 else 1 end) * dc.Subtotal ) 
					from DocumentosCabecera DC where ( dc.TipoDocumentoId like 'FC%' or dc.TipoDocumentoId like 'NC%' ) and 
							dc.Fecha between @FechaInio and @FecFin  AND DC.IVA <> 0  and dc.EstadoDocumentoId <> 3) VentaE
					from ( 
					select distinct  sum(Total) Cant, sum(s1) S1, sum(s2) s2, sum(s3) S3, sum(s4) S4, sum(Dia0) Dia0, Dia1, Precio, Costo, TipoReporte  from (
					select a.ProductoId, a.Descripcion,
						(CASE WHEN A.TipoReporte = 'PedPend' and D.Estado <>3 then sum(a.TotalCantidad)
							ELSE (CASE WHEN A.TipoReporte = 'RemPxV' and d.CondV <> 10 and D.Estado <>3 THEN  sum(a.TotalCantidad ) else A.TotalCantidad END ) END)  Total,
						(CASE WHEN A.TipoReporte = 'PedPend' and D.Estado <>3 then sum(a.s2 * a.Unitario) 
							ELSE (CASE WHEN A.TipoReporte = 'RemPxV' and d.CondV <> 10 and D.Estado <>3 THEN  sum(s1) ELSE sum(a.s1) END ) END) S1, 
						(CASE WHEN A.TipoReporte = 'PedPend' and D.Estado <>3 THEN sum(a.s2 * a.Costo)
							ELSE (CASE WHEN A.TipoReporte = 'RemPxV' and d.CondV <> 10 and D.Estado <>3 THEN  sum(s2) ELSE a.s2 END ) END) S2,
					(CASE WHEN A.TipoReporte = 'PedPend' and d.CondV <> 10  and d.FechaP  between  @FechaInio and  @FecFin THEN sum(a.TotalCantidad * a.Unitario ) ELSE 0 END) S3, 
					(CASE WHEN A.TipoReporte = 'PedPend' and d.CondV <> 10  and d.FechaP  between  @FechaInio and  @FecFin and d.Estado = 1 THEN sum(a.TotalCantidad * a.Unitario ) ELSE 0 END) S4, 
					(CASE WHEN (A.TipoReporte  = @FactMesActual or ( A.TipoReporte = 'RemPxV' and A.FechaE between @FechaInio and @FecFin )) and a.ClasificacionProdID in (12,16,17) THEN sum(a.totalcantidad) ELSE 0 END) Dia0,
					(CASE WHEN (A.TipoReporte  = @FactMesActual or ( A.TipoReporte = 'RemPxV' and A.FechaE between @FechaInio and @FecFin )) and a.ClasificacionProdID in (12,16,17) and f.FormSemi <> 0 THEN sum(case when a.ClasificacionProdID =17 THEN f.Cantidad*a.TotalCantidad ELSE A.TotalCantidad END) ELSE 0 END) Dia1,
					(CASE WHEN (A.TipoReporte  = @FactMesActual or ( A.TipoReporte = 'RemPxV' and A.FechaE between @FechaInio and @FecFin )) and a.ClasificacionProdID in (12,16,17) and f.FormSemi <> 0 THEN sum(a.S1 ) ELSE 0 END) Precio,
					(CASE WHEN (A.TipoReporte  = @FactMesActual or ( A.TipoReporte = 'RemPxV' and A.FechaE between @FechaInio and @FecFin )) and a.ClasificacionProdID in (12,16,17) and f.FormSemi <> 0 THEN sum(a.S2 ) ELSE 0 END) Costo,
						A.TipoReporte
					from AuxPed A left join Documento D on a.DocumentoId = d.DocumentoId left join DesgloseFormula F on f.FormulaID = a.FormulaID and f.FormSemi <> 0
					group by a.ProductoId, a.Descripcion, a.TipoReporte, d.CondV, d.Estado, d.FechaP, d.FechaE, a.TotalCantidad, a.s1, a.s2, a.FechaE,
							a.ClasificacionProdID, f.FormSemi) T1 
					group by TipoReporte, Dia1, Precio, Costo
					) T2
					) T3 GROUP BY IVAF, VentaE, Ventas";
		
			$ValorR = 0;
			try{
				$Rsql = $conex->prepare($consulta);
				$Rsql->execute();
			//	$FilaR = $Rsql->fetch();
				while($FilaR = $Rsql->fetch()){

				$VentaAct = $FilaR['Ventas'] + 0;
				$VentaAnt = $FilaR['VentaMa'] + 0;
				$Pedidos = $FilaR['Tpedidos'] + 0;
				$VentaElec = $FilaR['VentaE'] + 0;
				$IVA = $FilaR['IVA'] + 0;
				$Lts = $FilaR['Litros'] + 0;
				$PedA = $FilaR['PedA'] + 0;
				$PedNP = $FilaR['PedPNP'] + 0;
				$PorcPed = $FilaR['PorcPed'] + 0;
				$MesPartL = $FilaR['MesPart'];
				if ($Lts == 0){
					$CostoP =  0;
					$PrecioP = 0;
				}else {
				$CostoP =  ( $FilaR['Costo'] / $Lts ) + 0;
				$PrecioP = ( $FilaR['Precio'] / $Lts) + 0;
				}
				$Unds = $FilaR['Unds'] + 0;
				$VnetaN = $VentaAct -  $VentaElec + 0;
				if ($Pedidos > 0) {
					$CMgPedido = ($Pedidos - $FilaR['TCostoP'] ) / $Pedidos * 100;
				}else {
					$CMgPedido = 0;
				}
				$CostoTRem = $FilaR['CostoTRem'] + 0;
				$TotalRem = $FilaR['TotalRem'] + 0;
				if ($TotalRem > 0){
					$CMgRemito = ($TotalRem - $CostoTRem) / $TotalRem * 100;
				}else{
					$CMgRemito = 0;
				}
			}

				$result = array("VentaAct"=> $VentaAct, "VentaAnt"=>$VentaAnt, "MesPart"=>$MesPartL, "Pedidos"=>$Pedidos,
						"VentaE"=>$VentaElec, "VentaN"=>$VnetaN, "Iva"=>$IVA, "LitrosV"=>$Lts, "UndsV"=>$Unds, "CostoP"=>$CostoP,"PrecioP"=>$PrecioP, "CmgPedido"=>$CMgPedido, "TotalRemP"=>$TotalRem, 
						"CMgRem"=>$CMgRemito, "PedidoAct"=>$PedA, "PorcPed"=>$PorcPed, "PedPNP"=>$PedNP );
				$ValorR = json_encode($result);
				
			}catch(PDOException $e){
				echo "Error Conexión ". $e;
				return -1;
			}
			
			return $ValorR;
		}

		public function getUnidades()
		{
			include ("../../config.php");
			$cad = "sqlsrv:Server=" . $this->ServerN . "; Database=" . $this->BaseDB;
			$cad = "sqlsrv:Server=" . $ServerName . "; Database=" . $Base;
			$conex = new PDO($cad,$User,$Passw);

			$consulta = "set dateformat YMD
				declare @FechaInio as datetime = format(getdate(),'yyyy-MM-01 00:00:00')
				declare @Ult as varchar(2) =  format(EOMONTH( @FechaInio ),'dd')
				declare @Antes as varchar(5) = format(EOMONTH( @FechaInio -1),'yy-MM')
				declare @FecFin as datetime = format(getdate(),'yyyy-MM-' + @Ult + ' 23:59:59')
				declare @FactMesActual varchar(10) = 'Fact-' + format(@fechaInio, 'yy-MM')
				declare @FechaA as datetime = format(EOMONTH( @FechaInio -1),'yyyy-MM-dd')
							
					Select 'Liq.' Prod, Und, Litros, Precio, Costo, (Precio / TLts ) PrecioPx, (Costo / TLts ) CostoPx
						FROM(
							SELECT sum(Litros) Litros, sum(Unds) Und, sum(Costo)  Costo, sum(Precio) Precio, (CASE WHEN sum(Litros) > 0 THEN sum(Litros) ELSE 1 END ) TLts
								FROM (
									select  isnull(( select sum((case when dc.TipoDocumentoID like 'NC%' then -1 else 1 end) * dc.Subtotal ) 
											from DocumentosCabecera DC where ( dc.TipoDocumentoId like 'FC%' or dc.TipoDocumentoId like 'NC%' ) and 
											dc.Fecha between @FechaInio and @FecFin  and dc.EstadoDocumentoId <> 3),0) Ventas,
											(CASE WHEN  T2.TipoReporte = @FactMesActual or T2.TipoReporte = 'RemPxV' THEN isnull(T2.Dia0,0) ELSE 0 END ) Unds,
											(CASE WHEN  T2.TipoReporte = @FactMesActual or T2.TipoReporte = 'RemPxV' THEN isnull(T2.Dia1,0) ELSE 0 END ) Litros,
											(CASE WHEN  T2.TipoReporte = @FactMesActual or T2.TipoReporte = 'RemPxV' THEN isnull(T2.Precio,0) ELSE 0 END ) Precio,
											(CASE WHEN  T2.TipoReporte = @FactMesActual or T2.TipoReporte = 'RemPxV' THEN isnull(T2.Costo,0) ELSE 0 END ) Costo
										
									from ( 
										select distinct  sum(Total) Cant, sum(s1) S1, sum(s2) s2,  sum(Dia0) Dia0, Dia1, Precio, Costo, TipoReporte  
										from (
											select a.ProductoId, a.Descripcion,
												(CASE WHEN A.TipoReporte = 'RemPxV' and d.CondV <> 10 and D.Estado <>3 THEN  sum(a.TotalCantidad ) else A.TotalCantidad END )  Total,
												(CASE WHEN A.TipoReporte = 'RemPxV' and d.CondV <> 10 and D.Estado <>3 THEN  sum(s1) ELSE sum(a.s1) END )  S1, 
												(CASE WHEN A.TipoReporte = 'RemPxV' and d.CondV <> 10 and D.Estado <>3 THEN  sum(s2) ELSE a.s2 END )  S2,
								
												(CASE WHEN (A.TipoReporte  = @FactMesActual or ( A.TipoReporte = 'RemPxV' and A.FechaE between @FechaInio and @FecFin )) and a.ClasificacionProdID in (12,16,17) THEN sum(a.totalcantidad) ELSE 0 END) Dia0,
												(CASE WHEN (A.TipoReporte  = @FactMesActual or ( A.TipoReporte = 'RemPxV' and A.FechaE between @FechaInio and @FecFin )) and a.ClasificacionProdID in (12,16,17) and f.FormSemi <> 0 THEN sum(case when a.ClasificacionProdID =17 THEN f.Cantidad*a.TotalCantidad ELSE A.TotalCantidad END) ELSE 0 END) Dia1,
												(CASE WHEN (A.TipoReporte  = @FactMesActual or ( A.TipoReporte = 'RemPxV' and A.FechaE between @FechaInio and @FecFin )) and a.ClasificacionProdID in (12,16,17) and f.FormSemi <> 0 THEN sum(a.S1 ) ELSE 0 END) Precio,
												(CASE WHEN (A.TipoReporte  = @FactMesActual or ( A.TipoReporte = 'RemPxV' and A.FechaE between @FechaInio and @FecFin )) and a.ClasificacionProdID in (12,16,17) and f.FormSemi <> 0 THEN sum(a.S2 ) ELSE 0 END) Costo,
													A.TipoReporte
											from AuxPed A left join Documento D on a.DocumentoId = d.DocumentoId left join DesgloseFormula F on f.FormulaID = a.FormulaID and f.FormSemi <> 0
											group by a.ProductoId, a.Descripcion, a.TipoReporte, d.CondV, d.Estado, d.FechaP, d.FechaE, a.TotalCantidad, a.s1, a.s2, a.FechaE,
													a.ClasificacionProdID, f.FormSemi) T1 
										group by TipoReporte, Dia1, Precio, Costo) T2
									) T3 
								GROUP BY Ventas ) TF
					UNION
			
					SELECT 'Art.' Prod, Und, Litros, Precio, Costo, (Precio / TUnd ) PrecioPx, (Costo / TUnd ) CostoPx
						FROM(
							SELECT sum(Litros) Litros, sum(Unds) Und, sum(Costo)  Costo, sum(Precio) Precio, (CASE WHEN sum(Unds) > 0 THEN sum(Unds) ELSE 1 END ) TUnd
								FROM (
									select  isnull(( select sum((case when dc.TipoDocumentoID like 'NC%' then -1 else 1 end) * dc.Subtotal ) 
											from DocumentosCabecera DC where ( dc.TipoDocumentoId like 'FC%' or dc.TipoDocumentoId like 'NC%' ) and 
											dc.Fecha between @FechaInio and @FecFin  and dc.EstadoDocumentoId <> 3),0) Ventas,
											(CASE WHEN  T2.TipoReporte = @FactMesActual or T2.TipoReporte = 'RemPxV' THEN isnull(T2.Dia0,0) ELSE 0 END ) Unds,
											(CASE WHEN  T2.TipoReporte = @FactMesActual or T2.TipoReporte = 'RemPxV' THEN isnull(T2.Dia1,0) ELSE 0 END ) Litros,
											(CASE WHEN  T2.TipoReporte = @FactMesActual or T2.TipoReporte = 'RemPxV' THEN isnull(T2.Precio,0) ELSE 0 END ) Precio,
											(CASE WHEN  T2.TipoReporte = @FactMesActual or T2.TipoReporte = 'RemPxV' THEN isnull(T2.Costo,0) ELSE 0 END ) Costo
										
									from ( 
										select distinct  sum(Total) Cant, sum(s1) S1, sum(s2) s2,  sum(Dia0) Dia0, Dia1, Precio, Costo, TipoReporte  
										from (
											select a.ProductoId, a.Descripcion,
												(CASE WHEN A.TipoReporte = 'RemPxV' and d.CondV <> 10 and D.Estado <>3 THEN  sum(a.TotalCantidad ) else A.TotalCantidad END )  Total,
												(CASE WHEN A.TipoReporte = 'RemPxV' and d.CondV <> 10 and D.Estado <>3 THEN  sum(s1) ELSE sum(a.s1) END )  S1, 
												(CASE WHEN A.TipoReporte = 'RemPxV' and d.CondV <> 10 and D.Estado <>3 THEN  sum(s2) ELSE a.s2 END )  S2,
								
												(CASE WHEN (A.TipoReporte  = @FactMesActual or ( A.TipoReporte = 'RemPxV' and A.FechaE between @FechaInio and @FecFin )) and a.ClasificacionProdID in (34) THEN sum(a.totalcantidad) ELSE 0 END) Dia0,
												(CASE WHEN (A.TipoReporte  = @FactMesActual or ( A.TipoReporte = 'RemPxV' and A.FechaE between @FechaInio and @FecFin )) and a.ClasificacionProdID in (34)  THEN sum(case when a.ClasificacionProdID =34 THEN f.Cantidad*a.TotalCantidad ELSE A.TotalCantidad END) ELSE 0 END) Dia1,
												(CASE WHEN (A.TipoReporte  = @FactMesActual or ( A.TipoReporte = 'RemPxV' and A.FechaE between @FechaInio and @FecFin )) and a.ClasificacionProdID in (34)  THEN sum(a.S1 ) ELSE 0 END) Precio,
												(CASE WHEN (A.TipoReporte  = @FactMesActual or ( A.TipoReporte = 'RemPxV' and A.FechaE between @FechaInio and @FecFin )) and a.ClasificacionProdID in (34)  THEN sum(a.S2 ) ELSE 0 END) Costo,
													A.TipoReporte
											from AuxPed A left join Documento D on a.DocumentoId = d.DocumentoId left join DesgloseFormula F on f.FormulaID = a.FormulaID and f.FormSemi <> 0
											group by a.ProductoId, a.Descripcion, a.TipoReporte, d.CondV, d.Estado, d.FechaP, d.FechaE, a.TotalCantidad, a.s1, a.s2, a.FechaE,
													a.ClasificacionProdID, f.FormSemi) T1 
										group by TipoReporte, Dia1, Precio, Costo) T2
									) T3 
								GROUP BY Ventas ) TF";

			$ValorR = 0;
			$rowData = array();
			try{
				$Rsql = $conex->prepare($consulta);
				$Rsql->execute();
				$i = 0;
				while($FilaR = $Rsql->fetch()){
					$data = new Vendidos;
					$data->Prod = $FilaR['Prod'];
					$data->Und = $FilaR['Und'] + 0;
					$data->Litros = $FilaR['Litros'] + 0;
					$data->Precio = $FilaR['Precio'] + 0;
					$data->Costo = $FilaR['Costo'] + 0;
					$data->PrecioPx = $FilaR['PrecioPx'] + 0;
					$data->CostoPx = $FilaR['CostoPx'] + 0;
					$rowData[$i++] = $data;
				}

				$ValorR = json_encode($rowData);
				
			}catch(PDOException $e){
				echo "Error Conexión ". $e;
				return -1;
			}
						
			return $ValorR;
		}

		public function getResumenCompra()
		{
			include ("../../config.php");
			$cad = "sqlsrv:Server=" . $this->ServerN . "; Database=" . $this->BaseDB;
			$cad = "sqlsrv:Server=" . $ServerName . "; Database=" . $Base;
			$conex = new PDO($cad,$User,$Passw);
			$FechaActual = date("Y-m-d HH:mm:ss") ;
			$FechaMesAnt = date("Y-m-d");
			$FecActual = date("Y-m");
			$MesAnt = "Fact-" . date("y-m", strtotime($FechaMesAnt. "- 1 month"));
			$RemMesActual = "Comp-" .date("y-m");
			$PrimerDia = date("Y-m") . '-01 00:00:00';
			$FechaFin = new datetime($PrimerDia);
			$FechaFin->modify('last day of this month');
			$UltimoDia =  $FechaFin->format('Y-m-d') . ' 23:59:59';
			$PrimerDia = date("Y-m") . '-01 00:00:00';
			

			$consulta = "set dateformat YMD
						DECLARE @Fecha as Date = '$PrimerDia'
						SELECT sum(subTotal) Compras,
							sum(CASE WHEN IVA >= 0 and SucDT in (4,5) THEN IVA ELSE 0 END ) IVA,
							sum(CASE WHEN SUcDT in (4,5) THEN Subtotal ELSE 0 END ) ComprasE,
							sum(CASE WHEN SUcDT not in (4,5) THEN Subtotal ELSE 0 END ) ComprasN,
							(select sum(subtotal) total from Documento where TipoReporte = 'RemPxC') TotalRem
						FROM (
							SELECT SucursalIdDest SucDT, TipoDocumentoId Tipo,
								(CASE WHEN TipoDocumentoId like 'NC%' THEN Subtotal * -1 ELSE Subtotal END ) SubTotal,
								(CASE WHEN TipoDocumentoId like 'NC%' THEN Iva * -1 ELSE Iva END ) Iva,
								(CASE WHEN TipoDocumentoId like 'NC%' THEN total * -1 ELSE total END ) Total
							FROM cprasDocumentosCabecera DC
							WHERE DC.Fecha >= @Fecha and ( TipoDocumentoId like 'FC%' or TipoDocumentoId like 'NC%') 
							and dc.EstadoDocumentoId <> 3)T1
						";
						$ValorR = 0;
						try{
							$Rsql = $conex->prepare($consulta);
							$Rsql->execute();
						//	$FilaR = $Rsql->fetch();
							while($FilaR = $Rsql->fetch()){
								$ComprasAct = $FilaR['Compras'] ;
								$ComprasE = $FilaR['ComprasE'] ;
								$ComprasN = $FilaR['ComprasN'] ;
								$IVA = $FilaR['IVA'] ;
								$TotalRem = $FilaR['TotalRem'] ;
							}
			
							$result = array("ComprasAct"=> $ComprasAct, "ComprasE"=>$ComprasE, "ComprasN"=>$ComprasN, "Iva"=>$IVA,
									"TotalRem"=>$TotalRem);
							$ValorR = json_encode($result);
							
						}catch(PDOException $e){
							echo "Error Conexión ". $e;
							return -1;
						}
						
			return $ValorR;
		}
	
		public function getPedidos() 
		{
			include ("../../config.php");
			$cad = "sqlsrv:Server=" . $this->ServerN . "; Database=" . $this->BaseDB;
			$cad = "sqlsrv:Server=" . $ServerName . "; Database=" . $Base;
			$conex = new PDO($cad,$User,$Passw);
			$consulta = "Select isnull(sum(subtotal),0) tPedidos from " . $Base . "..documento where TipoReporte = 'PedPend2' and subtotal <> 0";
			$ValorR = 0;
			try{
				$Rsql = $conex->prepare($consulta);
				$Rsql->execute();
				$FilaR = $Rsql->fetch();
				$ValorR = $FilaR['tPedidos'];
			}catch(PDOException $e){
				echo "Error Conexión ". $e;
				return -1;
			}
			
			return $ValorR;
		}
		
		public function getEvolucionV()
		{
			include ("../../config.php");
			$cad = "sqlsrv:Server=" . $ServerName . "; Database=" . $Base;
			$conex = new PDO($cad,$User,$Passw);
			$jDatosR = '';
			$rowData = array();
			$i = 0;
			$consulta= "SET LANGUAGE Spanish
			set Dateformat YMD
			declare @FechaI datetime = format(getdate(), 'yyyy-MM-01 00:00:00')
			select FechaN, Fecha, tVentas, tCosto from (
				select SUBSTRING(TipoReporte,6,5) as FechaN, CONCAT(DATENAME(month, CONCAT('20',substring(tiporeporte,6,5),'-01 00:00:00')), '-20', SUBSTRING(tiporeporte,6,2)) as Fecha, 
						   sum(s1) as tVentas, sum(s2) tCosto, CONCAT('20',substring(tiporeporte,6,5),'-01 00:00:00') as FechaE
						 from AuxPed where TipoReporte like 'Fact-%'
						   group by TipoReporte ) T1
						where fechae > dateadd(month, -13, @fechaI)
						order by 1 asc";
			try {
				$Rsql= $conex->prepare($consulta);
				$Rsql->execute();
				while($FilaR = $Rsql->fetch()){
					$oDatosV = new DatosV();
					$oDatosV->Fecha = $FilaR['Fecha'];
					$oDatosV->FechaN = $FilaR['FechaN'];
					$oDatosV->tVentas = $FilaR['tVentas'];
					$oDatosV->tCosto = $FilaR['tCosto'];
					$oDatosV->totalCMg = $oDatosV->tVentas - $oDatosV->tCosto ;
					if ($oDatosV->tVentas == 0){
						$oDatosV->totalCMgP  = 0;
					}else {
						$oDatosV->totalCMgP = $oDatosV->totalCMg / $oDatosV->tVentas * 100;
					}
					$rowData[$i] = $oDatosV;
					$i++;
				}
				$jDatosR = json_encode( $rowData);
			}catch(PDOException $e){
				echo "Error Conexión ". $e;
				return -1;
			}
			
			return $jDatosR;
		}

		public function getEvolucionC()
		{
			include ("../../config.php");
			$cad = "sqlsrv:Server=" . $ServerName . "; Database=" . $Base;
			$conex = new PDO($cad,$User,$Passw);
			$jDatosR = '';
			$rowData = array();
			$i = 0;
			$consulta= "SET LANGUAGE Spanish
			set Dateformat YMD
			declare @FechaI datetime = format(getdate(), 'yyyy-MM-01 00:00:00')
			select FechaN, Fecha, tCompras, tComprasP, tComprasNP, tComprasN, tComprasE from (
				select SUBSTRING(TipoReporte,6,5) as FechaN, CONCAT(DATENAME(month, CONCAT('20',substring(tiporeporte,6,5),'-01 00:00:00')), '-20', SUBSTRING(tiporeporte,6,2)) as Fecha, 
						   sum(s1) as tCompras, sum(CASE WHEN ClasificacionProdID > 0 then s1 else 0 end) tComprasP,
						   sum(CASE WHEN ClasificacionProdID = 0 then s1 else 0 end) tComprasNP,
						   sum(CASE WHEN IVA = 0 then s1 else 0 end) tComprasN,
						   sum(CASE WHEN IVA > 0 then s1 else 0 end) tComprasE, CONCAT('20',substring(tiporeporte,6,5),'-01 00:00:00') as FechaE
						 from AuxPed where TipoReporte like 'Comp-%'
						   group by TipoReporte ) T1
						where fechae > dateadd(month, -13, @fechaI)
						order by 1 asc ";
			try {
				$Rsql= $conex->prepare($consulta);
				$Rsql->execute();
				while($FilaR = $Rsql->fetch()){
					$oDatosC = new DatosC();
					$oDatosC->Fecha = $FilaR['Fecha'];
					$oDatosC->FechaN = $FilaR['FechaN'];
					$oDatosC->tCompras = $FilaR['tCompras'];
					$oDatosC->tComprasP = $FilaR['tComprasP'];
					$oDatosC->tComprasNP = $FilaR['tComprasNP'];
					$oDatosC->tComprasN = $FilaR['tComprasN'];
					$oDatosC->tComprasE = $FilaR['tComprasE'];
					$rowData[$i] = $oDatosC;
					$i++;
				}
				$jDatosR = json_encode( $rowData);
			}catch(PDOException $e){
				echo "Error Conexión ". $e;
				return -1;
			}
			
			return $jDatosR;
		}

		public function getEvolucionVH()
		{
			include ("../../config.php");
			$cad = "sqlsrv:Server=" . $ServerName . "; Database=" . $Base;
			$conex = new PDO($cad,$User,$Passw);
			$jDatosR = '';
			$rowData = array();
			$i = 0;
			$consulta= "SET LANGUAGE Spanish
			set Dateformat YMD
			select format(fechaE,'yy/MM') as FechaN, CONCAT(DATENAME(month, fechaE), '-', year(FechaE)) as Fecha, 
				   sum(s2) as tVentas, sum(s3) tCosto
			 from " . $Base . "..AuxPed where TipoReporte like 'Fact-%' 
			 and fechae > dateadd(month, -11, @fechaI)
			  group by format(fechaE,'yy/MM'), (CONCAT(DATENAME(month, fechaE), '-', year(fechaE)) )
			  order by 1 asc ";
			try {
				$Rsql= $conex->prepare($consulta);
				$Rsql->execute();
				while($FilaR = $Rsql->fetch()){
					$oDatosV = new DatosV();
					$oDatosV->Fecha = $FilaR['Fecha'];
					$oDatosV->FechaN = $FilaR['FechaN'];
					$oDatosV->tVentas = $FilaR['tVentas'];
					$oDatosV->tCosto = $FilaR['tCosto'];
					$oDatosV->totalCMg = $oDatosV->tVentas - $oDatosV->tCosto ;
					$oDatosV->totalCMgP = $oDatosV->totalCMg / $oDatosV->tVentas * 100;
					console.log($oDatosV->totalCMgP);
					$rowData[$i] = $oDatosV;
					$i++;
				}
				$jDatosR = json_encode( $rowData);
			}catch(PDOException $e){
				echo "Error Conexión ". $e;
				return -1;
			}
			
			return $jDatosR;
		}

		public function getCuentaXCobrar() 
		{
			include ("../../config.php");
			$cad = "sqlsrv:Server=" . $this->ServerN . "; Database=" . $this->BaseDB;
			$cad = "sqlsrv:Server=" . $ServerName . "; Database=" . $Base;
			$conex = new PDO($cad,$User,$Passw);
			$consulta = "Select sum(dc.MontoAplicar) Total, 0 as CobMes, 0 as CobDia
			From DocumentosCabecera DC
			  WHERE dc.EstadoDocumentoId = 1 and dc.Total >0 AND dc.Fecha>=CONVERT(DATETIME,'1900-1-1',101) 
				AND (dc.TipoDocumentoId LIKE 'FC%' OR dc.TipoDocumentoId LIKE 'ND%')";
			$ValorR = 0;
			try{
				$Rsql = $conex->prepare($consulta);
				$Rsql->execute();
				$FilaR = $Rsql->fetch();
				$CxC = $FilaR['Total']+0;
				$Cob_Mes = $FilaR['CobMes']+0;
				$Cob_Dia = $FilaR['CobDia']+0;
				$result = array("CxC"=> $CxC, "CobMes"=>$Cob_Mes, "CobDia"=>$Cob_Dia);
				$ValorR = json_encode($result);
			}catch(PDOException $e){
				echo "Error Conexión ". $e;
				return -1;
			}
			
			return $ValorR;
		}

		public function getDetalleCxC()
		{
			include ("../../config.php");
			$cad = "sqlsrv:Server=" . $ServerName . "; Database=" . $Base;
			$conex = new PDO($cad,$User,$Passw);
			$jDatosR = '';
			$rowData = array();
			$i = 0;
			$consulta= "set dateformat YMD

			declare @hoy varchar(10) = format(getdate(),'yyyy-MM-dd')
			declare @FechaI datetime = format(getdate(), 'yyyy-MM-01 00:00:00')
			select C.descripcion Clasif, isnull(sum(C.monto),0) Mes, ( select isnull( sum(cc.monto),0) from cuentas CC where cc.TipoReporte = 'Cobranza' 
															 and cc.Fecha = @hoy and cc.Id = c.id) Dia
			  from cuentas C
			  where  TipoReporte = 'Cobranza'  and fecha >= @FechaI
			  group by Descripcion, id order by descripcion";
			try {
				$Rsql= $conex->prepare($consulta);
				$Rsql->execute();
				while($FilaR = $Rsql->fetch()){
					$oDatosC = new Cobranza();
					$oDatosC->Tipo = $FilaR['Clasif'];
					$oDatosC->Tmes = $FilaR['Mes']+0;
					$oDatosC->Tdia = $FilaR['Dia']+0;
					$rowData[$i] = $oDatosC;
					$i++;
				}
				$jDatosR = json_encode( $rowData);
			}catch(PDOException $e){
				echo "Error Conexión ". $e;
				return -1;
			}
			
			return $jDatosR;
		}

		public function getCuentaXPagar() 
		{
			include ("../../config.php");
			$cad = "sqlsrv:Server=" . $this->ServerN . "; Database=" . $this->BaseDB;
			$cad = "sqlsrv:Server=" . $ServerName . "; Database=" . $Base;
			$conex = new PDO($cad,$User,$Passw);
			$consulta = "select sum(0) PagMes, sum(0) PagDia, sum(MontoAplicar) Total 
			              from cprasDocumentosCabecera where TipoDocumentoId in ('FCA','FCB', 'FCC', 'NDA', 'NDB', 'NDC') ";
			$ValorR = 0;
			try{
				$Rsql = $conex->prepare($consulta);
				$Rsql->execute();
				$FilaR = $Rsql->fetch();
				$CxP = $FilaR['Total']+0;
				$Pag_Mes = $FilaR['PagMes']+0;
				$Pag_Dia = $FilaR['PagDia']+0;
				$result = array("CxP"=> $CxP, "PagMes"=>$Pag_Mes, "PagDia"=>$Pag_Dia);
				$ValorR = json_encode($result);
			}catch(PDOException $e){
				echo "Error Conexión ". $e;
				return -1;
			}  
			
			return $ValorR;
		}

		public function getDetalleCxP()
		{
			include ("../../config.php");
			$cad = "sqlsrv:Server=" . $ServerName . "; Database=" . $Base;
			$conex = new PDO($cad,$User,$Passw);
			$jDatosR = '';
			$rowData = array();
			$i = 0;
			$consulta= "set dateformat YMD
					declare @hoy varchar(10) = format(getdate(),'yyyy-MM-dd')
					declare @FechaI datetime = format(getdate(), 'yyyy-MM-01 00:00:00')
					select C.descripcion Clasif, isnull(sum(C.monto),0) Mes, ( select isnull( sum(cc.monto),0) from cuentas CC where cc.TipoReporte = 'Pago' 
																	and cc.Fecha = @hoy and cc.Id = c.id) Dia
					from cuentas C
					where  TipoReporte = 'Pago' and fecha >= @FechaI
					group by Descripcion, id order by descripcion";
			try {
				$Rsql= $conex->prepare($consulta);
				$Rsql->execute();
				while($FilaR = $Rsql->fetch()){
					$oDatosC = new Cobranza();
					$oDatosC->Tipo = $FilaR['Clasif'];
					$oDatosC->Tmes = $FilaR['Mes']+0;
					$oDatosC->Tdia = $FilaR['Dia']+0;
					$rowData[$i] = $oDatosC;
					$i++;
				}
				$jDatosR = json_encode( $rowData);
			}catch(PDOException $e){
				echo "Error Conexión ". $e;
				return -1;
			}
			
			return $jDatosR;
		}

		public function getStock()
		{
			include ("../../config.php");
			$cad = "sqlsrv:Server=" . $ServerName . "; Database=" . $Base;
			$conex = new PDO($cad,$User,$Passw);
			$jDatosR = '';
			$rowData = array();
			$i = 0;
			$consulta= "select Clasif1 Clasif, NameC1 Descp, sum (stock * CostoValor) Total from Productos
						where Stock > 0 and Clasif1 > 0
						group by Clasif1, NameC1 order by 1";
			try {
				$Rsql= $conex->prepare($consulta);
				$Rsql->execute();
				while($FilaR = $Rsql->fetch()){
					$oDatosC = new StockV();
					$oDatosC->Clasif = $FilaR['Clasif'];
					$oDatosC->Name = $FilaR['Descp'];
					$oDatosC->Valor = $FilaR['Total']+0;
					$rowData[$i] = $oDatosC;
					$i++;
				}
				$jDatosR = json_encode( $rowData);
			}catch(PDOException $e){
				echo "Error Conexión ". $e;
				return -1;
			}
			
			return $jDatosR;
		} 

		public function getGraficaPartCompra()
		{
			include ("../../config.php");
			$cad = "sqlsrv:Server=" . $ServerName . "; Database=" . $Base;
			$conex = new PDO($cad,$User,$Passw);
			$jDatosR = '';
			$rowData = array();
			$i = 0;
			$Tp = "Comp-" .date("y-m");
			$consulta= "SET LANGUAGE Spanish
			set Dateformat YMD
			declare @FechaI datetime =  format(getdate(), 'yyyy-MM-01 00:00:00')
				select SUBSTRING(TipoReporte,6,5) as FechaN, 
				    CONCAT(DATENAME(month, CONCAT('20',substring(tiporeporte,6,5),'-01 00:00:00')), '-20', SUBSTRING(tiporeporte,6,2)) as Fecha, 
					(CASE WHEN a.NombreClasifP != 'Sin Clasif' THEN a.NombreClasifP ELSE 'No Productiva' END ) Clase,
						   sum(s1) as tCompras,
						   (select sum(s1) from AuxPed where TipoReporte = '$Tp' ) as  Total
						 from AuxPed A where TipoReporte = '$Tp' 
						   group by TipoReporte, NombreClasifP
						order by 4 desc";
			try {
				$Rsql= $conex->prepare($consulta);
				$Rsql->execute();
				while($FilaR = $Rsql->fetch()){
					$oDatosC = new DatosG_C();
					$oDatosC->FechaN = $FilaR['FechaN'];
					$oDatosC->Fecha = $FilaR['Fecha'];
					$oDatosC->Clase = $FilaR['Clase'];
					$oDatosC->TCompras = $FilaR['tCompras']+0;
					$oDatosC->Part = $FilaR['tCompras'] / $FilaR['Total'] * 100;
					$rowData[$i] = $oDatosC;
					$i++;
				}
				$jDatosR = json_encode( $rowData);
			}catch(PDOException $e){
				echo "Error Conexión ". $e;
				return -1;
			}
			
			return $jDatosR;
		}

		public function getUsuario($User_DB, $Passw_DB)
		{
			session_start();
			include ("../../config.php");
			$cad = "sqlsrv:Server=" . $ServerName . "; Database=" . $Base;
			$conex = new PDO($cad,$User,$Passw);
			$jDatosR = '';
			$rowData = -1;
			$i = 0;
			$consulta= "Select Usuario, Clave, Nivel, Defecto from Usuarios where Usuario = '" . $User_DB . "'";
			
			try {
				$Rsql= $conex->prepare($consulta);
				$Rsql->execute();
				
				if($FilaR = $Rsql->fetch()) {
					$rUser = trim($FilaR['Usuario']);
					$rPassw = trim($FilaR['Clave']);
					
					if($rPassw == $Passw_DB){
						$rowData = $FilaR['Defecto'];
						$_SESSION["s_Usuario"] =$rUser;
						$_SESSION["s_Nivel"] = $FilaR['Nivel'];
						$_SESSION["s_Url"] = $FilaR['Defecto'];
					}else {
						$rowData = -3;
						$_SESSION["s_Usuario"] = null;
						$_SESSION["s_Nivel"] = null;
						$_SESSION["s_Url"] = "index.php";
					}
				}else{
					$_SESSION["s_Usuario"] = null;
					$_SESSION["s_Nivel"] = null;
					$_SESSION["s_Url"] = "index.php";
					$rowData = -2;
				}
				
			}catch(PDOException $e){
				echo "Error Conexión ". $e;
				return -1;
			}
			return $rowData;
		}
}

class DatosV{
	public $Fecha = 0;
	public $FechaN = 0;
	public $tVentas = 0;
	public $tCosto = 0;
	public $totalCMg = 0;
	public $totalCMgP = 0;
}

class DatosC {
	public $Fecha = 0;
	public $FechaN = 0;
	public $tCompras = 0;
	public $tComprasP = 0;
	public $tComprasNP = 0;
	public $tComprasE = 0;
	public $tComprasN = 0;
}

class DatosG_C {
	public $Fecha = 0;
	public $FechaN = 0;
	public $TCompras = 0;
	public $Clase = '';
	public $Part = 0;
}

class dUserDB{
	public $UserDB = '';
	public $Nivel = 0;
	public $pagDefecto = '';
}

Class Cobranza{
	public $Tipo = '';
	public $Tmes = 0;
	public $tdia = 0;
}

Class StockV{
	public $Clasif = 0;
	public $Name = '';
	public $Valor = 0;
}

Class Vendidos {
	public $Prod = '';
	public $Und = 0;
	public $Litros = 0;
	public $Precio = 0;
	public $Costo = 0;
	public $PrecioPx = 0;
	public $CostoPx = 0;
}
?>