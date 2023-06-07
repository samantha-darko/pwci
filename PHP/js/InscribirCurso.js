function VerificarSesion() {
    if (!("idusuario" in sessionStorage)) {
        window.location.href = "../paginas/IniciarSesion.php"
    }
    idcurso = sessionStorage.getItem('curso')
    $.ajax({
        url: '../php/VerCurso.php?curso=' + idcurso,
        success: function (resultado) {
            let res = JSON.parse(resultado);
            console.log(res)
            let total = 0
            if (res != "vacio") {
                $("#curso").append("<label>Titulo Curso:</label>" +
                    "<h2>" + res[0]['titulo_curso'] + "</h2>" +
                    "<div id='infocurso'>" +
                    "<div><label>Descripción:</label>" +
                    "<p class='descripcion_curso'>" + res[0]['descripcion_curso'] + "</p></div>")
                if (res[0]['costo_curso'] != "0.00") {
                    $("#curso").append("<div><label>Costo curso:</label>" +
                        "<h3 class='costo_curso'>" + res[0]['costo_curso'] + "</h3></div>" +
                        "</div>")
                    $("#curso").append("<div><p>*No se puede pagar por nivel, debe comprar el curso completo</p>")
                    total = parseFloat(res[0]['costo_curso'])
                    console.log(total)
                } else {
                    $("#curso").append("<div><p>*Este curso se cobra por nivel</p>")
                }
                for (i = 0; i < res.length; i++) {
                    console.log(res[i]);
                    precio = parseFloat(res[i]['costo_nivel'])
                    total = total + precio
                    $("#nivel").append(
                        "<div id='infonivel'>" +
                        "<div><label>Titulo nivel:</label>" +
                        "<h4>" + res[i]['titulo_nivel'] + "</h4></div>" +
                        "<div><label>Costo nivel:</label>" +
                        "<h4>" + res[i]['costo_nivel'] + "</h4></div>" +
                        "</div>")
                }
                console.log(total)
                $("#total").append("<h1>Total a pagar: $" + total + "</h1>")
            }
        }
    })
}

document.addEventListener("DOMContentLoaded", VerificarSesion)

paypal.Buttons({
    // Order is created on the server and the order id is returned
    createOrder() {
        return fetch("../php/crearorden.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            // use the "body" param to optionally pass additional order information
            // like product skus and quantities
            body: JSON.stringify({
                cart: [
                    {
                        sku: "YOUR_PRODUCT_STOCK_KEEPING_UNIT",
                        quantity: "YOUR_PRODUCT_QUANTITY",
                    },
                ],
            }),
        })
            .then((response) => response.json())
            .then((order) => order.id);
    },
    // Finalize the transaction on the server after payer approval
    onApprove(data) {
        return fetch("../php/capturarorden.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                orderID: data.orderID
            })
        })
            .then((response) => response.json())
            .then((orderData) => {
                // Successful capture! For dev/demo purposes:
                console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
                const transaction = orderData.purchase_units[0].payments.captures[0];
                alert(`Transaction ${transaction.status}: ${transaction.id}\n\nSee console for all available details`);
                // When ready to go live, remove the alert and show a success message within this page. For example:
                // const element = document.getElementById('paypal-button-container');
                // element.innerHTML = '<h3>Thank you for your payment!</h3>';
                // Or go to another URL:  window.location.href = 'thank_you.html';
            });
    }
}).render('#paypal-button-container');

$(document).ready(function () {
    document.querySelector('#salir').addEventListener('click', function (e) {
        e.preventDefault();
        if ('idusuario' in sessionStorage) {
            sessionStorage.removeItem('idusuario');
        }
        if ('rol' in sessionStorage) {
            sessionStorage.removeItem('rol');
        }
        sessionStorage.clear()
        $.ajax({
            url: '../php/CerrarSesion.php',
            success: function (resultado) {
                var res = JSON.parse(resultado)
                console.log(res)
                if (res) {
                    window.location.href = '../paginas/IniciarSesion.php'
                }
            }
        })
    })
})