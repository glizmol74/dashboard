function index(){
	this.ini = function(){
		console.log("Iniciando...");
		this.getGrafica();
	
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
				rq:"3"
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

				for(let j in jDatos){
					etiquetas.push(jDatos[j].Name);
					tValor.push(jDatos[j].Valor);
					tCLasif.push(jDatos[j].Clasif);
					
					TotalS = TotalS + jDatos[j].Valor;
					i = i + 1;
				}
				let resul = 0
				while (k < i) {
					resul = tValor[k] / TotalS * 100;
					tCMgP.push(resul);
					k = k + 1; 
				}

				var ctx = document.getElementById('idGrafStock').getContext('2d');
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
								size: 18,
							},
							offset: 0,  
							sliceOffset: 10,  
							labelOffset: 0,
							}
					},
				}
					
				})

			}
		});
	}


}

var oIndex = new index();

setTimeout(function() { oIndex.ini(); },100);