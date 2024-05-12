<?php

// Cabecera de la llamada
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

// Permitir preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
// Comprobar si la llamada es un POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Datos de la llamada
    $data = file_get_contents('php://input');
    
    $postData = json_decode($data, true);

    // Detectar si vienen las variables necesarias para traducir en la solicitud
    if(empty($postData['texto'])){
        echo json_encode("No hay texto para traducir. Por favor, introduzca un texto.");
        http_response_code(400);
        exit();
    } 
    if(empty($postData['idiomaSalida'])){
        echo json_encode("Falta el idioma de salida");
        http_response_code(400);
        exit();
    } 

    // Idiomas disponibles
    $idiomas = file_get_contents('./idiomas.json');
    $idiomasArray = json_decode($idiomas, true);

    // Formateo de los datos de la llamada
    $textoEntrada = $postData['texto'];
    $idiomaEntrada = buscarClavePorPalabra($postData['idiomaEntrada'],$idiomasArray);
    $idiomaSalida = buscarClavePorPalabra($postData['idiomaSalida'],$idiomasArray);
    $formal=$postData['opciones']['formal']??false;
    $formato=$postData['opciones']['formato']??false;
    $formalBBDD=$postData['opciones']['formal']?1:0;
    $formatoBBDD=$postData['opciones']['formato']?1:0;

    // Si el idioma de salida es default mostrar error
    if($idiomaSalida=='default'){
        echo json_encode("Idioma de salida incorrecto");
        http_response_code(400);
        exit();
    } 

    // Información de la base de datos
    $host = 'localhost';
    $dbname = 'postgres';
    $user = 'postgres';
    $password = 'andres';
    $port = '5433';

    // Conexión con la base de datos
    try {
        $conn = new PDO("pgsql:host=$host;dbname=$dbname;port=$port", $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Número de caracteres a traducir
        $numCaracteres = strlen($textoEntrada)??0;  

        // Número de caracteres total traduccidas
        $query = "SELECT SUM(num_caracteres) as total_caracteres FROM traducciones";
        $result = $conn->query($query);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $totalCaracteres = $row['total_palabras']+$numCaracteres;

        // Comprobación de límite de traducción de caracteres mensual
        if($totalCaracteres>=450000){
            throw new Exception("Número máximo de traducciones mensual alcanzaddo");
        }else{
            // Llamada Api al software de traducción
            // Datos para la solicitud
            $apiKey = "bd7210a9-bcd9-df24-d8b8-01aec4676138:fx";

            // Configuración de la solicitud cURL
            $url = "https://api-free.deepl.com/v2/translate";
            if($idiomaEntrada!='default'){
                $data = array(
                    'text' => $textoEntrada,
                    'target_lang' => $idiomaSalida,
                    'source_lang' => $idiomaEntrada,
                    'formality' => $formal?'prefer_more':'default',
                    'preserve_formatting' => $formato
                );
            }else{
                $data = array(
                    'text' => $textoEntrada,
                    'target_lang' => $idiomaSalida,
                    'formality' => $formal?'prefer_more':'default',
                    'preserve_formatting' => $formato
                );
            }  

            $options = array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query($data),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: DeepL-Auth-Key ' . $apiKey,
                    'Content-Type: application/x-www-form-urlencoded',
                ),
            );

            $ch = curl_init();
            curl_setopt_array($ch, $options);

            // Ejecutar la solicitud cURL y obtener la respuesta
            $response = json_decode(curl_exec($ch),true);
            $textoTraducido = $response['translations'][0]['text'];

            // Verificar si hubo algún error
            if (curl_errno($ch)) {
                echo 'Error en la solicitud cURL: ' . curl_error($ch);
            }

            // Cerrar la sesión cURL
            curl_close($ch);

            // Insercción en la base de datos
            $insert = $conn->prepare("INSERT INTO traducciones (idioma_entrada, idioma_salida, texto, traduccion, num_caracteres, formal, formato) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $insert->execute([$idiomaEntrada, $idiomaSalida, $textoEntrada, $textoTraducido, $numCaracteres, $formalBBDD, $formatoBBDD]);
        }
        //Respuesta con el texto traducido
        echo json_encode($textoTraducido);

    } catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    } catch (Exception $e) {
        die($e);
    }


}
else {
    $response = "Solicitud no válida";
    echo json_encode($response);
    http_response_code(400);
    exit();
}

// Función para devolver la clave de un array clave => valor
function buscarClavePorPalabra($palabra, $array) {
    $clave = array_search($palabra, $array);
    return $clave !== false ? $clave : 'default';
}
?>

