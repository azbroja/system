let dropdown = document.getElementById("id");
const filter = document.querySelector("#filter");
dropdown.selectedIndex = 0;

function addInvoiceProduct() {
    for (var item of invoiceProducts) {
        const list = document.getElementById("products-list");
        const row = document.createElement("tr");
        var addProductID = item.id;
        var addProductName =
            item.pivot.product_name == null
                ? item.name
                : item.pivot.product_name;
        var addProductSymbol =
            item.pivot.product_name == null ? "- " + item.symbol : " ";
        var addProductPurchasePrice =
            item.pivot.purchase_price == null ? "0" : item.pivot.purchase_price;
        var addProductNetPrice = item.pivot.net_unit_price;
        var addProductQuantity = item.pivot.quantity;

        if (item.is_gift == "1") {
            row.innerHTML = `
        <td style="display:none;"><input type="hidden" value="${addProductID}"></td>
        <td bgcolor="#0066FF"> <input class="product-name" style="width:400px;" type="text" value="${addProductName} ${addProductSymbol}"></td>
    <td><input class="netUnitPrice" type="text" value="${addProductNetPrice}"></td>
    <td><input class="purchasePrice" type="text" value="${addProductPurchasePrice}"></td>
    <td><input class="quantity" type="number" value="${addProductQuantity}" min="1" max="300" required></td>
    <td><a href="#" class="delete">X</a></td>
    `;
        } else {
            row.innerHTML = `
            <td style="display:none;"><input type="hidden" value="${addProductID}"></td>
            <td> <input class="product-name" type="text" style="width:400px;" value="${addProductName} ${addProductSymbol}"></td>
        <td><input class="netUnitPrice" type="text" value="${addProductNetPrice}"></td>
        <td><input class="purchasePrice" type="text" value="${addProductPurchasePrice}"></td>
        <td><input class="quantity" type="number" value="${addProductQuantity}" min="1" max="300" required></td>
        <td><a href="#" class="delete">X</a></td>
        `;
        }
        list.appendChild(row);
    }
}

if (invoiceProducts) {
    addInvoiceProduct();
}

let url = "/api/customer/products/" + customerID;

fetch(url)
    .then(function(response) {
        if (response.status !== 200) {
            console.warn(
                "Wygląda na to, że jest jakis problem, error nr: " +
                    response.status
            );
            return;
        }

        response.json().then(function(data) {
            let option;
            dropdown.length = 0;

            for (var item of data) {
                option = document.createElement("option");
                option.className = "products";
                option.text = item.name + " " + item.symbol;
                option.value = item.id;
                option.dataset.name = item.name;
                option.dataset.symbol = item.symbol;
                option.dataset.purchasePrice =
                    item.pivot.purchase_customer_price;
                option.dataset.netUnitPrice = item.pivot.selling_customer_price;
                option.dataset.isGift = item.is_gift;
                option.dataset.quantity = item.quantity;
                dropdown.add(option);
            }
        });
    })
    .catch(function(err) {
        console.error("Fetch Error -", err);
    });

filter.addEventListener("keyup", filterProducts);

function filterProducts(e) {
    const text = e.target.value.toLowerCase();
    //pobieramy wszystkie itemy bo tu mamy liste
    products = document.querySelectorAll(".products");
    products.forEach(function(product) {
        const item = product.firstChild.textContent;
        if (item.toLocaleLowerCase().indexOf(text) != -1) {
            product.style.display = "block";
        } else {
            product.style.display = "none";
        }
    });
}

dropdown.addEventListener("dblclick", addRow);

function addRow() {
    let selectedProduct = dropdown.options[dropdown.selectedIndex];

    var addProduct = selectedProduct.value;
    var addProductName = selectedProduct.dataset.name;
    var addProductSymbol = selectedProduct.dataset.symbol;
    var addProductPurchasePrice = selectedProduct.dataset.purchasePrice;
    var addProductNetPrice = selectedProduct.dataset.netUnitPrice;
    var addProductGift = selectedProduct.dataset.isGift;
    var addProductQuantity = "0";

    const list = document.getElementById("products-list");
    const row = document.createElement("tr");

    if (addProductGift == "1") {
        row.innerHTML = `
    <td style="display:none;"><input type="hidden" value="${addProduct}"></td>
<td bgcolor="#0066FF"> <input class="product-name" style="width:400px;" type="text" value="${addProductName} - ${addProductSymbol}" style="min-width:1px;"></td>
<td><input class="netUnitPrice" type="text" value="${addProductNetPrice}"></td>
<td><input class="purchasePrice" type="text" value="${addProductPurchasePrice}"></td>
<td><input class="quantity" type="number" value="${addProductQuantity}" min="1" max="300" required></td>
<td><a href="#" class="delete">X</a></td>
`;
    } else {
        row.innerHTML = `
        <td style="display:none;"><input type="hidden" value="${addProduct}"></td>
        <td> <input class="product-name" style="width:400px;" type="text" value="${addProductName} - ${addProductSymbol}" style="width:400px;"></td>
    <td><input class="netUnitPrice" type="text" value="${addProductNetPrice}"></td>
    <td><input class="purchasePrice" type="text" value="${addProductPurchasePrice}"></td>
    <td><input class="quantity" type="number" value="${addProductQuantity}" min="1" max="300" required></td>
    <td><a href="#" class="delete">X</a></td>
    `;
    }
    list.appendChild(row);
}

