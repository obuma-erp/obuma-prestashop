<div class="invoice-selector">
    <h3>¿Qué tipo de comprobante deseas?</h3>
    <label>
        <input type="radio" name="invoice_type" value="boleta"  onchange="addInvoiceType(this)" 
               {if $selected_option == 'boleta'}checked{/if}> Boleta
    </label>
    <label>
        <input type="radio" name="invoice_type" value="factura"   onchange="addInvoiceType(this)" 
               {if $selected_option == 'factura'}checked{/if}> Factura
    </label>
</div>


<script>
    
    function addInvoiceType(checkbox){

        const forms = document.querySelectorAll('form');

        const form = forms[forms.length - 1]; 

         if (form) {

            const selectedOption = document.querySelector('input[name="invoice_type"]:checked');

            if (selectedOption) {

                if(document.getElemenById("invoice_type_value")){

                    document.getElemenById("invoice_type_value").remove();

                }
                
                const hiddenInput = document.createElement('input');

                hiddenInput.type = 'hidden';

                hiddenInput.id = 'invoice_type_value';

                hiddenInput.name = 'invoice_type_value';

                hiddenInput.value = selectedOption.value;

                form.appendChild(hiddenInput);

            }

        }

    }


</script>

