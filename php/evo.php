<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Highcharts Example</title>

		<style type="text/css">
#container {
  height: 500px; 
  width: 100%;
}

.highcharts-figure, .highcharts-data-table table {
  min-width: 310px; 
  max-width: 100%;
  width: 100%;
  margin: 0.2em auto;
}

.highcharts-data-table table {
  font-family: Verdana, sans-serif;
  border-collapse: collapse;
  border: 1px solid #EBEBEB;
  margin: 2px auto;
  text-align: center;
  width: 100%;
  max-width: 100%;
}
.highcharts-data-table caption {
  padding: 1em 0;
  font-size: 1.2em;
  color: #555;
}
.highcharts-data-table th {
  font-weight: 600;
  padding: 0.5em; 
}
.highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
  padding: 0.1em;
}
.highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
  background: #f8f8f8;
}
.highcharts-data-table tr:hover {
  background: #f1f7ff;
}

		</style>
	</head>
	<body>
	<script src="js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script src="./code/highcharts.js"></script>
<script src="./code/highcharts-3d.js"></script>
<script src="./code/modules/exporting.js"></script>
<script src="./code/modules/export-data.js"></script>
<script src="./code/modules/accessibility.js"></script>

<figure class="highcharts-figure">
    <div id="container"></div>
    <p class="highcharts-description">
        Chart designed to show the difference between 0 and null in a 3D column
        chart. A null point represents missing data, while 0 is a valid value.
    </p>
</figure>


		<script type="text/javascript">
				var etiquetas = [];
				let tVentas = new Array();
				let tCosto = new Array();
				let tCMg = new Array();
				let colorV = new Array();
				let colorC = new Array();
				let colorCMg = new Array();
$.ajax({
			statuscode:{
				404:function(){
					console.log("Esta pagina no existe");
				}
            },
			url:'servidor.php',
			method:'POST',
			data:{
				rq:"5"
            }
		}).done(function(datos){
			if(datos != ''){

				var jDatos = JSON.parse(datos);

				for(let j in jDatos){
                    var xx = jDatos[j].totalCMg;
                    //console.log(xx);
                    etiquetas[j] = xx;
                    
					tVentas.push(jDatos[j].tVentas);
					tCosto.push(jDatos[j].tCosto);
					tCMg.push(jDatos[j].totalCMg);
					colorV.push("#00CC00");
					colorC.push("679B6B");
					colorCMg.push("	#3366FF");
				}

				//var ctx = document.getElementById('idGrafica').getContext('2d');

			}
		});



        let data2 = [1, 1.8, 3, 3.5, 4];
        let data4 =  Array(etiquetas);
        /*let etiquetas2 = ['Enero-2020','Febrero-2020','Marzo-2020','Abril-2020','Mayo-2020'];
        let etiquetas2 = JSON.stringify(etiquetas); */
        let data3 = data2;
        
        console.log(data3);
        console.log(etiquetas);
        console.log(data4);
        console.log(data4[0].toString());

       
Highcharts.chart('container', {
    chart: {
        type: 'column',
        options3d: {
            enabled: true,
            alpha: 15,
            beta: 15,
            depth: 50
        }
    },
    title: {
        text: '3D chart with null values'
    },
    subtitle: {
        text: 'Notice the difference between a 0 value and a null point'
    },
    plotOptions: {
        allowPointSelect: true,
        cursor: 'pointer',
        column: {
            depth: 55,
            dataLabels: {
                enabled: true,
                format : '<b> {point.y} </b>'
                
            }
        }
    },
    xAxis: {
        categories: data4[0], /*Highcharts.getOptions().lang.shortMonths,*/
        labels: {
            skew3d: true,
            style: {
                fontSize: '16px'
            }
        }
    },
    yAxis: {
        title: {
            text: null
        }
    },
    series: [{
        name: 'Sales',
        data: data3,
        pointWidth: 40
    },
    {
        name: 'Costo',
        data: data2,
        pointWidth: 40

    }]
});
		</script>
	</body>
</html>
