function TotalF(tPed){
	var tFact = $("#idVentas").text();
	var nFact = tFact.replace(",","");
	tFact = parseFloat(nFact);
	console.log(tFact);
	console.log(nFact);
	var xFact = tFact + tPed;
	$("#idTotal").text(parseFloat(xFact).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
	console.log(xFact);
}

	function index(){
		this.ini = function(){
			//this.getGraficaC();
			console.log("Iniciando...");
			
			this.getStock();
			console.log("Pasando Stock");
			this.getFacturado();
			console.log("Pasando Resumen Ventas");
			this.getCuentaXCobrar();
			
			this.getDetalleCxC();
			this.getCuentaXPagar();
			this.getDetalleCxP();
			//this.getGrafica();
			console.log("Pasando Resumen de Compra");
			
			console.log("Fin de Paso C ...");
			this.getCompras();
			//this.getGraficaPC();
			this.getGrafVentaEvol();
			this.getGrafCompraEvol();
			this.getUnidades();
	} 

		this.getCompras = function() {
			$.ajax({
				statuscode: {
					404:function() {
						console.log("Esta pagina no existe");
					}
				},
				url: '../php/servidor.php',
				method: 'POST',
				data: {
					rq: "11"
				}
			}).done(function(datos) {
				console.log("Compras...");
				var jDatos = JSON.parse(datos);
				console.log(datos);
				let ComprasAct = parseFloat(jDatos["ComprasAct"]) + 0 ;
				let ComprasE = parseFloat(jDatos["ComprasE"]) + 0 ;
				let ComprasN = parseFloat(jDatos["ComprasN"]) + 0 ;
				let IVA = parseFloat(jDatos["Iva"]) + 0 ;
				let TotalRem = parseFloat(jDatos["TotalRem"]) + 0 ;

				$("#idTotalC").text('E= ' + parseFloat(ComprasE).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') + '    N= ' + parseFloat(ComprasN).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
				$("#idIVAC").text('IVA Comp. Mes = ' + parseFloat(IVA).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') );
				$("#idRTotal").text( parseFloat(ComprasAct).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') );
				$("#idRemC").text('RemPxF C. ' + parseFloat(TotalRem).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') );
			})
		}

		this.getFacturado = function() {
			$.ajax({
				statuscode:{
					404:function(){
						console.log("Esta pagina no existe");
					}
				},
				url:'../php/servidor.php',
				method:'POST',
				data:{
					rq:"1"
				}
			}).done(function(datos){
				console.log("Resumen..");
				console.log(datos);
				var jDatos = JSON.parse(datos);
				console.log(jDatos);
				let VentasAct = parseFloat(jDatos["VentaAct"])+0;
				let VentasANT = parseFloat(jDatos["VentaAnt"])+0;
				let MesLetra =  jDatos["MesPart"];
				let Pedidos = parseFloat(jDatos["Pedidos"])+0;
				let VentasElec = parseFloat(jDatos["VentaE"])+0;
				let VentasN = parseFloat(jDatos["VentaN"])+0;
				let IvaMes = parseFloat(jDatos["Iva"])+0;
				let VentasPor = (VentasAct / VentasANT * 100) + 0;
				
				let LitrosVend = parseFloat(jDatos["LitrosV"])+0;
				let UndsVend = parseFloat(jDatos["UndsV"])+0;
				let CostoProm = parseFloat(jDatos["CostoP"])+0;
				let PrecioProm = parseFloat(jDatos["PrecioP"])+0;
				let CmgPedidos = parseFloat(jDatos["CmgPedido"])+0;
				let CmgRemitos = parseFloat(jDatos["CMgRem"])+0;
				let TotalRem = parseFloat(jDatos["TotalRemP"])+0;
				let PedActual = parseFloat(jDatos["PedidoAct"])+0;
				let PorcPed = parseFloat(jDatos["PorcPed"])+0;
				let PedNP = parseFloat(jDatos["PedPNP"])+0;

				let xFact = VentasAct + Pedidos + TotalRem;
				let Ped_Pend = Pedidos - PedNP;

				$("#idVentaE").text('E= ' + parseFloat(VentasElec).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',') + '    N= ' + parseFloat(VentasN).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
				$("#idPedA").text('N=' + parseFloat(PedActual).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',') ) ;
				$("#idPedP").text('PP=' + parseFloat(Ped_Pend).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',') ) ;
				$("#idPedNP").text('PNP=' + parseFloat(PedNP).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',') ) ;
				$("#idPorcPed").text(parseFloat(PorcPed).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') + ' %' + ' Meta') ;
				$("#idVentaN").text(parseFloat(VentasPor).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
				//$("#bar").css('width', VentasPor + '%');
				$("#idPartV").text(parseFloat(VentasPor).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')+ ' % / ' + MesLetra);
				$("#idVentas").text(parseFloat(VentasAct).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
				$("#idIVA").text('   IVA Mes = ' + parseFloat(IvaMes).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
				$("#idPedido").text(parseFloat(Pedidos).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
				$("#idRemPxV").text('RemPxF = ' + parseFloat(TotalRem).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
				$("#idRemCMG").text('CMg = '+parseFloat(CmgRemitos).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')+ ' %');
				$("#idTotal").text(parseFloat(xFact).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
				$("#modalPedPend").text(parseFloat(Pedidos).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
				$("#modalRemPend").text(parseFloat(TotalRem).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
				$("#modalFacturado").text(parseFloat(VentasAct).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
				$("#modalEstimada").text(parseFloat(xFact).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
				$("#idLitrosV").text(parseFloat(LitrosVend).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',')+ '  Lts.  /  ' + parseFloat(UndsVend).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',')+ '  Un.');
				$("#idUndV").text('Precio Prx= '+parseFloat(PrecioProm).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')+ ' $/Lts.');
				$("#idPromedio").text('Costo  Prx= '+parseFloat(CostoProm).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')+ ' $/Lts.');
				$("#idPedCMG").text('CMg = '+parseFloat(CmgPedidos).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')+ ' %');
				console.log(VentasAct);
				console.log(VentasANT);
				console.log(VentasPor);
				console.log(TotalRem);
			});


		}

		this.getPedidosP = function() {
			$.ajax({
				statuscode:{
					404:function(){
						console.log("Esta pagina no existe");
					}
				},
				url:'../php/servidor.php',
				method:'POST',
				data:{
					rq:"2"
				}
			}).done(function(datos){
				$("#idPedido").text(parseFloat(datos).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
				var tPed =  parseFloat(datos);
				setTimeout(function() { TotalF(tPed); },50);
			});


		}

		this.getCuentaXCobrar = function() {
			$.ajax({
				statuscode:{
					404:function(){
						console.log("Esta pagina no existe");
					}
				},
				url:'../php/servidor.php',
				method:'POST',
				data:{
					rq:"6"
				}
			}).done(function(datos){
				var jDatos = JSON.parse(datos);
				let TotalCxC = parseFloat(jDatos["CxC"]);
				let TotalCobxMes = parseFloat(jDatos["CobMes"]);
				let TotalCobxDia = parseFloat(jDatos["CobDia"]);
				$("#idCxC").text( 'Cuentas X Cobrar :  ' + parseFloat(TotalCxC).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
				//$("#idCxC").href = '#';
			//	$("#idCobMes").text('Cob del Mes :   ' + parseFloat(TotalCobxMes).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
			//	$("#idCobDia").text('Cob del Día :   ' + parseFloat(TotalCobxDia).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
				console.log(datos);
			});
		}

		this.getUnidades = function () {
			$.ajax({
				statuscode: {
					404:function() {
						console.log("Esta Pagina no existe");
					}
				},
				url: '../php/servidor.php',
				method: 'POST',
				data: {
					rq: "13"
				}
			}).done(function(datos){
				var jDatos = JSON.parse(datos);
				let Titulo = ['Prod.', 'Unidades', 'Litros', 'Precio', 'Costo', 'Precio ' + `x&#772` , 'Costo ' + `x&#772` ];
				let Liquidos = new Array();
				let Articulos = new Array();

				Liquidos.push('Liq.');
				Articulos.push('Art.');

				let Tabla = document.createElement('table');
				let Thd = document.createElement('thead');
				let Tbody = document.createElement('tbody');

				Tabla.classList.add('table', 'table-bordered', 'table-striped', 'table-sm');
				Thd.classList.add('thead-light');

				let tr=document.createElement('tr');
				let td 
				
				Titulo.forEach(function(elem){
					var th = document.createElement('th');
					th.innerHTML = elem;
					tr.appendChild(th);
				});
				Thd.appendChild(tr);
				Tabla.appendChild(Thd);

				jDatos.forEach(ele=> {
					tr= document.createElement('tr');
					$i = 0;
					for (var o in ele ) {
						td = document.createElement('td')
						if ($i == 0) {
							td.innerText = ele[o]	
						} else if ($i == 1) {
							td.innerText = parseFloat(ele[o]).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
							
						} else {
							td.innerText = parseFloat(ele[o]).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ','); 
							td.classList.add('text-center')
						}
						$i++
						tr.appendChild(td)
					}
					Tbody.appendChild(tr)
				});
				Tabla.appendChild(Tbody)

				var idCont = document.getElementById('idUnidad');
				idCont.appendChild(Tabla);
			});
		}

		this.getDetalleCxC = function() {
			$.ajax({
				statuscode:{
					404:function(){
						console.log("Esta pagina no existe");
					}
				},
				url:'../php/servidor.php',
				method:'POST',
				data:{
					rq:"7"
				}
			}).done(function(datos){
				var jDatos = JSON.parse(datos);
				let etiquetas = new Array();
				let valorMes = new Array();
				let valorDia = new Array();
				let k = 1;
				let TvalorD = 0;
				let TvalorM = 0;
				let RtotalD = 0;
				let RtotalM = 0;

				etiquetas.push("Cobro ");
				valorMes.push("Mes");
				valorDia.push("Dia");

				for(let j in jDatos){
					if(jDatos[j].Tipo.substr(0,4) == "Rete"){
						TvalorD = TvalorD + jDatos[j].Tdia;
						TvalorM= TvalorM + jDatos[j].Tmes;
					}else {
						etiquetas.push(jDatos[j].Tipo);
						valorMes.push(jDatos[j].Tmes);
						valorDia.push(jDatos[j].Tdia);
						k++;
					}
					RtotalD = RtotalD + jDatos[j].Tdia;
					RtotalM = RtotalM + jDatos[j].Tmes;
				}
				etiquetas.push("Retenciones");
				valorMes.push(TvalorM);
				valorDia.push(TvalorD);

				etiquetas.push("Total Cobranza");
				valorMes.push(RtotalM);
				valorDia.push(RtotalD);
				k=k+2;

				let TotalCxC = parseFloat(jDatos["CxC"]);
				let TotalCobxMes = parseFloat(jDatos["CobMes"]);
				let TotalCobxDia = parseFloat(jDatos["CobDia"]);

				var tablaDatos = document.createElement('table');
				tablaDatos.classList.add('table', 'table-bordered', 'table-striped', 'table-sm');
				var thd = document.createElement('thead');
				var tbody = document.createElement('tbody');
				thd.classList.add('thead-light');
				var tr=document.createElement('tr');
				let i = 0;

				while(i < k){
					var th = document.createElement('th');
					th.classList.add('text-center', 'thead-light');
					th.innerText =  etiquetas[i].substr(0,14);
					tr.appendChild(th);
					i++;
				}
				thd.appendChild(tr);
				tablaDatos.appendChild(thd);

				var tr=document.createElement('tr');
				i = 0;
				while(i < k){
					
					var td = document.createElement('td');
					if (i==0){
						td.innerHTML = '<a href="#">' + valorDia[i] + '</a>';
						td.classList.add('text-center');

					}else{
						td.classList.add('text-center')
						td.innerText = ' ' + parseFloat(valorDia[i]).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
					}
					tr.appendChild(td);
					i++;
				}
				tbody.appendChild(tr);
				tablaDatos.appendChild(tbody);

				var tr=document.createElement('tr');
				i = 0;
				while(i < k){
					
					var td = document.createElement('td');
					
					if (i==0){
						//td.innerText = valorMes[i];
						td.innerHTML = '<a href="#">' + valorMes[i] + '</a>';
						td.classList.add('text-center');
					}else{
						td.classList.add('text-center')
						//td.align.right;
						td.innerText =' ' + parseFloat(valorMes[i]).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
						
					}
					tr.appendChild(td);
					i++;
				}
				tbody.appendChild(tr);
				tablaDatos.appendChild(tbody);

				

				var idCont = document.getElementById('idTablaCxC');
				idCont.appendChild(tablaDatos);

			});
		}

		this.getCuentaXPagar = function() {
			$.ajax({
				statuscode:{
					404:function(){
						console.log("Esta pagina no existe");
					}
				},
				url:'../php/servidor.php',
				method:'POST',
				data:{
					rq:"8"
				}
			}).done(function(datos){
				var jDatos = JSON.parse(datos);
				let TotalCxP = parseFloat(jDatos["CxP"]);
				let TotalPagxMes = parseFloat(jDatos["PagMes"]);
				let TotalPagxDia = parseFloat(jDatos["PagDia"]);
				$("#idCxP").text(   'Cuentas X Pagar :  $ ' + parseFloat(TotalCxP).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
			//	$("#idCobMes").text('Cob del Mes :   ' + parseFloat(TotalCobxMes).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
			//	$("#idCobDia").text('Cob del Día :   ' + parseFloat(TotalCobxDia).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
				console.log(datos);
			});
		}

		this.getDetalleCxP = function() {
			$.ajax({
				statuscode:{
					404:function(){
						console.log("Esta pagina no existe");
					}
				},
				url:'../php/servidor.php',
				method:'POST',
				data:{
					rq:"9"
				}
			}).done(function(datos){
				var jDatos = JSON.parse(datos);
				let etiquetas = new Array();
				let valorMes = new Array();
				let valorDia = new Array();
				let k = 1;
				let TvalorD = 0;
				let TvalorM = 0;
				let RtotalD = 0;
				let RtotalM = 0;

				etiquetas.push("Pagos");
				valorMes.push("Mes");
				valorDia.push("Dia");

				for(let j in jDatos){
					if(jDatos[j].Tipo.substr(0,4) == "Rete"){
						TvalorD = TvalorD + jDatos[j].Tdia;
						TvalorM= TvalorM + jDatos[j].Tmes;
					}else {
						etiquetas.push(jDatos[j].Tipo);
						valorMes.push(jDatos[j].Tmes);
						valorDia.push(jDatos[j].Tdia);
						k++;
					}
					RtotalD = RtotalD + jDatos[j].Tdia;
					RtotalM = RtotalM + jDatos[j].Tmes;
				}

				if( TvalorD > 0 || TvalorM > 0 ){
					etiquetas.push("Retenciones");
					valorMes.push(TvalorM);
					valorDia.push(TvalorD);
					k=k+1;
				}

				etiquetas.push("Total Pagos.  $ ");
				valorMes.push(RtotalM);
				valorDia.push(RtotalD);
				k=k+1;

				let TotalCxP = parseFloat(jDatos["CxP"]);
				let TotalPagxMes = parseFloat(jDatos["PagMes"]);
				let TotalPagxDia = parseFloat(jDatos["PagDia"]);

				var tablaDatos = document.createElement('table');
				tablaDatos.classList.add('table','table-bordered', 'table-striped', 'table-sm');
				var thd = document.createElement('thead');
				var tbody = document.createElement('tbody');
				thd.classList.add('thead-light');
				var tr=document.createElement('tr');
				let i = 0;

				while(i < k){
					var th = document.createElement('th');
					th.classList.add('text-center', 'thead-light');
					th.innerText =  etiquetas[i].substr(0,14);
					tr.appendChild(th);
					i++;
				}
				thd.appendChild(tr);
				tablaDatos.appendChild(thd);
				
				var tr=document.createElement('tr');
				i = 0;
				while(i < k){
					
					var td = document.createElement('td');
					if (i==0){
						td.innerText = valorDia[i];
						td.innerHTML = '<a href="#">' + valorDia[i] + '</a>';
						td.classList.add('text-center');
					}else{
						td.classList.add('text-center')
						td.innerText = ' ' + parseFloat(valorDia[i]).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
					}
					tr.appendChild(td);
					i++;
				}
				tbody.appendChild(tr);
				tablaDatos.appendChild(tbody);

				var tr=document.createElement('tr');
				i = 0;
				while(i < k){
					
					var td = document.createElement('td');
					if (i==0){
						td.innerText = valorMes[i];
						td.innerHTML = '<a href="#">' + valorMes[i] + '</a>';
						td.classList.add('text-center');
					}else{
						td.classList.add('text-center')
						td.innerText =' ' + parseFloat(valorMes[i]).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
					}
					tr.appendChild(td);
					i++;
				}
				tbody.appendChild(tr);
				tablaDatos.appendChild(tbody);

				var idCont = document.getElementById('idTablaCxP');
				idCont.appendChild(tablaDatos);

			});
		}

		this.getStock = function() {
			$.ajax({
				statuscode:{
					404:function(){
						console.log("Esta pagina no existe");
					}
				},
				url:'../php/servidor.php',
				method:'POST',
				data:{
					rq:"3"
				}
			}).done(function(datos){
				console.log(datos);
				var jDatos = JSON.parse(datos);
				let etiquetas = new Array();
				let Calsif = new Array();
				let valorT = new Array();
				let k = 0;
				let TvalorT = 0;
				let Rtotal = 0;
				

				for(let j in jDatos){
					etiquetas.push(jDatos[j].Name+' ');
					valorT.push(jDatos[j].Valor);
					Calsif.push(jDatos[j].Calsif);
					k++;
					Rtotal = Rtotal + jDatos[j].Valor;
				}

		

				var tablaDatos = document.createElement('table');
				tablaDatos.classList.add('table','table-bordered', 'table-striped', 'table-sm');
				var thd = document.createElement('thead');
				thd.classList.add('thead-light', 'text-center');
				var tr=document.createElement('tr');
				let i = 0;

				while(i < k){
					var th = document.createElement('th');
					th.innerText =  etiquetas[i];
					tr.appendChild(th);
					i++;
				}
				thd.appendChild(tr)
				tablaDatos.appendChild(thd);

				var tr=document.createElement('tr');
				i = 0;
				while(i < k){
					
					var td = document.createElement('td');
					td.align.left;
					td.innerText =' ' + parseFloat(valorT[i]).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
					td.classList.add('text-center');
					tr.appendChild(td);
					i++;
				}
				tablaDatos.appendChild(tr);


				var idCont = document.getElementById('idTablaSxV');
				idCont.appendChild(tablaDatos);
				$("#idSxV").text(   'Stock Valorizado :  $ ' + parseFloat(Rtotal).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ','));

			});
		}


		this.getGraficaC = function(){
			$.ajax({
				statuscode:{
					404:function(){
						console.log("Esta pagina no existe");
					}
				},
				url:'../php/servidor.php',
				method:'POST',
				data:{
					rq:"10"
				}
			}).done(function(datos){
				if(datos != ''){
					let etiquetas = new Array();
					let tCompras = new Array();
					let tComprasP = new Array();
					let tComprasNP= new Array();
					let tComprasE = new Array();
					let tComprasN = new Array();
					let tCMgP = new Array();
					let colorC = new Array();
					let colorP = new Array();
					let colorNP = new Array();
					var jDatos = JSON.parse(datos);

					var tablaDatos = document.createElement('tabla');
					tablaDatos.classList.add('table','table-striped');
					var tr=document.createElement('tr');
					

					for(let j in jDatos){
						etiquetas.push(jDatos[j].Fecha);
						tCompras.push(jDatos[j].tCompras);
						tComprasP.push(jDatos[j].tComprasP);
						tComprasNP.push(jDatos[j].tComprasNP);
						tComprasE.push(jDatos[j].tComprasE);
						tComprasN.push(jDatos[j].tComprasN);
						colorC.push("#FF0033");
						colorP.push("#CCCC99");
						colorNP.push("#3366FF");
						var td = document.createElement('td');
						
						var enlace1 = document.createElement('a');
						var MesC = 'Fact-' + jDatos[j].FechaN;
						var MesL = jDatos[j].Fecha;
						var MesConsu = MesC.replace("/","-");
						var tama = MesL.length- 2
						var Etiq = MesL.substr(0,3) + '-' + MesL.substr(tama,2);
						console.log(MesConsu);
						console.log(Etiq);
						enlace1.innerText = Etiq;
						enlace1.href= 'RankingCompras.php?detalleR=' + MesConsu + '& Pagina=dashboard.php & TC=' + jDatos[j].tCompras;
						enlace1.href = enlace1.href + '&TcE=' + jDatos[j].tComprasE + '&TcN=' + jDatos[j].tComprasN + '&TcP=' + jDatos[j].tComprasP;
						enlace1.href = enlace1.href + '&TcNP=' + jDatos[j].tComprasNP + '&Op=1';
						var th = document.createElement('th');
						th = enlace1;
						tr.appendChild(th);
						var th = document.createElement('th');
						th.innerText = ''
						tr.appendChild(th);
					}
					tablaDatos.appendChild(tr);

					var idCont = document.getElementById('idConTablaC');
					idCont.appendChild(tablaDatos);

					var ctx = document.getElementById('idGraficaC').getContext('2d');
					var myChar = new Chart(ctx, {
						type: 'bar',
						data: {
							labels: etiquetas,
					
							datasets: [
								{
									label: 'Compras',
									data: tCompras,
									backgroundColor: colorC
								},
								{
									label: 'Compras Productiva',
									data: tComprasP,
									backgroundColor: colorP
								},
								{
									label: 'Compras No Productivas',
									data: tComprasNP,
									backgroundColor: colorNP
								}
							]
						},
						options:
						{
							layout: {
								padding:{
									left: 0,
									right: 0,
									top: 35,
									bottom: 40
								}
							},
							responsive : true,
							scales: {
								yAxes: [{
								ticks: {
									beginAtZero: true,
									callback: function(value, index, values) {
									if(parseInt(value) >= 1000){
										return '$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
									} else {
										return '$' + value;
									}
									}
								}
								}]
							},
							plugins: {
								datalabels: {
									
									formatter: function(value, context) {
										value = value / 1000;
										return Number(value).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
									},
									textAlign: 'center',
									align: 'top',
									anchor: 'end',
									clamp: true,
									rotation: -90,
									font: {
										weight: 'bold',
										size: 11,
									}
								}
							},
							legend: {
								
								position: 'bottom',
								align: 'start',
								rtl: true,
								padding: 70
							}
						}
					})

					var ctx2 = document.getElementById('idGraficaCMg').getContext('2d');
					var myChar = new Chart(ctx2, {
						type: 'line',
						data: {
							labels: etiquetas,
							datasets: [
								{
									label: 'CMg %',
									data: tCMgP,
									fill: false,
									borderColor: 'red',
									backgroundColor: 'transparent',
									pointBorderColor: 'blue',
									pointBackgroundColor: 'blue'
								}
							]
						},
						options:
						{
							layout: {
								padding:{
									left: 0,
									right: 1,
									top: 15,
									bottom: 0
								}
							},
							responsive : true,
							yAxes:[{
								ticks:{
									max: 100
								}
							}],
							plugins: {
								datalabels: {
									
									formatter: function(value, context) {
										return Number(value).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',') + ' %';
									},
									textAlign: 'center',
									align: 'top',
									anchor: 'end',
									clamp: true,
									rotation: -15,
									font: {
										weight: 'bold',
										size: 12,
									}
								}
							},
							legend: {
								
								position: 'bottom',
								align: 'start',
								rtl: true,
								padding: 30
							}
						}
					})
				}
			});
		}


		this.getGraficaPC = function(){
			$.ajax({
				statuscode:{
					404:function(){
						console.log("Esta pagina no existe");
					}
				},
				url:'servidor.php',
				method:'POST',
				data:{
					rq:"12"
				}
			}).done(function(datos){
				if(datos != ''){
					let etiquetas = new Array();
					let tValor = new Array();
					let tCLasif = new Array();
					let tCMgP = new Array();
					let colorV	 = new Array();
					let  TotalS = 0;
					var jDatos = JSON.parse(datos);
					console.log('Datos Grafica Pie de Compra');
					console.log(datos);
					let i = 0;
					let k = 0;
	
					let COLORS = [
						'#4dc9f6',
						'#f67019',
						'#f53794',
						'#f1c40f',
						'#acc236',
						'#166a8f'
					];

					var tablaDatos = document.createElement('table');
					var tbbody = document.createElement("tbody");
					tablaDatos.classList.add('table','table-striped');

					var tr=document.createElement('tr');
	
					for(let j in jDatos){
						etiquetas.push(jDatos[j].Clase);
						tValor.push(jDatos[j].TCompras);
						tCLasif.push(jDatos[j].Clase);
						var td = document.createElement('td');
						var texto = document.createTextNode(etiquetas[j] + " = " + Number(tValor[j]).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') + " ");
						td.appendChild(texto);
						tr.appendChild(td);
						TotalS = TotalS + jDatos[j].TCompras;
						i = i + 1;
					}
					tbbody.appendChild(tr);
					tablaDatos.appendChild(tbbody);

					var idCont = document.getElementById('idTablaPie');
					idCont.appendChild(tablaDatos);
					
					let resul = 0
					while (k < i) {
						resul = tValor[k] / TotalS * 100;
						tCMgP.push(resul);
						k = k + 1;
					}
	
					var ctx = document.getElementById('idGraficaPie').getContext('2d');
					var myChar = new Chart(ctx, {
						animationEnabled: true,
						
						 type: 'pie',
						 data: {
	
							yValueFormatString: "##0.00\"%\"",
							indexLabel: "{label} {y}",
							labels: etiquetas,
					
							 datasets: [
								 {
									 data: tCMgP,
									 backgroundColor: COLORS
								 }
							 ]
						 },
						responsive : true,
						options: {
							plugins: {
							  datalabels: {
								formatter: (value) => {
								  return Number(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') + ' %';
								},
								align: 'end',
								font: {
									size: 16,
								},
								offset: -20,  
								sliceOffset: 25,  
								labelOffset: 0,
								}
						},
					}
						
					})
	
				}
			});
		}

		this.getGrafCompraEvol = function() {
			$.ajax({
				statuscode:{
					404:function(){
						console.log("Esta pagina no existe");
					}
				},
				url:'../php/servidor.php',
				method:'POST',
				data:{
					rq:"10"
				}
			}).done(function(datos){
				if(datos != ''){
					console.log('highCharts Compras')
					var jDatos = JSON.parse(datos);
					
					//var cat = ['<a ref="#"> Marzo-21 </a>','Abril-21', 'Mayo-21','Junio-21','Julio-21','Agosto-21','Septiembre-21','Octubre-21','Noviembre-21','Diciembre-21','Eneto-22','Febrero-22','Marzo-22']
					var cat = new Array()
					var tCompras = new Array();
					var tComprasP = new Array();
					var tComprasNP = new Array();
					var tComprasE = new Array();
					var tComprasN = new Array();
					var Ref = new Array();

					var tablaDatos = document.createElement('table');
					tablaDatos.classList.add('table','table-striped', 'table-sm');
					var tr=document.createElement('tr');

					for(let j in jDatos){
						cat.push(jDatos[j].Fecha);
						tCompras.push(parseFloat(jDatos[j].tCompras));
						tComprasP.push(parseFloat(jDatos[j].tComprasP));
						tComprasNP.push(parseFloat(jDatos[j].tComprasNP));
						tComprasE.push(parseFloat(jDatos[j].tComprasE));
						tComprasN.push(parseFloat(jDatos[j].tComprasN));

						var td = document.createElement('td');
					
						var enlace1 = document.createElement('a');
						var MesC = 'Fact-' + jDatos[j].FechaN;
						var MesL = jDatos[j].Fecha;
						var MesConsu = MesC.replace("/","-");
						var tama = MesL.length- 2
						var Etiq = MesL.substr(0,3) + '-' + MesL.substr(tama,2);
						console.log(MesConsu);
						console.log(Etiq);
						enlace1.innerText = Etiq;
						enlace1.href= 'RankingCompras.php?detalleR=' + MesConsu + '& Pagina=dashboard.php & TC=' + jDatos[j].tCompras;
						enlace1.href = enlace1.href + '&TcE=' + jDatos[j].tComprasE + '&TcN=' + jDatos[j].tComprasN + '&TcP=' + jDatos[j].tComprasP;
						enlace1.href = enlace1.href + '&TcNP=' + jDatos[j].tComprasNP + '&Op=1';
						var gEtiq = '<a href="' + enlace1.href + '">'
						Ref.push(gEtiq)
						var th = document.createElement('th');
						th = enlace1;
						tr.appendChild(th);
						var th = document.createElement('th');
						th.innerText = ''
						tr.appendChild(th);
					}

					tablaDatos.appendChild(tr);

					var idCont = document.getElementById('idConTablaC');
					idCont.appendChild(tablaDatos);


					var chart = Highcharts.chart('GraficaComprasEvol', {
						chart: {
							type: 'column'
						},
		
						title: { 
							text: 'Evolución de Compras',
							style: {
								color: '#FF0033',
								fontSize: '24px'
							}
						},
		
						legend: {
							align: 'center',
							verticalAlign: 'top',
							layout: 'horizontal',
							y:5,
							enabled: true,
						},
						colors: ["#FF0033","#CCCC99","#4dc9f6"],

						xAxis: {
							categories: [
								cat[0], cat[1], cat[2], cat[3], cat[4], cat[5], cat[6],
								cat[7], cat[8], cat[9], cat[10], cat[11], cat[12]
							],
							labels: {
								format: '{value}',
								// formatter: function() {
								// 	var cad = this.axis.defaultLabelFormatter.call(this)
								// 	var i = 0
								// 	var label = ''

								// 	for (i= 0; i<13; i++) {
								// 		if (cad == cat[i] ) {
								// 			label = '<a href="RankingCompras.php">' + cad + '</a>'
								// 			console.log(Ref[i])
								// 			break
								// 		}
								// 	}
								// 	return label
								// },
								useHTML: true,
							},
							crosshair: true,
						},

						yAxis: {
							min: 0,
							labels: {
								
								 formatter: function() {
										this.value = this.value;

										return '$ ' + Number(this.value).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
									},
							},
							title: null
							
						},
		
						tooltip: {
							headerFormat: '<span style="font-size:12px"><b>{point.x}</span></b><br>',
							pointFormat: '<span style="color:{point.color}">{series.name}</span>: $ <b>{point.y:,.2f} </b><br>',
							footerFormat: '</table>',
							shared: true,
							useHTML: true,
						},
		
						
						plotOptions: {
							column: {
								pointPadding: 0.1,
								borderWidth: 0,
								cursor: 'pointer',
								dataLabels: {
									enabled: true,
									rotation: -90,
									//color: '#FFF0FF',
									backgroundColor: null,
									align: 'end',
									allowOverlap: true,
									overflow: "allow",
									formatter: function() {
										value = this.point.y / 1000;
										return Number(value).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
									},
									
									y: -5,
									style: {
										fontSize: '8px',
										fontFamily: 'Verdana, sans-serif'
									},
								}
								
								
							}
						},
		
						series: [{
							name: 'Compras',
							data: [ tCompras[0], tCompras[1], tCompras[2], tCompras[3], tCompras[4], tCompras[5], tCompras[6],
								    tCompras[7], tCompras[8], tCompras[9], tCompras[10], tCompras[11], tCompras[12] ],
									
						}, {
							name: 'Compras Productivas',
							data: [ tComprasP[0], tComprasP[1], tComprasP[2], tComprasP[3], tComprasP[4], tComprasP[5], tComprasP[6], 
						    	    tComprasP[7], tComprasP[8], tComprasP[9], tComprasP[10], tComprasP[11], tComprasP[12] ],
									
						}, {
							name: 'Compras No Productivas',
							data: [ tComprasNP[0], tComprasNP[1], tComprasNP[2], tComprasNP[3], tComprasNP[4], tComprasNP[5], tComprasNP[6],
									tComprasNP[7], tComprasNP[8], tComprasNP[9], tComprasNP[10], tComprasNP[11], tComprasNP[12]]
						}],
					});

				}
			});
		}

		this.getGrafVentaEvol = function() {
			$.ajax({
				statuscode:{
					404:function(){
						console.log("Esta pagina no existe");
					}
				},
				url:'../php/servidor.php',
				method:'POST',
				data:{
					rq:"4"
				}
			}).done(function(datos){
				if(datos != ''){
					console.log('highCharts')
					var jDatos = JSON.parse(datos);
					
					//var cat = ['<a ref="#"> Marzo-21 </a>','Abril-21', 'Mayo-21','Junio-21','Julio-21','Agosto-21','Septiembre-21','Octubre-21','Noviembre-21','Diciembre-21','Eneto-22','Febrero-22','Marzo-22']
					var cat = new Array()
					var tVentas = new Array();
					var tCosto = new Array();
					var tCMg = new Array();
					var tCMgP = new Array();
					var minimo = jDatos[0].totalCMgP;
					var maximo = jDatos[0].totalCMgP;

					var tablaDatos = document.createElement('table');
					tablaDatos.classList.add('table','table-striped', 'table-sm');
					var tr=document.createElement('tr');

					for(let j in jDatos){
						cat.push(jDatos[j].Fecha);
						tVentas.push(parseFloat(jDatos[j].tVentas));
						tCosto.push(parseFloat(jDatos[j].tCosto));
						tCMg.push(parseFloat(jDatos[j].totalCMg));
						tCMgP.push(jDatos[j].totalCMgP);
						if ( jDatos[j].totalCMgP < minimo )
							minimo = jDatos[j].totalCMgP;
						if (jDatos[j].totalCMgP > maximo)
							maximo = jDatos[j].totalCMgP;
						
						var td = document.createElement('td');
					
						var enlace1 = document.createElement('a');
						var MesC = 'Fact-' + jDatos[j].FechaN;
						var MesL = jDatos[j].Fecha;
						var MesConsu = MesC.replace("/","-");
						var tama = MesL.length- 2
						var Etiq = MesL.substr(0,3) + '-' + MesL.substr(tama,2);
						console.log(MesConsu);
						console.log(Etiq);
						enlace1.innerText = Etiq;
						enlace1.href= 'RankingVentas.php?detalleR=' + MesConsu + '& Pagina=dashboard.php';
						var th = document.createElement('th');
						th = enlace1;
						tr.appendChild(th);
						var th = document.createElement('th');
						th.innerText = ''
						tr.appendChild(th);
					}

					tablaDatos.appendChild(tr);

					var idCont = document.getElementById('idConTabla');
					idCont.appendChild(tablaDatos);

					var x = Math.ceil(minimo / 10, 0) - 1
					minimo = x*10
					x = Math.floor(maximo / 10, 0) + 2
					maximo = x*10
					console.log( 'Minimo ')
					console.log( minimo)
					var cTitulo = "Evolución de CMg % / Facturación"

					var chart = Highcharts.chart('GraficaVentaEvol', {
						chart: {
							type: 'column'
						},
		
						title: { 
							text: 'Evolución de Facturación',
							style: {
								color: '#27AE60',
								fontSize: '24px'
							}
						},
		
						legend: {
							align: 'center',
							verticalAlign: 'top',
							layout: 'horizontal',
							y:5,
							enabled: true,
						},
						colors: ["#00CC00","#CCCC99","#3366FF"],

						xAxis: {
							categories: [
								cat[0], cat[1], cat[2], cat[3], cat[4], cat[5], cat[6],
								cat[7], cat[8], cat[9], cat[10], cat[11], cat[12]
							],
							labels: {
								format: '{value}',
								useHTML: true,
							},
							crosshair: true,
						},

						yAxis: {
							min: 0,
							labels: {
								
								 formatter: function() {
										this.value = this.value;

										return '$ ' + Number(this.value).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
									},
							},
							title: null
							
						},
		
						tooltip: {
							headerFormat: '<span style="font-size:12px"><b>{point.x}</span></b><br>',
							pointFormat: '<span style="color:{point.color}">{series.name}</span>: $ <b>{point.y:,.2f} </b><br>',
							footerFormat: '</table>',
							shared: true,
							useHTML: true,
						},
		
						
						plotOptions: {
							column: {
								pointPadding: 0.1,
								borderWidth: 0,
								cursor: 'pointer',
								dataLabels: {
									enabled: true,
									rotation: -90,
									//color: '#FFF0FF',
									backgroundColor: null,
									align: 'end',
									allowOverlap: true,
									overflow: "allow",
									formatter: function() {
										value = this.point.y / 1000;
										return Number(value).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
									},
									
									y: -5,
									style: {
										fontSize: '8px',
										fontFamily: 'Verdana, sans-serif'
									},
								}
								
								
							}
						},
		
						series: [{
							name: 'Facturado',
							data: [ tVentas[0], tVentas[1], tVentas[2], tVentas[3], tVentas[4], tVentas[5], tVentas[6],
								    tVentas[7], tVentas[8], tVentas[9], tVentas[10], tVentas[11], tVentas[12] ],
									
						}, {
							name: 'Costo',
							data: [ tCosto[0], tCosto[1], tCosto[2], tCosto[3], tCosto[4], tCosto[5], tCosto[6], 
						    	    tCosto[7], tCosto[8], tCosto[9], tCosto[10], tCosto[11], tCosto[12] ],
									
						}, {
							name: 'CMg',
							data: [ tCMg[0], tCMg[1], tCMg[2], tCMg[3], tCMg[4], tCMg[5], tCMg[6],
									tCMg[7], tCMg[8], tCMg[9], tCMg[10], tCMg[11], tCMg[12]]
						}],
					});

					console.log('highCharts CMG')

					var chart2 = Highcharts.chart('idGraficaCMg', {
						chart: {
							type: 'line'
						},
		
						title: { 
							//text: 'Evolución de CMg % Facturación',
							text: cTitulo,
							style: {
								color: '#27AE60',
								fontSize: '24px'
							}
						},
		
						legend: {
							align: 'center',
							verticalAlign: 'top',
							layout: 'horizontal',
							y:5,
							enabled: true,
						},
						colors: ["#00CC00","#CCCC99","#3366FF"],

						xAxis: {
							categories: [
								cat[0], cat[1], cat[2], cat[3], cat[4], cat[5], cat[6],
								cat[7], cat[8], cat[9], cat[10], cat[11], cat[12]
							],
							labels: {
								format: '{value}',
								useHTML: true,
							},
							
						},

						yAxis: {
							min: minimo,
							max: maximo,
							labels: {
								
								 formatter: function() {
										this.value = this.value;

										return Number(this.value).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ',') + ' %';
									},
							},
							title: null
							
						},
		
						tooltip: {
							headerFormat: '<span style="font-size:11px">{point.x}</span><br>',
							pointFormat: '<span style="color:{point.color}">{series.name}</span>: <b>{point.y:.2f} %</b>',
							footerFormat: '</table>',
							shared: true,
							crosshair: true,
						},
		
						
						plotOptions: {
							line: {
								pointPadding: 0,
								borderWidth: 0,
								cursor: 'pointer',
								dataLabels: {
									enabled: true,
									rotation: -90,
									//color: '#FFF0FF',
									backgroundColor: null,
									align: 'end',
								
									formatter: function() {
										value = this.point.y 
										return Number(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',') + ' %';
									},
									
									y: -10,
									style: {
										fontSize: '8px',
										fontFamily: 'Verdana, sans-serif'
									}
								},
								enableMouseTracking: true
								
							}
						},
		
						series: [{
							name: 'CMg %',
							data: [ tCMgP[0], tCMgP[1], tCMgP[2], tCMgP[3], tCMgP[4], tCMgP[5], tCMgP[6],
									tCMgP[7], tCMgP[8], tCMgP[9], tCMgP[10], tCMgP[11], tCMgP[12]]
									
						}],
					});
				}
			});
		}
	}
	
	
var oIndex = new index();

setTimeout(function() { oIndex.ini(); }, 200);
