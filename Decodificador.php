<?php

//Cargar el fichero csv con los datos
$csv = 'datos.csv';
$array_data = [];

if (($gestor = fopen($csv, "r")) !== FALSE) {
    //Recorrer cada uno los datos
    while (($data = fgetcsv($gestor, 1000, ",")) !== FALSE) {
        $user = $data[0];
        $number_system = $data[1];
        $score = $data[2];
        //Llamar a la función que te devolverá los datos decodificados y guardarlos en un array
        $array_data[] = decode_score($user, $number_system, $score);
    }
    fclose($gestor);
} else {
    echo "Error al abrir el archivo.";
}

//Ordenar el array de mayor puntuación a menor
usort($array_data, function($a, $b) {
    return $b[1] - $a[1];
});

//Guardado de datos en un fichero csv con los datos ya decodificados
$file_data_decoded = fopen("datos_descodificados.csv", "w");

foreach ($array_data as $value) {
    fwrite($file_data_decoded, $value[0].",".$value[1] . PHP_EOL);
}

fclose($file_data_decoded);

//Función para decodificar las puntuaciones
function decode_score($user, $number_system, $score) {
    //La base con la que quieres obtener el numero (en este caso en base decimal)
    $output_base = 10;
    //Base por la que hay que hacer el calculo (Binario, Ternario, etc)
    $numerical_base = strlen($number_system);
    //Como los carácteres de la base codificados siempre van de menor a mayor (0,1,2,etc) aprovecho y lo convierto en un array
    // El indice del array será el número que le corrresponde a cada caracter
    $chars_number_system = str_split($number_system);
    $chars_score = str_split($score);
    $number = "";

    foreach ($chars_score as $char){
        $num = array_search($char, $chars_number_system);
        $number .= $num;
    }

    //Función para obtener el numero decodificado
    $score_decoded = base_convert($number, $numerical_base, $output_base);

    return array($user, $score_decoded);
}