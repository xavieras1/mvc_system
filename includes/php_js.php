<?php //var_dump($_SESSION["current_cargo"]);?>
<?php //var_dump($_SESSION["current_cargo"]["data"]["permisos"]["info"]);?>
<script type="text/javascript">
var data = eval('(' + JSON.stringify(<?php print json_encode($_SESSION["current_cargo"]);?>) + ')');

function set_table(current){
  $(".agregar").show();
  $('.div_niveles').remove();

  var $data=eval('('+JSON.stringify(data.data)+')')[current];
  var table='<tr class="table_title">';
  if(current==="permisos"){
    table+='<th>CARGO</th>';
    table+='<th>AREA</th>';
    table+='<th>TIPO DE INSTANCIA</th>';
    table+='<th>NIVEL</th>';
    table+='<th>PERMISO</th>';
    table+='<th>PROPIEDAD</th>';
    table+='<th>FUNCIONES</th></tr>';
    for(var j=0;j<$data.data.length;j++){
      table+='<tr class="table_row '+current+$data.data[j].id+'">';
      if($data.data[j].cargo_id===0)
        table+='<td></td>';
      else{
        var flag=0;
        for(var i=0;i<$data.info.cargos.length||flag===0;i++){
          if($data.info.cargos[i].id===$data.data[j].cargo_id){
            flag=1;
            table+='<td>'+$data.info.cargos[i].nombre+'</td>';
          }
        }
      }
      if($data.data[j].area_id==="0")
        table+='<td></td>';
      else{
        flag=0;
        for(var i=0;i<$data.info.areas.length||flag===0;i++){
          if($data.info.areas[i].id===$data.data[j].area_id){
            flag=1;
            table+='<td>'+$data.info.areas[i].nombre+'</td>';
          }
        }
      }
      if($data.data[j].tipo_instancia_id==="0")
        table+='<td></td>';
      else{
        flag=0;
        for(var i=0;i<$data.info.tipos.length||flag===0;i++){
          if($data.info.tipos[i].id===$data.data[j].tipo_instancia_id){
            flag=1;
            table+='<td>'+$data.info.tipos[i].nombre+'</td>';
          }
        }
      }     
      table+='<td>'+$data.data[j].nivel+'</td>';
      
      if($data.data[j].permisos){
        table+='<td><select class="tipo_instancia">';
        table+='<option value="0">--Ver Instancias--</option>';
        for (var i = 0; i < $data.data[j].permisos.length; i++) {
          flag=0;
          for (var k = 0; k < $data.info.tipos.length||flag===0; k++) {
            if($data.info.tipos[k].id===$data.data[j].permisos[i].tipo){
              flag=1;
              table+='<option value=".tipo'+$data.data[j].id+'_'+i+'">'+$data.info.tipos[k].nombre+'</option>';      
            }            
          }          
        }
        
        table+='</select></td>';
        table+='<td>';
        for (var i = 0; i < $data.data[j].permisos.length; i++) {          
          table+='<span class="perm'+$data.data[j].id+'_'+i+' permiso_span">'+$(this).text()+$data.data[j].permisos[i].permiso+'</span>';
        }
        table+='</td>';
      }else{
        table+='<td></td>';
        table+='<td></td>';
      }
      //var id=boton.parent().parent().attr("class").substring(boton.parent().parent().attr("class").indexOf(" ")+1+current.length);
      table+='<td><input type="button" value="EDITAR" class="btneditar '+$data.data[j].id+'"></td>';
      table+='</tr>';
    }
  }else if(current==="tipos_instancia"){
    table+='<th>LOGO</th>';
    table+='<th>CLASIFICACIÓN</th>';
    table+='<th>NOMBRE</th>';
    table+='<th>DESCRIPCIÓN</th>';
    table+='<th>FUNCIONES</th></tr>';
    if($data[0]){
      for(var j=0;j<$data.length;j++){
        table+='<tr id="'+current+$data[j].id+'" class="table_row '+current+$data[j].id+'">'        
        table+='<td><form enctype="multipart/form-data" action="/SubirLogo" method="POST">'+$data[j].logo+'</form></td>';
        console.log($data[j].logo);
        table+='<td>'+$data[j].clasificacion+'</td>';
        table+='<td>'+$data[j].nombre+'</td>';
        table+='<td>'+$data[j].descripcion+'</td>';
        table+='<td><input type="button" value="EDITAR" class="btneditar"><br/><input type="button" value="ELIMINAR" class="btneliminar" onclick="eliminar(\''+current+'\','+$data[j].id+')"></td>';
        table+='</tr>';
      }
    }else{
      table+='<tr class="none"><td>NO HAY ELEMENTOS</td></tr>';
    }
  
  }else if(current==="nucleo"){
     table+='<th>NOMBRE</th>';
     table+='<th>CARGO</th>';
     table+='<th>FECHA INICIO</th>';
     table+='<th>FUNCIONES</th></tr>';
     if($data[0]){
       for(var j=0;j<$data.length;j++){
         table+='<tr id="'+current+$data[j].id+'" class="table_row '+current+$data[j].id+'">'
         table+='<td>'+$data[j].nombre+'</td>';
         table+='<td>'+$data[j].descripcion+'</td>';
         table+='<td><input type="button" value="EDITAR" class="btneditar"><br/><input type="button" value="ELIMINAR" class="btneliminar" onclick="eliminar(\''+current+'\','+$data[j].id+')"></td>';
         table+='</tr>';
       }
     }else{
       table+='<tr class="none"><td>NO HAY ELEMENTOS</td></tr>';
     }
  }else{//cargo o area
    table+='<th>NOMBRE</th>';
    table+='<th>DESCRIPCIÓN</th>';
    table+='<th>FUNCIONES</th></tr>';
    if($data[0]){
      for(var j=0;j<$data.length;j++){
        table+='<tr id="'+current+$data[j].id+'" class="table_row '+current+$data[j].id+'">'
        table+='<td>'+$data[j].nombre+'</td>';
        table+='<td>'+$data[j].descripcion+'</td>';
        table+='<td><input type="button" value="EDITAR" class="btneditar"><br/><input type="button" value="ELIMINAR" class="btneliminar" onclick="eliminar(\''+current+'\','+$data[j].id+')"></td>';
        table+='</tr>';
      }
    }else{
      table+='<tr class="none"><td>NO HAY ELEMENTOS</td></tr>';
    }
  }
  $('#main_table tbody').append(table);
  $('.btneditar').click(function(){
    editar($(this));
  });

  $('.permiso_span').hide();
  $('.tipo_instancia').change(function(){
      $('.permiso_span').hide();
       $(this).find(":selected").each(function() {
        var idtipo=$(this).attr('value').substring($(this).attr('value').indexOf(' ')+6);
        $('.perm'+idtipo).css("display", "inline");
       });
  });
}

