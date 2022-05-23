<!DOCTYPE html>
<html>
<head>
<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
<meta content="utf-8" http-equiv="encoding">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Table Export Demo : Simple Example to extract table data into JSON XML,PNG,CSV,TXT,SQL,MS-Word,Ms-Excel,Ms-Powerpoint and PDF format</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript" src="tableExport.js"></script>
<script type="text/javascript" src="jquery.base64.js"></script>
<script type="text/javascript" src="html2canvas.js"></script>
<script type="text/javascript" src="jspdf/libs/sprintf.js"></script>
<script type="text/javascript" src="jspdf/jspdf.js"></script>
<script type="text/javascript" src="jspdf/libs/base64.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body style="padding:30px;">
<div class="row">
<div class="col-md-12">
<h3>Demo : Simple Example to extract table data into JSON XML,PNG,CSV,TXT,SQL,MS-Word,Ms-Excel,Ms-Powerpoint and PDF format</h3>
<div class="btn-group pull-right" style=" padding: 10px; margin-right:140px">
			<div class="dropdown open">
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
     <span class="glyphicon glyphicon-th-list"></span> Export
   
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
    <li><a href="#" onclick="$('#customers').tableExport({type:'json',escape:'false'});"> <img src="images/json.jpg" width="24px"> JSON</a></li>
								<li><a href="#" onclick="$('#customers').tableExport({type:'json',escape:'false',ignoreColumn:'[2,3]'});"><img src="images/json.jpg" width="24px">JSON (ignoreColumn)</a></li>
								<li><a href="#" onclick="$('#customers').tableExport({type:'json',escape:'true'});"> <img src="images/json.jpg" width="24px"> JSON (with Escape)</a></li>
								<li class="divider"></li>
								<li><a href="#" onclick="$('#customers').tableExport({type:'xml',escape:'false'});"> <img src="images/xml.png" width="24px"> XML</a></li>
								<li><a href="#" onclick="$('#customers').tableExport({type:'sql'});"> <img src="images/sql.png" width="24px"> SQL</a></li>
								<li class="divider"></li>
								<li><a href="#" onclick="$('#customers').tableExport({type:'csv',escape:'false'});"> <img src="images/csv.png" width="24px"> CSV</a></li>
								<li><a href="#" onclick="$('#customers').tableExport({type:'txt',escape:'false'});"> <img src="images/txt.png" width="24px"> TXT</a></li>
								<li class="divider"></li>				
								
								<li><a href="#" onclick="$('#customers').tableExport({type:'excel',escape:'false'});"> <img src="images/xls.png" width="24px"> XLS</a></li>
								<li><a href="#" onclick="$('#customers').tableExport({type:'doc',escape:'false'});"> <img src="images/word.png" width="24px"> Word</a></li>
								<li><a href="#" onclick="$('#customers').tableExport({type:'powerpoint',escape:'false'});"> <img src="images/ppt.png" width="24px"> PowerPoint</a></li>
								<li class="divider"></li>
								<li><a href="#" onclick="$('#customers').tableExport({type:'png',escape:'false'});"> <img src="images/png.png" width="24px"> PNG</a></li>
								<li><a href="#" onclick="$('#customers').tableExport({type:'pdf',pdfFontSize:'7',escape:'false'});"> <img src="images/pdf.png" width="24px"> PDF</a></li>
								
  </ul>
</div>
		</div>
<table id="customers" class="table table-striped" >
	<thead>			
		<tr class='warning'>
			<th>Country</th>
			<th>Population</th>
			<th>Date</th>
			<th>%ge</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Chinna</td>
			<td>1,363,480,000</td>
			<td>March 24, 2014</td>
			<td>19.1</td>
		</tr>
		<tr>
			<td>India</td>
			<td>1,241,900,000</td>
			<td>March 24, 2014</td>
			<td>17.4</td>
		</tr>
		<tr>
			<td>United States</td>
			<td>317,746,000</td>
			<td>March 24, 2014</td>
			<td>4.44</td>
		</tr>
		<tr>
			<td>Indonesia</td>
			<td>249,866,000</td>
			<td>July 1, 2013</td>
			<td>3.49</td>
		</tr>
		<tr>
			<td>Brazil</td>
			<td>201,032,714</td>
			<td>July 1, 2013</td>
			<td>2.81</td>
		</tr>
	</tbody>
</table> 
</div>
</div>
</body>
</html>