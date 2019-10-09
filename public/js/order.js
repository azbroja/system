function addProduct(product) {
    let cell;


    const row = document.createElement('tr');
    const tbody = document.querySelector('.orders-table tbody');

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
    sellingPriceInput.name = product !== undefined ? 'order_products_prices[' + product.id + '][net_unit_price]' : '';
    sellingPriceInput.value = product !== undefined ? product.pivot.selling_customer_price : '';
    sellingPriceInput.classList.add('product-selling-price-input', 'form-control');
    sellingPriceInput.textContent = product !== undefined ? product.net_unit_price : '';
    cell.appendChild(sellingPriceInput);
    row.appendChild(cell);

    cell = document.createElement('td');
    const quantity = document.createElement('input');
    quantity.type = 'text';
    quantity.name = product !== undefined ? 'order_products_prices[' + product.id + '][quantity]' : '';
    quantity.value = product !== undefined ? '' : '';
    quantity.classList.add('product-quantity-input', 'form-control');
    cell.appendChild(quantity);
    row.appendChild(cell);



    cell = document.createElement('td');
    const deleteLink = document.createElement('a');
    deleteLink.classList.add('delete-order');
    deleteLink.style.cursor = "pointer";
    deleteLink.textContent = 'X';
    cell.appendChild(deleteLink);
    row.appendChild(cell);

    tbody.appendChild(row);
}

document.querySelector('.add-order-product').addEventListener('click', e => {
    addProduct();
});

document.querySelector('.add-all-order-product').addEventListener('click', e => {
    customerProducts.forEach(product => {
        addProduct(product);
    });
});


document.querySelector('.orders-table').addEventListener('change', e => {
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
        sellingPriceInputName = 'order_products_prices[' + product.id + '][net_unit_price]';
        quantityName = 'order_products_prices[' + product.id + '][quantity]';

        const customerProduct = customerProducts.filter(product => product.id.toString() === e.target.value)[0];

        if (customerProduct !== undefined) {
            sellingPriceInputValue = customerProduct.pivot.selling_customer_price;
        }
    }


    const row = e.target.parentNode.parentNode;
    row.querySelector('.product-symbol').textContent = productSymbol;
    row.querySelector('.product-selling-price-input').name = sellingPriceInputName;
    row.querySelector('.product-selling-price-input').value = sellingPriceInputValue;
    row.querySelector('.product-quantity-input').name = quantityName;

});

document.querySelector('.orders-table').addEventListener('click', e => {
    if (!e.target.classList.contains('delete-order')) {
        return;
    }

    e.preventDefault();

    e.target.parentNode.parentNode.remove();
});

orderProducts.forEach(product => {
    addProduct(product);
});
