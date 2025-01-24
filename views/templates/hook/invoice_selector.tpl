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
