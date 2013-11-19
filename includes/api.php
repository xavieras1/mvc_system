<?php
include('MVC-modelo.php');


if (isset($_REQUEST['request'])) {
	$modelo = new modelo();

	$lnk = mysql_connect('localhost', 'root', '')
	or die (
		print json_encode( array('error'=>102,'descriptionerror'=>'dberror: NO Se pudo '.
			'conectar al servidor de DB-> '. mysql_error()))
	);

	mysql_select_db('mvc_system', $lnk) or die (			
		print json_encode( array('error'=>103,'descriptionerror'=>'dberror: NO Se pudo '.
			'conectar-> '. mysql_error() ))
	);
	switch ($_REQUEST['request']) {
		case 'login':
			print json_encode($modelo->login($_REQUEST['user'],$_REQUEST['contrasena']));
			break;
		case 'cambio_cargo':
			print json_encode($modelo->change($_REQUEST['id']));
			break;
		case 'guardar':
			print json_encode($modelo->save($_REQUEST['tipo'],$_REQUEST));
			break;
		case 'eliminar':
			print json_encode($modelo->delete($_REQUEST['tipo'],$_REQUEST['id']));
			break;
		case 'logout':
			print json_encode($modelo->logout());
			break;
		
		default:
			print json_encode(array('error'=>104,'descriptionerror'=>'Requerimiento '.
				'no definido'));
	}
	/*$data = json_decode(stripslashes($_REQUEST['data']));
	if ($data->request) {
		$modelo = new modelo();

		$lnk = mysql_connect('localhost', 'root', '')
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
				print json_encode($modelo->login($data->data->user,$data->data->contrasena));
				break;
			default:
				print json_encode(array('error'=>104,'descriptionerror'=>'Requerimiento '.
					'no definido'));
		}
	}
	else
		print json_encode(array('error'=>101,'descriptionerror'=>'No hay requerimiento'));*/
}
else
	print json_encode(array('error'=>100,'descriptionerror'=>'No existe \'request\' en el '.
		'requerimiento web'));
?>