const orderDetails = {
    '5789': {
        time: 'August 25 2024, 2:13pm',
        id: '5789',
        type: 'Deliver',
        status: 'Cooking',
        payment: 'Visa (xxxx-5617)',
        address: 'Wall Street 420 #6-90',
        items: [
            { name: 'Shrimp Fried Rice', quantity: 1, remarks: 'without shrimp', price: 8.50 },
            { name: 'Pork Fried Rice', quantity: 2, remarks: '', price: 17.00 },
            { name: 'Chicken Chop Fried Rice Set', quantity: 1, remarks: 'Chicken Chop Fried Rice x2\nLemon Barley Drink x2', price: 16.00 },
            { name: 'Coca Cola 1L', quantity: 1, remarks: '', price: 2.50 }
        ],
        delivery_fee: 5.00,
        gst: 4.23,
        total: 51.23
    }
};

function showOrderDetails(orderId) {
    const order = orderDetails[orderId];
    if (!order) return;

    const detailsContainer = document.getElementById('orderDetails');
    detailsContainer.classList.remove('hidden');

    const html = `
        <div class="order-header">
            <p>Order Time: ${order.time}</p>
            <p>Order ID: ${order.id}</p>
            <p>Order Type: ${order.type}</p>
            <p>Order Status: <span class="status-${order.status.toLowerCase()}">${order.status}</span></p>
            <p>Paid By: ${order.payment}</p>
            <p>Deliver Address: ${order.address}</p>
        </div>
        <div class="order-items">
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Items</th>
                        <th>Remarks</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    ${order.items.map(item => `
                        <tr>
                            <td>${item.quantity}x ${item.name}</td>
                            <td>${item.remarks || '-'}</td>
                            <td>$${item.price.toFixed(2)}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>
        <div class="order-summary">
            <p>Total: $${order.total.toFixed(2)}</p>
            <p>Delivery: $${order.delivery_fee.toFixed(2)}</p>
            <p>9% GST: $${order.gst.toFixed(2)}</p>
            <p class="grand-total">$${(order.total + order.delivery_fee + order.gst).toFixed(2)}</p>
        </div>
        <div class="details-actions">
            <button class="btn-rate">Rate</button>
            <button class="btn-order-again">Order Again</button>
        </div>
    `;

    detailsContainer.innerHTML = html;
}

// Add click event listeners for the order cards
document.querySelectorAll('.order-card').forEach(card => {
    card.addEventListener('click', (e) => {
        if (!e.target.classList.contains('btn-rate')) {
            const orderId = card.dataset.orderId;
            showOrderDetails(orderId);
        }
    });
});