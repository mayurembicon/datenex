@extends('layouts.app')
@section('content')


    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Subheader-->
        <div class="subheader py-2 py-lg-6  subheader-transparent " id="kt_subheader">
            <div class="container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">

                    <!--begin::Page Heading-->
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <!--begin::Page Title-->
                        <h5 class="text-dark font-weight-bold my-1 mr-5">
                            Proforma Invoice </h5>
                        <!--end::Page Title-->

                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Transaction </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Proforma Invoice </a>
                            </li>
                        </ul>
                        <!--end::Breadcrumb-->
                    </div>
                    <!--end::Page Heading-->
                </div>
                <!--end::Info-->
                <div class="d-flex align-items-center">
                    <!--begin::Actions-->
                    <a href="{{route('pi.create')}}" class="btn btn-light-primary font-weight-bolder btn-sm">
                        <i class="flaticon-add-circular-button"></i> Add New
                    </a>

                    <!--end::Actions-->

                    <!--begin::Dropdown-->
                    <!--end::Dropdown-->
                </div>
                <!--begin::Toolbar-->

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

                    <form action="{{ url('search-pi') }}" method="POST" role="search">
                        {{ csrf_field() }}
                        <div class="form-group row">

                            <div class="col-lg-2">
                                <label class="col-form-label">Company Name</label>
                                <select
                                    class="form-control customer-select2 @error('customer_id') is-invalid @enderror "
                                    name="customer_id">
                                    <?php if(!empty($selectedCustomer)){ ?>
                                    <option
                                        value="<?php echo $selectedCustomer; ?>"><?php echo $selectedCustomer; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label class="col-form-label ">Product Name</label>
                                <select
                                    class="item-clr form-control item-select2 @error('item_id') is-invalid @enderror "
                                    name="item_id">
                                    <?php if(!empty($selectedItem)){ ?>
                                    <option value="<?php echo $selectedItem; ?>"><?php echo $selectedItem; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label class="col-form-label ">Pi No</label>
                                <select
                                    class="item-clr form-control @error('item_id') is-invalid @enderror "
                                    id="pi_no"
                                    name="pi_no">
                                    <?php if(!empty($selectedPiNo)){ ?>
                                    <option
                                        value="<?php echo $selectedPiNo; ?>"><?php echo $selectedPiNo; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label class="col-form-label ">Order No</label>
                                <select
                                    class="item-clr form-control @error('item_id') is-invalid @enderror "
                                    id="order_no"
                                    name="order_no">
                                    <?php if(!empty($selectedOrderNo)){ ?>
                                    <option
                                        value="<?php echo $selectedOrderNo; ?>"><?php echo $selectedOrderNo; ?></option>
                                    <?php } ?>
                                </select>
                            </div>


                        </div>
                        <div class="col-lg-2">


                            <button type="submit" name="name"
                                    class="btn btn-primary btn-sm @error('name') is-invalid @enderror">
                                <i class="flaticon-search"></i> Search
                            </button>
                            <a href="{{ url('clear-pi') }}"
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
                        <table class="table table-bordered table-hover table-checkable mt-11 col-form-label">
                            <thead>
                            <tr>
                                <th>Pi No</th>
                                <th>Order No</th>
                                <th>Pi Date</th>
                                <th>Due Date</th>
                                <th>Company Name</th>
                                <th>Status</th>
                                <th>Print</th>
                                <th>Telegram</th>
                                <th>Mail</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($pi as $key=>$value)
                                <tr>

                                    <td>{{$value->pi_no}}</td>
                                    <td>{{$value->order_no}}</td>
                                    <td>{{date('d-m-Y',strtotime($value->pi_date))}}</td>
                                    <td>{{date('d-m-Y',strtotime($value->due_date))}}</td>
                                    <td>{{$value->company_name}}</td>
                                    {{--                                    <td>{{$value->sales_person}}</td>--}}
                                    {{--                                    <td>{{isset($value->paymentterms)?$value->paymentterms->payment_terms:''}}</td>--}}
                                    {{--                                    <td>{{$value->subject}}</td>--}}

                                    @if($value->sales_status=='Pending')
                                        <td>
                                            <div class="example-preview">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">Status
                                                    </button>

                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item"
                                                           href="{{url('make-sales',[$value->pi_id,'PI'])}}"> Make
                                                            Sales</a>
                                                        <a class="dropdown-item view-model" data-toggle="modal"
                                                           data-id="{{ $value->pi_id }}"
                                                           data-target="#exampleModal"> Follow Up</a>
                                                        <a class="dropdown-item timeline-model" data-toggle="modal"
                                                           data-id="{{ $value->pi_id }}"
                                                           data-target="#timeLine"> TimeLine</a>


                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ url('print-pi/'.$value->pi_id) }}" target="_blank"
                                               class="btn btn-info btn-sm"><i
                                                    class="fa fa-print"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{url('send-pi-telegram-msg',$value->pi_id)}}">
                                                <img src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/media/logos/telegram.svg'}}" width="40" height="40" ></a>
                                        </td>
                                        <td>
                                            <a href="#" data-toggle="modal"
                                               data-id="{{ $value->pi_id }}"
                                               data-target="#mailModel"
                                               class="btn btn-info btn-sm view-mail">
                                                <i class="fa fa-envelope"></i></a>
                                        </td>
                                        <td>
                                            <a href="{{route('pi.edit',$value->pi_id)}}"
                                               class="btn btn-warning btn-sm"><i class="fa fa-edit"></i>
                                            </a>


                                        </td>
                                        <td>

                                            <form action="{{route('pi.destroy',$value->pi_id) }}" method="post">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm "
                                                        onclick="return confirm('Are you sure  want to delete?')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>

                                        </td>
                                    @else
                                        <td>
                                            <div class="example-preview">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-light-danger dropdown-toggle"
                                                            data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">{{$value->sales_status}}
                                                    </button>

                                                    <div class="dropdown-menu">

                                                        <a class="dropdown-item view-model" data-toggle="modal"
                                                           data-id="{{ $value->pi_id }}"
                                                           data-target="#exampleModal"> Follow Up</a>
                                                        <a class="dropdown-item timeline-model" data-toggle="modal"
                                                           data-id="{{ $value->pi_id }}"
                                                           data-target="#timeLine"> TimeLine</a>


                                                    </div>
                                                </div>
                                            </div>


                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $pi->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" data-backdrop="static" tabindex="-1" role="dialog"
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
                    <input type="hidden" id="pi_id" name="pi_id" value="">

                    <div class="form-group row">
                        <label class="col-lg-12 col-form-label">Company Name </label>
                        <div class="col-lg-6">
                            <input type="text" disabled id="company_id"
                                   class="form-control select2-control"
                                   name="pi_id" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-12 col-form-label ">Pi Person :</label>
                        <div class="col-lg-6">

                            <input type="text" name="pi_id" disabled
                                   class="form-control" id="contact_id"
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
                    <button type="button" class="btn btn-primary font-weight-bold" onclick="PiSaveFollowup()">Save

                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="timeLine" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">TimeLine

                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div data-scroll="true" data-height="500">
                        <input type="hidden" id="pi_id" name="pi_id" value="">

                        <div class="timeline timeline-4">

                            <div class="timeline-bar"></div>

                            <div class="timeline-items" id="timeline">

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="mailModel" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Mail

                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <form action="{{ url('send-pi-email') }}"
                      method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="mail_pi_id" name="mail_pi_id" value="">

                        <div class="form-group row">
                            <label class="col-lg-12 col-form-label">Email </label>
                            <div class="col-lg-10">
                                <input type="email" id="email" required
                                       class="form-control select2-control email"
                                       name="email" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-12 col-form-label ">Title</label>
                            <div class="col-lg-10">
                                <input type="text" name="mail_title" required
                                       class="form-control mail_title" id="title"
                                       value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-12  col-form-label">Body</label>
                            <div class="col-lg-12">
                                <textarea name="mail_body" id="mail_body"
                                          class="mail_body">{{$setting->mail_body}}</textarea>
                                <div id="remark_alert" class="invalid-feedback" role="alert"></div>
                                @error('remark')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-4 col-form-label">Attachment With Pdf</label>
                            <div class="col-2">
                            <span class="switch switch-outline switch-icon switch-primary">
								<label>
									<input type="checkbox" checked
                                           class=""
                                           name="attachment"
                                           id="attachment">
									<span></span>
								</label>
							</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Close
                        </button>
                        <button type="submit" class="btn btn-primary font-weight-bold">Send

                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <link
        href="{{ env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/datatables/datatables.bundle.css?v=7.0.5'}}"
        rel="stylesheet"
        type="text/css"/>
    <link href="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/css/style.bundle.css'}}" rel="stylesheet"
          type="text/css"/>
