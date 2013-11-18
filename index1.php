<?
include('MVCmodel.php');


if (isset($_REQUEST['data'])) {

	$data = json_decode(stripslashes($_REQUEST['data']));
	if ($data->request) {
		$modelo = new modelo();

		$lnk = mysql_connect('localhost', 'root', 'jigldb')
		or die (
			print json_encode( array('error'=>102,'descriptionerror'=>'dberror: NO Se pudo '.
				'conectar al servidor de DB-> '. mysql_error()))
		);

		mysql_select_db('mvc_system', $lnk) or die (			
			print json_encode( array('error'=>103,'descriptionerror'=>'dberror: NO Se pudo '.
				'conectar-> '. mysql_error() ))
		);
		switch ($data->request) {
			case 'login':
				print json_encode($modelo->login($data->data->email,$data->data->contrasena));
				break;
			case 'guardar_configuracion':
				print json_encode($modelo->registrarConfiguracion($data->data->cargo,
					$data->data->area,$data->data->tipo_ins,$data->data->permisos));
				break;
			case 'registrar_cargo':
				print json_encode($modelo->registrarCargo($data->data->nombre,
					$data->data->descripcion));
				break;
			case 'asignar_cargo':
				print json_encode($modelo->asignarCargo($data->data->persona,$data->data->cargo,
					$data->data->area,$data->data->permanencia,$data->data->despliegue));
				break;
			case 'miembro_cargo':
				print json_encode($modelo->cargosByMiembro($data->data->id));
				break;
			case 'registrar_area':
				print json_encode($modelo->registrarArea($data->data->nombre,
					$data->data->descripcion));
				break;
			case 'registrar_tipo_instancia':
				print json_encode($modelo->registrarTipo($data->data->tipo,$data->data->nombre,
					$data->data->descripcion,$data->data->logo));
				break;
			case 'registrar_permanencia':
				print json_encode($modelo->registrarPermanencia($data->data->foto,
					$data->data->nombre,$data->data->iniciales,$data->data->fecha,
					$data->data->fin,$data->data->telefono,$data->data->email,
					$data->data->direccion,$data->data->descripcion,$data->data->tipo,
					$data->data->centro));
				break;
			case 'consultar_asociacion':
				print json_encode($modelo->consultarAsociacion($data->data->id));
				break;
			case 'registrar_despliegue':
				print json_encode($modelo->registrarDespliegue($data->data->tipo,
					$data->data->permanencia,$data->data->despliegue,$data->data->nombre,
					$data->data->descripcion,$data->data->hora,$data->data->fecha,
					$data->data->lugar,$data->data->colaboracion,$data->data->ntaller,
					$data->data->asistentes,$data->data->categoria,$data->data->contenidos,
					$data->data->observaciones,$data->data->recursos,$data->data->fpago,
					$data->data->inicio,$data->data->fin));
				break;
			case 'registrar_persona':
				print json_encode($modelo->registrarPersona($data->data->foto,
					$data->data->nombre,$data->data->apellido,$data->data->email,
					$data->data->contrasena,$data->data->ciudad,$data->data->sexo,
					$data->data->edad,$data->data->fecha,$data->data->telefono,
					$data->data->claro,$data->data->movi,$data->data->alegro,$data->data->pin,
					$data->data->fb,$data->data->twitter,$data->data->direccion,
					$data->data->estudios,$data->data->institucion,$data->data->cargo,
					$data->data->area,$data->data->permanencia,$data->data->despliegue));
				break;
			case 'registrar_configuracion':
				print json_encode($modelo->registrarConfiguracion($data->data->cargo,
					$data->data->area,$data->data->instancia,$data->data->permisos));
				break;
			default:
				print json_encode(array('error'=>104,'descriptionerror'=>'Requerimiento '.
					'no definido'));
		}
	}
	else
		print json_encode(array('error'=>101,'descriptionerror'=>'No hay requerimiento'));
}
else
	print json_encode(array('error'=>100,'descriptionerror'=>'No existe \'data\' en el '.
		'requerimiento web'));
?>