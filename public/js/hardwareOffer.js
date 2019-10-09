let dropdown = document.getElementById('id');
let defaultOption = document.createElement('option');
const filter = document.querySelector('#filter');
dropdown.selectedIndex = 0;


let url = '/api/customer/products/' + customerID;

fetch(url)
    .then(
        function (response) {
            if (response.status !== 200) {
                console.warn('Wygląda na to, że jest jakis problem, error nr: ' +
                    response.status);
                return;
            }

            // Examine the text in the response
            response.json().then(function (data) {

                let option;
                dropdown.length = 0;


                for (var item of data) {
                    option = document.createElement('option');
                    option.className = 'products';
                    option.text = item.name + ' ' + item.symbol;
                    option.value = item.id;
                    option.dataset.name = item.name;
                    option.dataset.symbol = item.symbol;
                    option.dataset.sellingPrice = item.pivot.selling_customer_price;
                    option.dataset.purchasePrice = item.pivot.purchase_customer_price;
                    option.dataset.consumedPrice = item.pivot.consumed_customer_price;
                    option.dataset.isGift = item.is_gift;

                    dropdown.add(option);

                }
            });
        }
    )
    .catch(function (err) {
        console.error('Fetch Error -', err);
    });



filter.addEventListener('keyup', filterProducts);


function filterProducts(e) {
    const text = e.target.value.toLowerCase();
    //pobieramy wszystkie itemy bo tu mamy liste
    products = document.querySelectorAll('.products');
    products.forEach(function (product) {
        const item = product.firstChild.textContent;
        if (item.toLocaleLowerCase().indexOf(text) != -1) {
            product.style.display = 'block';
        } else {
            product.style.display = 'none';
        }
    });
}
// });
// const add = document.querySelector('.btn');


if (updateOffer) {

    let option;
    dropdown.length = 0;


    for (var item of offerProducts) {
        let selectedProduct = dropdown.options[dropdown.selectedIndex];
        console.log(item);
        var addProductID = item.id;
        var addProductName = item.pivot.product_name;
        var addProductSymbol = item.symbol;
        var addProductSellingPrice = item.pivot.selling_customer_price;
        var addProductPurchasePrice = item.pivot.purchased_customer_price;
        var addProductConsumedPrice = item.pivot.consumed_customer_price;
        var addProductGift = item.is_gift;



        const list = document.getElementById('products-list');
        const row = document.createElement('tr');

        if (addProductGift == '1') {
            row.innerHTML = `
            <td style="display:none;"><input type="hidden" value="${addProductID}"></td>
            <td bgcolor="#0066FF"> <input class="product-name" style="width:400px;" type="text" value="${addProductName} $"></td>
            <td>${addProductSymbol}</td>
        <td><input disabled class="sellingPrice" type="text" value="${addProductSellingPrice}"></td>
        <td style="display:none;"><input disabled class="purchasePrice" type="text" value="${addProductPurchasePrice}"></td>
        <td><input disabled class="consumedPrice" type="text" value="${addProductConsumedPrice}"></td>
        <td><a href="#" class="delete">X</a></td>
        `;
        } else {
            row.innerHTML = `
            <td style="display:none;"><input type="hidden" value="${addProductID}"></td>
            <td> <input class="product-name" type="text" style="width:400px;" value="${addProductName}"></td>
            <td>${addProductSymbol}</td>
        <td><input disabled class="sellingPrice" type="text" value="${addProductSellingPrice}"></td>
        <td style="display:none;"><input disabled class="purchasePrice" type="text" value="${addProductPurchasePrice}"></td>
        <td><input disabled class="consumedPrice" type="text" value="${addProductConsumedPrice}"></td>
        <td><a href="#" class="delete">X</a></td>
        `;
        }

        list.appendChild(row);
    }


}


dropdown.addEventListener('dblclick', addRow);

function addRow() {
    let selectedProduct = dropdown.options[dropdown.selectedIndex];
    var addProductID = selectedProduct.value;
    var addProductName = selectedProduct.dataset.name;
    var addProductSymbol = selectedProduct.dataset.symbol;
    var addProductSellingPrice = selectedProduct.dataset.sellingPrice;
    var addProductPurchasePrice = selectedProduct.dataset.purchasePrice;
    var addProductConsumedPrice = selectedProduct.dataset.consumedPrice;
    var addProductGift = selectedProduct.dataset.isGift;


    const list = document.getElementById('products-list');
    const row = document.createElement('tr');

    if (addProductGift == '1') {
        row.innerHTML = `
        <td style="display:none;"><input type="hidden" value="${addProductID}"></td>
        <td bgcolor="#0066FF"> <input class="product-name" style="width:400px;" type="text" value="${addProductName}"></td>
            <td>${addProductSymbol}</td>
    <td><input disabled class="sellingPrice" type="text" value="${addProductSellingPrice}"></td>
    <td style="display:none;"><input disabled class="purchasePrice" type="text" value="${addProductPurchasePrice}"></td>
    <td><input disabled class="consumedPrice" type="text" value="${addProductConsumedPrice}"></td>
    <td><a href="#" class="delete">X</a></td>
    `;
    } else {
        row.innerHTML = `
        <td style="display:none;"><input type="hidden" value="${addProductID}"></td>
        <td> <input class="product-name" type="text" style="width:400px;" value="${addProductName}"></td>
        <td>${addProductSymbol}</td>
    <td><input disabled class="sellingPrice" type="text" value="${addProductSellingPrice}"></td>
    <td style="display:none;"><input disabled class="purchasePrice" type="text" value="${addProductPurchasePrice}"></td>
    <td><input disabled class="consumedPrice" type="text" value="${addProductConsumedPrice}"></td>
    <td><a href="#" class="delete">X</a></td>
    `;
    }

    list.appendChild(row);

}

