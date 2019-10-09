function addInvoiceProduct(product) {
    let cell;

    const row = document.createElement('tr');
    const tbody = document.querySelector('.invoices-table tbody');

    cell = document.createElement('td');
    const productSelect = document.createElement('select');
    productSelect.classList.add('product-select');

    [{
        id: '',
        name: '- Wybierz produkt -'
    }].concat(customerProducts).forEach(product => {
        const productOption = document.createElement('option');
        productOption.value = product.id;
        productOption.textContent = product.name;
        productSelect.append(productOption);

    });
    productSelect.name = 'products[id][]';
    productSelect.value = product !== undefined ? product.id : '';
    cell.appendChild(productSelect);
    row.appendChild(cell);

    cell = document.createElement('td');
    cell.classList.add('product-symbol');
    cell.textContent = product !== undefined ? product.symbol : '';
    row.appendChild(cell);


    cell = document.createElement('td');
    const sellingPriceInput = document.createElement('input');
    sellingPriceInput.type = 'text';
    sellingPriceInput.name = 'products[sellingPrice][]';
    sellingPriceInput.value = product !== undefined ? product.pivot.net_unit_price : '';
    sellingPriceInput.classList.add('product-selling-price-input', 'form-control');
    sellingPriceInput.textContent = product !== undefined ? product.net_unit_price : '';
    cell.appendChild(sellingPriceInput);
    row.appendChild(cell);

    cell = document.createElement('td');
    const quantity = document.createElement('input');
    quantity.type = 'text';
    quantity.name = 'products[quantity][]';
    quantity.value = product !== undefined ? product.pivot.quantity : '';
    quantity.classList.add('product-quantity-input', 'form-control');
    cell.appendChild(quantity);
    row.appendChild(cell);



    cell = document.createElement('td');
    const deleteLink = document.createElement('a');
    deleteLink.classList.add('delete-invoice');
    deleteLink.textContent = 'Usuń';
    cell.appendChild(deleteLink);
    row.appendChild(cell);

    tbody.appendChild(row);
}

document.querySelector('.add-invoice-product').addEventListener('click', e => {
    addInvoiceProduct();
});



document.querySelector('.invoices-table').addEventListener('change', e => {
    if (!e.target.classList.contains('product-select')) {
        return;
    }

    const product = customerProducts.filter(product => product.id.toString() === e.target.value)[0];

    let productSymbol = '';
    let sellingPriceInputName = '';
    let sellingPriceInputValue = '';
    let quantityName = '';

    if (product !== undefined) {
        productSymbol = product.symbol;

        const customerProduct = customerProducts.filter(product => product.id.toString() === e.target.value)[0];

        if (customerProduct !== undefined) {
            sellingPriceInputValue = customerProduct.pivot.selling_customer_price;
        }
    }

    const row = e.target.parentNode.parentNode;
    row.querySelector('.product-symbol').textContent = productSymbol;
    row.querySelector('.product-selling-price-input').value = sellingPriceInputValue;

});

document.querySelector('.invoices-table').addEventListener('click', e => {
    if (!e.target.classList.contains('delete-invoice')) {
        return;
    }

    e.preventDefault();

    e.target.parentNode.parentNode.remove();
});

invoiceProducts.forEach(product => {
    addInvoiceProduct(product);
});
