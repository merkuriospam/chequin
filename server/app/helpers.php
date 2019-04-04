<?php

function articulos_tipos() 
{	$tipos = array('producto' => 'Producto', 'servicio' => 'Servicio', 'lugar' => 'Lugar');
	return $tipos;
}

function operaciones_estados() 
{	$estados = array();
	$estados[1] = 'Pendiente';
	$estados[2] = 'Cancelado';
	$estados[3] = 'Confirmado';
	return $estados;
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function queDia($n = 1) {
    $indice = (string)$n;
    $days = [
      '1' => 'Lunes',
      '2' => 'Martes',
      '3' => 'Miercoles',
      '4' => 'Jueves',
      '5' => 'Viernes',
      '6' => 'Sabado',
      '7' => 'Domingo'
    ];
    return $days[$indice];
}

function getHorariosTurnos() {
    $horarios = [
          '0' => array('00:00','00:29'),
          '1' => array('12:00','12:29'),
          '2' => array('12:30','12:59'),
          '3' => array('13:00','13:29'),
          '4' => array('13:30','13:59'),
          '5' => array('14:00','14:29'),
          '6' => array('14:30','14:59')
    ];
    return $horarios;
}

function ajustarGMT($fecha) {
  return date('Y-m-d H:i:s', strtotime($fecha.' -3 hours'));
}

function slugme($string) {
    return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8'))), ' '));
}

?>