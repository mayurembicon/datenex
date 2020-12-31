<div class="modal fade" id="customer-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times-circle"></i></span>
                </button>
            </div>
            <div class="modal-body" id="customer-model-body">
                    @include('customer.form')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-brand btn-danger mr-2" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-brand btn-primary mr-2" onclick="saveCustomer()" id="save-product">Save changes
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Class definition
    function customermodelopen() {
        var val = $('select[name="customer_id"]').val();
        console.log(val)
        if (val == 'NEW') {
            $('#customer-modal').modal('toggle');
        }

    }

    function saveCustomer() {
        var customer_name = ($('input[name="customer_name"]').val());
        var customer_type = ($('select[name="customer_type"]').val());
        var company_name = ($('input[name="company_name"]').val());
        var opening_balance = ($('input[name="opening_balance"]').val());
        var opening_balance_type = ($('select[name="opening_balance_type"]').val());
        var email = ($('#customer-model-body input[name="email"]').val());
        var f_phone_no = ($('#customer-model-body input[name="f_phone_no"]').val());
        var s_phone_no = ($('#customer-model-body input[name="s_phone_no"]').val());
        var website = ($('input[name="website"]').val());
        var contact_person_name = ($('input[name="contact_person_name"]').val());
        var gst_no = ($('input[name="gst_no"]').val());
        var place_of_supply = ($('input[name="place_of_supply"]').val());
        var payment_terms_id = ($('select[name="payment_terms_id"]').val());
        var remark = ($('textarea[name="remark"]').val());
        var notes = ($('textarea[name="notes"]').val());
        var billing_attention = ($('input[name="billing_attention"]').val());
        var country_id = ($('input[name="country_id"]').val());
        var billing_address1 = ($('input[name="billing_address1"]').val());
        var billing_address2 = ($('input[name="billing_address2"]').val());
        var billing_address3 = ($('input[name="billing_address3"]').val());
        var billing_pincode = ($('input[name="billing_pincode"]').val());
        var billing_city = ($('input[name="billing_city"]').val());
        var billing_distinct = ($('input[name="billing_distinct"]').val());
        var state_id = ($('input[name="state_id"]').val());
        var shipping_attention = ($('input[name="shipping_attention"]').val());
        var shipping_country_id = ($('input[name="shipping_country_id"]').val());
        var shipping_address1 = ($('input[name="shipping_address1"]').val());
        var shipping_address2 = ($('input[name="shipping_address2"]').val());
        var shipping_address3 = ($('input[name="shipping_address3"]').val());
        var shipping_pincode = ($('input[name="shipping_pincode"]').val());
        var shipping_city = ($('input[name="shipping_city"]').val());
        var shipping_distinct = ($('input[name="shipping_distinct"]').val());
        var shipping_state_id = ($('input[name="shipping_state_id"]').val());

        // $('#customer-model-body #customer_name').removeClass('is-invalid');
        // $('#customer-model-body #company_name').removeClass('is-invalid');
        //
        // $('#customer-model-body #customer_name').html('');
        // $('#customer-model-body #company_name').html('');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '{{ url('customer') }}',
            data: {
                customer_name: customer_name,
                customer_type: customer_type,
                company_name: company_name,
                opening_balance: opening_balance,
                opening_balance_type: opening_balance_type,
                email: email,
                f_phone_no: f_phone_no,
                s_phone_no: s_phone_no,
                website: website,
                contact_person_name: contact_person_name,
                gst_no: gst_no,
                place_of_supply: place_of_supply,
                payment_terms_id: payment_terms_id,
                remark: remark,
                notes: notes,
                billing_attention: billing_attention,
                country_id: country_id,
                billing_address1: billing_address1,
                billing_address2: billing_address2,
                billing_address3: billing_address3,
                billing_pincode: billing_pincode,
                billing_city: billing_city,
                billing_distinct: billing_distinct,
                state_id: state_id,
                shipping_attention: shipping_attention,
                shipping_country_id: shipping_country_id,
                shipping_address1: shipping_address1,
                shipping_address2: shipping_address2,
                shipping_address3: shipping_address3,
                shipping_pincode: shipping_pincode,
                shipping_city: shipping_city,
                shipping_distinct: shipping_distinct,
                shipping_state_id: shipping_state_id,
            },
            success: function (data) {
                if (data.error) {
                    $.each(data.error, function (key, value) {
                        $("#" + key).toggleClass("is-invalid");
                        $("#" + key + '_alert').html(value);
                    });
                } else {
                    if (data.success) {
                        var newOption = new Option(data.customer.customer_name, data.customer.customer_id, false, true);
                        $('#customer_id').append(newOption).trigger('change');

                        $('#customer-modal').modal('toggle');
                        // $("#customer-model-body #customer_name").val();
                        // $("#customer-model-body #company_name").val();
                    }
                }

            }
        });
    }


</script>


