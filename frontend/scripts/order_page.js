function showOrderDetails(orderId) {
    const allOrderDetails = document.querySelectorAll('.order-detail-content');
    allOrderDetails.forEach(detail => detail.classList.remove('active'));

    const selectedOrderDetail = document.getElementById(orderId);
    if (selectedOrderDetail) {
        selectedOrderDetail.classList.add('active');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    showOrderDetails('order1');
});
