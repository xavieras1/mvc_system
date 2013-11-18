// JavaScript Document
//Angel Astudillo && Andrea Simbaña && Yuri Cosquillo
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

var id_perm;
var nivel;
function editar(boton) {
  var current=$('.active').attr('href').substring(1);

  if(current==="permisos"){
    id_perm=(boton.parent().parent().attr("class").substring(boton.parent().parent().attr("class").indexOf(" ")+1+current.length));
    nivel=(boton.parent().parent().children(":nth-child(4)").text());
    $(".agregar").hide();
    $("#content_title").text(boton.parent().parent().children(":nth-child(1)").text()+" de "+
      boton.parent().parent().children(":nth-child(2)").text()+" de "+
      boton.parent().parent().children(":nth-child(3)").text()
      );
    $("#content_title").attr("class","permiso"+boton.parent().parent().attr("class").replace( /^\D+/g, ''));
    $('#main_table tbody').empty();
    set_table_editar(current);

    
    //$("#main").load("editar_permiso.php");
    //window.location.href = "http://stackoverflow.com";//la intencion es llamar a una nueva pagina para editar un permiso
  }else if(current==="tipos_instancia"){
    //table+='<td><input name="logo" type="file"></td>';
      boton.parent().parent().children(":nth-child(1)").html('<input name="logo" type="file" value="'+boton.parent().parent().children(":nth-child(1)").val()+'">');
      boton.parent().parent().children(":nth-child(2)").html('<input name="clasificacion" type="text" value="'+boton.parent().parent().children(":nth-child(2)").text()+'">');
      boton.parent().parent().children(":nth-child(3)").html('<input name="nombre" type="text" value="'+boton.parent().parent().children(":nth-child(3)").text()+'">');
      boton.parent().parent().children(":nth-child(4)").html('<input name="descripcion" type="textarea" value="'+boton.parent().parent().children(":nth-child(4)").text()+'">');
  }else{
    boton.parent().parent().children(":nth-child(1)").html('<input name="nombre" type="text" value="'+boton.parent().parent().children(":nth-child(1)").text()+'">');

    boton.parent().parent().children(":nth-child(2)").html('<input name="descripcion" type="textarea" value="'+boton.parent().parent().children(":nth-child(2)").text()+'">');    
  }        
  boton.parent().parent().children(":last-child").html('<input type="button" value="GUARDAR" class="guardar"><br/><input type="button" value="CANCELAR" class="cancelar">');

  $(".agregar").attr("disabled", "disabled");
  $('input.guardar').click(function(){
    guardar($(this),"");
  });

  $('input.cancelar').click(function(){ //cancelar del editar
    //$('#main_table tbody tr:last-child').remove();
    $('#main_table tbody').empty();
    set_table(current);
  $(".agregar").removeAttr("disabled");   
   });
     
}
function guardar(boton,additional) {
  var current=$('.active').attr('href').substring(1);
  var id="";

  if(boton.attr("class")==="save_permiso"){
    var url="includes/api.php?request=guardar&tipo="+current+"&id="+id_perm;
  }else{
    if(boton.parent().parent().attr("class").indexOf(" ")>0)
      id=boton.parent().parent().attr("class").substring(boton.parent().parent().attr("class").indexOf(" ")+1+current.length);
    var url="includes/api.php?request=guardar&tipo="+current+"&id="+id;
  }
  if(current==="permisos"){
    var arr=$('.table_row');
    var ids=new Array();
    var permisos=new Array();
    for (var i = 0; i < arr.length; i++) {
      var thenum=$(arr[i]).children(":nth-child(1)").attr('id').replace( /^\D+/g, '');
      ids.push(thenum);
      permisos.push($("input[name='permiso"+thenum+"']:checked").val());
    };
    url+="&nivel="+nivel;
    url+="&ids="+ids;
    url+="&permisos="+permisos;
    //$("#main").load("./index.php");
    //window.location.href = "http://stackoverflow.com";//la intencion es llamar a una nueva pagina para editar un permiso
  }else if(current==="nucleo"){
    if(additional)
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
  }else if(current==="tipos_instancia"){
    //table+='<td><input name="logo" type="file"></td>';}
    url+="&"+boton.parent().parent().children(":nth-child(1)").children("input").attr("name")+"="+boton.parent().parent().children(":nth-child(1)").children("input").val();
    url+="&"+boton.parent().parent().children(":nth-child(2)").children("input").attr("name")+"="+boton.parent().parent().children(":nth-child(2)").children("input").val();
    url+="&"+boton.parent().parent().children(":nth-child(3)").children("input").attr("name")+"="+boton.parent().parent().children(":nth-child(3)").children("input").val();
    url+="&"+boton.parent().parent().children(":nth-child(4)").children("input").attr("name")+"="+boton.parent().parent().children(":nth-child(4)").children("input").val();
  }else{
    url+="&"+boton.parent().parent().children(":nth-child(1)").children("input").attr("name")+"="+boton.parent().parent().children(":nth-child(1)").children("input").val();
    url+="&"+boton.parent().parent().children(":nth-child(2)").children("input").attr("name")+"="+boton.parent().parent().children(":nth-child(2)").children("input").val();
  }
  /*for (var i = 0; i < $('table input[type="text"]').length; i++) {
    url+='&'+$($('table input[type="text"]')[i]).attr('name')+'='+$($('table input[type="text"]')[i]).val();
  };*/
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
        var cargo=boton.parent().parent().children(":nth-child(1)").children("input").val();
        boton.parent().parent().children(":nth-child(1)").html(cargo);
        var area=boton.parent().parent().children(":nth-child(2)").children("input").val();
        boton.parent().parent().children(":nth-child(2)").html(area);
        var tipo_instancia=boton.parent().parent().children(":nth-child(3)").children("input").val();
        boton.parent().parent().children(":nth-child(3)").html(tipo_instancia);
        var nivel=boton.parent().parent().children(":nth-child(4)").children("input").val();
        boton.parent().parent().children(":nth-child(4)").html(nivel);
        var permiso=boton.parent().parent().children(":nth-child(1)").children("input").val();
        boton.parent().parent().children(":nth-child(1)").html(permiso);//cambiar a combo box
        var propiedad=boton.parent().parent().children(":nth-child(2)").children("input").val();
        boton.parent().parent().children(":nth-child(2)").html(propiedad);//cmbiar a combo


        //poner las rows de lo guardad
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

      }else if(current==="nucleo"){
        if(additional)
          alert("hols");
      }else{
        var nombre=boton.parent().parent().children(":nth-child(1)").children("input").val();
        boton.parent().parent().children(":nth-child(1)").html(nombre);
        var descripcion=boton.parent().parent().children(":nth-child(2)").children("input").val();
        boton.parent().parent().children(":nth-child(2)").html(descripcion);
        boton.parent().parent().attr("class","table_row "+current+json.id);
        var nuevo={};
        nuevo['id']=json.id;
        nuevo['nombre']=nombre;
        nuevo['descripcion']=descripcion;
        //data.data[current].push(nuevo);  --> local session , global session(php)
      }
      boton.parent().parent().children(":last-child").html('<input type="button" value="EDITAR" class="btneditar"><br/><input type="button" value="ELIMINAR" class="btneliminar">');
      
      $('.btneditar').click(function(){
      editar($(this));
      });

      $('.btneliminar').click(function(){
      eliminar(current,nuevo['id']);
      });
      
      $(".agregar").removeAttr("disabled");
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

        var flag=0;
        for (var i = 0; i < data.data[table].length&&flag===0; i++) {
          if(data.data[table][i].id===id){
            data.data[table].splice(i, 1);
            flag=1;
          }
        }
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
    var current=$('.active').attr('href').substring(1);
    var table='<tr class="table_row">';
    if(current==="permisos"){
      $("#main").load("./index.php");
      //window.location.href = "http://stackoverflow.com";//la intencion es llamar a una nueva pagina para agregar un permiso
    }else if(current==="tipos_instancia"){
      table+='<td><input name="logo" type="file"></td>';
      table+='<td><input placeholder="CLASIFICACI&Oacute;N" name="clasificacion" type="text"></td>';
      table+='<td><input placeholder="NOMBRE" name="nombre" type="text"></td>';
      table+='<td><input placeholder="DESCRIPCI&Oacute;N" name="descripcion" type="textarea"></td>';
    }else if(current==="nucleo"){
      table+='<td><select id="selPersona" name="nombre"><option value="">ELEGIR PERSONA</option>';
      for(var o=0;o<data.data[current]['info']['personas'].length;o++){
        table+='<option value="'+data.data[current]['info']['personas'][o].id+'">'+data.data[current]['info']['personas'][o].nombre+" "+data.data[current]['info']['personas'][o].apellido+'</option>';
      }
      table+='<option value="new">Agregar Persona...</option>'+
      '</select></td>';
      table+='<td><select name="nombre"><option value="">ELEGIR CARGO</option>'+
      '<option value="">ENCARGADO GENERAL</option>'+
      '<option value="">ENCARGADO DE INSTRUCCIÓN</option>'+
      '<option value="">ENCARGADO DE ESPIRITUALIDAD</option>'+
      '<option value="">ENCARGADO DE APOSTOLADO</option>'+
      '<option value="">ENCARGADO DE TEMPORALIDADES</option>'+
      '<option value="">ENCARGADO DE COMUNICACIONES</option>'+
      '</select></td>';
      table+='<td><input name="fecha" type="date"></td>';
      //table+='<td><input placeholder="DESCRIPCIÓN" name="descripcion" type="textarea"></td>';
    }else{
      table+='<td><input placeholder="NOMBRE" name="nombre" type="text"></td>';
      table+='<td><input placeholder="DESCRIPCI&Oacute;N" name="descripcion" type="textarea"></td>';
    }
    table+='<td><input type="button" value="GUARDAR" class="guardar"><br/><input type="button" value="CANCELAR" class="cancelar"></td></tr>';
    if($(".none")[0])
      $(".none").remove();
    $('#main_table tbody').append(table);
    $(".agregar").attr("disabled", "disabled");
    $('input.guardar').click(function(){
      guardar($(this),"");
    });
    $('input.cancelar').click(function(){
      $('#main_table tbody tr:last-child').remove();
      $(".agregar").removeAttr("disabled");   
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
  });
});
