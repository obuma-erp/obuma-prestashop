<div class="invoice-selector">
    <h3>¿Qué tipo de comprobante deseas?</h3>
    <label>
        <input type="radio" name="invoice_type" value="boleta" 
               {if $selected_option == 'boleta'}checked{/if}> Boleta
    </label>
    <label>
        <input type="radio" name="invoice_type" value="factura" 
               {if $selected_option == 'factura'}checked{/if}> Factura
    </label>
</div>



<script>
    document.addEventListener('submit', function (e) {
        const selectedOption = document.querySelector('input[name="invoice_type"]:checked');
        if (selectedOption) {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'invoice_type_value';
            hiddenInput.value = selectedOption.value;
            e.target.appendChild(hiddenInput);
        }
    });
</script>

