let dropdown = document.getElementById('id');
let products = null;


fetch('/api/customer/products/' + customerID)
    .then(
        function (response) {
            if (response.status !== 200) {
                console.warn('Wygląda na to, że jest jakis problem, error nr: ' +
                    response.status);
                return;
            }
            response.json().then(function (data) {
                products = data;
                let option;
                dropdown.length = 0;
                for (var item of data) {
                    const list = document.getElementById('products-list');
                    const row = document.createElement('tr');

                    if (item.is_gift == '1') {

                        row.innerHTML = `
                    <td style="display:none;"><input type="hidden" value="${item.id}"></td>
                <td bgcolor="#0066FF">${item.name} </td>
                <td>${item.symbol}</td>
                <td><input class="sellingPrice" type="text" value="${item.pivot.selling_customer_price}"></td>
                <td><input class="purchasePrice" type="text" value="${item.pivot.purchase_customer_price}"></td>
                <td><input class="consumedPrice" type="text" value="${item.pivot.consumed_customer_price}"></td>
                <td><a href="#" class="delete">X</a></td>
                `;
                    } else {
                        row.innerHTML = `
                        <td style="display:none;"><input type="hidden" value="${item.id}"></td>
                    <td>${item.name}</td>
                    <td>${item.symbol}</td>
                    <td><input class="sellingPrice" type="text" value="${item.pivot.selling_customer_price}"></td>
                    <td><input class="purchasePrice" type="text" value="${item.pivot.purchase_customer_price}"></td>
                    <td><input class="consumedPrice" type="text" value="${item.pivot.consumed_customer_price}"></td>
                    <td><a href="#" class="delete">X</a></td>
                    `;
                    }
                    list.appendChild(row);
                }
            });
        }
    )
    .catch(function (err) {
        console.error('Fetch Error -', err);
    });

let defaultOption = document.createElement('option');
defaultOption.text = 'Wybierz Product';

const filter = document.querySelector('#filter');

dropdown.add(defaultOption);
dropdown.selectedIndex = 0;


needle.addEventListener('input', function (e) {
    text = e.target.value;
    needle = text;

    let url = '/api/customer/' + customerID + '/products' + '?q=' + needle;
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
                        option.dataset.sellingPrice = item.selling_price;
                        option.dataset.purchasePrice = item.purchase_price;
                        option.dataset.consumedPrice = item.consumed_price;
                        option.dataset.isGift = item.is_gift;
                        dropdown.add(option);

                    }
                });
            }
        )
        .catch(function (err) {
            console.error('Fetch Error -', err);
        });

});


dropdown.addEventListener('dblclick', addRow);

function addRow() {
    let selectedProduct = dropdown.options[dropdown.selectedIndex];

    function isOnList(product) {
        return product.id == selectedProduct.value;
    }

    let isOnProductsList = products.some(isOnList);

    if (isOnProductsList) {
        return;
    }
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
<td bgcolor="#0066FF">${addProductName} </td>
<td>${addProductSymbol}</td>
<td><input class="sellingPrice" type="text" value="${addProductSellingPrice}"></td>
<td><input class="purchasePrice" type="text" value="${addProductPurchasePrice}"></td>
<td><input class="consumedPrice" type="text" value="${addProductConsumedPrice}"></td>
<td><a href="#" class="delete">X</a></td>
`;
    } else {
        row.innerHTML = `
        <td style="display:none;"><input type="hidden" value="${addProductID}"></td>
    <td>${addProductName}</td>
    <td>${addProductSymbol}</td>
    <td><input class="sellingPrice" type="text" value="${addProductSellingPrice}"></td>
    <td><input class="purchasePrice" type="text" value="${addProductPurchasePrice}"></td>
    <td><input class="consumedPrice" type="text" value="${addProductConsumedPrice}"></td>
    <td><a href="#" class="delete">X</a></td>
    `;
    }
    list.appendChild(row);
}

document.getElementById('products-list').addEventListener('click', e => {
    if (!e.target.classList.contains('delete')) {
        return;
    }
    e.preventDefault();

    e.target.parentNode.parentNode.remove();
});

const save = document.querySelector('#sendProductsForm');

save.addEventListener('submit', saveProducts);

function saveProducts(event) {
    event.preventDefault();

    let customer_prices = [...document.querySelectorAll('#products-list tr')].reduce((previousValue, currentValue) => ({
        ...previousValue,
        [currentValue.querySelector('input[type=hidden]').value]: {
            selling_customer_price: currentValue.querySelector('.sellingPrice').value,
            purchase_customer_price: currentValue.querySelector('.purchasePrice').value,
            consumed_customer_price: currentValue.querySelector('.consumedPrice').value,
        }
    }), {});



    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let url = '/api/customer/products/' + customerID;
    let redirect = url;

    fetch(url, {
            method: "PUT",
            body: JSON.stringify(customer_prices),
            redirect: window.location.replace("/customer/product/update/" + customerID),
            headers: {
                "Content-Type": "application/json",
                "credentials": "same-origin",
                "X-CSRF-TOKEN": token
            }
        })
        .then(res => res.json())
        .then(res => console.log(res))
        .catch(error => console.error(`Error: {error}`))




}
