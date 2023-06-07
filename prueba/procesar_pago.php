<?php
// Configuración de PayPal
$paypalConfig = [
    'client_id' => 'TU_CLIENT_ID',
    'client_secret' => 'TU_CLIENT_SECRET',
    'sandbox' => true // Cambiar a false en producción
];

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

// Procesar el pago completado
function procesarPagoCompletado($paymentId, $payerId) {
    global $paypalConfig, $db;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v1/payments/payment/{$paymentId}/execute");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . obtenerTokenPayPal()
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'payer_id' => $payerId
    ]));
    $response = curl_exec($ch);
    curl_close($ch);

    $responseData = json_decode($response, true);

    if ($responseData['state'] === 'approved') {
        // Actualizar el estado del pago en la base de datos
        $stmt = $db->prepare('UPDATE pagos SET estado = :estado WHERE paypal_id = :paypal_id');
        $stmt->bindValue(':estado', 'COMPLETADO');
        $stmt->bindValue(':paypal_id', $paymentId);
        $stmt->execute();

        // Realizar acciones adicionales (ejemplo: enviar correo electrónico, registrar información, etc.)
        // ...

        return 'success';
    }

    return 'error';
}

// Ejemplo de uso:

$paymentId = $_POST['paymentID']; // ID de pago de PayPal
$payerId = $_POST['payerID']; // ID de pagador de PayPal

// Procesar el pago completado
$resultado = procesarPagoCompletado($paymentId, $payerId);

echo $resultado;
