<?php
// URL del endpoint de login
$url = 'http://localhost/PROYECT/SERVER/API/Login/login.php';

// Datos de prueba
$data = [
    'user' => 'Sergio',
    'password' => '1234'
];

// Preparar la petición POST con JSON
$options = [
    'http' => [
        'header'  => "Content-Type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($data),
    ],
];

$context  = stream_context_create($options);

// Ejecutar la petición
$result = file_get_contents($url, false, $context);

if ($result === FALSE) {
    echo "Error al hacer la petición\n";
} else {
    // Mostrar la respuesta decodificada
    $response = json_decode($result, true);
    print_r($response);
}
?>
