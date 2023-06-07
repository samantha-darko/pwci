<?php
// Configuración de PayPal
$paypalConfig = [
    'client_id' => 'AethZxpwW4JKFS27zgIA8qAqhqiLe3EMZYDNaKMnRsTBzb-BTlguvbOn_6BlN0q0ORhMhTkTECJiM398',
    'client_secret' => 'EILVwFGiVnlxU8VcbfcplvaiRYnZbU3wSR3qbBbkvUHQbIQxgcLkxccf72dct-KSAEQnHrV3tNJWrsJ3',
    'sandbox' => true // Cambiar a false en producción
];

// Conexión a la base de datos con PDO
$dbConfig = [
    'host' => 'localhost:3307',
    'dbname' => 'basededatosmultimedia',
    'username' => 'root',
    'password' => ''
];

try {
    $db = new PDO("mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']}", $dbConfig['username'], $dbConfig['password']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}

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

// Obtener el ID de pago de PayPal desde la base de datos
function obtenerIdPagoPayPal($idPago) {
    global $db;

    $stmt = $db->prepare('SELECT paypal_id FROM pagos WHERE id = :id');
    $stmt->bindParam(':id', $idPago);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['paypal_id'];
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

        return true;
    }

    return false;
}

// Ejemplo de uso:

// Crear un nuevo pago en la base de datos
$stmt = $db->prepare('INSERT INTO pagos (cantidad, descripcion, estado) VALUES (:cantidad, :descripcion, :estado)');
$stmt->bindValue(':cantidad', 100);
$stmt->bindValue(':descripcion', 'Producto de ejemplo');
$stmt->bindValue(':estado', 'PENDIENTE');
$stmt->execute();
$idPago = $db->lastInsertId();

// Crear el pago en PayPal
$paypalResponse = crearPagoPayPal(100, 'Producto de ejemplo');

// Obtener el ID de pago de PayPal y actualizar la base de datos
$paypalId = $paypalResponse['id'];
$stmt = $db->prepare('UPDATE pagos SET paypal_id = :paypal_id WHERE id = :id');
$stmt->bindValue(':paypal_id', $paypalId);
$stmt->bindValue(':id', $idPago);
$stmt->execute();

// Redireccionar al usuario a la página de PayPal para completar el pago
header('Location: https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $paypalResponse['token']);
exit();

// En la página de retorno de PayPal (pago_completado.php), obtener los parámetros 'paymentId' y 'PayerID'
$paymentId = $_GET['paymentId'];
$payerId = $_GET['PayerID'];

// Procesar el pago completado
if (procesarPagoCompletado($paymentId, $payerId)) {
    // Pago completado con éxito
    echo '¡El pago se ha completado correctamente!';
} else {
    // Fallo en el procesamiento del pago
    echo 'Ha ocurrido un error al procesar el pago.';
}