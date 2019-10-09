function addProduct(product) {
    let cell;

    const row = document.createElement('tr');
    const tbody = document.querySelector('.offers-table tbody');

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
    const sellingCustomerPriceInput = document.createElement('input');
    sellingCustomerPriceInput.type = 'text';
    sellingCustomerPriceInput.name = product !== undefined ? 'customer_prices[' + product.id + '][selling_customer_price]' : '';
    sellingCustomerPriceInput.value = product !== undefined ? product.pivot.selling_customer_price : '';
    sellingCustomerPriceInput.classList.add('product-selling-customer-price-input', 'form-control');

    sellingCustomerPriceInput.textContent = product !== undefined ? product.selling_customer_price : '';

    cell.appendChild(sellingCustomerPriceInput);
    row.appendChild(cell);



    cell = document.createElement('td');
    const purchaseCustomerPriceInput = document.createElement('input');
    purchaseCustomerPriceInput.type = 'text';
    purchaseCustomerPriceInput.name = product !== undefined ? 'customer_prices[' + product.id + '][purchase_customer_price]' : '';
    purchaseCustomerPriceInput.value = product !== undefined ? product.pivot.purchase_customer_price : '';
    purchaseCustomerPriceInput.classList.add('product-purchase-customer-price-input', 'form-control');

    purchaseCustomerPriceInput.textContent = product !== undefined ? product.purchase_customer_price : '';

    cell.appendChild(purchaseCustomerPriceInput);
    row.appendChild(cell);

    cell = document.createElement('td');
    const consumedCustomerPriceInput = document.createElement('input');
    consumedCustomerPriceInput.type = 'text';
    consumedCustomerPriceInput.name = product !== undefined ? 'customer_prices[' + product.id + '][consumed_customer_price]' : '';
    consumedCustomerPriceInput.value = product !== undefined ? product.pivot.consumed_customer_price : '';
    consumedCustomerPriceInput.classList.add('product-consumed-customer-price-input', 'form-control');

    consumedCustomerPriceInput.textContent = product !== undefined ? product.consumed_customer_price : '';

    cell.appendChild(consumedCustomerPriceInput);
    row.appendChild(cell);

    cell = document.createElement('td');
    const deleteLink = document.createElement('a');
    deleteLink.classList.add('delete-offer');
    deleteLink.textContent = 'Usuń';
    cell.appendChild(deleteLink);
    row.appendChild(cell);

    tbody.appendChild(row);
}

document.querySelector('.add-offer').addEventListener('click', e => {
    addProduct();
});

document.querySelector('.offers-table').addEventListener('change', e => {
    if (!e.target.classList.contains('product-select')) {
        return;
    }

    const product = customerProducts.filter(product => product.id.toString() === e.target.value)[0];

    let productSymbol = '';
    let sellingCustomerPriceInputName = '';

    let sellingCustomerPriceInputValue = '';

    let purchaseCustomerPriceInputName = '';
    let purchaseCustomerPriceInputValue = '';
    let consumedCustomerPriceInputName = '';
    let consumedCustomerPriceInputValue = '';


    if (product !== undefined) {
        productSymbol = product.symbol;
        sellingCustomerPriceInputName = 'customer_prices[' + product.id + '][selling_customer_price]';
        purchaseCustomerPriceInputName = 'customer_prices[' + product.id + '][purchase_customer_price]';
        consumedCustomerPriceInputName = 'customer_prices[' + product.id + '][consumed_customer_price]';



        const customerProduct = customerProducts.filter(product => product.id.toString() === e.target.value)[0];

        if (customerProduct !== undefined) {
            sellingCustomerPriceInputValue = customerProduct.pivot.selling_customer_price;
            purchaseCustomerPriceInputValue = customerProduct.pivot.purchase_customer_price;
            consumedCustomerPriceInputValue = customerProduct.pivot.consumed_customer_price;

        }


    }

    const row = e.target.parentNode.parentNode;
    row.querySelector('.product-symbol').textContent = productSymbol;
    row.querySelector('.product-selling-customer-price-input').name = sellingCustomerPriceInputName;

    row.querySelector('.product-selling-customer-price-input').value = sellingCustomerPriceInputValue;

    row.querySelector('.product-purchase-customer-price-input').name = purchaseCustomerPriceInputName;
    row.querySelector('.product-purchase-customer-price-input').value = purchaseCustomerPriceInputValue;
    row.querySelector('.product-consumed-customer-price-input').name = consumedCustomerPriceInputName;
    row.querySelector('.product-consumed-customer-price-input').value = consumedCustomerPriceInputValue;

});

document.querySelector('.offers-table').addEventListener('click', e => {
    if (!e.target.classList.contains('delete-offer')) {
        return;
    }

    e.preventDefault();

    e.target.parentNode.parentNode.remove();
});

customerProducts.forEach(product => {
    addProduct(product);
});
