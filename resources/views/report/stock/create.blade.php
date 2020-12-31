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
                            <h3 class="card-title">Stock </h3>
                            <div class="card-toolbar">
                                <div class="example-tools justify-content-center">

                                    <span class="example-copy" data-toggle="tooltip" title=""
                                          data-original-title="Copy code"></span>
                                </div>
                            </div>
                        </div>

                        <!--begin::Form-->
                        <form action="{{route('stock.store')}}" method="post">
                            @csrf

                            <div class="card-body">
                                <div class="mb-1">


                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">Date Range</label>
                                        <div class="col-lg-6">
                                            <input class="form-control @error('date_range') is-invalid @enderror "
                                                   name="date_range" id="kt_daterangepicker_1"
                                                   readonly="readonly" placeholder="Select time"
                                                   value="{{(isset($date_range))?$date_range:''}}  {{ (old('date_range')?old('date_range'): '') }}"
                                                   type="text">
                                            @error('date_range')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label"></label>
                                        <div class="col-9 col-form-label">
                                            <div class="radio-inline">

                                                <label for="chkYes" class="radio  col-form-label ">
                                                    <input type="radio" id="chkYes" name="item"
                                                           @error('item_id') checked @enderror
                                                           value="itemwise" {{(!empty($selectedItem))&& ($selectedItem == 'itemwise')?'checked':'' }}/><span></span>Itemwise
                                                </label>

                                            </div>
                                        </div>
                                    </div>


                                    <div id="itemWise" style="display: none" class="form-group row">
                                        <label class="col-lg-3 col-form-label">Item Name</label>
                                        <div class="col-lg-6">
                                            <select
                                                class="form-control select2-control  @error('item_id') is-invalid @enderror"
                                                style="width: 100%"
                                                multiple="multiple"
                                                name="item_id[]">

                                                <option value="A">All</option>

                                                @foreach($item as $key=>$value)
                                                    <option
                                                        value="{{$value->item_id}}" {{(isset($itemMasterID) &&  in_array($value->item_id,$itemMasterID))?'selected':''}}> {{$value->name}} </option>
                                                @endforeach
                                            </select>
                                            @error('item_id')
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
                    <div class="col-lg-12">
                        <!--begin::Card-->
                        <div class="card card-custom">
                            <div class="card-body">

                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-checkable mt-10"
                                           id="kt_datatable">
                                        <thead>
                                        <tr>
                                            <th rowspan="2">Item Name</th>
                                            <th rowspan="2">Opening Stock</th>
                                            <th colspan="2">Stock In</th>
                                            <th rowspan="2">Total</th>
                                            <th colspan="2">Stock Out</th>
                                            <th rowspan="2">Closing Stock</th>
                                        </tr>
                                        <tr>
                                            <th>Purchase</th>
                                            <th>Sale Return</th>
                                            <th>Sales</th>
                                            <th>Purchase Return</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($itemSummaryQuery as $key=>$value)
                                            @php

                                                $opening_stock = $value->opening_stock + $value->purchase_opening+ $value->salesReturnOpening - $value->sales_opening - $value->purchaseReturnOpening;
                                                $current_purchase = $value->current_purchase;
                                                $current_sales_return = $value->currentSalesReturn;
                                                $total = $opening_stock + $current_purchase+$current_sales_return;
                                                $current_sales = $value->current_sales;
                                                $current_purchase_return = $value->currentPurchaseReturn;
                                                $closing = $total - $current_sales-$current_purchase_return;
                                            @endphp

                                            <td>{{$value->item_name}}</td>
                                            <td> {{$value->opening_stock}}</td>
                                            <td>{{isset($value->current_purchase)?$value->current_purchase:0}}</td>
                                            <td>{{isset($value->currentSalesReturn)?$value->currentSalesReturn:0}}</td>
                                            <td>{{$total}}</td>
                                            <td>{{isset($value->current_sales)?$value->current_sales:0}}</td>
                                            <td>{{isset($value->currentPurchaseReturn)?$value->currentPurchaseReturn:0}}</td>
                                            <td>{{$closing}}</td>



                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <!--end: Datatable-->
                                </div>


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
    <link href="{{env('ASSET_URL').'assets/plugins/custom/datatables/datatables.bundle.css?v=7.0.5'}}" rel="stylesheet"
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
                if ($("#chkYes").is(":checked")) {
                    $("#itemWise").show();
                } else {
                    $("#itemWise").hide();
                }
            });
        });


    </script>
    <script>
        @php
            if(!empty($selectedItem)&&$selectedItem == 'itemwise'){
        @endphp
        $("#itemWise").show();

        @php
            }
        @endphp
        @error('item_id')   $("#itemWise").show(); @enderror

    </script>
    <script src="https://cdn.datatables.net/buttons/1.6.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src=" https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src=" https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src=" https://cdn.datatables.net/buttons/1.6.3/js/buttons.html5.min.js"></script>
@endpush

