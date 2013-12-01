// JavaScript Document
//Angel Astudillo && Andrea Simbaña
// Wait until the DOM has loaded before querying the document

function getAge(dateString) {
  var today = new Date();
  var birthDate = new Date(dateString);
  var age = today.getFullYear() - birthDate.getFullYear();
  var m = today.getMonth() - birthDate.getMonth();
  if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) 
  {
    age--;
  }
  return age;
}

function getIndexByIndex(array,id){
  for(var i=0; i < array.length; i++) { 
    if(array[i].id==id){
      return i;
    }
  }
}

var id_perm;
var nivel;
var cargo, area, tipo, level;
var alarm=0;
var permisos=new Array();
var ids=new Array();
function editar(boton) {
  var current=$('.active').attr('href').substring(1);

  if(current==="permisos"){
    id_perm=(boton.parent().parent().attr("class").substring(boton.parent().parent().attr("class").indexOf(" ")+1+current.length));
    nivel=(boton.parent().parent().children(":nth-child(4)").text());
    $(".agregar").hide();
    if (nivel==="1"){//Superadmin
      $("#content_title").text(boton.parent().parent().children(":nth-child(1)").text());
    }else if (nivel==="4"){//Animador
      $("#content_title").text(boton.parent().parent().children(":nth-child(1)").text()+" de "+
      boton.parent().parent().children(":nth-child(3)").text());
    }else if (nivel==="2"){//Nucleo
      $("#content_title").text(boton.parent().parent().children(":nth-child(1)").text()+" de "+
      boton.parent().parent().children(":nth-child(2)").text());
    }else{  
      $("#content_title").text(boton.parent().parent().children(":nth-child(1)").text()+" de "+
      boton.parent().parent().children(":nth-child(2)").text()+" de "+
      boton.parent().parent().children(":nth-child(3)").text());
    }
    
    $("#content_title").attr("class","permiso"+boton.parent().parent().attr("class").replace( /^\D+/g, ''));
    $('#main_table tbody').empty();

    var clase=boton.attr("class");
    var botonid=clase.substring(10,16);
    set_table_editar(current,botonid,nivel);
    //$("#main").load("editar_permiso.php");
    //window.location.href = "http://stackoverflow.com";//la intencion es llamar a una nueva pagina para editar un permiso
  }else if(current==="tipos_instancia"){
    //table+='<td><input name="logo" type="file"></td>';
      $(".agregar").hide();
      boton.parent().parent().children(":nth-child(1)").html('<input name="logo" type="file" value="'+boton.parent().parent().children(":nth-child(1)").val()+'">');
      boton.parent().parent().children(":nth-child(2)").html('<input name="clasificacion" type="text" value="'+boton.parent().parent().children(":nth-child(2)").text()+'">');
      boton.parent().parent().children(":nth-child(3)").html('<input name="nombre" type="text" value="'+boton.parent().parent().children(":nth-child(3)").text()+'">');
      boton.parent().parent().children(":nth-child(4)").html('<input name="descripcion" type="textarea" value="'+boton.parent().parent().children(":nth-child(4)").text()+'">');
  }else if(current==="centros"){    
      $(".agregar").hide();
      boton.parent().parent().children(":nth-child(1)").html('<input name="nombre" type="text" value="'+boton.parent().parent().children(":nth-child(1)").val()+'">');
      boton.parent().parent().children(":nth-child(2)").html('<input name="descripcion" type="textarea" value="'+boton.parent().parent().children(":nth-child(2)").text()+'">');
      boton.parent().parent().children(":nth-child(3)").html('<input name="fecha_creacion" type="date" value="'+boton.parent().parent().children(":nth-child(3)").text()+'">');
      boton.parent().parent().children(":nth-child(4)").html('<input name="telefono" type="text" value="'+boton.parent().parent().children(":nth-child(4)").text()+'">');
      boton.parent().parent().children(":nth-child(5)").html('<input name="direccion" type="text" value="'+boton.parent().parent().children(":nth-child(5)").text()+'">');  
  }else{
    $(".agregar").hide();
    boton.parent().parent().children(":nth-child(1)").html('<input name="nombre" type="text" value="'+boton.parent().parent().children(":nth-child(1)").text()+'">');
    boton.parent().parent().children(":nth-child(2)").html('<input name="descripcion" type="textarea" value="'+boton.parent().parent().children(":nth-child(2)").text()+'">');    
  }        
  boton.parent().parent().children(":last-child").html('<input type="button" value="GUARDAR" class="guardar"><br/><input type="button" value="CANCELAR" class="cancelar">');

  $(".agregar").attr("disabled", "disabled");
  $('input.guardar').click(function(){
    //$(".agregar").show();
    guardar($(this),"");
  });

  $('input.cancelar').click(function(){ //cancelar del editar
    if (current==="permisos"){
      $('#content_title').text("PERMISOS"); 
    }
    $('#main_table tbody').empty();
    set_table(current);
    $(".agregar").removeAttr("disabled");
  });
     
}
function guardar(boton,additional) {
  var current=$('.active').attr('href').substring(1);
  var $data=eval('('+JSON.stringify(data.data)+')')[current];

  var id="";

  if(boton.attr("class")==="save_permiso"){
    var url="includes/api.php?request=guardar&tipo="+current+"&id="+id_perm;
  }else if(boton.attr("class")==="nuevo_permiso"){
    var idcargo=$('.select_cargo').val().substring(6);
    var idarea=$('.select_area').val().substring(5);
    var idtipo=$('.select_tipo').val().substring(5);
    id =idcargo+"-"+idarea+"-"+idtipo;
    var url="includes/api.php?request=guardar&tipo="+current+"&id="+id;
  }else{
    if(boton.parent().parent().attr("class").indexOf(" ")>0)
      id=boton.parent().parent().attr("class").substring(boton.parent().parent().attr("class").indexOf(" ")+1+current.length);
    var url="includes/api.php?request=guardar&tipo="+current+"&id="+id;
  }
  if(current==="permisos"){
    var arr=$('.table_row');
    // var ids=new Array();
    // var permisos=new Array();
    for (var i = 0; i < arr.length; i++) {
      var thenum=$(arr[i]).children(":nth-child(1)").attr('id').replace( /^\D+/g, '');
      ids.push(thenum);
      if($("input[name='permiso"+thenum+"']:checked").val()==="editar" || $("input[name='permiso"+thenum+"']:checked").val()==="ver"){
        permisos.push($("input[name='permiso"+thenum+"']:checked").val());
      }else{
        permisos.push("nada");
      }
    }
    url+="&nivel="+$('.select_nivel').val();
    url+="&ids="+ids;
    url+="&permisos="+permisos;
    //$("#main").load("./index.php");
    //window.location.href = "http://stackoverflow.com";//la intencion es llamar a una nueva pagina para editar un permiso
  }else if(current==="nucleo"){
      
    if(additional){
       url="includes/api.php?request=guardar&tipo="+additional+"&id="+id;
       url+="&"+"foto="+boton.parent().children("input,select").filter("[name='foto']").val();
       url+="&"+"nombre="+boton.parent().children("input,select").filter("[name='nombre']").val();
       url+="&"+"apellido="+boton.parent().children("input,select").filter("[name='apellido']").val();
       url+="&"+"ciudad="+boton.parent().children("input,select").filter("[name='ciudad']").val();
       url+="&"+"sexo="+boton.parent().children("input,select").filter("[name='sexo']").val();
       url+="&"+"edad="+boton.parent().children("input,select").filter("[name='edad']").val();
       url+="&"+"nacimiento="+boton.parent().children("input,select").filter("[name='date']").val();
       url+="&"+"domicilio="+boton.parent().children("input,select").filter("[name='address']").val();
       url+="&"+"estudio="+boton.parent().children("input,select").filter("[name='estudio']").val();
       url+="&"+"institucion="+boton.parent().children("input,select").filter("[name='institucion']").val();
       url+="&"+"telefono="+boton.parent().children("input,select").filter("[name='home']").val();
       url+="&"+"claro="+boton.parent().children("input,select").filter("[name='claro']").val();
       url+="&"+"movi="+boton.parent().children("input,select").filter("[name='movi']").val();
       url+="&"+"pin="+boton.parent().children("input,select").filter("[name='pin']").val();
       url+="&"+"email="+boton.parent().children("input,select").filter("[name='email']").val();
       url+="&"+"fb="+boton.parent().children("input,select").filter("[name='fb']").val();
       url+="&"+"tw="+boton.parent().children("input,select").filter("[name='tw']").val();
       url+="&"+"user="+boton.parent().children("input,select").filter("[name='user']").val();
       url+="&"+"pass="+boton.parent().children("input,select").filter("[name='pass']").val();
    }else{
      url+="&"+"id_persona="+$('#selPersona').val();
      url+="&"+"id_cargo="+$('#selCargo').val().substring(0,$('#selCargo').val().indexOf('-'));
      url+="&"+"id_area="+$('#selCargo').val().substring($('#selCargo').val().indexOf('-')+1);
      url+="&"+"fecha_inicio="+boton.parent().parent().children(":nth-child(3)").children("input").val();
    } 
  }else if(current==="tipos_instancia"){
    //table+='<td><input name="logo" type="file"></td>';}
    url+="&"+boton.parent().parent().children(":nth-child(1)").children("input").attr("name")+"="+boton.parent().parent().children(":nth-child(1)").children("input").val();
    url+="&"+boton.parent().parent().children(":nth-child(2)").children("input").attr("name")+"="+boton.parent().parent().children(":nth-child(2)").children("input").val();
    url+="&"+boton.parent().parent().children(":nth-child(3)").children("input").attr("name")+"="+boton.parent().parent().children(":nth-child(3)").children("input").val();
    url+="&"+boton.parent().parent().children(":nth-child(4)").children("input").attr("name")+"="+boton.parent().parent().children(":nth-child(4)").children("input").val();
  }else if(current==="centros"){
    url+="&"+boton.parent().parent().children(":nth-child(1)").children("input").attr("name")+"="+boton.parent().parent().children(":nth-child(1)").children("input").val();
    url+="&"+boton.parent().parent().children(":nth-child(2)").children("input").attr("name")+"="+boton.parent().parent().children(":nth-child(2)").children("input").val();
    url+="&"+boton.parent().parent().children(":nth-child(3)").children("input").attr("name")+"="+boton.parent().parent().children(":nth-child(3)").children("input").val();
    url+="&"+boton.parent().parent().children(":nth-child(4)").children("input").attr("name")+"="+boton.parent().parent().children(":nth-child(4)").children("input").val();
    url+="&"+boton.parent().parent().children(":nth-child(4)").children("input").attr("name")+"="+boton.parent().parent().children(":nth-child(5)").children("input").val();
  }else{
    url+="&"+boton.parent().parent().children(":nth-child(1)").children("input").attr("name")+"="+boton.parent().parent().children(":nth-child(1)").children("input").val();
    url+="&"+boton.parent().parent().children(":nth-child(2)").children("input").attr("name")+"="+boton.parent().parent().children(":nth-child(2)").children("input").val();
  }
  /*for (var i = 0; i < $('table input[type="text"]').length; i++) {
    url+='&'+$($('table input[type="text"]')[i]).attr('name')+'='+$($('table input[type="text"]')[i]).val();
  };*/
  /**********************GUARDAR EN DB**********************/
  console.log("url: "+url);
  $.ajax({
    url: url,
    type: "GET",
  }).done(function( html ) {
    console.log(html);
    var json=JSON.parse(html);
    if(json.error){
      alert(json.descriptionerror);
    }else{
      if(current==="permisos"){
        $(".father[href='#permisos']").trigger("click");
        if (alarm===1){
          var row='<tr class="table_row '+current+id+'">';
          row+='<td>'+cargo+'</td>';
          row+='<td>'+area+'</td>';
          row+='<td>'+tipo+'</td>';
          row+='<td>'+level+'</td>';
          row+='<td><select class="ver_instancia">';
          row+='<option value=".tipo0">--Ver Instancias--</option>';
          for (var i = 0; i < $data.info.tipos.length; i++) {
            row+='<option value=".tipo'+id+'_'+$data.info.tipos[i].id+'">'+$data.info.tipos[i].nombre+'</option>';      
          }
          row+='</select></td>';
          row+='<td><span class="ver_permiso"></span></td>';
          row+='<td><input type="button" value="EDITAR" class="btneditar"></td>';
          $('#main_table tbody').append(row);
          alarm=0; 
        }

        var nuevo={};
        nuevo['id']=json.id;
        nuevo['cargo_id']=cargo;
        nuevo['area_id']=area;
        nuevo['tipo_instancia_id']=tipo;
        nuevo['nivel']=nivel;
        nuevo['permisos']=permisos;
        data.data[current].data.push(nuevo);
        //window.location.href = "http://stackoverflow.com";//la intencion es llamar a una nueva pagina para editar un permiso
      }else if(current==="tipos_instancia"){
        //table+='<td><input name="logo" type="file"></td>';}
        var logo=boton.parent().parent().children(":nth-child(1)").children("input").val();
        boton.parent().parent().children(":nth-child(1)").html(logo);
        var clasificacion=boton.parent().parent().children(":nth-child(2)").children("input").val();
        boton.parent().parent().children(":nth-child(2)").html(clasificacion);
        var nombre=boton.parent().parent().children(":nth-child(3)").children("input").val();
        boton.parent().parent().children(":nth-child(3)").html(nombre);
        var descripcion=boton.parent().parent().children(":nth-child(4)").children("input").val();
        boton.parent().parent().children(":nth-child(4)").html(descripcion);
        boton.parent().parent().attr("class","table_row "+current+json.id);

        var nuevo={};
        nuevo['id']=json.id;
        nuevo['logo']=logo;
        nuevo['clasificacion']=clasificacion;
        nuevo['nombre']=nombre;
        nuevo['descripcion']=descripcion;

      }else if(current==="centros"){
        var nombre=boton.parent().parent().children(":nth-child(1)").children("input").val();
        boton.parent().parent().children(":nth-child(1)").html(nombre);
        var descripcion=boton.parent().parent().children(":nth-child(2)").children("input").val();
        boton.parent().parent().children(":nth-child(2)").html(descripcion);
        var fecha_creacion=boton.parent().parent().children(":nth-child(3)").children("input").val();
        boton.parent().parent().children(":nth-child(3)").html(fecha_creacion);
        var telefono=boton.parent().parent().children(":nth-child(4)").children("input").val();
        boton.parent().parent().children(":nth-child(4)").html(telefono);
        var direccion=boton.parent().parent().children(":nth-child(5)").children("input").val();
        boton.parent().parent().children(":nth-child(5)").html(direccion);
        boton.parent().parent().attr("class","table_row "+current+json.id);

        var nuevo={};
        nuevo['id']=json.id;
        nuevo['nombre']=nombre;
        nuevo['descripcion']=descripcion;
        nuevo['fecha_creacion']=fecha_creacion;
        nuevo['telefono']=telefono;
        nuevo['direccion']=direccion;
      }else if(current==="nucleo"){
        var persona_id=boton.parent().parent().children(":nth-child(1)").children("input").val();
        boton.parent().parent().children(":nth-child(1)").html(persona_id);
        var cargo_id=boton.parent().parent().children(":nth-child(2)").children("input").val();
        boton.parent().parent().children(":nth-child(2)").html(cargo_id);
        var area_id=boton.parent().parent().children(":nth-child(3)").children("input").val();
        boton.parent().parent().children(":nth-child(3)").html(area_id);
        var fecha_inicio=boton.parent().parent().children(":nth-child(4)").children("input").val();
        boton.parent().parent().children(":nth-child(4)").html(fecha_inicio);
        boton.parent().parent().attr("class","table_row "+current+json.id);

        var nuevo={};
        nuevo['id']=json.id;
        nuevo['persona_id']=persona_id;
        nuevo['cargo_id']=cargo_id;
        nuevo['area_id']=area_id;
        nuevo['fecha_inicio']=fecha_inicio;
       if(additional)
          alert("hols");

      }else{
        /**********************FRONT END**********************/
        var nombre=boton.parent().parent().children(":nth-child(1)").children("input").val();
        boton.parent().parent().children(":nth-child(1)").html(nombre);
        var descripcion=boton.parent().parent().children(":nth-child(2)").children("input").val();
        boton.parent().parent().children(":nth-child(2)").html(descripcion);
        boton.parent().parent().attr("class","table_row "+current+json.id);

        /*******************LOCAL SESSION********************/
        var nuevo={};
        if(json.id==0){
          nuevo['id']=id;
          nuevo['nombre']=nombre;
          nuevo['descripcion']=descripcion;
          data.data[current][getIndexByIndex(data.data[current],id)]=nuevo;
        }else{
          nuevo['id']=json.id;
          nuevo['nombre']=nombre;
          nuevo['descripcion']=descripcion;
          data.data[current].push(nuevo);
        }
        console.log(data.data[current]);
      }
      boton.parent().parent().children(":last-child").html('<input type="button" value="EDITAR" class="btneditar"><br/><input type="button" value="ELIMINAR" class="btneliminar">');
      
      $('.btneditar').click(function(){
        editar($(this));
      });

      $('.btneliminar').click(function(){
        eliminar(current,nuevo['id']);
      });
      
      $(".agregar").removeAttr("disabled");
      $(".agregar").show();
      //$('.ver_permiso').hide();
      $('.ver_instancia').change(function(){
      //$('.ver_permiso').hide();
         $(this).find(":selected").each(function() {
          var idt=$(this).attr('value').substring($(this).attr('value').indexOf('_')+1);
          console.log(idt);
          for (var p = 0; p < permisos.length; p++) {
            if(ids[p]===idt){
              $('.ver_permiso').text(permisos[p]);
            }            
          }
         });
      });
    }
  });
}
function eliminar(table,id){
  var answer = confirm ("¿Está seguro que desea eliminar esta fila?");

  if (answer){
    var url="includes/api.php?request=eliminar&tipo="+table+"&id="+id;
    console.log("url: "+url);
    $.ajax({
      url: url,
      type: "GET",
    }).done(function( html ) {
      var json=JSON.parse(html);
      if(json.error){
        alert(json.descriptionerror);
      }else{
        var trId= table + id;
        $('#' + trId).remove();
        data.data[table].splice(getIndexByIndex(data.data[table],id), 1);
      }
    });
  }
}
$(document).ready(function(){
  /*************************************HEADER**************************************/
  $('ul.roles').hide();
  $('#cuentas,#saludo').click(function(){
    $('ul.roles').is(":visible")? $('ul.roles').hide():$('ul.roles').show();
    $('ul.roles').is(":visible")?$('#saludo').css('background-color','#0061A1'):$('#saludo').css('background-color','#005597');
  });
  $('.agregar').click(function(a){
    $(".agregar").hide();
    var current=$('.active').attr('href').substring(1);
    var $data=eval('('+JSON.stringify(data.data)+')')[current];
    var table='<tr class="table_perm">';
    
    if(current==="permisos"){
      var table_cai='<tr class="table_perm">';
      $('#main_table tbody').empty();
      $('#content_title').text("NUEVO PERMISO");
      //Cargos
      table_cai+='<td>Cargo: <select class="select_cargo">';
        table_cai+='<option value=".cargo0">--Elegir Cargo--</option>';
        for (var i = 0; i < $data.info.cargos.length; i++) {                
            table_cai+='<option value=".cargo'+$data.info.cargos[i].id+'">'+$data.info.cargos[i].nombre+'</option>';      
        }        
      table_cai+='</select></td>';

      //Areas
      table_cai+='<td>Área: <select class="select_area">';
        table_cai+='<option value=".area0">--Elegir Area--</option>';
        for (var i = 0; i < $data.info.areas.length; i++) { 
            table_cai+='<option value=".area'+$data.info.areas[i].id+'">'+$data.info.areas[i].nombre+'</option>';      
        }        
      table_cai+='</select></td>';

      //Tipos de Instancia
      table_cai+='<td>Tipo de Instancia: <select class="select_tipo">';
        table_cai+='<option value=".tipo0">--Elegir Tipo de Instancia--</option>';
        for (var i = 0; i < $data.info.tipos.length; i++) { 
              table_cai+='<option value=".tipo'+$data.info.tipos[i].id+'">'+$data.info.tipos[i].nombre+'</option>';      
        }        
      table_cai+='</select></td>';
      table_cai+='<td>Nivel: <select class="select_nivel">';
      table_cai+='<option value="0">--Elegir Nivel--</option>';
      table_cai+='<option value="1">1</option>';
      table_cai+='<option value="2">2</option>';
      table_cai+='<option value="3">3</option>';
      table_cai+='<option value="4">4</option>';  
      table_cai+='</select></td>';  
      $('#main_table tbody').append(table_cai); 
      
      //Permisos
      for(var i=0;i<$data.info.tipos.length;i++){
             var tableperm='<tr class="table_row">';
             tableperm+='<td id="tipo'+$data.info.tipos[i].id+'">'+$data.info.tipos[i].clasificacion+" - "+$data.info.tipos[i].nombre+'</td>';
             tableperm+='<td><input type="radio" name="permiso'+$data.info.tipos[i].id+'" value="editar">Editar <input type="radio" name="permiso'+$data.info.tipos[i].id+'" value="ver">Ver <input type="radio" name="permiso'+$data.info.tipos[i].id+'" value="nada">Nada</td>';
             tableperm+='</tr>';
             $('#main_table tbody').append(tableperm);             
      }
      table+='<td><input type="button" value="GUARDAR" class="nuevo_permiso"><input type="button" value="CANCELAR" class="cancel cancelar"></td>';
      table+='<td></td></tr>';
     }else if(current==="tipos_instancia"){
      table+='<td><input name="logo" type="file"></td>';
      table+='<td><input placeholder="CLASIFICACI&Oacute;N" name="clasificacion" type="text"></td>';
      table+='<td><input placeholder="NOMBRE" name="nombre" type="text"></td>';
      table+='<td><input placeholder="DESCRIPCI&Oacute;N" name="descripcion" type="textarea"></td>';
      table+='<td><input type="button" value="GUARDAR" class="guardar"><br/><input type="button" value="CANCELAR" class="cancelar"></td></tr>';
     }else if(current==="nucleo"){
       table+='<td><select id="selPersona" name="nombre"><option value="">ELEGIR PERSONA</option>';
       for(var o=0;o<data.data[current]['info']['personas'].length;o++){
         table+='<option value="'+data.data[current]['info']['personas'][o].id+'">'+data.data[current]['info']['personas'][o].nombre+" "+data.data[current]['info']['personas'][o].apellido+'</option>';
       }
       table+='<option value="new">Agregar Persona...</option>'+
       '</select></td>';
       table+='<td><select id="selCargo" name="cargo"><option value="">ELEGIR CARGO</option>';
        for(var a=0;a<data.data[current]['info']['cargos'].length;a++){
         table+='<option value="'+data.data[current]['info']['cargos'][a].id+'">'+data.data[current]['info']['cargos'][a].cargo_nombre+" "+data.data[current]['info']['cargos'][a].area_nombre+'</option>';
        }
       '</select></td>';
       table+='<td><input name="fecha" type="date"></td>';
       table+='<td><input type="button" value="GUARDAR" class="nuevo_nucleo"><br/><input type="button" value="CANCELAR" class="cancelar"></td></tr>';
       //table+='<td><input placeholder="DESCRIPCIÓN" name="descripcion" type="textarea"></td>';  
    }else if(current==="centros"){
      table+='<td><input placeholder="NOMBRE" name="nombre" type="text"></td>';
      table+='<td><input placeholder="DESCRIPCIÓN" name="descripcion" type="textarea"></td>';
      table+='<td><input placeholder="FECHA DE CREACIÓN" name="fecha_creacion" type="date"></td>';
      table+='<td><input placeholder="TELÉFONO" name="telefono" type="text"></td>';
      table+='<td><input placeholder="DIRECCIÓN" name="direccion" type="text"></td>';
      table+='<td><input type="button" value="GUARDAR" class="guardar"><br/><input type="button" value="CANCELAR" class="cancelar"></td></tr>';
    }else{
      table+='<td><input placeholder="NOMBRE" name="nombre" type="text"></td>';
      table+='<td><input placeholder="DESCRIPCI&Oacute;N" name="descripcion" type="textarea"></td>';
      table+='<td><input type="button" value="GUARDAR" class="guardar"><br/><input type="button" value="CANCELAR" class="cancelar"></td></tr>';
    }
    
    if($(".none")[0])
      $(".none").remove();
    $('#main_table tbody').append(table);
    $(".agregar").attr("disabled", "disabled");
    $('input.guardar').click(function(){
      guardar($(this),"");
    });
    $('input.nuevo_permiso').click(function(){
      alarm=1;
      cargo=$('.select_cargo option:selected').text();
      area=$('.select_area option:selected').text();
      tipo=$('.select_tipo option:selected').text();
      if($('.select_area option:selected').val()===".area0"){
        area="";
      }else if($('.select_tipo option:selected').val()===".tipo0"){
        tipo="";  

      }
      level=$('.select_nivel').val();
      guardar($(this),"");
    });
    $('input.nuevo_nucleo').click(function(){
      guardar($(this),"");
    });
    $('input.cancelar').click(function(){
      $('#main_table tbody tr:last-child').remove();
      $(".agregar").removeAttr("disabled");
      $(".agregar").show();
    });
    $("#selPersona").change(function(){
       if($(this).find(":selected").val()=="new"){
         $('.background').show();
         $('input[name="date"]').change(function(){
           $('input[name="edad"]').val(getAge($(this).val()));
         });
         $('.cerrar').click(function(){
           $('.background').hide();
        });
         $('.guardarPersona').click(function(){
           guardar($(this),"persona");
         });
       }
    });
    $('input.cancel').click(function(){
      $('#main_table tbody').empty();
      set_table(current); 
      $('#content_title').text("PERMISOS"); 
    });
  });  
});
