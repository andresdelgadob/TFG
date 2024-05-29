<?php

$url = 'http://localhost/public/traduccion';

$casos = array(
    array(
        'data' => array(
            'texto' => 'Hello',
            'idiomaEntrada' => 'English',
            'idiomaSalida' => 'Spanish',
            'opciones' => array(
                'formal' => false,
                'formato' => false
            )
        ),
        'respuestaEsperada' => 'Hola',
        'codigoHTTPEsperado' => 200,
        'titulo'=> 'traducción base sin opciones activas'
    ),
    array(
        'data' => array(
            'texto' => 'Hello',
            'idiomaEntrada' => '',
            'idiomaSalida' => 'Spanish',
            'opciones' => array(
                'formal' => false,
                'formato' => false
            )
        ),
        'respuestaEsperada' => 'Hola',
        'codigoHTTPEsperado' => 200,
        'titulo'=> 'traducción base sin opciones activas, ni idioma de entrada'
    ),
    array(
        'data' => array(
            'texto' => 'Hello',
            'idiomaEntrada' => 'English',
            'idiomaSalida' => 'Spanish',
            'opciones' => array(
                'formal' => true,
                'formato' => false
            )
        ),
        'respuestaEsperada' => 'Hola',
        'codigoHTTPEsperado' => 200,
        'titulo'=> 'opción de traducción más formalidad activada'
    ),
    array(
        'data' => array(
            'texto' => 'Hello',
            'idiomaEntrada' => 'English',
            'idiomaSalida' => 'Spanish',
            'opciones' => array(
                'formal' => false,
                'formato' => true
            )
        ),
        'respuestaEsperada' => 'Hola',
        'codigoHTTPEsperado' => 200,
        'titulo'=> 'opción de traducción mantener formato activado'
    ),
    array(
        'data' => array(
            'texto' => 'hello Sir',
            'idiomaEntrada' => 'English',
            'idiomaSalida' => 'Spanish',
            'opciones' => array(
                'formal' => true,
                'formato' => true
            )
        ),
        'respuestaEsperada' => 'hola Señor',
        'codigoHTTPEsperado' => 200,
        'titulo'=> 'ambas opciones de traducción activadas'
    ),   
    array(
        'data' => array(
            'texto' => '',
            'idiomaEntrada' => 'English',
            'idiomaSalida' => 'Spanish',
            'opciones' => array(
                'formal' => false,
                'formato' => false
            )
        ),
        'respuestaEsperada' => 'No hay texto para traducir. Por favor, introduzca un texto.',
        'codigoHTTPEsperado' => 400,
        'titulo'=> 'falta el texto de entrada'
    ),
    array(
        'data' => array(
            'texto' => 'Hello',
            'idiomaEntrada' => 'EN',
            'idiomaSalida' => '',
            'opciones' => array(
                'formal' => false,
                'formato' => false
            )
        ),
        'respuestaEsperada' => 'Falta el idioma de salida.',
        'codigoHTTPEsperado' => 400,
        'titulo'=> 'falta el idioma de salida'
    ),
    array(
        'data' => array(
            'texto' => 'Hello',
            'idiomaEntrada' => 'EN',
            'idiomaSalida' => 'ES',
            'opciones' => array(
                'formal' => false,
                'formato' => false
            )
        ),
        'respuestaEsperada' => 'Idioma de salida incorrecto.',
        'codigoHTTPEsperado' => 400,
        'titulo'=> 'idioma de salida erroneo'
    )
);

foreach ($casos as $caso) {
    $resultado = llamadaAPI($url, $caso['data']);

    print('Caso: '. $caso['titulo']."\n\n");

    if ($resultado['codigoHTTP'] == $caso['codigoHTTPEsperado']) {
        echo "Test correcto: Estado HTTP correcto\n";
    } else {
        echo "Test fallido:" . $resultado['codigoHTTP'] . "\n";
        echo "Estado HTTP incorrecto: " . $resultado['codigoHTTP'] . "\n";
        echo "Estado HTTP esperado: " . $caso['codigoHTTPEsperado'] . "\n";
    }

    if ($resultado['respuesta'] == $caso['respuestaEsperada']) {
        echo "Test correcto: Respuesta correcta\n\n";
    } else {
        echo "Test fallido:". "\n";
        echo "Respuesta incorrecta: " . $resultado['respuesta'] . "\n";
        echo "Respuesta esperada: " . $caso['respuestaEsperada'] . "\n\n";
    }
}


function llamadaAPI($url, $data) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json'
    ));
    $respuesta = json_decode(curl_exec($ch));
    $codigoHTTP = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return array('respuesta' => $respuesta, 'codigoHTTP' => $codigoHTTP);
}
