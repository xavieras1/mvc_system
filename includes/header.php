<!-- HEADER -->
<div id="logo">
  <a href="/main.php"><img src="img/logo_blanco.png" alt="Log MVC Ecuador" title="MvcSystem" /></a>
</div>
<div id="info">
  <?php
    print '<span id="titulo" ';
    if ($_SESSION["current_cargo"]['info']['nivel']==1) {
      print '>'.$_SESSION["current_cargo"]['info']['nombre_cargo'];
    } elseif ($_SESSION["current_cargo"]['info']['nivel']==2) {
      print '>Movimiento de Vida Cristiana Ecuador';
    }else{
      print 'class="permanencia'.$_SESSION["current_cargo"]['info']['permanencia_id'].'">'.$_SESSION["current_cargo"]['info']['permanencia_nombre'];
    }
  ?>
</span>
<div id="profile">
  <a href="#"><div id="saludo">
    <span><?php echo $_SESSION["user"]['name']?></span>
  </div>
  <div id="cuentas" class="cuadros">
    <img src="img/btn_cuentas.png">
  </div></a>
  <a href="includes/api.php?request=logout"><div id="cerrar" class="cuadros">
    <img src="img/btn_cerrar.png">
  </div></a>
  <ul class="roles">
  <li><img src="img/separador_menu_cuentas.png"><span>CUENTAS</span></li>
  <li><img src="img/separador_menu_cuentas.png">
  <?php
  $i=0;
  foreach ($_SESSION["cargos"] as $cargo) {
    print '<a class="cargo'.$cargo['info']['id'].'" href="includes/api.php?request=cambio_cargo,id='.$i.'">';
    print '<span>'.$cargo['info']['nombre_cargo'];
    if($cargo['info']['nivel']==2) {
      print ' de '.$cargo['info']['nombre_area'].' del MVC';
    }elseif ($cargo['info']['nivel']==3) {
      print ' de '.$cargo['info']['nombre_area'].' de '.$cargo['info']['iniciales_instancia'];
    }
    print '</span>';
    print '</a>';
    $i++;
  }
  ?>
  </li>
  <li><img src="img/separador_menu_cuentas.png"><span>OPCIONES</span></li>
  <li><img src="img/separador_menu_cuentas.png"><a id="btn_ver_perfil" href="#ver_perfil"><span>Ver Perfil</span></a></li>
  </ul>
</div>
</div>
