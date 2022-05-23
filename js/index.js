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
		console.log("Iniciando...");
		this.getFacturado();
		this.getCuentaXCobrar();
		this.getGrafica();
	}

	this.getFacturado = function() {
		$.ajax({
			statuscode:{
				404:function(){
					console.log("Esta pagina no existe");
				}
			},
			url:'servidor.php',
			method:'POST',
			data:{
				rq:"1"
			}
		}).done(function(datos){
			var jDatos = JSON.parse(datos);
			let VentasAct = parseFloat(jDatos["VentaAct"]);
			let VentasANT = parseFloat(jDatos["VentaAnt"]);
			let MesLetra =  jDatos["MesPart"];
			let Pedidos = parseFloat(jDatos["Pedidos"]);
			let VentasElec = parseFloat(jDatos["VentaE"]);
			let VentasN = parseFloat(jDatos["VentaN"]);
			let IvaMes = parseFloat(jDatos["Iva"]);
			let VentasPor = VentasAct / VentasANT * 100;
			let TotalRem = parseFloat(jDatos["TotalRemP"])+0;
			let xFact = VentasAct + Pedidos + TotalRem;

			$("#idVentaE").text('E= ' + parseFloat(VentasElec).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
			$("#idVentaN").text(' N= ' + parseFloat(VentasN).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
			$("#idPartV").text(parseFloat(VentasPor).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',')+ ' % / ' + MesLetra);
			$("#idVentas").text(parseFloat(VentasAct).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
			$("#idIVA").text('IVA Mes = ' + parseFloat(IvaMes).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
			$("#idPedido").text(parseFloat(Pedidos).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
			$("#idRemPxV").text('RemPxF = ' + parseFloat(TotalRem).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
			$("#idTotal").text(parseFloat(xFact).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
			console.log(VentasAct);
			console.log(VentasANT);
			console.log(VentasPor);
		});


	}
	this.getPedidosP = function() {
		$.ajax({
			statuscode:{
				404:function(){
					console.log("Esta pagina no existe");
				}
			},
			url:'servidor.php',
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
			url:'servidor.php',
			method:'POST',
			data:{
				rq:"6"
			}
		}).done(function(datos){
			var jDatos = JSON.parse(datos);
			let TotalCxC = parseFloat(jDatos["CxC"]);
			let TotalCobxMes = parseFloat(jDatos["CobMes"]);
			let TotalCobxDia = parseFloat(jDatos["CobDia"]);
			$("#idCxC").text(   'CtaXcob : ' + parseFloat(TotalCxC).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
			$("#idCobMes").text('Cob del Mes :   ' + parseFloat(TotalCobxMes).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
			$("#idCobDia").text('Cob del Día :   ' + parseFloat(TotalCobxDia).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
			console.log(datos);
		});
	}

	this.getGrafica = function(){
		$.ajax({
			statuscode:{
				404:function(){
					console.log("Esta pagina no existe");
				}
			},
			url:'servidor.php',
			method:'POST',
			data:{
				rq:"4"
			}
		}).done(function(datos){
			if(datos != ''){
				let etiquetas = new Array();
				let tVentas = new Array();
				let tCosto = new Array();
				let tCMg = new Array();
				let tCMgP = new Array();
				let colorV = new Array();
				let colorC = new Array();
				let colorCMg = new Array();
				var jDatos = JSON.parse(datos);

				var tablaDatos = document.createElement('tabla');
				tablaDatos.classList.add('table','table-striped');
				var tr=document.createElement('tr');
				

				for(let j in jDatos){
					etiquetas.push(jDatos[j].Fecha);
					tVentas.push(jDatos[j].tVentas);
					tCosto.push(jDatos[j].tCosto);
					tCMg.push(jDatos[j].totalCMg);
					tCMgP.push(jDatos[j].totalCMgP);
					colorV.push("#00CC00");
					colorC.push("#CCCC99");
					colorCMg.push("	#3366FF");
				//	var td = document.createElement('td');
					
					var enlace1 = document.createElement('a');
					var MesC = 'Fact-' + jDatos[j].FechaN;
					var MesConsu = MesC.replace("/","-");
					console.log(MesConsu);
					enlace1.innerText = jDatos[j].Fecha;
					enlace1.href= 'RankingVentas.php?detalleR=' + MesConsu + '&Pagina=evolucion.php';
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

				var ctx = document.getElementById('idGrafica').getContext('2d');
				var myChar = new Chart(ctx, {
					 type: 'bar',
					 data: {
						 labels: etiquetas,
				
						 datasets: [
							 {
								 label: 'Facturado',
								 data: tVentas,
								 backgroundColor: colorV
							 },
							 {
								 label: 'Costo',
								 data: tCosto,
								 backgroundColor: colorC
							 },
							 {
								 label: 'CMg',
								 data: tCMg,
								 backgroundColor: colorCMg
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
                				bottom: 0
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
						  tooltips: {
							callbacks: {
								title: function(tooltipItem, data) {
									return data['labels'][tooltipItem[0]['index']];
								 // },
								//  label: function(tooltipItem, data) {
								//	return data['datasets'][0]['data'][tooltipItem['index']];
									
								//  },
								 // afterLabel: function(tooltipItem, data) {
								//	var dataset = data['datasets'][0];
								//	var percent = Math.round((dataset['data'][tooltipItem['index']] / dataset["_meta"][0]['total']) * 100)
							//		return '(' + percent + '%)';
								  }
							}
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
						}
					 }
				})
			}
		});
	}


}

var oIndex = new index();

setTimeout(function() { oIndex.ini(); },100);