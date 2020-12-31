@extends('layouts.app')

@section('content')
    <!-- begin:: Subheader -->

    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Subheader-->
        <div class="subheader py-2 py-lg-6  subheader-transparent " id="kt_subheader">
            <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">

                    <!--begin::Page Heading-->
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <!--begin::Page Title-->
                        <h5 class="text-dark font-weight-bold my-1 mr-5">
                            Online Inquiry </h5>
                        <!--end::Page Title-->

                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Transaction </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Online Inquiry </a>
                            </li>
                        </ul>
                        <!--end::Breadcrumb-->
                    </div>
                    <!--end::Page Heading-->
                </div>
                <!--end::Info-->

                <!--begin::Toolbar-->
                <div class="d-flex align-items-center">
                    <!--begin::Actions-->
                    <div class="kt-subheader__wrapper">
                        <a href="{{url('sync-inquiry/INDIAMART')}}"
                           class="btn btn-sm  btn-primary"><i class="flaticon-refresh"></i> Sync IndiMart</a>
                        <a href="{{url('sync-inquiry/TRADEINDIA')}}"
                           class="btn btn-sm btn-warning"><i class="flaticon-refresh"></i> Sync TradeIndia</a>
                    </div>
                    <!--end::Actions-->

                    <!--begin::Dropdown-->
                    <!--end::Dropdown-->
                </div>

                <!--end::Toolbar-->
            </div>
        </div>

        <!--end::Subheader-->
        <!--begin::Entry-->

        <!--end::Entry-->
        <!--begin::Container-->
        <div class="container-fluid">
        @include('layouts.flash-message')

        <!--begin::Notice-->

            <!--end::Notice-->
            <!--begin::Card-->
            <div class="card card-custom">
                <div class="card-body" style="padding:1px 10px;!important;">
                    <form action="{{ url('search-online-inquiry') }}" method="POST" role="search">
                        {{ csrf_field() }}
                        <div class="form-group row">
                            <div class="col-lg-2">
                                <label class="col-form-label ">Inquiry From</label>
                                <select class="form-control select-chosen" name="inquiry_from" id="inquiry_from">
                                    <option value=""></option>
                                    <option
                                        value="INDIAMART" {{ !empty($search_item AND ($search_item['inquiry_from'] == 'INDIAMART'))?'selected':'' }}>
                                        INDIAMART
                                    </option>
                                    <option
                                        value="TRADEINDIA" {{ !empty($search_item AND ($search_item['inquiry_from'] == 'TRADEINDIA'))?'selected':'' }}>
                                        TRADEINDIA
                                    </option>

                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label class="col-form-label ">Contact Person</label>
                                <select
                                    class="item-clr form-control @error('item_id') is-invalid @enderror "
                                    id="customer_name"
                                    name="sender_name">
                                    <?php if(!empty($selectedContactPer)){ ?>
                                    <option
                                        value="<?php echo $selectedContactPer; ?>"><?php echo $selectedContactPer; ?></option>
                                    <?php } ?>
                                </select>
                            </div>


                            <div class="col-lg-2">
                                <label class="col-form-label">Company Name</label>
                                <select
                                    class="form-control customer-select2 @error('customer_id') is-invalid @enderror "
                                    name="sender_company">
                                    <?php if(!empty($selectedCustomer)){ ?>
                                    <option
                                        value="<?php echo $selectedCustomer; ?>"><?php echo $selectedCustomer; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label class="col-form-label ">Phone No </label>
                                <select
                                    class="item-clr form-control @error('phone_no') is-invalid @enderror "
                                    id="sender_mobile"
                                    name="sender_mobile">
                                    <?php if(!empty($selectedPhone)){ ?>
                                    <option value="<?php echo $selectedPhone; ?>"><?php echo $selectedPhone; ?></option>
                                    <?php } ?>
                                </select>

                            </div>


                            <div class="col-lg-2">
                                <label class="col-form-label">Email</label>
                                <select
                                    class="item-clr form-control @error('email') is-invalid @enderror " id="email"
                                    name="sender_email">
                                    <?php if(!empty($selectedEmail)){ ?>
                                    <option value="<?php echo $selectedEmail; ?>"><?php echo $selectedEmail; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-lg-2">
                                <label class="col-form-label ">Product Name</label>
                                <select
                                    class="form-control item-select2 @error('item_id') is-invalid @enderror "
                                    name="product_name">
                                    <?php if(!empty($selectedItem)){ ?>
                                    <option value="<?php echo $selectedItem; ?>"><?php echo $selectedItem; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                            <div class="col-lg-2">


                                <button type="submit" name="name"
                                        class="btn btn-primary btn-sm @error('name') is-invalid @enderror">
                                    <i class="flaticon-search"></i> Search
                                </button>
                                <a href="{{ url('clear-online-inquiry') }}"
                                   class="btn btn-sm btn-danger @error('name') is-invalid @enderror">
                                    <i class="fas fa-eraser"></i> Clear</a>

                            </div>
                    </form>
                </div>
            </div>
            <br>
            <div class="card card-custom">

                <div class="card-body">
                    <div class="table-responsive">
                        <!--begin: Datatable-->
                        <table class="table table-bordered table-hover table-checkable mt-10 col-form-label" id="">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Inquiry From</th>
                                <th>Customer Name</th>
                                <th>Company Name</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Product Name</th>
                                <th>Follow Up</th>
                                <th>Delete</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($onlineInquiry as $row)
                                <tr>
                                    <td>{{ date('d-m-Y',strtotime($row->inq_date)) }}</td>
                                    <td>{{ isset($row->inquiry_from)?$row->inquiry_from:'' }}</td>
                                    <td>{{ isset($row->sender_name)?$row->sender_name:'' }}</td>
                                    <td>{{ isset($row->sender_company)?$row->sender_company:'' }}</td>
                                    <td>{{ isset($row->sender_mobile)?$row->sender_mobile:'' }}</td>
                                    <td>{{ isset($row->sender_email)?$row->sender_email:'' }}</td>
                                    <td>{{ isset($row->subject)?$row->subject:'' }}</td>
                                    <td>{{ isset($row->product_name)?$row->product_name:'' }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm view-model"
                                                data-toggle="modal"
                                                data-id="{{ $row->o_id }}"
                                                data-target="#OnlineinquiryModal"> Follow Up
                                        </button>
                                    </td>
                                    <td>
                                        <form action="{{route('online-inquiry.destroy',$row->o_id) }}" method="post">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm "
                                                    onclick="return confirm('Are you sure  want to delete?')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>

                                    </td>
                                    <td><a href="{{ url('make-inquiry/'.$row->o_id) }}"
                                           class="btn btn-pill btn-primary btn-sm"><i class="flaticon2-chat-1"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $onlineInquiry->links() }}

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="OnlineinquiryModal" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Follow Up

                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="customer_id" name="o_id" value="">

                    <div class="form-group row">
                        <label class="col-lg-12 col-form-label">Company Name </label>
                        <div class="col-lg-6">
                            <input type="text" disabled id="sender_company"
                                   class="form-control select2-control"
                                   name="o_id" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-12 col-form-label ">Contact Person :</label>
                        <div class="col-lg-6">

                            <input type="text" name="o_id" disabled
                                   class="form-control" id="sender_name"
                                   value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-12  col-form-label">Remark</label>
                        <div class="col-lg-12">

                                            <textarea name="remark" placeholder="Remark" id="remark"
                                                      class="form-control @error('remark') is-invalid @enderror"></textarea>
                            <div id="remark_alert" class="invalid-feedback" role="alert"></div>
                            @error('remark')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-12 col-form-label">Next FollowUp Date</label>
                        <div class="col-lg-6">
                            <div class="input-group date">
                                <input type="text"
                                       name="next_followup_date" autocomplete="off"
                                       class="form-control @error('next_followup_date') is-invalid @enderror"
                                       placeholder="dd-mm-yyyy"
                                       id="next_followup_date"
                                       value="{{ !empty(old('next_followup_date'))?old('next_followup_date'):(!empty($transportdeteils->next_followup_date)?date('d-m-Y',strtotime($transportdeteils->next_followup_date)):date('d-m-Y')) }}">
                                <div class="input-group-append">
															<span class="input-group-text">
																<i class="la la-calendar"></i>
															</span>
                                </div>
                                <div id="next_followup_date_alert" class="invalid-feedback" role="alert"></div>
                                @error('next_followup_date')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Close
                    </button>
                    <button type="button" class="btn btn-primary font-weight-bold" onclick="saveFollowup()">Save

                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- end:: Content -->
@endsection
@push('styles')
    <link
        href="{{ env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/datatables/datatables.bundle.css?v=7.0.5'}}"
        rel="stylesheet"
        type="text/css"/>
@endpush

@push('scripts')
    <!--begin::Page Vendors(used by this page)-->
    <script
        src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/js/pages/crud/forms/widgets/select2.js'}}"></script>
    <script
        src="{{ env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.5'}}"></script>

    <!--end::Page Vendors-->
    <!--begin::Page Scripts(used by this page)-->
    <script>
        // Class definition

        var KTBootstrapDatepicker = function () {

            var arrows;
            if (KTUtil.isRTL()) {
                arrows = {
                    leftArrow: '<i class="la la-angle-right"></i>',
                    rightArrow: '<i class="la la-angle-left"></i>'
                }
            } else {
                arrows = {
                    leftArrow: '<i class="la la-angle-left"></i>',
                    rightArrow: '<i class="la la-angle-right"></i>'
                }
            }

            // Private functions
            var demos = function () {
                // minimum setup
                $('#kt_datepicker_1, #kt_datepicker_1_validate').datepicker({
                    rtl: KTUtil.isRTL(),
                    todayHighlight: true,
                    orientation: "bottom left",
                    templates: arrows
                });

                // minimum setup for modal demo
                $('#kt_datepicker_1_modal').datepicker({
                    rtl: KTUtil.isRTL(),
                    todayHighlight: true,
                    orientation: "bottom left",
                    templates: arrows
                });

                // input group layout
                $('#kt_datepicker_2, #kt_datepicker_2_validate').datepicker({
                    rtl: KTUtil.isRTL(),
                    todayHighlight: true,
                    orientation: "bottom left",
                    templates: arrows
                });

                // input group layout for modal demo
                $('#kt_datepicker_2_modal').datepicker({
                    rtl: KTUtil.isRTL(),
                    todayHighlight: true,
                    orientation: "bottom left",
                    templates: arrows
                });

                // enable clear button
                $('#next_followup_date, #kt_datepicker_3_validate').datepicker({
                    rtl: KTUtil.isRTL(),
                    todayBtn: "linked",
                    format: "dd-mm-yyyy",
                    clearBtn: true,
                    todayHighlight: true,
                    templates: arrows
                });

                // enable clear button for modal demo
                $('#kt_datepicker_3_modal').datepicker({
                    rtl: KTUtil.isRTL(),
                    todayBtn: "linked",
                    format: "dd-mm-yyyy",
                    clearBtn: true,
                    todayHighlight: true,
                    templates: arrows
                });

                // orientation
                $('#kt_datepicker_4_1').datepicker({
                    rtl: KTUtil.isRTL(),
                    orientation: "top left",
                    todayHighlight: true,
                    templates: arrows
                });

                $('#kt_datepicker_4_2').datepicker({
                    rtl: KTUtil.isRTL(),
                    orientation: "top right",
                    todayHighlight: true,
                    templates: arrows
                });

                $('#kt_datepicker_4_3').datepicker({
                    rtl: KTUtil.isRTL(),
                    orientation: "bottom left",
                    todayHighlight: true,
                    templates: arrows
                });

                $('#kt_datepicker_4_4').datepicker({
                    rtl: KTUtil.isRTL(),
                    orientation: "bottom right",
                    todayHighlight: true,
                    templates: arrows
                });

                // range picker
                $('#kt_datepicker_5').datepicker({
                    rtl: KTUtil.isRTL(),
                    todayHighlight: true,
                    templates: arrows
                });

                // inline picker
                $('#kt_datepicker_6').datepicker({
                    rtl: KTUtil.isRTL(),
                    todayHighlight: true,
                    templates: arrows
                });
            }

            return {
                // public functions
                init: function () {
                    demos();
                }
            };
        }();

        jQuery(document).ready(function () {
            KTBootstrapDatepicker.init();
        });

        $(document).on("click", ".view-model", function () {
            var ID = $(this).data('id');
            var C = $(this).data('o_id');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ url('get-online-customer-info')}}',
                type: 'POST',

                data: {o_id: ID},
                success: function (data) {
                    setInterval('refreshPage()', 5000);
                    $('#modal-title').html($('#item_name_' + ID).html());
                    $("#sender_company").val(data.sender_company);
                    $("#sender_name").val(data.sender_name);
                    $("#customer_id").val(data.o_id);

                }
            });
        });

        /* start follow up */
        function saveFollowup() {
            var remark = ($('textarea[name="remark"]').val());
            var next_followup_date = ($('input[name="next_followup_date"]').val());
            var o_id = ($('input[name="o_id"]').val());
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ url('oi-follow-up') }}',
                data: {

                    remark: remark,
                    next_followup_date: next_followup_date,
                    o_id: o_id,

                },
                success: function (data) {
                    if (data.error) {
                        $.each(data.error, function (key, value) {
                            $("#" + key).toggleClass("is-invalid");
                            $("#" + key + '_alert').html(value);
                        });
                    } else {
                        if (data.success) {
                            $('#OnlineinquiryModal').modal('toggle');
                            $('#remark').val('');
                            // $("#remark").toggleClass("is-invalid");

                            $('#next_followup_date').val('');
                            // $("#next_followup_date").toggleClass("is-invalid");
                            $('#o_id').val('');
                            // Swal.fire({
                            //     position: "center",
                            //     icon: "success",
                            //     title: "Followup saved successfully.",
                            //     showConfirmButton: false,
                            //     timer: 1500
                            // });

                            // $("#customer-model-body #customer_name").val();
                            // $("#customer-model-body #company_name").val();
                        }

                    }

                }
            });
        }

    </script>
    <script>
        $('#kt_datatable').DataTable({});


        $(".customer-select2").select2({
            placeholder: "Select..", ajax: {
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ url('index-serach-list')}}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term,
                        table_name: 'online_inquiry',
                        field_name: 'sender_company'
                    };
                }, processResults: function (response) {
                    return {results: response};
                }, cache: true
            }
        });
        $(".item-select2").select2({
            placeholder: "Select..",
            ajax: {
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ url('index-serach-list')}}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term,
                        table_name: 'online_inquiry',
                        field_name: 'product_name'
                    };
                }, processResults: function (response) {
                    return {results: response};
                }, cache: true
            }
        });

        $("#customer_name").select2({
            placeholder: "Select..",
            ajax: {
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ url('index-serach-list')}}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term,
                        table_name: 'online_inquiry',
                        field_name: 'sender_name'
                    };
                }, processResults: function (response) {
                    return {results: response};
                }, cache: true
            }
        });

        $("#email").select2({
            placeholder: "Select..",
            ajax: {
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ url('index-serach-list')}}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term,
                        table_name: 'online_inquiry',
                        field_name: 'sender_email'
                    };
                }, processResults: function (response) {
                    return {results: response};
                }, cache: true
            }
        });
        $("#sender_mobile").select2({
            placeholder: "Select..",
            ajax: {
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ url('index-serach-list')}}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term,
                        table_name: 'online_inquiry',
                        field_name: 'sender_mobile'
                    };
                }, processResults: function (response) {
                    return {results: response};
                }, cache: true
            }
        });

    </script>

@endpush
