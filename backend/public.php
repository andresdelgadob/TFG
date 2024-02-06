<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = file_get_contents('php://input');
    
    $postData = json_decode($data, true);
        // Idiomas disponibles
        $idiomas = file_get_contents('./idiomas.json');
        $idiomasArray = json_decode($idiomas, true);

        $textoEntrada = $postData['texto'];
        $idiomaEntrada = buscarClavePorPalabra($postData['idiomaEntrada'],$idiomasArray);
        $idiomaSalida = buscarClavePorPalabra($postData['idiomaSalida'],$idiomasArray);

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

            // Número de palabras a traducir
            $numCaracteres = strlen($textoEntrada)??0;  

            // Número de palabras total traduccidas
            $query = "SELECT SUM(num_palabras) as total_palabras FROM traducciones";
            $result = $conn->query($query);
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $totalCaracteres = $row['total_palabras']+$numCaracteres;

            // Comprobación de límite de traducción de palabras mensual
            if($totalCaracteres>=450000){
                throw new Exception("Número máximo de traducciones mensual alcanzaddo");
            }else{
                // Llamada Api al software de traducción
                // Datos para la solicitud
                $apiKey = "bd7210a9-bcd9-df24-d8b8-01aec4676138:fx";

                // Configuración de la solicitud cURL
                $url = "https://api-free.deepl.com/v2/translate";
                $data = array(
                    'text' => $textoEntrada,
                    'target_lang' => $idiomaSalida,
                    'source_lang' => $idiomaEntrada
                );

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
                $insert = $conn->prepare("INSERT INTO traducciones (idioma_entrada, idioma_salida, texto, traduccion, num_palabras) VALUES (?, ?, ?, ?, ?)");
                $insert->execute([$idiomaEntrada, $idiomaSalida, $textoEntrada, $textoTraducido, $numCaracteres]);
            }

            echo json_encode($textoTraducido);

        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        } catch (Exception $e) {
            die($e);
        }


    }
 else {
    $response = ["error" => "Solicitud no válida"];
    echo json_encode($response);
}

function buscarClavePorPalabra($palabra, $array) {
    $clave = array_search($palabra, $array);
    return $clave !== false ? $clave : 'No se encontró la clave para la palabra proporcionada';
}
?>

