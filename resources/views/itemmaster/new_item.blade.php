<div class="modal fade" id="item-model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-times-circle"></i></span>
                </button>
            </div>
            <div class="modal-body" id="customer-model-body">
                <input type="hidden" name="control_name" id="control_name">
                @include('itemmaster.form')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-brand btn-danger mr-2" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-brand btn-primary mr-2" onclick="saveItem()" id="save-product">Save changes
                </button>
            </div>
        </div>
    </div>
</div>
@push('scripts')

    <script>
        $(document).on('change', "select.item-clr", function () {
            if($( this ).val()==='NEW'){
                $('#control_name').val($( this ).attr('name'));
                $('#item-model').modal('toggle');
            }
        });


        function saveItem() {
            var type = ($('select[name="type"]').val());
            var name = ($('input[name="name"]').val());
            var unit = ($('input[name="unit"]').val());
            var hsn = ($('input[name="hsn"]').val());
            var sku = ($('input[name="sku"]').val());
            var opening_stock = ($('input[name="opening_stock"]').val());
            var sale_rate = ($('input[name="sale_rate"]').val());
            var purchase_rate = ($('input[name="purchase_rate"]').val());
            var discount_amount = ($('input[name="discount_amount"]').val());
            var taxrate = ($('select[name="taxrate"]').val());
            var descripation = ($('textarea[name="descripation"]').val());
            var ratedIndex = ($('input[name="ratedIndex"]').val());
            var controlName = ($('#control_name').val());


            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ url('item-master') }}',
                data: {
                    type: type,
                    name: name,
                    unit: unit,
                    hsn: hsn,
                    sku: sku,
                    opening_stock: opening_stock,
                    sale_rate: sale_rate,
                    purchase_rate: purchase_rate,
                    discount_amount: discount_amount,
                    taxrate: taxrate,
                    descripation: descripation,
                    ratedIndex: ratedIndex,

                },
                success: function (data) {
                    if (data.error) {
                        $.each(data.error, function (key, value) {
                            $("#" + key).toggleClass("is-invalid");
                            $("#" + key + '_alert').html(value);
                        });
                    } else {
                        if (data.success) {
                            var opt = {
                                id: data.item.item_id,
                                text: data.item.name
                            };
                            var newOption = new Option(opt.text, opt.id, false, false);
                            $('select[name="'+controlName+'"]').append(newOption).trigger('change');
                            $('select[name="'+controlName+'"]').val(opt.id).trigger('change');
                            $('#control_name').val();
                            $('#item-model').modal('toggle');
                        }
                    }

                }
            });
        }
        var ratedIndex = -1, uID = 0;

        $(document).ready(function () {
            resetStarColors();

            if (localStorage.getItem('ratedIndex') != null) {
                setStars(parseInt(localStorage.getItem('ratedIndex')));
                uID = localStorage.getItem('uID');
            }

            $('.fa-star').on('click', function () {
                ratedIndex = parseInt($(this).data('index'));
                localStorage.setItem('ratedIndex', ratedIndex);
                saveToTheDB();
            });

            $('.fa-star').mouseover(function () {
                resetStarColors();
                var currentIndex = parseInt($(this).data('index'));
                setStars(currentIndex);
            });

            $('.fa-star').mouseleave(function () {
                resetStarColors();

                if (ratedIndex != -1)
                    setStars(ratedIndex);
            });
        });

        function saveToTheDB() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ url('item-rating') }}',
                data: {
                    ratedIndex: ratedIndex,

                },

                success: function (data) {
                    $("#ratedIndex").val(data.ratedIndex);
                    uID = r.id;
                    localStorage.setItem('uID', uID);

                }
            });
        }

        function setStars(max) {
            for (var i = 0; i <= max; i++)
                $('.fa-star:eq(' + i + ')').css('color', 'yellow');
        }

        function resetStarColors() {
            $('.fa-star').css('color', 'grey');
        }
    </script>
    <script>
        $('.select2-control').select2({
            allowClear: false,
            placeholder: 'Select ',
        });
    </script>
@endpush
