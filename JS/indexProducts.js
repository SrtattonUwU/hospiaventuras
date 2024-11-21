const btnCart = document.querySelector('.container-icon');
const containerCartProducts = document.querySelector('.container-cart-products');
const contadorProductos = document.getElementById('contador-productos');
const cartItems = {};
let total = 0;
const btnPagar = document.getElementById('payButton');
const totalField = document.getElementById('total');

btnCart.addEventListener('click', () => {
    containerCartProducts.classList.toggle('hidden-cart');
});

document.addEventListener("DOMContentLoaded", () => {
    const items = document.querySelectorAll('.item');

    items.forEach(item => {
        const priceElement = item.querySelector('.price');
        const incrementBtn = item.querySelector('.btn-increment');
        const decrementBtn = item.querySelector('.btn-decrement');
        const quantityElement = item.querySelector('.product-quantity');
        const addButton = item.querySelector('.btn-add');

        let quantity = 1;
        const basePrice = parseFloat(priceElement.getAttribute('data-price'));
        const totalPriceElement = item.querySelector('.total-price');

        const updateTotalPrice = () => {
            const totalPrice = (basePrice * quantity).toFixed(2);
            if (totalPriceElement) {
                totalPriceElement.textContent = `$${totalPrice}`;
            }
        };

        updateTotalPrice();

        incrementBtn.addEventListener('click', () => {
            quantity++;
            quantityElement.textContent = quantity;
            updateTotalPrice();
        });

        decrementBtn.addEventListener('click', () => {
            if (quantity > 1) {
                quantity--;
                quantityElement.textContent = quantity;
                updateTotalPrice();
            }
        });

        addButton.addEventListener('click', () => {
            const productId = item.dataset.id;
            const titleElement = item.querySelector('.titulo-producto');

            if (!titleElement) {
                console.error(`No se encontró un elemento con la clase '.titulo-producto' en el producto con ID: ${productId}`);
                return;
            }

            const productTitle = titleElement.textContent;
            if (!cartItems[productId]) {
                cartItems[productId] = {
                    quantity: 0,
                    price: basePrice,
                    title: productTitle,
                };
            }
            cartItems[productId].quantity += quantity;
            updateCartDisplay();
            contadorProductos.textContent = Object.keys(cartItems).length;
            quantity = 1;
            quantityElement.textContent = quantity;
        });
        
    });

    const updateCartDisplay = () => {
        containerCartProducts.innerHTML = '';
        total = 0;

        for (const id in cartItems) {
            const product = cartItems[id];
            const productElement = document.createElement('div');
            productElement.classList.add('cart-product');
            productElement.innerHTML = `
                <div class="info-cart-product">
                    <span class="cantidad-producto-carrito">${product.quantity}</span>
                    <p class="titulo-producto-carrito">${product.title}</p>
                    <span class="precio-producto-carrito">$${(product.price * product.quantity).toFixed(2)}</span>
                </div>
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="icon-close"
                    data-id="${id}"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            `;
            containerCartProducts.appendChild(productElement);
            total += product.price * product.quantity;
        }

        const totalElement = document.createElement('div');
        totalElement.classList.add('cart-total');
        totalElement.innerHTML = `
            <h3>Total:</h3>
            <span class="total-pagar">$${total.toFixed(2)}</span>
        `;
        containerCartProducts.appendChild(totalElement);

        contadorProductos.textContent = Object.keys(cartItems).length;
    };

    const sendTotalToServer = (total) => {
        fetch('../PHP/products.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `total=${total}`
        })
        .then(response => response.text())
        .then(data => {
            console.log('Respuesta del servidor:', data);
        })
        .catch(error => {
            console.error('Error al enviar el total:', error);
        });
    };

    btnPagar.addEventListener('click', (event) => {
        event.preventDefault();
        if (Object.keys(cartItems).length === 0) {
            alert('No hay productos en el carrito');
        } else {
            totalField.value = total.toFixed(2);
            document.getElementById('paymentForm').submit();
        }
    });
});