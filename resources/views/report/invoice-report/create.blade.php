@extends('layouts.app')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="subheader py-2 py-lg-4 subheader-transparent" id="kt_subheader">
            <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <!--begin::Page Heading-->
                    <div class="d-flex align-items-baseline mr-5">
                        <!--begin::Page Title-->
                        <h5 class="text-dark font-weight-bold my-2 mr-5">Report</h5>
                        <!--end::Page Title-->
                        <!--begin::Breadcrumb-->
                        <!--end::Breadcrumb-->
                    </div>
                    <!--end::Page Heading-->
                </div>
                <!--end::Info-->
                <!--begin::Toolbar-->

                <!--end::Toolbar-->
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-lg-8 text-center">
                    <div class="card card-custom gutter-b example example-compact ">
                        <div class="card-header">
                            <h3 class="card-title">Invoice Report</h3>
                            <div class="card-toolbar">
                                <div class="example-tools justify-content-center">

                                    <span class="example-copy" data-toggle="tooltip" title=""
                                          data-original-title="Copy code"></span>
                                </div>
                            </div>
                        </div>

                        <!--begin::Form-->
                        <form action="{{route('report-invoice.store')}}" method="post">
                            @csrf

                            <div class="card-body">
                                <div class="mb-1">


                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Date Range</label>
                                        <div class="col-lg-6">
                                            <input class="form-control @error('date_range') is-invalid @enderror" type="text"
                                                   name="date_range" id="kt_daterangepicker_1"
                                                   readonly="readonly" placeholder="Select time"
                                                   value="{{(isset($date_range))?$date_range:''}}  {{ (old('date_range')?old('date_range'): '') }}">
                                            @error('date_range')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label"></label>
                                        <div class="col-9 col-form-label">
                                            <div class="radio-inline">
                                                <label class="radio  col-form-label">
                                                    <input type="radio" name="item" id="customer_wise"  @error('customer_id') checked @enderror
                                                    value="customer_wise" {{(!empty($selectedItem))&& ($selectedItem == 'customer_wise')?'checked':'' }}/><span></span>Company
                                                    Wise
                                                </label>

                                                <label class="radio  col-form-label">
                                                    <input type="radio" name="item" id="product_wise"  @error('item_id') checked @enderror
                                                    value="product_wise" {{(!empty($selectedItem))&& ($selectedItem == 'product_wise')?'checked':'' }}/><span></span>Product
                                                    Wise

                                                </label>
                                                <label class="radio  col-form-label">
                                                    <input type="radio" id="user_wise" name="item"   @error('user_id') checked @enderror
                                                    value="user_wise" {{(!empty($selectedItem))&& ($selectedItem == 'user_wise')?'checked':'' }}/><span></span>User
                                                    Wise

                                                </label>

                                            </div>
                                        </div>
                                    </div>



                                    <div id="Customer_Wise" style="display: none" class="form-group row">
                                        <label class="col-lg-3 col-form-label">Company Name</label>
                                        <div class="col-lg-6">
                                            <select
                                                class="form-control select2-control  @error('customer_id') is-invalid @enderror" style="width: 100%"
                                                multiple="multiple"
                                                name="customer_id[]">

                                                <option value="A" >All</option>

                                                @foreach($customer as $key=>$value)
                                                    <option
                                                        value="{{$value->customer_id}}" {{(isset($customerID) &&  in_array($value->customer_id,$customerID))?'selected':''}}> {{$value->company_name}} </option>
                                                @endforeach
                                            </select>
                                            @error('customer_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror

                                        </div>
                                    </div>
                                    <div id="Product_Wise" style="display: none" class="form-group row">
                                        <label class="col-lg-3 col-form-label">Product Name</label>
                                        <div class="col-lg-6">
                                            <select
                                                class="form-control select2-control @error('item_id') is-invalid @enderror" style="width: 100%"
                                                name="item_id[]" multiple="multiple">
                                                <option value="A" >All</option>

                                                @foreach($item as $key=>$value)
                                                    <option
                                                        value="{{$value->item_id}}" {{(isset($itemID) &&  in_array($value->item_id,$itemID))?'selected':''}}>{{$value->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('item_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror

                                        </div>
                                    </div>
                                    <div id="User_Wise" style="display: none" class="form-group row">
                                        <label class="col-lg-3 col-form-label">User Name</label>
                                        <div class="col-lg-6">
                                            <select
                                                class="form-control select2-control  @error('user_id') is-invalid @enderror" style="width: 100%"
                                                name="user_id[]" multiple="multiple">
                                                <option value="A" >All</option>

                                                @foreach($user as $key=>$value)
                                                    <option
                                                        value="{{$value->id}}" {{(isset($userID) &&  in_array($value->id,$userID))?'selected':''}}>{{$value->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('user_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror


                                        </div>
                                    </div>

                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col-lg-3"></div>
                                            <div class="col-lg-1"></div>

                                            <button type="submit" class="btn btn-danger mr-3">Submit
                                            </button>
                                            <button type="reset" class="btn btn-secondary "><i
                                                    class="ki ki-bold-close icon-md"></i> Reset
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @if(!empty($itemSummaryQuery))
                <div class="row">
                    <!--begin::Notice-->
                    <!--end::Notice-->
                    <!--begin::Card-->
                    <div class="col-lg-8">
                        <!--begin::Card-->
                        <div class="card card-custom">
                            <div class="card-body">
                                @if($selectedItem=='customer_wise'  )
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-checkable mt-10"
                                               id="kt_datatable">
                                            <thead>
                                            <tr>

                                                <th>Invoice No</th>
                                                <th>Date</th>
                                                <th>Company Name</th>
                                                <th>Ref Order No</th>


                                            </tr>
                                            </thead>
                                            <tbody>

                                            @foreach($itemSummaryQuery as $key=>$value)

                                                <tr>
                                                    <td class="text-center">{{$value->invoice_no }}</td>
                                                    @if($value->invoice_date)
                                                        <td>{{date('d-m-Y',strtotime($value->invoice_date))}}</td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                    <td>{{$value->company_name }}</td>

                                                    <td>{{$value->ref_order_no }}</td>


                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        <!--end: Datatable-->
                                    </div>
                                @elseif($selectedItem=='product_wise' )
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-checkable mt-10"
                                               id="kt_datatable">
                                            <thead>
                                            <tr>

                                                <th>Invoice No</th>
                                                <th>Date</th>
                                                <th>Item Name</th>
                                                <th>Qty</th>
                                                <th>Amount</th>


                                            </tr>
                                            </thead>
                                            <tbody>

                                            @foreach($itemSummaryQuery as $key=>$value)
                                                <tr>
                                                    <td class="text-center">{{$value->invoice_no }}</td>
                                                    @if($value->invoice_date)
                                                        <td class="text-center" >{{date('d-m-Y',strtotime($value->invoice_date))}}</td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                    <td>{{$value->item_name }}</td>

                                                    <td class="text-center">{{$value->sumQty }}</td>
                                                    <td class="text-right">{{$value->totalAmount }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        <!--end: Datatable-->
                                    </div>
                                @elseif($selectedItem=='user_wise')
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-checkable mt-10"
                                               id="kt_datatable">
                                            <thead>
                                            <tr>


                                                <th>Invoice No</th>
                                                <th>Date</th>
                                                <th>User Name</th>
                                                <th>Ref Order No</th>

                                            </tr>
                                            </thead>
                                            <tbody>

                                            @foreach($itemSummaryQuery as $key=>$value)

                                                <td class="text-center">{{$value->invoice_no }}</td>
                                                @if($value->invoice_date)
                                                    <td class="text-center">{{date('d-m-Y',strtotime($value->invoice_date))}}</td>
                                                @else
                                                    <td></td>
                                                @endif
                                                <td>{{$value->name }}</td>


                                                <td>{{$value->ref_order_no }}</td>


                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        <!--end: Datatable-->
                                    </div>

                                @endif
                            </div>
                        </div>
                    </div>


                </div>
            @endif


        </div>


    </div>






    <!--end::Card-->
@endsection

@push('styles')
    <link href="{{ env('ASSET_URL').'assets/plugins/custom/datatables/datatables.bundle.css?v=7.0.5'}}" rel="stylesheet"
          type="text/css"/>
@endpush
@push('scripts')
    <script src="{{ env('ASSET_URL').'assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.5'}}"></script>
    <script>
        $('#kt_daterangepicker_1').daterangepicker({
            buttonClasses: ' btn',
            format: 'dd-mm-YYYY',
            applyClass: 'btn-primary',
            cancelClass: 'btn-secondary',
            locale: {
                format: 'DD/MM/YYYY'
            }
        }, function (start, end, label) {
            $('#kt_daterangepicker_1 .form-control').val(start.format('DD-MM-YYYY') + ' / ' + end.format('DD-MM-YYYY'));
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#kt_datatable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            });
        });


    </script>


@endpush
@push('styles')
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link href="https://cdn.datatables.net/buttons/1.6.3/css/buttons.dataTables.min.css">
@endpush
@push('scripts')
    <script>
        $('.select2-control').select2({
            placeholder: "Select ",
            allowClear: true
        });
    </script>
    <script>
        $(function () {
            $("input[name='item']").click(function () {
                if ($("#customer_wise").is(":checked")) {
                    $("#Customer_Wise").show();
                } else {
                    $("#Customer_Wise").hide();
                }
            });
        });
        $(function () {
            $("input[name='item']").click(function () {
                if ($("#product_wise").is(":checked")) {
                    $("#Product_Wise").show();
                } else {
                    $("#Product_Wise").hide();
                }
            });
        });
        $(function () {
            $("input[name='item']").click(function () {
                if ($("#user_wise").is(":checked")) {
                    $("#User_Wise").show();
                } else {
                    $("#User_Wise").hide();
                }
            });
        });
    </script>
    <script>
        @php
            if(!empty($selectedItem)&&$selectedItem == 'customer_wise'){
        @endphp
        $("#Customer_Wise").show();
        @php
            }
        @endphp
        @php
            if(!empty($selectedItem)&&$selectedItem == 'product_wise'){
        @endphp
        $("#Product_Wise").show();
        @php
            }
        @endphp
        @php
            if(!empty($selectedItem)&&$selectedItem == 'user_wise'){
        @endphp
        $("#User_Wise").show();
        @php
            }
        @endphp
        @error('customer_id')   $("#Customer_Wise").show(); @enderror
        @error('user_id')   $("#User_Wise").show(); @enderror
        @error('item_id')   $("#Product_Wise").show(); @enderror
    </script>
    <script src="https://cdn.datatables.net/buttons/1.6.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src=" https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src=" https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src=" https://cdn.datatables.net/buttons/1.6.3/js/buttons.html5.min.js"></script>
@endpush

