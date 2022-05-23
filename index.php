<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
 <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Login</title>
  <link data-n-head="1" rel="icon" type="image/x-icon" href="../favicon.ico">
  <link rel="stylesheet" href="css/bootstrap.min4.css">
  <link rel="stylesheet" href="css/sweetalert2.min.css">
  <link rel="stylesheet" href="html/estilos.css">
</head>
<body>
  <div id="login">
      <h3 class="text-center text-white display-4"> SWELL </h3>
  	  <div class="container">
        <div id="login-row" class="row justify-content-center align-items-center">
          <div id="login-column" class="col-md-6">
            <div id="login-box" class="col-md-12 bg-light text-dark">
              <form id="formLogin" class="form" action="" method="post">
                <h3 class="text-center text-dark"> Iniciar Sesión </h3>
                <div class="form-group">
                	<label for="usuario" class="text-dark">Usuario</label>
                  <input type="text" name="Usuario" id="usuario" class="form-control" placeholder="User" value="">
                </div>
                <div class="form-group">
                <label  for="password" class="text-dark">Password</label>
                
                  <input type="password" name="password" id="password" class="form-control" placeholder="password" value="">
                </div>
  		          <div class="form-group text-center">
                  <input type="submit" name="submit" class="btn btn-dark btn-lg btn-black" value="Ingresar">
                </div>
                
              </form>
            </div>
  	      </div>
        </div>
      </div>
  </div>
  <script src="js/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  <script src="swa/sweetalert2.all.min.js"> </script>
  <script src= "js/codigo.js"></script>
</body>
</html>