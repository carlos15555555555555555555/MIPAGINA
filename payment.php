<?php
require 'vendor/autoload.php'; // Asegúrate de que Composer esté configurado
$config = require 'config.php';

use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;

// Recuperar y decodificar el carrito
$cartJSON = isset($_GET['cart']) ? $_GET['cart'] : null;
if (!$cartJSON) {
    die("El carrito está vacío o no se envió correctamente.");
}

$cart = json_decode($cartJSON, true);
if (!$cart || !is_array($cart)) {
    die("El carrito no tiene un formato válido.");
}

// Calcular el total
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'];
}

if ($total <= 0) {
    die("El monto total no es válido: $total");
}

// Configurar las credenciales
$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        $config['client_id'],
        $config['secret']
    )
);
$apiContext->setConfig($config['settings']);

// Crear el pagador
$payer = new Payer();
$payer->setPaymentMethod("paypal");

// Configurar el monto
$amount = new Amount();
$amount->setCurrency("MXN")
       ->setTotal($total); // Usar el monto calculado

// Crear la transacción
$transaction = new Transaction();
$transaction->setAmount($amount)
            ->setDescription("Pago de carrito con PayPal");

// Configurar las URLs de retorno y cancelación
$redirectUrls = new RedirectUrls();
$redirectUrls->setReturnUrl("http://localhost/PayPal_NEI_2025/success.php") // Cambiar por tu URL
             ->setCancelUrl("http://localhost/PayPal_NEI_2025/cancel.php");

// Crear el pago
$payment = new Payment();
$payment->setIntent("sale")
        ->setPayer($payer)
        ->setTransactions([$transaction])
        ->setRedirectUrls($redirectUrls);

try {
    $payment->create($apiContext);
    // Redirigir al usuario a PayPal para completar el pago
    header("Location: " . $payment->getApprovalLink());
    exit();
} catch (Exception $ex) {
    echo "Error creando el pago: " . $ex->getMessage();
}
?>