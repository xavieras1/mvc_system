    var table='<tr class="table_row">';

      //Cargos
      table+='<td><select id="cargos">';
        table+='<option selected="selected" value="0">--Elegir Cargo--</option>';
        for (var i = 0; i < $data.info.cargos.length; i++) {                
            table+='<option value=".cargo'+$data.info.cargos[i].id+'_'+i+'">'+$data.info.cargos[i].nombre+'</option>';      
        }        
      table+='</select></td>';

      //Areas
      table+='<td><select id="areas">';
        table+='<option selected="selected" value="0">--Elegir Area--</option>';
        for (var i = 0; i < $data.info.areas.length; i++) { 
            table+='<option value=".cargo'+$data.info.areas[i].id+'_'+i+'">'+$data.info.areas[i].nombre+'</option>';      
        }        
      table+='</select></td>';

      //Tipos de Instancia
      table+='<td><select id="cargos">';
        table+='<option selected="selected" value="0">--Elegir Tipo de Instancia--</option>';
        for (var i = 0; i < $data.info.tipos.length; i++) { 
              table+='<option value=".cargo'+$data.info.tipos[i].id+'_'+i+'">'+$data.info.tipos[i].nombre+'</option>';      
        }        
      table+='</select></td>';      
      table+='<td><input placeholder="NIVEL" name="nivel" type="textarea"></td>';
      table+='<td></td>';
      table+='<td></td>';
