// Handle Stock In Form
document.getElementById('stockInForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const productId = formData.get('product_id');
    const quantity = formData.get('quantity');
    this.action = `/products/${productId}/stock-in`;
    this.submit();
});

// Handle Stock Out Form
document.getElementById('stockOutForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const productId = formData.get('product_id');
    const quantity = formData.get('quantity');
    this.action = `/products/${productId}/stock-out`;
    this.submit();
});

// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('#productsTable tbody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});

// Filter low stock
document.getElementById('filterLowStock').addEventListener('click', function() {
    const rows = document.querySelectorAll('#productsTable tbody tr');
    rows.forEach(row => {
        const quantity = parseInt(row.getAttribute('data-quantity'));
        const minQuantity = parseInt(row.getAttribute('data-min-quantity'));
        row.style.display = (quantity > 0 && quantity <= minQuantity) ? '' : 'none';
    });
});

// Show all
document.getElementById('showAll').addEventListener('click', function() {
    const rows = document.querySelectorAll('#productsTable tbody tr');
    rows.forEach(row => {
        row.style.display = '';
    });
});