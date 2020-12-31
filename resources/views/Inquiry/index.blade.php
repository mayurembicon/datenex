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
                            Inquiry </h5>
                        <!--end::Page Title-->

                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Transaction </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Inquiry </a>
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
                    <a href="{{route('inquiry.create')}}" class="btn btn-light-primary font-weight-bolder btn-sm">
                        <i class="flaticon-add-circular-button"></i> Add New
                    </a>

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

                    <form action="{{ url('search-inquiry') }}" method="POST" role="search">
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
                                    class="form-control item-select2 @error('item_id') is-invalid @enderror "
                                    name="item_id">
                                    <?php if(!empty($selectedItem)){ ?>
                                    <option value="<?php echo $selectedItem; ?>"><?php echo $selectedItem; ?></option>
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
                            <a href="{{ url('clear-inquiry') }}"
                               class="btn btn-sm btn-danger @error('name') is-invalid @enderror">
                                <i class="fas fa-eraser"></i> Clear</a>

                        </div>
                    </form>
                </div>
            </div>
            <br>
            <div class="card card-custom">

                <div class="card-body">
                    <div class=" table-responsive">
                    <!--begin: Datatable-->
                    <table class="table table-bordered table-hover table-checkable mt-10 col-form-label">
                        <thead>

                        <tr>
                            <th>Inq No</th>
                            <th>Date</th>
                            <th>Rating</th>
                            <th>Inquiry Form</th>
                            <th>Company Name</th>
                            <th>Contact Person</th>
                            <th>Subject</th>
                            <th>Phone No</th>
                            <th>Status</th>
                            <th>Telegram</th>
                            <th>Update</th>
                            <th>Delete</th>


                        </tr>
                        </thead>
                        <tbody>

                        @foreach($inquiry as $key=>$value)
                            <tr>
                                <td>{{$value->inquiry_id}}</td>
                                <td>{{date('d-m-Y',strtotime($value->date))}}</td>

                                <td>
                                            <span class="svg-icon svg-icon-danger svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\General\Star.svg--><svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                    height="24px"
                                                    viewBox="0 0 24 24" version="1.1">
        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
            <polygon points="0 0 24 0 24 24 0 24"/>
            <path
                d="M12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.3476862,4.32173209 11.9473121,4.11839309 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 Z"
                fill="#000000"/>
        </g>
    </svg><!--end::Svg Icon--></span>{{$value->ratedIndex}}</td>
                                <td>{{ isset($value->inquiry_from)?$value->inquiry_from:''}}</td>
                                <td>{{ isset($value->company_name)?$value->company_name:'' }}</td>
                                <td>{{$value->contact_person}}</td>
                                <td>{{$value->subject}}</td>

                                <td>{{$value->phone_no}}</td>

                                @if($value->inquiry_status=='Pending')
                                    <td>
                                        <div class="example-preview">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">Status
                                                </button>
                                                <div class="dropdown-menu customClassForDropDown">
                                                    <a class="dropdown-item get-customer"
                                                       href="{{url('make-quotation',$value->inquiry_id)}}"> Make
                                                        Quotation</a>
                                                    <a class="dropdown-item view-model" data-toggle="modal"
                                                       data-id="{{ $value->inquiry_id }}"
                                                       data-target="#exampleModal"> Follow Up</a>
                                                    <a class="dropdown-item assign-model" data-toggle="modal"
                                                       data-id="{{ $value->inquiry_id }}"
                                                       data-target="#Assign"> Assign</a>
                                                    <a class="dropdown-item timeline-model" data-toggle="modal"
                                                       data-id="{{ $value->inquiry_id}}"
                                                       data-target="#timeLine"> TimeLine</a>
                                                    <a class="rating-model dropdown-item " data-toggle="modal"
                                                       data-id="{{ $value->inquiry_id }}"
                                                       data-target="#Rating"> Rating</a>
                                                    <a class="dropdown-item "
                                                       href="{{url('inquiry-close',$value->inquiry_id)}}"
                                                       onclick="return confirm('Are you sure  want to Inquiry Close?')">
                                                        Inquiry Closed

                                                    </a>


                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <a href="{{url('send-telegram-msg',$value->inquiry_id)}}">
                                            <img src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/media/logos/telegram.svg'}}" width="40" height="40" ></a>
                                    </td>
                                    <td>
                                        <a href="{{route('inquiry.edit',$value->inquiry_id)}}"
                                           class="btn btn-warning btn-sm">
                                            <i class="fa fa-edit"></i></a>
                                    </td>

                                    <td>
                                        <form action="{{route('inquiry.destroy',$value->inquiry_id)}}" method="post">
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
                                                        aria-expanded="false">{{$value->inquiry_status}}
                                                </button>
                                                    <div class="dropdown-menu">
                                                        @if($value->inquiry_status=='Inquiry Close')
                                                            <a class="dropdown-item"
                                                               href="{{url('inquiry-active',$value->inquiry_id)}}" onclick="return confirm('Are you sure  want to Inquiry Active?')">
                                                            Inquiry Active
                                                        </a>
                                                        @endif
                                                        @if($value->inquiry_status=='Inquiry Close' || $value->inquiry_status=='Quotation Created')
                                                            <a class="dropdown-item timeline-model" data-toggle="modal"
                                                               data-id="{{ $value->inquiry_id}}"
                                                               data-target="#timeLine"> TimeLine</a>
                                                        @endif
                                                    </div>

                                            </div>
                                        </div>


                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                @endif
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                    </div>
                    {{ $inquiry->links() }}
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
                    <input type="hidden" id="inquiry_id" name="inquiry_id" value="">

                    <div class="form-group row">
                        <label class="col-lg-12 col-form-label">Company Name </label>
                        <div class="col-lg-6">
                            <input type="text" disabled id="company_id"
                                   class="form-control select2-control"
                                   name="inquiry_id" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-12 col-form-label ">Customer Name :</label>
                        <div class="col-lg-6">

                            <input type="text" name="inquiry_id" disabled
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

    <div class="modal fade" id="Assign" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Assign
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="inquiry_id" name="inquiry_id" value="">

                    <div class="form-group row">
                        <label class="col-lg-12 col-form-label"> Current Assign User </label>
                        <div class="col-lg-6">
                            <input class="form-control" id="last_user" disabled>


                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-12 col-form-label">Assign User </label>
                        <div class="col-lg-12">
                            <select class="form-control select2-control @error('user_id') is-invalid @enderror"
                                    id="user_id" name="user_id">
                                <option value="">
                                    select User
                                </option>
                                @foreach($user as $key=>$value)
                                    <option
                                        value="{{$value->id}}">
                                        {{$value->name}}</option>
                                @endforeach

                            </select>
                            <div id="user_id_alert" class="invalid-feedback" role="alert"></div>
                            @error('user_id')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Close
                    </button>
                    <button type="button" class="btn btn-primary font-weight-bold" onclick="saveAssign()">Save

                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="Rating" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="staticBackdrop" aria-hidden="true">

        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <input type="hidden" id="rating_inquiry_id" name="rating_inquiry_id" value="">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Rating
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">


                    <div align="center">
                        <i class="fa fa-star fa-2x" data-index="0"></i>
                        <i class="fa fa-star fa-2x" data-index="1"></i>
                        <i class="fa fa-star fa-2x" data-index="2"></i>
                        <i class="fa fa-star fa-2x" data-index="3"></i>
                        <i class="fa fa-star fa-2x" data-index="4"></i>
                        <br><br>

                    </div>


                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Close
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
                        <input type="hidden" id="inquiry_id" name="inquiry_id" value="">

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


@endsection

@push('styles')
    <style>
        .customClassForDropDown
        {
            height: 150px;
            overflow-y: auto;
        }
        </style>
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
            var inquiry_id = ($('#rating_inquiry_id').val());
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ url('rating') }}',
                data: {

                    ratedIndex: ratedIndex,
                    inquiry_id: inquiry_id,

                },

                success: function (r) {

                    uID = r.id;
                    localStorage.setItem('uID', uID);
                    $('#Rating').modal('toggle');
                    location.reload();
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
        $(document).on("click", ".view-model", function () {
            var ID = $(this).data('id');
            var C = $(this).data('inquiry_id');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ url('get-composition-items')}}',
                type: 'POST',

                data: {inquiry_id: ID},
                success: function (data) {
                    setInterval('refreshPage()', 5000);
                    $('#modal-title').html($('#item_name_' + ID).html());
                    $("#company_id").val(data.customer_name);
                    $("#contact_id").val(data.contact_person);
                    $("#inquiry_id").val(data.inquiry);

                }
            });
        });
        $(document).on("click", ".rating-model ", function () {
            var clickedInquiryID = $(this).data('id');
            $("#rating_inquiry_id").val(clickedInquiryID);

        });
    </script>
    <script>
        $(document).on("click", ".assign-model", function () {
            var ID = $(this).data('id');
            $("#inquiry_id").val(ID);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ url('get-last-user')}}',
                type: 'POST',
                data: {inquiry_id: ID},
                success: function (data) {
                    $("#last_user").val(data.last_user);

                }
            });


        });
    </script>

    <script>
        function saveFollowup() {
            var remark = ($('textarea[name="remark"]').val());
            var next_followup_date = ($('input[name="next_followup_date"]').val());
            var inquiry_id = ($('input[name="inquiry_id"]').val());
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ url('follow-up') }}',
                data: {

                    remark: remark,
                    next_followup_date: next_followup_date,
                    inquiry_id: inquiry_id,

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
                            $('#inquiry_id').val('');
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
        function saveAssign() {
            var user_id = ($('select[name="user_id"]').val());
            var inquiry_id = ($('input[name="inquiry_id"]').val());
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ url('assign') }}',
                data: {

                    user_id: user_id,
                    inquiry_id: inquiry_id,

                },
                success: function (data) {
                    if (data.error) {
                        $.each(data.error, function (key, value) {
                            $("#" + key).toggleClass("is-invalid");
                            $("#" + key + '_alert').html(value);
                        });
                    } else {
                        if (data.success) {
                            $('#Assign').modal('toggle');
                            $('#user_id').val('');
                            // $("#user_id").toggleClass("is-invalid");
                            $('#inquiry_id').val('');
                            // Swal.fire({
                            //     position: "center",
                            //     icon: "success",
                            //     title: "User Assign  successfully.",
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
    <!--end::Page Vendors-->
    <!--begin::Page Scripts(used by this page)-->
    <script>
        $('#kt_datatable').DataTable({});

    </script>
    <script>
        $('#kt_datatable').DataTable({});

        $(document).on("click", ".timeline-model", function () {
            var ID = $(this).data('id');
            var C = $(this).data('inquiry_id');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ url('get-inquiry-timeline')}}',
                type: 'POST',

                data: {inquiry_id: ID},
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
                        table_name: 'inquiry',
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
                        table_name: 'inquiry',
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
                        table_name: 'inquiry',
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
                        table_name: 'inquiry',
                        field_name: 'phone_no'
                    };
                }, processResults: function (response) {
                    return {results: response};
                }, cache: true
            }
        });

    </script>


@endpush



