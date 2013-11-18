<!--Angel Astudillo && Andrea Simbaña && Yuri Cosquillo-->
<!DOCTYPE html>
<html>
  <head>
    <title>MVC SYSTEM LOGIN</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script src="js/login.js"></script>
    <link rel='stylesheet' href='css/login.css' />
  </head>
  <body>
    <div id="wrapper">
      <div id="left_container">
      <img src="img/slide1.jpg" width="500" height="400" id="rotator">
        <div id="text">
          <span>BIENVENIDOS</span>
          <p>MVC-System: Sistema web de administración de base de datos y control de actividades</p>
        </div>
      </div>
      <div id="right_container">
        <img src="img/MVC_horizontal.png"/>
        <form action="includes/api.php" method="post">
          <input type="hidden" name="request" value="login">
          <div id="user">
            <!--Usuario (Email)</br>-->
            <input type="text" name="user" placeholder="Usuario (Email)">
          </div>
          <div id="password">
            <!--Contraseña</br>-->
            <input type="password" name="contrasena" placeholder="Contraseña">
          </div>
          <div id="login">
            <input type="submit" value="Iniciar sesión" id="botoning">
          </div>
        </form>
      </div>
    </div>
  </body>
</html>