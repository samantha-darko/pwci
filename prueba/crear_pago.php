<?php
// Configuración de PayPal
$paypalConfig = [
    'client_id' => 'TU_CLIENT_ID',
    'client_secret' => 'TU_CLIENT_SECRET',
    'sandbox' => true // Cambiar a false en producción
];

// Crear un pago con PayPal
function crearPagoPayPal($cantidad, $descripcion) {
    global $paypalConfig;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.sandbox.paypal.com/v1/payments/payment');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . obtenerTokenPayPal()
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'intent' => 'sale',
        'payer' => [
            'payment_method' => 'paypal'
        ],
        'transactions' => [
            [
                'amount' => [
                    'total' => $cantidad,
                    'currency' => 'USD'
                ],
                'description' => $descripcion
            ]
        ],
        'redirect_urls' => [
            'return_url' => 'https://tu_sitio.com/pago_completado.php',
            'cancel_url' => 'https://tu_sitio.com/pago_cancelado.php'
        ]
    ]));
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Obtener el token de acceso de PayPal
function obtenerTokenPayPal() {
    global $paypalConfig;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.sandbox.paypal.com/v1/oauth2/token');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Accept-Language: en_US'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "{$paypalConfig['client_id']}:{$paypalConfig['client_secret']}");
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
    $response = curl_exec($ch);
    curl_close($ch);

    $responseData = json_decode($response, true);
    return $responseData['access_token'];
}

// Crear un nuevo pago en la base de datos
function crearPago($cantidad, $descripcion) {
    global $db;

    $stmt = $db->prepare('INSERT INTO pagos (cantidad, descripcion, estado) VALUES (:cantidad, :descripcion, :estado)');
    $stmt->bindValue(':cantidad', $cantidad);
    $stmt->bindValue(':descripcion', $descripcion);
    $stmt->bindValue(':estado', 'PENDIENTE');
    $stmt->execute();

    return $db->lastInsertId();
}

// Ejemplo de uso:

$cantidad = 100; // Cantidad del pago
$descripcion = 'Producto de ejemplo'; // Descripción del pago

// Crear el pago en PayPal
$paypalResponse = crearPagoPayPal($cantidad, $descripcion);

// Obtener el ID de pago de PayPal y actualizar la base de datos
$paypalId = $paypalResponse['id'];
$idPago = crearPago($cantidad, $descripcion);

// Actualizar el ID de PayPal en la base de datos
$stmt = $db->prepare('UPDATE pagos SET paypal_id = :paypal_id WHERE id = :id');
$stmt->bindValue(':paypal_id', $paypalId);
$stmt->bindValue(':id', $idPago);
$stmt->execute();

// Devolver la respuesta con el ID de pago de PayPal
$response = [
    'idPago' => $idPago,
    'paypalId' => $paypalId
];

echo json_encode($response);
