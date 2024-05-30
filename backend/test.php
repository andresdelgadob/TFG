<?php

$url = 'http://localhost/public/traduccion';

$casos = array(
    array(
        'data' => array(
            'texto' => 'Dear John,Thank you for your email. I`m writing to confirm our meeting tomorrow at 10:00 AM.',
            'idiomaEntrada' => 'English',
            'idiomaSalida' => 'Spanish',
            'opciones' => array(
                'formal' => false,
                'formato' => false
            )
        ),
        'respuestaEsperada' => 'Estimado John,\nGracias por tu correo electrónico. Te escribo para confirmar nuestra reunión de mañana a las 10:00 AM.',
        'codigoHTTPEsperado' => 200,
        'titulo'=> 'traducción base sin opciones activas'
    ),
    array(
        'data' => array(
            'texto' => 'Bonjour, Comment ça va? Je voulais juste vous remercier pour votre aide précieuse lors de notre dernier projet.',
            'idiomaEntrada' => '',
            'idiomaSalida' => 'Spanish',
            'opciones' => array(
                'formal' => false,
                'formato' => false
            )
        ),
        'respuestaEsperada' => 'Hola, ¿Cómo estás? Solo quería agradecerte por tu valiosa ayuda en nuestro último proyecto.',
        'codigoHTTPEsperado' => 200,
        'titulo'=> 'traducción base sin opciones activas, ni idioma de entrada'
    ),
    array(
        'data' => array(
            'texto' => '¡Hola a todos! Gracias por unirse a nosotros en esta conferencia.',
            'idiomaEntrada' => 'Spanish',
            'idiomaSalida' => 'English',
            'opciones' => array(
                'formal' => true,
                'formato' => false
            )
        ),
        'respuestaEsperada' => 'Hello everyone! Thank you for joining us in this conference.',
        'codigoHTTPEsperado' => 200,
        'titulo'=> 'opción de traducción más formalidad activada'
    ),
    array(
        'data' => array(
            'texto' => 'Bonjour, Je suis désolé, mais je ne peux pas assister à la réunion demain.',
            'idiomaEntrada' => 'French',
            'idiomaSalida' => 'English',
            'opciones' => array(
                'formal' => false,
                'formato' => true
            )
        ),
        'respuestaEsperada' => 'Hello, I`m sorry, but I can`t attend the meeting tomorrow.',
        'codigoHTTPEsperado' => 200,
        'titulo'=> 'opción de traducción mantener formato activado'
    ),
    array(
        'data' => array(
            'texto' => 'Estimado Sr. Pérez, Gracias por su tiempo en nuestra reunión de hoy.',
            'idiomaEntrada' => 'Spanish',
            'idiomaSalida' => 'English',
            'opciones' => array(
                'formal' => true,
                'formato' => true
            )
        ),
        'respuestaEsperada' => 'Dear Mr. Perez, Thank you for your time in our meeting today.',
        'codigoHTTPEsperado' => 200,
        'titulo'=> 'ambas opciones de traducción activadas'
    ),   
    array(
        'data' => array(
            'texto' => '',
            'idiomaEntrada' => 'Spanish',
            'idiomaSalida' => 'English',
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
            'texto' => 'Guten Tag, Ich wünsche Ihnen einen schönen Tag!',
            'idiomaEntrada' => 'German',
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
            'texto' => 'Ciao, Spero che tu abbia una buona giornata!',
            'idiomaEntrada' => 'IT',
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

$tiempos = array();
foreach ($casos as $caso) {

    $resultado = llamadaAPI($url, $caso['data']);

    if($resultado['codigoHTTP']==200){
        $tiempo = $resultado['tiempo'];
        $tiempos[] = $tiempo;
    }

    print('Caso: '. $caso['titulo']."\n\n");

    if ($resultado['codigoHTTP'] == $caso['codigoHTTPEsperado']) {
        echo "Test correcto: Estado HTTP correcto\n";
    } else {
        echo "Test fallido:" . $resultado['codigoHTTP'] . "\n";
        echo "Estado HTTP incorrecto: " . $resultado['codigoHTTP'] . "\n";
        echo "Estado HTTP esperado: " . $caso['codigoHTTPEsperado'] . "\n";
    }

    $similaridad = 0;
    similar_text($resultado['respuesta'], $caso['respuestaEsperada'], $similaridad);

    if ($similaridad >= 75) {
        echo "Test correcto: Respuesta correcta\n";
        echo "Respuesta: " . $resultado['respuesta'] . "\n";
        echo "Similaridad: ". $similaridad. " \n\n";
    } else {
        echo "Test fallido:". "\n";
        echo "Respuesta incorrecta: " . $resultado['respuesta'] . "\n";
        echo "Respuesta esperada: " . $caso['respuestaEsperada'] . "\n";
        echo "Similaridad: ". $similaridad. " \n\n";

    }
}

$tiempoPromedio = array_sum($tiempos) / count($tiempos);
echo "Tiempo promedio de llamadas con código HTTP 200: " . $tiempoPromedio . " segundos";


function llamadaAPI($url, $data) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json'
    ));
    $inicio = microtime(true);
    $respuesta = json_decode(curl_exec($ch));
    $fin = microtime(true); 
    $codigoHTTP = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $tiempo = $fin - $inicio;
    curl_close($ch);

    return array('respuesta' => $respuesta, 'codigoHTTP' => $codigoHTTP, 'tiempo' =>  $tiempo);
}
