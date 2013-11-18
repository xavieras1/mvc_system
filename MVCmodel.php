<?
class modelo
{
	private function DBC($query){
		$result = mysql_query($query)
		or die(
			return array('error'=>1,'descriptionerror'=>'dberror: '.mysql_error().' query: '.$query)
		);
		return mysql_fetch_assoc($result);
	}
	public function login($email, $contrasena){
		$result = mysql_query('select * from persona where email=\''.mysql_escape_string($email).'\' 
			AND contrasena=\''.mysql_escape_string($contrasena).'\'')
		or die(
			print json_encode( array('error'=>1,'descriptionerror'=>'dberror: Invalid '.
				'query login1-> '. mysql_error()))
		);
		if ($row = mysql_fetch_assoc($result)) {
			$result1=mysql_query('select CONCAT(CAST(pcai.idcargo AS CHAR),CAST(pcai.idarea AS CHAR),
				CAST(pcai.idtipo_instancia_permanencia AS CHAR),
				CAST(pcai.idinstancia_despliegue AS CHAR)) as id,
				pcai.*,c.*
				from persona_cargo_area_instancia pcai, configuracion c
				where pcai.idpersona='.$row['id'].' 
				and c.idtipo_instancia=pcai.idtipo_instancia_permanencia 
				and c.idarea=pcai.idarea and c.idcargo=pcai.idcargo
				and c.tipo_menu!=0')
			or die(
				print json_encode( array('error'=>2,'descriptionerror'=>'dberror: Invalid '.
				'query login2-> '. mysql_error()))
			);
			$cargos_persona = array();
			while ($cargo_persona = mysql_fetch_assoc($result1)) {
				$cargos_persona[]=$this->getInfoData($cargo_persona);
			}
			mysql_free_result($result);
			mysql_free_result($result1);
			return array('error'=>0, 'data'=>array('user'=>$row,'cargos'=>$cargos_persona));
		}else{
			return array('error'=>3,'descriptionerror'=>'Combinación Email/Password incorrecta.');
		}
	}
	private function getInfoData($cargo_persona){
		switch ($cargo_persona['tipo_menu']) {
			case 1://SUPER ADMIN
				$result=mysql_query('select c.* from cargo c where c.id='.$cargo_persona['idcargo'])
						or die(
							print json_encode( array('error'=>4,'descriptionerror'=>'dberror: Invalid '.
							'query GetInfoData1-> '. mysql_error()))
						);
				$info=array();
				$info=array_merge($cargo_persona,mysql_fetch_assoc($result));
				mysql_free_result($result);
				return array('info'=>$info,
					'data'=>array(
						'cargos'=>$this->getCargos(),
						'areas'=>$this->getAreas(),
						'tipos_instancia'=>$this->getTipos(),
						'centros'=>
							array('info'=>$this->getTipos(array('nivel_arbol'=>1)),
								  'data'=>$this->getCentros()),
						/*'instancias_despliegue'=>
							array('info'=>array('tipos'=>$this->getTipos(array('dinamismo'=>'despliegue'))),
								  'data'=>$this->getPermanencias()),
						'persona_cargo_area_instancia'=>$this->getPcai(),*/
						'configuraciones'=>array(
							'info'=>array('cargos'=>$this->getCargos(array('notitulo'=>'0')),
										'tipos'=>$this->getTipos(array('nonivel_arbol'=>3))),
							'data'=>$this->getConfiguraciones())
						));
				break;
			case 8://ANIMADOR
				$result=mysql_query('select c.* from cargo c where c.id='.$cargo_persona['idcargo'])
						or die(
							print json_encode( array('error'=>4,'descriptionerror'=>'dberror: Invalid '.
							'query GetInfoData1-> '. mysql_error()))
						);
				$info=array();
				$info=array_merge($cargo_persona,mysql_fetch_assoc($result));
				mysql_free_result($result);
				//GET RECURSOS DE APOSTOLADO
				$result1=mysql_query('select ip.*, ti.tipo as tipo_tipo, ti.nombre as tipo_nombre,
					ti.descripcion as tipo_descripcion,ti.logo as tipo_logo,ti.dinamismo as tipo_dinamismo,
					ti.nivel_arbol as tipo_nivel,ti.campos as tipo_campos
					from instancia_permanencia ip, tipo_instancia ti 
					where ip.id='.$cargo_persona['idinstancia_permanencia'].' 
					and ip.idtipo_instancia=ti.id')
					or die(
						print json_encode( array('error'=>6,'descriptionerror'=>'dberror: Invalid '.
							'query getInfoData3-> '. mysql_error()))
					);
				$asociaciones = array();
				$asoInfo = mysql_fetch_assoc($result1);
				return array('info'=>$info,
					'data'=>array(
						'agrupacion'=>array('info'=>$asoInfo,'cargos'=>$this->getCargos(array('instancia'=>'asociacion')),'miembros'=>$this->getMiembrosAsociacion($asoInfo['id'])),
						'registros'=>$this->getRegistrosAsociacion($asoInfo['id']),
						'recursos'=>'',
						'cronograma'=>''));
				break;
			default:
				if($cargo_persona['idinstancia_permanencia']==0){//GENERAL
				}else{//DE CENTRO O ESPECÍFICO
					$result=mysql_query('select c.id as idcargo,c.nombre as nombre,c.titulo as titulo,
						c.instancia as cargo_instancia,a.id as idarea,a.nombre as nombre_area,
						ip.nombre as nombre_permanencia,ip.iniciales as iniciales_permanencia,ti.id as tipo_id_permanencia,
						ti.tipo as tipo_permanencia,ti.nombre as tipo_nombre_permanencia,
						ti.dinamismo as tipo_dinamismo_permanencia,ti.nivel_arbol as tipo_nivel_permanencia,
						ti.campos as tipo_campos_permanencia,centro.id as centro_id,centro.nombre as centro_nombre,
						centro.iniciales as centro_iniciales,id.id as idinstancia_despliegue,id.nombre as nombre_despliegue,
						ti1.id as tipo_id_despliegue,ti1.tipo as tipo_despliegue,ti1.nombre as tipo_nombre_despliegue,
						ti1.dinamismo as tipo_dinamismo_despliegue,ti1.nivel_arbol as tipo_nivel_despliegue,
						ti1.campos as tipo_campos_despliegue
						from (persona p,cargo c,area a)
						left join instancia_permanencia ip on ip.id='.$cargo_persona['idinstancia_permanencia'].'
						left join tipo_instancia ti on ti.id=ip.idtipo_instancia
						left join instancia_permanencia centro on centro.id=ip.idinstancia_permanencia
						left join instancia_despliegue id on id.id='.$cargo_persona['idinstancia_despliegue'].'
						left join tipo_instancia ti1 on ti1.id=id.idtipo_instancia
						where p.id='.$cargo_persona['idpersona'].' and c.id='.$cargo_persona['idcargo'].' 
						and a.id='.$cargo_persona['idarea'])
						or die(
							print json_encode( array('error'=>5,'descriptionerror'=>'dberror: Invalid '.
								'query getInfoData2-> '. mysql_error()))
						);
					$info=array();
					$info=array_merge($cargo_persona,mysql_fetch_assoc($result));
					switch ($cargo_persona['tipo_menu']) {
						case 2://ENCARGADO GENERAL
							$result1=mysql_query('select ip.*, ti.tipo as tipo_tipo, ti.nombre as tipo_nombre,
								ti.descripcion as tipo_descripcion,ti.logo as tipo_logo,ti.dinamismo as tipo_dinamismo,
								ti.nivel_arbol as tipo_nivel,ti.campos as tipo_campos
								from instancia_permanencia ip, tipo_instancia ti 
								where ip.idinstancia_permanencia='.$cargo_persona['idinstancia_permanencia'].' 
								and ip.idtipo_instancia=ti.id')
								or die(
									print json_encode( array('error'=>6,'descriptionerror'=>'dberror: Invalid '.
										'query getInfoData3-> '. mysql_error()))
								);
							$asociaciones = array();
							while ($asoInfo = mysql_fetch_assoc($result1)) {
								$asociaciones[]=array('info'=>$asoInfo,
									'miembros'=>$this->getMiembrosAsociacion($asoInfo['id']),
									'registros'=>$this->getRegistrosAsociacion($asoInfo['id']));
							}
							mysql_free_result($result);
							mysql_free_result($result1);
							return array('info'=>$info,
								'data'=>array(
									'asociaciones'=>array(
										'info'=>array(
											'asociaciones_tipo'=>$this->getTipos(array('tipo'=>'asociación')),
											'asociaciones_cargos'=>$this->getCargos(array('instancia'=>'asociacion'))),
										'data'=>$asociaciones),
									'recursos'=>$this->getRecursos(),
									'actividades'=>array('info'=>array('actividades_tipo'=>$this->getTipos()),
										'data'=>$this->getDespliegues())
									)
								);
							break;
						case 3://ENCARGADO INSTRUCCIÓN
							break;
						case 4://ENCARGADO ESPIRITUALIDAD
							break;
						case 5://ENCARGADO APOSTOLADO
							break;
						case 6://ENCARGADO COMUNICACIONES
							break;
						case 7://ENCARGADO TEMPORALIDADES
							break;
					}
				}
				break;
		}
	}
	private function getMiembrosAsociacion($id){
		$result=mysql_query('select pcai.*,p.foto as persona_foto,p.nombre as persona_nombre,p.apellido as persona_apellido,
			p.email as persona_email,p.ciudad as persona_ciudad,p.sexo as persona_sexo, p.edad as persona_edad,
			p.fecha_nacimiento as persona_nacimiento,p.telefono as persona_telefono,p.celular_claro as persona_claro,
			p.celular_movistar as persona_movi,p.celular_alegro as persona_alegro, p.pin as persona_pin,
			p.facebook as persona_fb,p.twitter as persona_tw,p.direccion as persona_direccion,
			p.nivel_estudio as persona_estudio,p.institucion as persona_institucion,c.id as cargo_id,
			c.nombre as cargo_nombre, c.descripcion as cargo_descripcion,c.titulo as cargo_titulo,
			c.instancia as cargo_instancia
			from persona p,cargo c,persona_cargo_area_instancia pcai
			where pcai.idpersona=p.id and pcai.idcargo=c.id and pcai.idinstancia_permanencia='.$id.' 
			and pcai.idinstancia_despliegue=0 order by c.titulo DESC')
		or die(
			print json_encode( array('error'=>7,'descriptionerror'=>'dberror: Invalid '.
				'query getMiembrosAsociacion-> '. mysql_error()))
		);
		$miembros=array();
		while ($miembro = mysql_fetch_assoc($result)){
			$miembros[]=$miembro;
		}
		mysql_free_result($result);
		return $miembros;
	}
	private function getRegistrosAsociacion($id){
		$result=mysql_query("select p.idpersona,p.fecha_inicio,p.idinstancia_despliegue,
			t.*,id.nombre, id.fecha, id.contenidos,id.observaciones
			from persona_cargo_area_instancia p, tipo_instancia t,instancia_despliegue id
			where t.id=idtipo_instancia_despliegue and t.tipo='Compartir'
			and id.id=p.idinstancia_despliegue and idinstancia_permanencia=".$id." 
			order by id.fecha DESC")
		or die(
			print json_encode( array('error'=>7,'descriptionerror'=>'dberror: Invalid '.
				'query getMiembrosAsociacion-> '. mysql_error()))
		);
		$registros=array();
		while ($registro = mysql_fetch_assoc($result)){
			$registros[]=$registro;
		}
		mysql_free_result($result);
		return $registros;
	}
	/*private function getObjetos($sql,$cond){
		$objetos = array();
		$result = mysql_query($sql)
		or die(
			print json_encode( array('error'=>9,'descriptionerror'=>'dberror: Invalid '.
				'query login1-> '. mysql_error()))
		);
		while ($objeto = mysql_fetch_assoc($result)) {
			$objetos[] = $objeto;
		}
		mysql_free_result($result);
		return $objetos;
	}*/
	private function getCargos($cond){
		$cargos = array();
		$where='';
		if($cond['instancia']!=null){
			$where='where instancia=\''.$cond['instancia'].'\'';
		}
		if($cond['notitulo']!=null){
			if($where=='')
				$where='where titulo!='.$cond['notitulo'];
			else
				$where='AND titulo!='.$cond['notitulo'];
		}
		$result = mysql_query('select * from cargo '.$where.' order by nombre ASC ')
		or die(
			print json_encode( array('error'=>8,'descriptionerror'=>'dberror: Invalid '.
				'query login1-> '. mysql_error()))
		);
		while ($cargo = mysql_fetch_assoc($result)) {
			$cargos[] = $cargo;
		}
		mysql_free_result($result);
		return $cargos;
	}
	private function getAreas(){
		$areas = array();
		$result = mysql_query('select * from area order by nombre ASC ')
		or die(
			print json_encode( array('error'=>9,'descriptionerror'=>'dberror: Invalid '.
				'query login1-> '. mysql_error()))
		);
		while ($area = mysql_fetch_assoc($result)) {
			$areas[] = $area;
		}
		mysql_free_result($result);
		return $areas;
	}
	private function getTipos($cond){
		$tipos = array();
		$where='';
		if($cond['tipo']!=null){
			$where='where tipo=\''.$cond['tipo'].'\'';
		}
		if($cond['dinamismo']!=null){
			if($where=='')
				$where='where dinamismo=\''.$cond['dinamismo'].'\'';
			else
				$where='AND dinamismo=\''.$cond['dinamismo'].'\'';
		}
		if($cond['nivel_arbol']!=null){
			if($where=='')
				$where='where nivel_arbol='.$cond['nivel_arbol'];
			else
				$where='AND nivel_arbol='.$cond['nivel_arbol'];
		}
		if($cond['nonivel_arbol']!=null){
			if($where=='')
				$where='where nivel_arbol!='.$cond['nonivel_arbol'];
			else
				$where='AND nivel_arbol!='.$cond['nonivel_arbol'];
		}
		$result = mysql_query('select * from tipo_instancia '.$where.' order by tipo ASC ')
		or die(
			print json_encode( array('error'=>10,'descriptionerror'=>'dberror: Invalid '.
				'query login1-> '. mysql_error()))
		);
		while ($tipo = mysql_fetch_assoc($result)) {
			$tipos[] = $tipo;
		}
		mysql_free_result($result);
		return $tipos;
	}
	private function getCentros(){
		$instancias = array();
		$result = mysql_query('select * 
			from instancia_permanencia ip
			where ip.idinstancia_permanencia=0
			order by ip.fecha_creacion ASC ')
		or die(
			print json_encode( array('error'=>311,'descriptionerror'=>'dberror: Invalid '.
				'query login1-> '. mysql_error()))
		);
		while ($instancia = mysql_fetch_assoc($result)) {
			$instancias[] = $instancia;
		}
		mysql_free_result($result);
		return $instancias;
	}
	private function getAsociaciones($cond){
		$instancias = array();
		$result = mysql_query('select a.*,b.foto AS foto_centro,b.nombre AS nombre_centro,b.iniciales AS iniciales_centro,
			b.fecha_creacion AS creacion_centro,b.fecha_fin AS fin_centro,b.telefono AS telefono_centro,
			b.email_contacto AS email_centro,b.direccion AS direccion_centro,
			b.descripcion AS descripcion_centro from instancia_permanencia a left join instancia_permanencia b 
			on a.idinstancia_permanencia=b.id  order by a.fecha_creacion ASC ')
		or die(
			print json_encode( array('error'=>11,'descriptionerror'=>'dberror: Invalid '.
				'query login1-> '. mysql_error()))
		);
		while ($instancia = mysql_fetch_assoc($result)) {
			$instancias[] = $instancia;
		}
		mysql_free_result($result);
		return $instancias;
	}
	private function getRecursos(){
		$instancias = array();
		$result = mysql_query('select id.id as ID,id.nombre as NOMBRE,
			id.descripcion as DESCRIPCION,id.categoria,id.lista_recursos,ti.*
			from instancia_despliegue id, tipo_instancia ti
			where id.idtipo_instancia=ti.id and ti.tipo="Recurso"')
		or die(
			print json_encode( array('error'=>12,'descriptionerror'=>'dberror: Invalid '.
				'query login1-> '. mysql_error()))
		);
		while ($instancia = mysql_fetch_assoc($result)) {
			$instancias[] = $instancia;
		}
		mysql_free_result($result);
		return $instancias;
	}
	private function getDespliegues(){
		$instancias = array();
		$result = mysql_query('select * from instancia_despliegue order by nombre ASC ')
		or die(
			print json_encode( array('error'=>12,'descriptionerror'=>'dberror: Invalid '.
				'query login1-> '. mysql_error()))
		);
		while ($instancia = mysql_fetch_assoc($result)) {
			$instancias[] = $instancia;
		}
		mysql_free_result($result);
		return $instancias;
	}
	private function getPersonas(){
		$personas = array();
		$result = mysql_query('select * from persona order by nombre ASC ')
		or die(
			print json_encode( array('error'=>13,'descriptionerror'=>'dberror: Invalid '.
				'query login1-> '. mysql_error()))
		);
		while ($persona = mysql_fetch_assoc($result)) {
			$personas[] = $persona;
		}
		mysql_free_result($result);
		return $personas;
	}
	private function getPcai(){
		$pcais = array();
		$result = mysql_query('select * from persona_cargo_area_instancia')
		or die(
			print json_encode( array('error'=>14,'descriptionerror'=>'dberror: Invalid '.
				'query login1-> '. mysql_error()))
			);
		while ($pcai = mysql_fetch_assoc($result)) {
			$pcais[] = $pcai;
		}
		mysql_free_result($result);
		return $pcais;
	}
	private function getConfiguraciones(){
		$configuraciones = array();
		$result = mysql_query('select * from configuracion')
		or die(
			print json_encode( array('error'=>15,'descriptionerror'=>'dberror: Invalid '.
				'query login1-> '. mysql_error()))
			);
		while ($configuracion = mysql_fetch_assoc($result)) {
			if(count($configuraciones)==0){
				$tmp=array('id'=>$configuracion['idcargo'].$configuracion['idarea'].$configuracion['idtipo_instancia'],
					'idcargo'=>$configuracion['idcargo'],'idarea'=>$configuracion['idarea'],
					'idtipo_instancia'=>$configuracion['idtipo_instancia']);
				if($configuracion['idtipo']==0)
					$tmp['tipo_menu']=$configuracion['tipo_menu'];
				else
					$tmp['permisos'][]=array('editar'=>$configuracion['editar'],'tipo'=>$configuracion['idtipo']);
				array_push($configuraciones,$tmp);
			}else{
				$done=0;
				for($i=0;$i<count($configuraciones);$i++){
					if($configuraciones[$i]['id']==$configuracion['idcargo'].$configuracion['idarea'].$configuracion['idtipo_instancia']){
						if($configuracion['idtipo']==0)
							$configuraciones[$i]['tipo_menu']=$configuracion['tipo_menu'];
						else{
							$configuraciones[$i]['permisos'][]=array('editar'=>$configuracion['editar'],'tipo'=>$configuracion['idtipo']);
						}
						$done=1;
					}
				}
				if($done==0){
					$tmp=array('id'=>$configuracion['idcargo'].$configuracion['idarea'].$configuracion['idtipo_instancia'],
						'idcargo'=>$configuracion['idcargo'],'idarea'=>$configuracion['idarea'],
						'idtipo_instancia'=>$configuracion['idtipo_instancia']);
					if($configuracion['idtipo']==0)
						$tmp['tipo_menu']=$configuracion['tipo_menu'];
					else
						$tmp['permisos'][]=array('editar'=>$configuracion['editar'],'tipo'=>$configuracion['idtipo']);
					array_push($configuraciones,$tmp);
				}
			}
			//$configuraciones[] = $configuracion;
		}
		mysql_free_result($result);
		return $configuraciones;
	}
	public function registrarConfiguracion($cargo,$area,$tipo,$permisos){
		$textarea=' , idarea=0';
		if($area!=null)
			$textarea=' , idarea='.$area;
		$tesxttipo=' , idtipo_instancia=0';
		if($tipo!=null)
			$tesxttipo=' , idtipo_instancia='.$tipo;
		for($i=0;sizeof($permisos)>$i;$i++){
			mysql_query('insert into configuracion set idcargo='.$cargo.$textarea.$tesxttipo.
				' , editar=\''.$permisos[$i]->permiso.'\' , idtipo='.$permisos[$i]->tipo)or die(
				print json_encode( array('error'=>26,'descriptionerror'=>'dberror: Invalid '.
				'query login1-> '. mysql_error()))
				);
		}
		return array('error'=>0, 'data'=> 'ok!');
	}
	public function registrarPermanencia($foto,$nombre,$iniciales,$fecha,$fechafin,$telefono,$email,$direccion,
		$descripcion,$tipo,$centro){
		$result = mysql_query('select * from instancia_permanencia where nombre=\''.mysql_escape_string($nombre).'\'
			AND idtipo_instancia='.$tipo.' AND idinstancia_permanencia='.$centro)
		or die(
			print json_encode( array('error'=>18,'descriptionerror'=>'dberror: Invalid '.
				'query login1-> '. mysql_error()))
			);

		if ($row = mysql_fetch_array($result)) {
			return array('error'=>19,'descriptionerror'=>'Esta asociación en este centro ya se encuentra registrada.');
			// no se que numero de error poner
		}
		else {
			mysql_query('insert into instancia_permanencia set idtipo_instancia='.$tipo.', 
				idinstancia_permanencia='.$centro.', nombre=\''.mysql_escape_string($nombre).'\',
				iniciales=\''.mysql_escape_string($iniciales).'\', fecha_creacion=FROM_UNIXTIME('.strtotime($fecha).'),
				fecha_fin=FROM_UNIXTIME('.strtotime( $fechafin).'), telefono= \''.mysql_escape_string($telefono).'\',
				email_contacto= \''.mysql_escape_string($email).'\', direccion= \''.mysql_escape_string($direccion).'\',
				descripcion= \''.mysql_escape_string($descripcion).'\'')
			or die(
				print json_encode( array('error'=>20,'descriptionerror'=>'dberror: Invalid '.
				'query login1-> '. mysql_error()))
			);
			$row['id'] = mysql_insert_id();
			if($foto==1){
				$a='update instancia_permanencia set foto = \'permanencia'.$row['id'].'.jpg\' where id='.$row['id'];
				$result = mysql_query($a)
				or die("Invalid query: " . mysql_error() . " Query: " . $a);
			}
			return array('error'=>0, 'data'=>array('id'=>$row['id']));
		}
	}
	public function cargosByMiembro($id){
		$cargos = array();
		$result=mysql_query('select pcai.fecha_inicio as fecha_inicio_cargo, pcai.fecha_fin as fecha_fin_cargo,
			c.id as cargo_id,c.nombre as cargo_nombre, c.descripcion as cargo_descripicon, c.titulo as cargo_titulo,
			c.instancia as cargo_instancia,a.id as area_id, a.nombre as area_nombre, a.descripcion as area_descripcion,
			ip.*, id.id as despliegue_id,id.nombre as despliegue_nombre,id.idinstancia_despliegue as despliegue_id_despliegue,
			id.idtipo_instancia as despliegue_id_tipo, id.fecha as despliegue_fecha,id.descripcion as despliegue_descripcion,
			id.hora as despliegue_hora, id.lugar as despliegue_lugar, id.colaboracion as despliegue_colaboracion,
			id.categoria as despliegue_categoria, id.numero_asistentes as despliegue_numero_asistentes,
			id.contenidos as despliegue_contenidos, id.lista_recursos as despliegue_recursos,
			id.observaciones as despliegue_observaciones,id.forma_pago as despliegue_forma_pago 
			from persona p, persona_cargo_area_instancia pcai 
			left join cargo c on c.id=pcai.idcargo left join area a on a.id=pcai.idarea 
			left join instancia_permanencia ip on ip.id=pcai.idinstancia_permanencia
			left join instancia_despliegue id on id.id=pcai.idinstancia_despliegue
			where p.id=pcai.idpersona and p.id='.$id.' and (pcai.idinstancia_permanencia=0 or pcai.idinstancia_despliegue=0)') 
		or die(
			print json_encode( array('error'=>16,'descriptionerror'=>'dberror: Invalid '.
				'query login1-> '. mysql_error()))
			);
		while($cargo = mysql_fetch_assoc($result)){
			$cargos[]=$cargo;
		}
		return array('error'=>0, 'data'=>array('cargos'=>$cargos));	
	}
	public function registrarCargo($nombre, $descripcion){
		mysql_query('insert into cargo set nombre=\''.mysql_escape_string($nombre).'\',
			descripcion= \''.mysql_escape_string($descripcion).'\'');
		$row['id'] = mysql_insert_id();
		return array('error'=>0, 'data'=> array('id'=>$row['id']));
	}
	public function registrarArea($nombre, $descripcion){
		mysql_query('insert into area set nombre=\''.mysql_escape_string($nombre).'\',
			descripcion= \''.mysql_escape_string($descripcion).'\'');
		$row['id'] = mysql_insert_id();
		return array('error'=>0, 'data'=>array('id'=>$row['id']));
	}
	public function registrarTipo($tipo, $nombre, $descripcion,$logo){
		mysql_query('insert into tipo_instancia set tipo=\''.mysql_escape_string($tipo).'\',
			nombre=\''.mysql_escape_string($nombre).'\',
			descripcion= \''.mysql_escape_string($descripcion).'\'');
		$row['id'] = mysql_insert_id();
		if($logo==1){
			$a='update tipo_instancia set logo = \'tipo'.$row['id'].'.jpg\' where id='.$row['id'];
			$result = mysql_query($a)
			or die(
				print json_encode( array('error'=>17,'descriptionerror'=>'dberror: Invalid '.
				'query login1-> '. mysql_error()))
				);
		}
		return array('error'=>0, 'data'=>array('id'=>$row['id']));
	}

	public function registrarDespliegue($tipo,$permanencia,$despliegue,$nombre,$descripcion,$hora,$fecha,$lugar,$colaboracion,
		$ntaller,$asistentes,$categoria,$contenidos,$observaciones,$recursos,$fpago,$inicio,$fin){
		mysql_query('insert into instancia_despliegue set idtipo_instancia='.$tipo.', idinstancia_despliegue='.$despliegue.',
			nombre=\''.mysql_escape_string($nombre).'\',descripcion=\''.mysql_escape_string($descripcion).'\',
			fecha=FROM_UNIXTIME('.strtotime( $fecha).'),hora=FROM_UNIXTIME('.strtotime( $hora).'),
			lugar=\''.mysql_escape_string($lugar).'\',colaboracion=\''.mysql_escape_string($colaboracion).'\',
			numero_taller='.$ntaller.',numero_asistentes='.$asistentes.',categoria=\''.mysql_escape_string($categoria).'\',
			contenidos=\''.mysql_escape_string($contenidos).'\',observaciones=\''.mysql_escape_string($observaciones).'\',
			lista_recursos=\''.mysql_escape_string($recursos).'\',forma_pago=\''.mysql_escape_string($fpago).'\'');
		$row['id'] = mysql_insert_id();
		if($permanencia!=0)
			mysql_query('insert into instancia_despliegue_permanencia set idinstancia_despliegue='.$row['id'].',
				idinstancia_permanencia='.$permanencia.', fecha_inicio=FROM_UNIXTIME('.strtotime( $inicio).'),
				fecha_fin=FROM_UNIXTIME('.strtotime( $fin).')');
		return array('error'=>0, 'data'=>array('id'=>$row['id']));
	}

	public function registrarPersona($foto,$nombre,$apellido,$email,$contrasena,$ciudad,$sexo,$edad,$fecha,$telefono,
		$claro,$movi,$alegro,$pin,$fb,$twitter,$direccion,$estudios,$institucion,$cargo,$area,$permanencia,$despliegue){
		$result = mysql_query('select * from persona where email=\''.mysql_escape_string($email).'\'')
		or die(
			print json_encode( array('error'=>21,'descriptionerror'=>'dberror: Invalid '.
				'query login1-> '. mysql_error()))
			);
		if ($row = mysql_fetch_array($result)) {
			return array('error'=>22,'descriptionerror'=>'Ya existe una persona registrada con este email en el sistema,
				por favor revisar.');
		}
		else {
			mysql_query('insert into persona set nombre=\''.mysql_escape_string($nombre).'\',
				apellido=\''.mysql_escape_string($apellido).'\', email=\''.mysql_escape_string($email).'\',
				contrasena=\''.mysql_escape_string($contrasena).'\', ciudad=\''.$ciudad.'\',sexo=\''.$sexo.'\',
				edad='.$edad.', fecha_nacimiento=FROM_UNIXTIME('.strtotime( $fecha).'), telefono=\''.mysql_escape_string($telefono).'\',
				celular_claro=\''.mysql_escape_string($claro).'\', celular_movistar=\''.mysql_escape_string($movi).'\',
				celular_alegro=\''.mysql_escape_string($alegro).'\', pin=\''.$pin.'\', facebook=\''.mysql_escape_string($fb).'\',
				twitter=\''.mysql_escape_string($twitter).'\', direccion=\''.mysql_escape_string($direccion).'\',
				nivel_estudio=\''.$estudios.'\',
				institucion=\''.mysql_escape_string($institucion).'\'');
			$row['id'] = mysql_insert_id();
			if($foto==1){
				$a='update persona set foto = \'persona'.$row['id'].'.jpg\' where id='.$row['id'];
				$result = mysql_query($a)
				or die(
					print json_encode( array('error'=>23,'descriptionerror'=>'dberror: Invalid '.
				'query login1-> '. mysql_error()))
					);
			}
			mysql_query('insert into persona_cargo_area_instancia set idpersona='.$row['id'].', idcargo='.$cargo.', 
				idarea='.$area.',idinstancia_despliegue='.$despliegue.', idinstancia_permanencia='.$permanencia)
			or die(
				print json_encode( array('error'=>24,'descriptionerror'=>'dberror: Invalid '.
				'query login1-> '. mysql_error()))
				);
			if($permanencia==0&&$despliegue==0){
				$result = mysql_query('select * from instancia_permanencia ip where ip.idinstancia_permanencia=0');
				while ($centro = mysql_fetch_assoc($result)) {
					mysql_query('insert into persona_cargo_area_instancia set idpersona='.$row['id'].', idcargo='.$cargo.',
						idarea='.$area.', idinstancia_despliegue='.$despliegue.', idinstancia_permanencia='.$result['id'])
					or die(
						print json_encode( array('error'=>25,'descriptionerror'=>'dberror: Invalid '.
						'query login1-> '. mysql_error()))
					);
				}
			}
			return array('error'=>0, 'data'=> array('id'=>$row['id']));
		}
	}
		
	public function asignarCargo($persona, $cargo, $area, $permanencia, $despliegue){
		mysql_query('insert into persona_cargo_area_instancia set idpersona='.$persona.', idcargo='.$cargo.',
			idarea='.$area.',idinstancia_permanencia='.$permanencia.', idinstancia_despliegue='.$despliegue)
		or die(
			print json_encode( array('error'=>27,'descriptionerror'=>'dberror: Invalid '.
				'query login1-> '. mysql_error()))
			);
		$row['id'] = mysql_insert_id();
		return array('error'=>0, 'data'=> array('id'=>$row['id']));
	}
}
?>