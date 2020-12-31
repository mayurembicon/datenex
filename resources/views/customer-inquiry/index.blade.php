@extends('layouts.app')
@section('content')


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
                            Customer Inquiry </h5>
                        <!--end::Page Title-->

                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Transaction </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Customer Inquiry </a>
                            </li>
                        </ul>
                        <!--end::Breadcrumb-->
                    </div>
                    <!--end::Page Heading-->
                </div>

                <!--begin::Toolbar-->
                <div class="d-flex align-items-center">
                    <a href="{{url('send-telegram','link')}}"
                       class="btn btn-info btn-sm">
                        <i class="fa fa-sms"></i>Send Telegram</a>
                   &nbsp;
                   &nbsp;
                   &nbsp;
                    <a href="#" data-toggle="modal"
                       data-target="#mailModel"
                       class="btn btn-info btn-sm view-mail">
                        <i class="fa fa-envelope"></i>Send Mail</a>

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


            <div class="card card-custom">
                <div class="card-body" style="padding:1px 10px;!important;">
                    <form action="{{ url('search-customer-inquiry') }}" method="POST" role="search">
                        {{ csrf_field() }}
                        <div class="form-group row">


                            <div class="col-lg-2">
                                <label class="col-form-label">Company Name</label>
                                <select
                                    class="form-control customer-select2 @error('customer_id') is-invalid @enderror "
                                    name="company_name">
                                    <?php if(!empty($selectedCustomer)){ ?>
                                    <option
                                        value="<?php echo $selectedCustomer; ?>"><?php echo $selectedCustomer; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-lg-2">
                                <label class="col-form-label ">Contact Person</label>
                                <select
                                    class="item-clr form-control @error('item_id') is-invalid @enderror "
                                    id="contact_person"
                                    name="contact_person">
                                    <?php if(!empty($selectedContactPer)){ ?>
                                    <option
                                        value="<?php echo $selectedContactPer; ?>"><?php echo $selectedContactPer; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-lg-2">
                                <label class="col-form-label ">Subject</label>
                                <select
                                    class="item-clr form-control @error('subject') is-invalid @enderror " id="subject"
                                    name="subject">
                                    <?php if(!empty($selectedSubject)){ ?>
                                    <option
                                        value="<?php echo $selectedSubject; ?>"><?php echo $selectedSubject; ?></option>
                                    <?php } ?>
                                </select>
                            </div>


                            <div class="col-lg-2">
                                <label class="col-form-label">Email</label>
                                <select
                                    class="item-clr form-control @error('email') is-invalid @enderror " id="email"
                                    name="email">
                                    <?php if(!empty($selectedEmail)){ ?>
                                    <option value="<?php echo $selectedEmail; ?>"><?php echo $selectedEmail; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-lg-2">
                                <label class="col-form-label ">Phone No </label>
                                <select
                                    class="item-clr form-control @error('phone_no') is-invalid @enderror " id="phone_no"
                                    name="phone_no">
                                    <?php if(!empty($selectedPhone)){ ?>
                                    <option value="<?php echo $selectedPhone; ?>"><?php echo $selectedPhone; ?></option>
                                    <?php } ?>
                                </select>

                            </div>


                        </div>
                        <div class="col-lg-2">


                            <button type="submit" name="name"
                                    class="btn btn-primary btn-sm @error('name') is-invalid @enderror">
                                <i class="flaticon-search"></i> Search
                            </button>
                            <a href="{{ url('clear-customer-inquiry') }}"
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
                        <table class="table table-bordered table-hover table-checkable mt-10 col-form-label">
                            <thead>
                            <tr>

                                <th>Date</th>
                                <th>Company Name</th>
                                <th>Contact Person</th>
                                <th>Subject</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>View</th>
                                <th>Follow Up</th>
                                <th>Delete</th>
                                <th>Action</th>


                            </tr>
                            </thead>
                            <tbody>

                            @foreach($customerinquiry as $key=>$value)
                                <tr>

                                    <td>{{date('d-m-Y',strtotime($value->created_at))}}</td>


                                    <td>{{$value->company_name }}</td>
                                    <td>{{$value->contact_person}}</td>
                                    <td>{{$value->subject}}</td>
                                    <td>{{$value->phone_no}}</td>
                                    <td>{{$value->email}}</td>
                                    <td>
                                        <button class="btn btn-sm customer-info btn-primary" data-toggle="modal"
                                                data-id="{{$value->customer_inquiry_id}}"
                                                data-target="#exampleModal">
                                          <span class="svg-icon svg-icon-danger  svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Shopping\Sort1.svg--><svg
                                                  xmlns="http://www.w3.org/2000/svg"
                                                  xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                                  viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <rect fill="#000000" x="4" y="5" width="16" height="3" rx="1.5"/>
        <path
            d="M7.5,11 L16.5,11 C17.3284271,11 18,11.6715729 18,12.5 C18,13.3284271 17.3284271,14 16.5,14 L7.5,14 C6.67157288,14 6,13.3284271 6,12.5 C6,11.6715729 6.67157288,11 7.5,11 Z M10.5,17 L13.5,17 C14.3284271,17 15,17.6715729 15,18.5 C15,19.3284271 14.3284271,20 13.5,20 L10.5,20 C9.67157288,20 9,19.3284271 9,18.5 C9,17.6715729 9.67157288,17 10.5,17 Z"
            fill="#000000" opacity="0.3"/>
    </g>
</svg><!--end::Svg Icon--></span>View
                                        </button>

                                    </td>
                                    <td>


                                        <button type="button" class="btn btn-primary view-model" data-toggle="modal"
                                                data-id="{{ $value->customer_inquiry_id }}"
                                                data-target="#FollowUpModal"> Follow Up
                                        </button>


                                    </td>
                                    <td>
                                        <form
                                            action="{{route('customer-inquiry.destroy',$value->customer_inquiry_id )}}"
                                            method="post">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm "
                                                    onclick="return confirm('Are you sure  want to delete?')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>

                                    <td><a href="{{ url('create-inquiry/'.$value->customer_inquiry_id) }}"
                                           class="btn btn-pill btn-primary btn-sm"><i class="flaticon2-chat-1"></i></a>
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{ $customerinquiry->links() }}
                    </div>
                </div>
            </div>
            <!--begin::Notice-->

            <!--end::Notice-->
            <!--begin::Card-->
        </div>
    </div>

    <div class="modal fade" id="exampleModal" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Customer Inquiry

                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body" id="customer_inquiry_detail">
                    <input type="hidden" id="customer_inquiry_id" name="customer_inquiry_id" value="">


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Close
                    </button>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="FollowUpModal" data-backdrop="static" tabindex="-1" role="dialog"
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
                    <input type="hidden" id="customer_id" name="c_i_id" value="">

                    <div class="form-group row">
                        <label class="col-lg-12 col-form-label">Company Name </label>
                        <div class="col-lg-6">
                            <input type="text" disabled id="company_id"
                                   class="form-control select2-control"
                                   name="customer_inquiry_id" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-12 col-form-label ">Contact Person :</label>
                        <div class="col-lg-6">

                            <input type="text" name="customer_inquiry_id" disabled
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
                    <button type="button" class="btn btn-primary font-weight-bold" onclick="saveFollowup()">Save

                    </button>
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
                <form action="{{ url('send-email-link') }}"
                      method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="mail_quotation_id" name="mail_quotation_id" value="">

                        <div class="form-group row">
                            <label class="col-lg-12 col-form-label">Email </label>
                            <div class="col-lg-10">
                                <input type="email" id="emails" required
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
                                          class="mail_body">{{url('')."/customer-inquiry/create"}}</textarea>
                                <div id="remark_alert" class="invalid-feedback" role="alert"></div>
                                @error('remark')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group row" style="display: none">
                        <label class="col-4 col-form-label">Attachment With Pdf</label>
                        <div class="col-2">
                            <span class="switch switch-outline switch-icon switch-primary">
								<label>
									<input type="checkbox"
                                           class=""
                                           name="attachment"
                                           id="attachment">
									<span></span>
								</label>
							</span>
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
        src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/js/pages/features/miscellaneous/sweetalert2.js'}}"></script>
    <script
        src="{{ env('ASSET_URL','http://localhost/datenex/public/').'assets/js/pages/crud/forms/editors/ckeditor-classic.js'}}"></script>
    <script
        src="{{ env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js'}}"></script>
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

    </script>
    <script>
        $('#kt_datatable').DataTable({});


    </script>
    <script>
        $(document).on("click", ".customer-info", function () {
            var customerID = $(this).data('id');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ url('customer-info') }}',
                data: {'customerID': customerID},
                success: function (data) {
                    $('#customer_inquiry_detail').html(data);
                }
            });
        });

    </script>
    <script>
        function saveFollowup() {
            var remark = ($('textarea[name="remark"]').val());
            var next_followup_date = ($('input[name="next_followup_date"]').val());
            var c_i_id = ($('input[name="c_i_id"]').val());
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ url('ci-follow-up') }}',
                data: {

                    remark: remark,
                    next_followup_date: next_followup_date,
                    c_i_id: c_i_id,

                },
                success: function (data) {
                    if (data.error) {
                        $.each(data.error, function (key, value) {
                            $("#" + key).toggleClass("is-invalid");
                            $("#" + key + '_alert').html(value);
                        });
                    } else {
                        if (data.success) {
                            $('#FollowUpModal').modal('toggle');
                            $('#remark').val('');
                            // $("#remark").toggleClass("is-invalid");

                            $('#next_followup_date').val('');
                            // $("#next_followup_date").toggleClass("is-invalid");
                            $('#c_i_id').val('');
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
        $(document).on("click", ".view-model", function () {
            var ID = $(this).data('id');
            var C = $(this).data('customer_inquiry_id');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ url('get-customer-items')}}',
                type: 'POST',

                data: {customer_inquiry_id: ID},
                success: function (data) {
                    setInterval('refreshPage()', 5000);
                    $('#modal-title').html($('#item_name_' + ID).html());
                    $("#company_id").val(data.company_name);
                    $("#contact_id").val(data.contact_person);
                    $("#customer_id").val(data.customer_inquiry_id);

                }
            });
        });
        $(document).on("click", ".rating-model ", function () {
            var clickedInquiryID = $(this).data('id');
            $("#rating_inquiry_id").val(clickedInquiryID);

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
                        table_name: 'customer_inquiry',
                        field_name: 'company_name'
                    };
                }, processResults: function (response) {
                    return {results: response};
                }, cache: true
            }
        });


        $("#contact_person").select2({
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
                        table_name: 'customer_inquiry',
                        field_name: 'contact_person'
                    };
                }, processResults: function (response) {
                    return {results: response};
                }, cache: true
            }
        });

        $("#subject").select2({
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
                        table_name: 'customer_inquiry',
                        field_name: 'subject'
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
                        table_name: 'customer_inquiry',
                        field_name: 'email'
                    };
                }, processResults: function (response) {
                    return {results: response};
                }, cache: true
            }
        });
        $("#phone_no").select2({
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
                        table_name: 'customer_inquiry',
                        field_name: 'phone_no'
                    };
                }, processResults: function (response) {
                    return {results: response};
                }, cache: true
            }
        });

    </script>
    <script>

        function QuotationMail() {
            var mail_title = $('.mail_title').val();
            var mail_body = $('.mail_body').html();
            alert(mail_body);
            return false;
            var email = $('.email').val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ url('send-email-link') }}',
                data: {
                    mail_title: mail_title,
                    mail_body: mail_body,
                    email: email,
                    mail_quotation_id: mail_quotation_id,
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



