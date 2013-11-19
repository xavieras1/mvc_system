<!--Angel Astudillo && Andrea Simbaña && Yuri Cosquillo-->
<?php session_start();
//var_dump($_SESSION);
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo $_SESSION["user"]['name']?></title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script type="text/javascript" language="javascript1.5" src="js/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" language="javascript1.5" src="js/mvc.js"></script>
    <link rel='stylesheet' href='css/mvc.css' />
    <?php include '/includes/php_js.php';?>
  </head>
  <body>
    <div id="header">
      <?php include '/includes/header.php';?>
    </div>
    <div id="wrapper">
      <div id="menu_bar">
        <?php 
          if ($_SESSION["current_cargo"]['info']['nivel']==1) {
            include '/includes/menu_sa.php';
          }elseif ($_SESSION["current_cargo"]['info']['nivel']==2) {
            include '/includes/menu_nucleo.php';
          }elseif ($_SESSION["current_cargo"]['info']['nivel']==3) {
            include '/includes/menu_centro.php';
          }else{
            include '/includes/menu_a.php';
          }
        ?>
      </div>
      <div id="main">
        <div id="content_header">
          <span id="content_title"></span>
          <input type="button" value="+" class="agregar">
        </div>
        <div id="content">
          <table id="main_table" border="0">
          <tbody>
          </tbody>
          </table>
        </div>
      </div>
    </div>
    <div id="footer">
      <span>MVC-SYSTEM</span></br>
      <span>MOVIMIENTO DE VIDA CRISTIANA ECUADOR</span></br>
      <span>(C) SAC 2013</span></br>
    </div>
  </body>
</html>