function set_table_editar(current,botonid,nivel){  
  var $data=eval('('+JSON.stringify(data.data)+')')[current];
  var table='<tr class="table_title">';     
      table+='<th>PROPIEDAD</th>';
      table+='<th>PERMISO</th>';
      table+='</tr>';

  var levels='<div class="div_niveles">Nivel: <select class="select_nivel">';
      levels+='<option value="0">--Elegir Nivel--</option>';
      levels+='<option value="1">1</option>';
      levels+='<option value="2">2</option>';
      levels+='<option value="3">3</option>';
      levels+='<option value="4">4</option>';  
      levels+='</select></div>'; 

     $('#content_header').append(levels);
     $('.select_nivel').val(nivel);
     $('#main_table tbody').append(table);

     for(var p=0;p<$data.data.length;p++){
      if ($data.data[p].id===botonid){
        for(var i=0;i<$data.info.tipos.length;i++){
             table='<tr class="table_row">';
             table+='<td id="tipo'+$data.info.tipos[i].id+'">'+$data.info.tipos[i].clasificacion+" - "+$data.info.tipos[i].nombre+'</td>';
             table+='<td><input type="radio" name="permiso'+$data.info.tipos[i].id+'" value="editar">Editar <input type="radio" name="permiso'+$data.info.tipos[i].id+'" value="ver">Ver <input type="radio" name="permiso'+$data.info.tipos[i].id+'" value="nada">Nada</td>';
             table+='</tr>';
             $('#main_table tbody').append(table);
             if (i<$data.data[p].permisos.length){
              $('input[name="permiso'+$data.info.tipos[i].id+'"]').filter('[value="'+$data.data[p].permisos[i].permiso+'"]').prop("checked",true);
             }else{
              $('input[name="permiso'+$data.info.tipos[i].id+'"]').prop("checked",true);
             }             
        }
      }
     }

      table='<td><input type="button" value="GUARDAR" class="save_permiso"><input type="button" value="CANCELAR" class="cancelar"></td>';
      $('#main_table tbody').append(table);      
      $('input.save_permiso').click(function(){
          guardar($(this),"");
          $('.div_niveles').remove();
      });
      $('input.cancelar').click(function(){
        $('#main_table tbody').empty();
        set_table(current);  
        $('.div_niveles').remove();
      });
}
$(document).ready(function(){
  
  /*************************************MENU**************************************/
  $('#menu_bar').each(function(){
    // For each set of tabs, we want to keep track of
    // which tab is active and it's associated content
    var $active, $content, $links = $(this).find('a.father');
    $links.push($('#btn_ver_perfil')[0]);
    // If the location.hash matches one of the links, use that as the active tab.
    // If no match is found, use the first link as the initial active tab.
    $active = $($links.filter('[href="'+location.hash+'"]')[0] ||$links[0]);
    $active.addClass('active');
    $content = $($active.attr('href'));

    $('#content_title').text($active.first().text());
    set_table($active.attr('href').substring(1));

    // Hide the remaining content
    $links.not($active).each(function () {
      $($(this).attr('href')).hide();
    });

    // Bind the click event handler
    $(this).on('click', 'a.father,a#btn_ver_perfil', function(e){
      // Make the old tab inactive.
      $active.removeClass('active');
      $content.hide();
      $('#main_table tbody').empty();
      $(".agregar").removeAttr("disabled");

      // Update the variables with the new link and content
      $active = $(this);
      $content = $($(this).attr('href'));

      // Make the tab active.
      $active.addClass('active');
      $content.show();

      $('#content_title').text($active.first().text());
      set_table($active.attr('href').substring(1));

      if($(this)==$('#btn_ver_perfil')){
        $('ul.roles').hide();
        $('#saludo').css('background-color','#005597');
      }

      // Prevent the anchor's default click action
      e.preventDefault();
    });
  });
});

/*
PONER TITULOS AUTOMATICAMENTE CON LOS KEY DE LOS JSON
nombre_c = eval('(' + JSON.stringify($data[0]) + ')');
      claves= new Array();
      var table="";
      table+='<tr class="table_title">';
      var band=0;
      for(nombre in nombre_c) {
        if(band===1){
          table+='<th>'+nombre.toUpperCase()+'</th>';
          claves.push(nombre);
        }
        band=1;
      }
      table+='<th>FUNCIONES</th></tr>';*/
</script>