const addAll = document.querySelector('#addAll');
addAll.addEventListener('click', addAllProducts);

function addAllProducts() {
    for (var item of customerProducts) {

        console.log(item);

        var addProductID = item.id;
        var addProductName = item.name;
        var addProductSymbol = item.symbol;
        var addProductSellingPrice = item.pivot.selling_customer_price;
        var addProductPurchasePrice = item.pivot.purchase_customer_price;
        var addProductConsumedPrice = item.pivot.consumed_customer_price;
        var addProductGift = item.is_gift;

        const list = document.getElementById('products-list');
        const row = document.createElement('tr');


        if (addProductGift == '1') {
            row.innerHTML = `
            <td style="display:none;"><input type="hidden" value="${addProductID}"></td>
            <td bgcolor="#0066FF"> <input class="product-name" style="width:400px;" type="text" value="${addProductName}"></td>
            <td>${addProductSymbol}</td>
        <td><input disabled class="sellingPrice" type="text" value="${addProductSellingPrice}"></td>
        <td style="display:none;"><input disabled class="purchasePrice" type="text" value="${addProductPurchasePrice}"></td>
        <td><input disabled class="consumedPrice" type="text" value="${addProductConsumedPrice}"></td>
        <td><a href="#" class="delete">X</a></td>
        `;
        } else {
            row.innerHTML = `
            <td style="display:none;"><input type="hidden" value="${addProductID}"></td>
            <td> <input class="product-name" type="text" style="width:400px;" value="${addProductName}"></td>
            <td>${addProductSymbol}</td>
        <td><input disabled class="sellingPrice" type="text" value="${addProductSellingPrice}"></td>
        <td style="display:none;"><input disabled class="purchasePrice" type="text" value="${addProductPurchasePrice}"></td>
        <td><input disabled class="consumedPrice" type="text" value="${addProductConsumedPrice}"></td>
        <td><a href="#" class="delete">X</a></td>
        `;
        }

        list.appendChild(row);
    }
}

document.getElementById('products-list').addEventListener('click', e => {
    if (!e.target.classList.contains('delete')) {
        return;
    }
    e.preventDefault();

    e.target.parentNode.parentNode.remove();
});

if (updateOffer) {

    const save = document.querySelector('#sendProductsForm');
    save.addEventListener('submit', saveProducts);

    function saveProducts(event) {
        event.preventDefault();

        if (document.querySelectorAll('#products-list tr').length == 0) {
            return
        }

        let customer_prices = [...document.querySelectorAll('#products-list tr')].reduce((previousValue, currentValue) => ({
            ...previousValue,
            [currentValue.querySelector('input[type=hidden]').value]: {
                product_name: currentValue.querySelector('.product-name').value,
                selling_customer_price: currentValue.querySelector('.sellingPrice').value,
                purchase_customer_price: '0',
                consumed_customer_price: currentValue.querySelector('.consumedPrice').value,
            }
        }), {});


        let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let url = '/api/customer/offer/update/' + offerId;

        fetch(url, {
                method: "PUT",
                body: JSON.stringify(customer_prices),
                headers: {
                    "Content-Type": "application/json",
                    "credentials": "same-origin",
                    "X-CSRF-TOKEN": token
                }
            })
            .then(res => res.json())
            .then(response => console.log('Success:', JSON.stringify(response)))
            .then(res => window.location.href = ("/offers/list/" + customerID))
            .catch(error => console.error(`Error: {error}`))


    }


} else {


    const save = document.querySelector('#sendProductsForm');
    save.addEventListener('submit', saveProducts);

    function saveProducts(event) {

        event.preventDefault();


        if (document.querySelectorAll('#products-list tr').length == 0) {
            return
        }

        let customer_prices = [...document.querySelectorAll('#products-list tr')].reduce((previousValue, currentValue) => ({
            ...previousValue,
            [currentValue.querySelector('input[type=hidden]').value]: {
                product_name: currentValue.querySelector('.product-name').value,
                selling_customer_price: currentValue.querySelector('.sellingPrice').value,
                purchase_customer_price: currentValue.querySelector('.purchasePrice').value,
                consumed_customer_price: currentValue.querySelector('.consumedPrice').value,
            }
        }), {});



        let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let url = '/api/customer/offer/create/' + customerID;

        fetch(url, {
                method: "POST",
                body: JSON.stringify(customer_prices),
                headers: {
                    "Content-Type": "application/json",
                    "credentials": "same-origin",
                    "X-CSRF-TOKEN": token
                }
            })
            .then(res => res.json())
            .then(response => console.log('Success:', JSON.stringify(response)))
            .then(res => window.location.href = ("/offers/list/" + customerID))
            .catch(error => console.error(`Error: {error}`))


    }
}
