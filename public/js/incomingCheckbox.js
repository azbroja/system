document.querySelector('#invoices-table').addEventListener('change', e => {
    if (!e.target.matches('input[type=checkbox]')) {
        return;
    }

    const sum = [...e.currentTarget.querySelectorAll(':checked')].reduce((sum, checkbox) => Number(sum) + Number(checkbox.dataset.price), 0)
    document.getElementById("total").value = sum.toFixed(2).replace('.', ',');
});
