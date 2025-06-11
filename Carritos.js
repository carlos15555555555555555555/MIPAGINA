const cart = [];

function addToCart(product, price) {
    cart.push({ product, price });
    updateCart();
}

function updateCart() {
    const cartItems = document.getElementById('cartItems');
    const totalPrice = document.getElementById('totalPrice');
    cartItems.innerHTML = '';
    let total = 0;

    cart.forEach(item => {
        const li = document.createElement('li');
        li.textContent = `${item.product} - $${item.price}`; // Comillas invertidas usadas correctamente
        cartItems.appendChild(li);
        total += item.price;
    });

    totalPrice.textContent = `$${total}`; // Comillas invertidas usadas correctamente
}

// Función para realizar el pago
function realizarPago() {
    const totalPrice = document.getElementById('totalPrice').textContent.replace('$', '');
    window.location.href = `http://localhost/Mercado%20Pago/ej.php?total=${totalPrice}`; // Comillas invertidas usadas correctamente
}

// Función para realizar el pago con PayPal
function realizarPagoPaypal() {
    const cartJSON = encodeURIComponent(JSON.stringify(cart));
    window.location.href = `http://localhost/Mercado%20Pago/payment.php?cart=${cartJSON}`; // Comillas invertidas usadas correctamente
}