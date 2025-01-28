<div class="invoice-selector">
    <h3>¿Qué tipo de comprobante deseas?</h3>
    <label>
        <input type="radio" name="invoice_type" value="boleta" onchange="updateInvoiceType(this)" 
               {if $selected_option == 'boleta'}checked{/if}> Boleta
    </label>
    <label>
        <input type="radio" name="invoice_type" value="factura" onchange="updateInvoiceType(this)" 
               {if $selected_option == 'factura'}checked{/if}> Factura
    </label>
</div>

<input type="hidden" id="invoice_type_value" name="invoice_type_value" value="{$selected_option}">


<script>
    function updateInvoiceType(radio) {
        const hiddenInput = document.getElementById('invoice_type_value');
        if (hiddenInput) {
            hiddenInput.value = radio.value;
        }
    }
</script>