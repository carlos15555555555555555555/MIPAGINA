<!DOCTYPE html>
<html lang="en">
<?php
header('Access-Control-Allow-Origin: https://stats.g.doubleclick.net');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Credentials: true");
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago</title>
</head>

<body>
    <?php
    require __DIR__ . '/vendor/autoload.php';

    use MercadoPago\SDK;
    use MercadoPago\Preference;
    use MercadoPago\Item;

    // Configura el token de acceso de Mercado Pago
    SDK::setAccessToken('TEST-160798400791756-102512-f4256a020f699624ffaf9e3e09842b38-2045425702');

    // Obtiene el total enviado por URL
    $total = isset($_GET['total']) ? floatval($_GET['total']) : 0;

    if ($total > 0) {
        // Crea un objeto de preferencia
        $preference = new MercadoPago\Preference();

        // Crea un ítem en la preferencia
        $item = new MercadoPago\Item();
        $item->title = 'Carrito de compras'; // Puedes cambiar el título si deseas
        $item->quantity = 1;
        $item->unit_price = $total;
        $item->currency_id = 'MXN'; // Define la moneda, en este caso, peso mexicano
        $preference->items = array($item);
        $preference->save();
    } else {
        echo "No se recibió un total válido.";
        exit;
    }
    ?>

    <script src="https://sdk.mercadopago.com/js/v2"></script>

    <script>
        const mp = new MercadoPago("TEST-28f8d62e-66ee-4720-b791-501dd5a8cb35");
        
        mp.bricks().create("wallet", "wallet_container", {
            initialization: {
                preferenceId: "<?php echo $preference->id; ?>",
            },
        });
    </script>

    <div id="wallet_container"></div>
</body>
</html>