const addAll = document.querySelector("#addAll");
addAll.addEventListener("click", addAllProducts);

function addAllProducts() {
    for (var item of customerProducts) {
        var addProduct = item.id;
        var addProductName = item.name;
        var addProductSymbol = item.symbol;
        var addProductPurchasePrice = item.pivot.purchase_customer_price;
        var addProductNetPrice = item.pivot.selling_customer_price;
        var addProductGift = item.is_gift;
        var addProductQuantity = "0";

        const list = document.getElementById("products-list");
        const row = document.createElement("tr");

        if (addProductGift == "1") {
            row.innerHTML = `
        <td style="display:none;"><input type="hidden" value="${addProduct}"></td>
    <td bgcolor="#0066FF"> <input class="product-name" style="width:400px;" type="text" value="${addProductName} - ${addProductSymbol}" style="min-width:1px;"></td>
    <td><input class="netUnitPrice" type="text" value="${addProductNetPrice}"></td>
    <td><input class="purchasePrice" type="text" value="${addProductPurchasePrice}"></td>
    <td><input class="quantity" type="number" value="${addProductQuantity}" min="1" max="300" required></td>
    <td><a href="#" class="delete">X</a></td>
    `;
        } else {
            row.innerHTML = `
            <td style="display:none;"><input type="hidden" value="${addProduct}"></td>
            <td> <input class="product-name" style="width:400px;" type="text" value="${addProductName} - ${addProductSymbol}" style="width:400px;"></td>
        <td><input class="netUnitPrice" type="text" value="${addProductNetPrice}"></td>
        <td><input class="purchasePrice" type="text" value="${addProductPurchasePrice}"></td>
        <td><input class="quantity" type="number" value="${addProductQuantity}" min="1" max="300" required></td>
        <td><a href="#" class="delete">X</a></td>
        `;
        }
        list.appendChild(row);
    }
}

document.getElementById("products-list").addEventListener("click", e => {
    if (!e.target.classList.contains("delete")) {
        return;
    }
    e.preventDefault();

    e.target.parentNode.parentNode.remove();
});