@endpush
@push('scripts')
    <!--begin::Page Vendors(used by this page)-->
    <script
        src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/js/pages/crud/forms/widgets/select2.js'}}"></script>
    <script
        src="{{ env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.5'}}"></script>
    <script
        src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/js/pages/crud/forms/widgets/select2.js'}}"></script>
    <script
        src="{{ env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js'}}"></script>
    <script
        src="{{ env('ASSET_URL','http://localhost/datenex/public/').'assets/js/pages/crud/forms/editors/ckeditor-classic.js'}}"></script>
    <!--end::Page Vendors-->
    <!--begin::Page Scripts(used by this page)-->
    <script>
        $('#kt_datatable').DataTable({});

        $(document).on("click", ".timeline-model", function () {
            var ID = $(this).data('id');
            var C = $(this).data('pi_id');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ url('get-pi-timeline')}}',
                type: 'POST',

                data: {pi_id: ID},
                success: function (data) {
                    $('#timeline').empty();
                    let timelineItemDirection = 'left';
                    $.each(data[0], function (key, value) {
                        // $('#timeline').append('<div class="timeline-item timeline-item-left" ><div class="timeline-badge"> <div class="bg-danger"></div> </div> <span class="text-muted">' + value.name + '</span> <div class="timeline-label"> <span class="text-primary font-weight-bold">' + value.created_at + '</span> </div> <div class="timeline-content mb-3">' + value.remark + '</div> </div></div>');
                        $('#timeline').append('<div class="timeline-item timeline-item-' + timelineItemDirection + '"><div class="timeline-badge"><div class="bg-danger"></div></div><div class="timeline-label"><span class="text-primary font-weight-bold">' + value.created_at + '</span></div><div class="timeline-content">' + value.remark + '<br/><span class="text-muted font-italic">' + value.name + '</span></div></div>');
                        timelineItemDirection = (timelineItemDirection === 'right') ? 'left' : 'right';
                    });

                }
            });
        });
        $(document).on("click", ".view-model", function () {
            var ID = $(this).data('id');
            var C = $(this).data('pi_id');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ url('get-pi-customer')}}',
                type: 'POST',

                data: {pi_id: ID},
                success: function (data) {
                    setInterval('refreshPage()', 5000);
                    $('#modal-title').html($('#item_name_' + ID).html());
                    $("#company_id").val(data.customer_name);
                    $("#contact_id").val(data.contact_person);
                    $("#pi_id").val(data.pi_id);

                }
            });
        });

        function PiSaveFollowup() {
            var remark = ($('textarea[name="remark"]').val());
            var next_followup_date = ($('input[name="next_followup_date"]').val());
            var pi_id = ($('input[name="pi_id"]').val());
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ url('pi-follow-up') }}',
                data: {

                    remark: remark,
                    next_followup_date: next_followup_date,
                    pi_id: pi_id,

                },
                success: function (data) {
                    if (data.error) {
                        $.each(data.error, function (key, value) {
                            $("#" + key).toggleClass("is-invalid");
                            $("#" + key + '_alert').html(value);
                        });
                    } else {
                        if (data.success) {
                            $('#exampleModal').modal('toggle');
                            $('#remark').val('');
                            // $("#remark").toggleClass("is-invalid");

                            $('#next_followup_date').val('');
                            // $("#next_followup_date").toggleClass("is-invalid");
                            $('#pi_id').val('');
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


    </script>
    <script>
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
                        table_name: 'customer',
                        field_name: 'company_name'
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
                        table_name: 'itemmaster',
                        field_name: 'name'
                    };
                }, processResults: function (response) {
                    return {results: response};
                }, cache: true
            }
        });


        $("#order_no").select2({
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
                        table_name: 'pi',
                        field_name: 'order_no'
                    };
                }, processResults: function (response) {
                    return {results: response};
                }, cache: true
            }
        });
        $("#pi_no").select2({
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
                        table_name: 'pi',
                        field_name: 'pi_no'
                    };
                }, processResults: function (response) {
                    return {results: response};
                }, cache: true
            }
        });
    </script>
