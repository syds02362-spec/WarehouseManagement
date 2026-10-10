document.addEventListener('DOMContentLoaded', function() {
    const stockInForm = document.getElementById('stockInForm');
    const stockOutForm = document.getElementById('stockOutForm');
    const searchInput = document.getElementById('searchInput');
    const filterLowStock = document.getElementById('filterLowStock');
    const showAll = document.getElementById('showAll');

    const getRows = () => Array.from(document.querySelectorAll('#productsTable tbody tr'));
    const safeInt = value => Number.parseInt(value, 10) || 0;

    const submitStockForm = (form, route) => {
        const formData = new FormData(form);
        const productId = formData.get('product_id');
        form.action = `/products/${productId}/${route}`;
        form.submit();
    };

    if (stockInForm) {
        stockInForm.addEventListener('submit', function(event) {
            event.preventDefault();
            submitStockForm(this, 'stock-in');
        });
    }

    if (stockOutForm) {
        stockOutForm.addEventListener('submit', function(event) {
            event.preventDefault();
            submitStockForm(this, 'stock-out');
        });
    }

    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            getRows().forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    }

    if (filterLowStock) {
        filterLowStock.addEventListener('click', function() {
            getRows().forEach(row => {
                const quantity = safeInt(row.getAttribute('data-quantity'));
                const minQuantity = safeInt(row.getAttribute('data-min-quantity'));
                row.style.display = (quantity > 0 && quantity <= minQuantity) ? '' : 'none';
            });
        });
    }

    if (showAll) {
        showAll.addEventListener('click', function() {
            getRows().forEach(row => row.style.display = '');
        });
    }
});