let dropdown = document.getElementById('supplierId');
const filter = document.querySelector('#filter');

let url = '/api/customers';

$(dropdown).select2({
    width: '100%',
    placeholder: 'Wybierz Dostawcę',

    ajax: {
        url: '/api/customers/',
        type: "GET",
        beforeSend: function (xhr) {
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.setRequestHeader("Accept", "application/json");
        },
        dataType: 'json',
        data: params => ({
            q: params.term
        }),
        processResults: suppliers => ({

            results: suppliers.map(supplier => ({
                id: supplier.id,
                text: supplier.name,
                supplier,
            }))

        })
    }
}).on('select2:select', e => {
    app.supplier = e.params.data.supplier;

});

// fetch(url)
//     .then(
//         function (response) {
//             if (response.status !== 200) {
//                 console.warn('Wygląda na to, że jest jakis problem, error nr: ' +
//                     response.status);
//                 return;
//             }

//             response.json().then(function (data) {

//                 let option;
//                 dropdown.length = 0;


//                 for (var item of data) {
//                     option = document.createElement('option');
//                     option.className = 'suppliers';
//                     option.text = item.name + ' ' + item.street + ' ' + item.city;
//                     option.value = item.id;
//                     dropdown.add(option);

//                 }
//             });
//         }
//     )
//     .catch(function (err) {
//         console.error('Fetch Error -', err);
//     });






count = 0;
dropdown.addEventListener('change', addRow);

function addRow(event) {
    console.log(event);
    count += 1;

    if (count >= 2) {
        return;
    }

    let selectedSupplier = dropdown.options[dropdown.selectedIndex];

    var addSupplierID = selectedSupplier.value;
    var addSupplierName = selectedSupplier.text;
    var termPayment = '';
    var addSupplierNumber = '';
    var addSupplierNetPrice = '';
    var addSupplierGrossPrice = '';



    const list = document.getElementById('suppliers-list');
    const row = document.createElement('tr');

    row.innerHTML = `
    <td style="display:none;"><input type="hidden" id="supplier-id" value="${addSupplierID}"></td>
<td>${addSupplierName}</td>
<td>
<select id="invoice-type">
<option value="cost">Koszt</option>
<option value="resale">Odsprzedaż</option>
</select>
</td>
<td><input class="invoice-number" type="text" value="${addSupplierNumber}" required></td>
<td><input class="term-payment" type="date" value="${termPayment}" required></td>
<td><input class="net-value" type="number" value="${addSupplierNetPrice}" min="1" required></td>
<td><input class="gross-value" type="number" value="${addSupplierGrossPrice}" min="1" required></td>
<td><a href="#" class="delete">X</a></td>
`;

    list.appendChild(row);


    addSupplierNetPrice = document.querySelector('.net-value');
    addSupplierGrossPrice = document.querySelector('.gross-value');
    addSupplierGrossPrice.addEventListener('keyup', function () {
        addSupplierNetPrice.value = Math.round((addSupplierGrossPrice.value / 1.23) * 100) / 100;

    })
}




document.querySelector('#sendSuppliersForm').addEventListener('submit', function (event) {

    event.preventDefault();

    let customerID = document.querySelector('#supplierId').value;
    let invoiceType = document.querySelector('#invoice-type').value;
    let invoiceNumber = document.querySelector('.invoice-number').value;
    let payTerm = document.querySelector('.term-payment').value;
    let netValue = document.querySelector('.net-value').value;
    let grossValue = document.querySelector('.gross-value').value;

    var payload = {
        userID: userID,
        customerID: customerID,
        invoiceType: invoiceType,
        invoiceNumber: invoiceNumber,
        payTerm: payTerm,
        netValue: netValue,
        grossValue: grossValue
    };




    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let url = '/api/customer/invoice/incoming/' + customerID;

    fetch(url, {
            method: "POST",
            body: JSON.stringify(payload),
            headers: {
                "Content-Type": "application/json",
                "credentials": "same-origin",
                "X-CSRF-TOKEN": token
            }
        })
        .then(res => res.json())
        .then(response => console.log('Success:', JSON.stringify(response)))
        .then(res => window.location.href = ("/invoices-incoming/add"))
        .catch(error => console.error(`Error: {error}`))


});
