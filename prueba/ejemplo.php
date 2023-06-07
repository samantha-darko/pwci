<!DOCTYPE html>
<html>
<head>
    <title>Pago con PayPal Sandbox</title>
    <script src="https://www.paypalobjects.com/api/checkout.js"></script>
</head>
<body>
    <div id="paypal-button"></div>

    <script>
        // Configuración del botón de pago de PayPal
        paypal.Button.render({
            env: 'sandbox', // Cambiar a 'production' en producción
            client: {
                sandbox: 'AethZxpwW4JKFS27zgIA8qAqhqiLe3EMZYDNaKMnRsTBzb-BTlguvbOn_6BlN0q0ORhMhTkTECJiM398', // Tu ID de cliente de PayPal Sandbox
                production: 'TU_CLIENT_ID' // Tu ID de cliente de PayPal en producción
            },
            commit: true,
            payment: function () {
                // Llamar al archivo PHP para crear el pago en PayPal
                return paypal.request.post('crear_pago.php')
                    .then(function (response) {
                        return response.id;
                    });
            },
            onAuthorize: function (data, actions) {
                // Llamar al archivo PHP para procesar el pago completado
                return paypal.request.post('procesar_pago.php', { paymentID: data.paymentID, payerID: data.payerID })
                    .then(function (response) {
                        // Mostrar un mensaje de éxito o error según la respuesta del servidor
                        if (response === 'success') {
                            alert('¡El pago se ha completado correctamente!');
                        } else {
                            alert('Ha ocurrido un error al procesar el pago.');
                        }
                    });
            },
            onCancel: function () {
                alert('El pago ha sido cancelado.');
            },
            onError: function (err) {
                console.error(err);
                alert('Ha ocurrido un error al procesar el pago.');
            }
        }, '#paypal-button');
    </script>
</body>
</html>
