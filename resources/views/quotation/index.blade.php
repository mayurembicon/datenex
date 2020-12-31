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
                            Quotation </h5>
                        <!--end::Page Title-->

                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Transaction </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Quotation </a>
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
                    <a href="{{route('quotation.create')}}" class="btn btn-light-primary font-weight-bolder btn-sm">
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
                    <form action="{{ url('search-quotation') }}" method="POST" role="search">
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
                            <a href="{{ url('clear-quotation') }}"
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
                    <table class="table table-bordered table-hover table-checkable mt-10  col-form-label" >
                        <thead>
                        <tr>
                            <th> Inquiry Date</th>
                            <th>Qtn No</th>
                            <th> Rating</th>
                            <th> Quotation Date</th>
                            <th> Company Name</th>
                            <th> Contact Person</th>
                            <th> Mobile</th>
                            <th> Next F.Up Date</th>
                            <th> Status</th>
                            <th> Print</th>
                            <th>Telegram</th>
                            <th>Mail</th>
                            <th>Update</th>
                            <th>Delete</th>

                        </tr>
                        </thead>
                        <tbody>


                        @foreach($quotation as $key=>$value)

                            <tr>
                                @if($value->i_date)
                                    <td>{{date('d-m-Y',strtotime($value->i_date))}}</td>
                                @else
                                    <td class="text-center">-</td>
                                @endif
                                    <td>{{isset($value->display_quotation_no)?$value->display_quotation_no:''}}</td>
                                @if($value->ratedIndex)

                                    <td><span class="svg-icon svg-icon-danger svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\General\Star.svg--><svg
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
</svg><!--end::Svg Icon--></span>{{($value->ratedIndex)}}</td>
                                @else
                                    <td class="text-center">-</td>
                                @endif

                                <td>{{date('d-m-Y',strtotime($value->q_date))}}</td>

                                <td>{{$value->company_name}}</td>
                                <td>{{$value->contact_person}}</td>
                                <td>{{$value->phone_no}}</td>
                                <td>{{$value->n_f_d}}</td>


                                @if($value->quotation_status=='Pending')
                                    <td>
                                        <div class="example-preview">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">Status
                                                </button>

                                                <div class="dropdown-menu customClassForDropDown">
                                                    <a class="dropdown-item get-customer"
                                                       href="{{url('make-pi',$value->quotation_id)}}"> Make Pi</a>
                                                    <a class="dropdown-item"
                                                       href="{{url('make-sales',[$value->quotation_id,'Quotation'])}}">
                                                        Make
                                                        Sales</a>
                                                    <a class="dropdown-item view-model" data-toggle="modal"
                                                       data-id="{{ $value->quotation_id }}"
                                                       data-target="#exampleModal"> Follow Up</a>
                                                    <a class="dropdown-item timeline-model" data-toggle="modal"
                                                       data-id="{{ $value->quotation_id }}"
                                                       data-target="#timeLine"> TimeLine</a>
                                                    {{--                                                    <a class="dropdown-item " target="_blank"--}}
                                                    {{--                                                       href="{{url('q-timeline', $value->quotation_id)}}"> TimeLine</a>--}}


                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a target="_blank"
                                           href="{{url('quotation-print',$value->quotation_id)}}"
                                           class="btn btn-info btn-sm">Print</a>
                                    </td>
                                        <td>
                                            <a href="{{url('send-quotation-telegram-msg',$value->quotation_id)}}">
                                                <img src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/media/logos/telegram.svg'}}" width="40" height="40" > </a>
                                        </td>
                                    <td>
                                        <a href="#" data-toggle="modal"
                                           data-id="{{ $value->quotation_id }}"
                                           data-target="#mailModel"
                                           class="btn btn-info btn-sm view-mail">
                                            <i class="fa fa-envelope"></i></a>
                                    </td>
                                    <td>
                                        <a href="{{route('quotation.edit',$value->quotation_id)}}"
                                           class="btn btn-warning btn-sm">
                                            <i class="fa fa-edit"></i></a>

                                    </td>
                                    <td>
                                        <form action="{{route('quotation.destroy',$value->quotation_id)}}"
                                              method="post">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger " onclick="return confirm('Are you sure  want to delete?')">
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
                                                        aria-expanded="false">{{$value->quotation_status}}
                                                </button>

                                                <div class="dropdown-menu">
                                                    @if($value->quotation_status=='Pi Created' || $value->quotation_status=='Sales Created' )
                                                        <a class="dropdown-item timeline-model" data-toggle="modal"
                                                           data-id="{{ $value->quotation_id }}"
                                                           data-target="#timeLine"> TimeLine</a>
                                                        {{--                                                    <a class="dropdown-item " target="_blank"--}}
                                                        {{--                                                       href="{{url('q-timeline', $value->quotation_id)}}"> TimeLine</a>--}}

                                                    @endif

                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                    <td>  <a target="_blank" class="btn btn-info btn-sm "
                                             href="{{url('quotation-print',$value->quotation_id)}}">
                                            Print</a></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                @endif
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                    </div>
                    {{ $quotation->links() }}

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
                    <input type="hidden" id="quotation_id" name="quotation_id" value="">

                    <div class="form-group row">
                        <label class="col-lg-12 col-form-label">Company Name </label>
                        <div class="col-lg-6">
                            <input type="text" disabled id="company_id"
                                   class="form-control select2-control"
                                   name="quotation_id" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-12 col-form-label ">Customer Name :</label>
                        <div class="col-lg-6">

                            <input type="text" name="quotation_id" disabled
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
                    <button type="button" class="btn btn-primary font-weight-bold" onclick="QuotationSaveFollowup()">
                        Save

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
                <form action="{{ url('send-email') }}"
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
                        <input type="hidden" id="quotation_id" name="quotation_id" value="">

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
        src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/js/pages/crud/forms/widgets/select2.js'}}"></script>
    <script
        src="{{ env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.5'}}"></script>


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
            var C = $(this).data('quotation_id');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ url('get-timeline')}}',
                type: 'POST',

                data: {quotation_id: ID},
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
            var C = $(this).data('quotation_id');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ url('get-customer')}}',
                type: 'POST',

                data: {quotation_id: ID},
                success: function (data) {
                    setInterval('refreshPage()', 5000);
                    $('#modal-title').html($('#item_name_' + ID).html());
                    $("#company_id").val(data.customer_name);
                    $("#contact_id").val(data.contact_person);
                    $("#quotation_id").val(data.quotation_id);

                }
            });
        });

        $(document).on("click", ".view-mail", function () {
            var ID = $(this).data('id');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ url('get-customer')}}',
                type: 'POST',

                data: {quotation_id: ID},
                success: function (data) {
                    $("#mail_quotation_id").val(data.quotation_id);
                    $("#emails").val(data.email);

                }
            });
        });

        function QuotationSaveFollowup() {
            var remark = ($('textarea[name="remark"]').val());
            var next_followup_date = ($('input[name="next_followup_date"]').val());
            var quotation_id = ($('input[name="quotation_id"]').val());
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ url('quotation-follow-up') }}',
                data: {

                    remark: remark,
                    next_followup_date: next_followup_date,
                    quotation_id: quotation_id,

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
                            $('#quotation_id').val('');
                            location.reload();
                            // Swal.fire({
                            //
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


        function QuotationMail() {
            var mail_title = $('.mail_title').val();
            var mail_body = $('.mail_body').html();
            alert(mail_body);
            return false;
            var email = $('.email').val();
            var mail_quotation_id = ($('input[name="mail_quotation_id"]').val());
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ url('send-email') }}',
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
                            $('#mail_quotation_id').val('');
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
                        table_name: 'quotation',
                        field_name: 'contact_person'
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
                        table_name: 'quotation',
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
                        table_name: 'quotation',
                        field_name: 'phone_no'
                    };
                }, processResults: function (response) {
                    return {results: response};
                }, cache: true
            }
        });

    </script>

@endpush