<script>
    $(document).on("click", ".view-mail", function () {
        var ID = $(this).data('id');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{ url('get-customer-email')}}',
            type: 'POST',

            data: {pi_id: ID},
            success: function (data) {
                $("#mail_pi_id").val(data.pi);
                $("#email").val(data.email);

            }
        });
    });
    function QuotationMail() {
        var mail_title = $('.mail_title').val();
        var mail_body = $('.mail_body').html();
        alert(mail_body);
        return false;
        var email = $('.email').val();
        var mail_pi_id = ($('input[name="mail_pi_id"]').val());
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '{{ url('send-pi-email') }}',
            data: {
                mail_title: mail_title,
                mail_body: mail_body,
                email: email,
                mail_pi_id: mail_pi_id,
            },
            success: function (data) {
                if (data.error) {
                    $.each(data.error, function (key, value) {
                        $("#" + key).toggleClass("is-invalid");
                        $("#" + key + '_alert').html(value);
                    });
                } else {
                    if (data.success) {
                        $('#exampleModal').modal('toggle');
                        $('#mail_title').val('');
                        $('#mail_body').val('');
                        $('#mail_pi_id').val('');
                        // Swal.fire({
                        //     position: "center",
                        //     icon: "success",
                        //     title: "Email Send successfully.",
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
@endpush



