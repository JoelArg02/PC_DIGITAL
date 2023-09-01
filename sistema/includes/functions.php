<?php
date_default_timezone_set('America/Guayaquil');

function fechaC()
{
	$mes = array(
		"",
		"Enero",
		"Febrero",
		"Marzo",
		"Abril",
		"Mayo",
		"Junio",
		"Julio",
		"Agosto",
		"Septiembre",
		"Octubre",
		"Noviembre",
		"Diciembre"
	);
	return date('d') . " de " . $mes[date('n')] . " de " . date('Y');
}

function isValidEmail($email)
{
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		return true;
	}
	return false;
}

function isValidCI($ci)
{
    if (!is_numeric($ci)) {
        return false;
    }

    if (strlen($ci) !== 10) {
        return false;
    }

    $total = 0;
    $coeficientes = array(2, 1, 2, 1, 2, 1, 2, 1, 2);

    for ($i = 0; $i < 9; $i++) {
        $temp = $ci[$i] * $coeficientes[$i];
        if ($temp > 9) {
            $temp -= 9;
        }
        $total += $temp;
    }

    $verificador = 10 - ($total % 10);
    if ($verificador === 10) {
        $verificador = 0;
    }

    return $ci[9] == $verificador;
}


?>