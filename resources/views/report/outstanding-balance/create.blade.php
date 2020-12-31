@extends('layouts.app')
@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="subheader py-2 py-lg-4 subheader-transparent" id="kt_subheader">
            <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <!--begin::Page Heading-->
                    <div class="d-flex align-items-baseline mr-5">
                        <!--begin::Page Title-->
                        <h5 class="text-dark font-weight-bold my-2 mr-5 ml-10">Outstanding Balance</h5>
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
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 text-center">

                    <form action="{{url('customer-deteils-report')}}" method="post">
                        @csrf
                    </form>

                </div>
            </div>
            @if(!empty($customers))
                <div class="container-fluid">
                    <div class="row">

                        <div class="col-lg-10">

                            <div class="card card-custom">

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-checkable mt-8"
                                               id="kt_datatable">
                                            <thead>

                                            <tr>

                                                <th>Customer Name</th>
                                                <th>Amount</th>
                                                <th>Type</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                                $creditAmount=0;

                                                $debitAmount=0;
                                            @endphp
                                            @foreach($customers as $item)
                                                @php
                                                    $opening = $item->openingBalance;
                                                    $openingType = $item->openingBalanceType;

                                                     $credit = $item->totalCredit;
                                                $debit = $item->totalDebit;
                                                $totalCredit = ($openingType == 'C') ? ($opening + $credit) : $credit;
                                                $totalDebit = ($openingType == 'D') ? ($opening + $debit) : $debit;
                                                if ($totalCredit > $totalDebit) {
                                                    $outstandingType = 'C';
                                                    $outstandingAmount = $totalCredit - $totalDebit;
                                                }

                                                elseif ($totalCredit < $totalDebit) {
                                                    $outstandingType = 'D';
                                                    $outstandingAmount = $totalDebit - $totalCredit;
                                                }
                                                else{
                                                  $outstandingType = 'C';
                                                  $outstandingAmount = 0;
                                                }



                                                @endphp
                                                <tr>
                                                    <td class="">
                                                        <a href="{{url('customer-deteils/'.$item->customer_id)}}">{{$item->customer_name}}</a>
                                                    </td>
                                                    <td class="text-left">
                                                        {{(number_format($outstandingAmount,3))}}
                                                    </td>
                                                    <td class="text-left">
                                                        {{($outstandingType == 'C') ? 'Credit' : 'Debit'}}
                                                    </td>

                                                </tr>
                                                {{--                                                @php--}}
                                                {{--                                                    $creditAmount+=$outstandingAmount;--}}


                                                {{--                                                @endphp--}}
                                            @endforeach
                                            </tbody>
                                            {{--                                            <tfoot>--}}
                                            {{--                                            <tr>--}}
                                            {{--                                                <th class="text-right">Total</th>--}}

                                            {{--                                                <td class="text-right">{{number_format($creditAmount,2)}}</td>--}}
                                            {{--                                            </tr>--}}
                                            {{--                                            </tfoot>--}}

                                        </table>
                                        <!--end: Datatable-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        @endif

    </div>

    <!--end::Card-->
@endsection

@push('styles')
    <link
        href="{{ env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/datatables/datatables.bundle.css?v=7.0.5'}}"
        rel="stylesheet" type="text/css"/>
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link href="https://cdn.datatables.net/buttons/1.6.3/css/buttons.dataTables.min.css">
@endpush
@push('scripts')
    <script
        src="{{ env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.5'}}"></script>

    <script src="https://cdn.datatables.net/buttons/1.6.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src=" https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src=" https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src=" https://cdn.datatables.net/buttons/1.6.3/js/buttons.html5.min.js"></script>
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
                ordering: false,
                bPaginate: false,
                buttons: [
                    'copyHtml5',
                    {
                        extend: 'excelHtml5',
                        text: 'Excel',
                        title: 'Outstanding Report',
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        footer: true,
                        title: 'Outstanding Report',
                        orientation: 'portrait',

                        exportOptions: {
                            stripNewlines: false
                        },
                        customize: function (doc) {
                            doc.defaultStyle.alignment = 'right';
                            doc.styles.tableHeader.alignment = 'right';
                            doc.content[1].table.widths =
                                Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                        }
                    }
                ],
            });
        });

    </script>




@endpush