if (updateInvoice) {
    document
        .querySelector("#sendProductsForm")
        .addEventListener("submit", function(event) {
            event.target.querySelector('[type = "submit"]').disabled = true;

            event.preventDefault();

            let customer_prices = [
                ...document.querySelectorAll("#products-list tr")
            ].reduce((previousValue, currentValue) => {
                const productId = currentValue.querySelector(
                    "input[type=hidden]"
                ).value;
                const productName = currentValue.querySelector(".product-name")
                    .value;
                const productSymbol = currentValue.querySelector(
                    ".product-name"
                ).value;
                const productQuantity = Number(
                    currentValue.querySelector(".quantity").value
                );

                if (previousValue.hasOwnProperty(productId)) {
                    previousValue[productId].quantity += productQuantity;
                } else {
                    previousValue[productId] = {
                        product_name: productName,
                        quantity: productQuantity,
                        purchase_price: currentValue.querySelector(
                            ".purchasePrice"
                        ).value,
                        net_unit_price: currentValue.querySelector(
                            ".netUnitPrice"
                        ).value
                    };
                }
                return previousValue;
            }, {});
            let customerID = document.getElementById("customer_id").value;

            let comments = document.querySelector("#comments").value;
            let payTerm = document.querySelector("#payTerm").value;
            let payType = document.querySelector("#payType");
            let selectedPayType = payType.options[payType.selectedIndex].value;
            var payload = {
                customerID: customerID,
                payTypeSend: selectedPayType,
                payTermSend: payTerm,
                comments: comments,
                customer_prices: customer_prices,
                invoice_products: invoiceProducts
            };

            let token = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");
            let url = "/api/customer/invoice/update/" + invoiceId;

            fetch(url, {
                method: "PUT",
                body: JSON.stringify(payload),
                headers: {
                    "Content-Type": "application/json",
                    credentials: "same-origin",
                    "X-CSRF-TOKEN": token
                }
            })
                .then(res => res.json())
                .then(response =>
                    console.log("Success:", JSON.stringify(response))
                )
                .then(
                    res =>
                        (window.location.href = "/invoices/list/" + customerID)
                )
                .catch(error => console.error(`Error: {error}`));
        });
} else if (proForma) {
    const save = document.querySelector("#sendProductsForm");
    save.addEventListener("submit", saveProducts);

    function saveProducts(event) {
        event.preventDefault();

        let customer_prices = [
            ...document.querySelectorAll("#products-list tr")
        ].reduce((previousValue, currentValue) => {
            const productId = currentValue.querySelector("input[type=hidden]")
                .value;
            const productName = currentValue.querySelector(".product-name")
                .value;
            const productSymbol = currentValue.querySelector(".product-name")
                .value;
            const productQuantity = Number(
                currentValue.querySelector(".quantity").value
            );

            if (previousValue.hasOwnProperty(productId)) {
                previousValue[productId].quantity += productQuantity;
            } else {
                previousValue[productId] = {
                    product_name: productName,
                    quantity: productQuantity,
                    purchase_price: currentValue.querySelector(".purchasePrice")
                        .value,
                    net_unit_price: currentValue.querySelector(".netUnitPrice")
                        .value
                };
            }
            return previousValue;
        }, {});

        console.log(customer_prices);

        let comments = document.querySelector("#comments").value;

        let payTerm = document.querySelector("#payTerm");
        let selectedPayTerm = payTerm.options[payTerm.selectedIndex].value;
        let payType = document.querySelector("#payType");
        let selectedPayType = payType.options[payType.selectedIndex].value;

        var payload = {
            payTypeSend: selectedPayType,
            payTermSend: selectedPayTerm,
            comments: comments,
            customer_prices: customer_prices,
            invoice_products: invoiceProducts
        };

        let token = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");
        let url = "/api/customer/invoice/pro-forma/create/" + customerID;

        fetch(url, {
            method: "POST",
            body: JSON.stringify(payload),
            headers: {
                "Content-Type": "application/json",
                credentials: "same-origin",
                "X-CSRF-TOKEN": token
            }
        })
            .then(res => res.json())
            .then(response => console.log("Success:", JSON.stringify(response)))
            .then(
                res => (window.location.href = "/invoices/list/" + customerID)
            )
            .catch(error => console.error(`Error: {error}`));
    }
} else {
    document
        .querySelector("#sendProductsForm")
        .addEventListener("submit", function(event) {
            event.target.querySelector('[type = "submit"]').disabled = true;

            event.preventDefault();

            let customer_prices = [
                ...document.querySelectorAll("#products-list tr")
            ].reduce((previousValue, currentValue) => {
                const productId = currentValue.querySelector(
                    "input[type=hidden]"
                ).value;
                const productName = currentValue.querySelector(".product-name")
                    .value;
                const productSymbol = currentValue.querySelector(
                    ".product-name"
                ).value;
                const productQuantity = Number(
                    currentValue.querySelector(".quantity").value
                );

                if (previousValue.hasOwnProperty(productId)) {
                    previousValue[productId].quantity += productQuantity;
                } else {
                    previousValue[productId] = {
                        product_name: productName,
                        quantity: productQuantity,
                        purchase_price: currentValue.querySelector(
                            ".purchasePrice"
                        ).value,
                        net_unit_price: currentValue.querySelector(
                            ".netUnitPrice"
                        ).value
                    };
                }
                return previousValue;
            }, {});

            console.log(customer_prices);

            let comments = document.querySelector("#comments").value;
            let payTerm = document.querySelector("#payTerm");
            let selectedPayTerm = payTerm.options[payTerm.selectedIndex].value;
            let payType = document.querySelector("#payType");
            let selectedPayType = payType.options[payType.selectedIndex].value;

            var payload = {
                payTypeSend: selectedPayType,
                payTermSend: selectedPayTerm,
                comments: comments,
                customer_prices: customer_prices,
                invoice_products: invoiceProducts
            };

            let token = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");
            let url = "/api/customer/invoice/create/" + customerID;

            fetch(url, {
                method: "POST",
                body: JSON.stringify(payload),
                headers: {
                    "Content-Type": "application/json",
                    credentials: "same-origin",
                    "X-CSRF-TOKEN": token
                }
            })
                .then(res => res.json())
                .then(
                    res =>
                        (window.location.href = "/invoices/list/" + customerID)
                )
                .catch(error => console.error(`Error: {error}`));
        });
}
