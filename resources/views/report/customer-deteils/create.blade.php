@extends('layouts.app')
@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="subheader py-2 py-lg-4 subheader-transparent" id="kt_subheader">
            <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">
                    <!--begin::Page Heading-->
                    <div class="d-flex align-items-baseline mr-5 ml-5">
                        <!--begin::Page Title-->
                        <h5 class="text-dark font-weight-bold my-3 mr-5">Ledger Transaction of
                            :<strong>{{$ledgerInfo->customer_name}}</strong></h5>
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

                    <form action="{{url('customer-deteils')}}" method="post">
                        @csrf
                    </form>

                </div>
            </div>


            <div class="container-fluid">
                <div class="row">
                    @if(!empty($journal))
                        @php
                            $creditAmount = floatval(($openingBalance->opening_balance_type=='C'?$openingBalance->opening_balance:0));
                            $debitAmount = floatval(($openingBalance->opening_balance_type=='D'?$openingBalance->opening_balance:0));
                        @endphp
                        <div class="col-lg-12">
                            <div class="card card-custom">
                                <div class="card-body">

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-sm small"
                                               id="kt_datatable" width="100%">

                                            <thead>

                                            <tr>
                                                <th>Date</th>
                                                <th width="40%">Transaction Type</th>
                                                <th>Type</th>
                                                <th>Credit</th>
                                                <th>Debit</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>{{date('d-m-Y',strtotime($financial->start_date))}}</td>
                                                <td>Opening Balance</td>
                                                <td></td>
                                                <td class="text-right">{{$openingBalance->opening_balance_type=='C'?number_format($openingBalance->opening_balance,2):number_format(0,2)}}</td>
                                                <td class="text-right">{{$openingBalance->opening_balance_type=='D'?number_format($openingBalance->opening_balance,2):number_format(0,2)}}</td>
                                            </tr>


                                            @foreach($journal as $value)
                                                <tr>
                                                    @php
                                                        $credit =floatval(($value->type == 'C') ? $value->grand_total : 0);
                                                        $debit = floatval(($value->type == 'D') ? $value->grand_total    : 0);
                                                    @endphp

                                                    <td>{{date('d-m-Y',strtotime($value->date))}}</td>
                                                    <td>
                                                        {{$value->ref_type_description}}
                                                        @php $invoiceNo=$customerName=''; @endphp
                                                        @if($value->type=='C' && $value->ref_type=='PU')

                                                            @php $purchase=\App\Purchase::find($value->transaction_id);
                                                            echo '<strong>Customer.: </strong><span class="text-primary">'.$purchase->customer->customer_name.'</span>';
                                                            echo '<br/><strong>Bill No.: </strong><span class="text-primary">'.$purchase->bill_no.'</span>';
                                                            @endphp
                                                        @elseif($value->type=='D' && $value->ref_type=='SL')
                                                            @php
                                                                $sales=\App\Sales::find($value->transaction_id);
                                                                echo '<strong>Customer.: </strong><span class="text-primary">'.$sales->Customer->customer_name.'</span>';
                                                                echo '<br/><strong>Invoice No.: </strong><span class="text-primary">'.$sales->invoice_id.'</span>';
                                                            @endphp
                                                        @elseif($value->type=='C' && $value->ref_type=='SR')
                                                            @php
                                                                $salesreturn=\App\SalesReturn::find($value->transaction_id);
                                                                echo '<strong>Customer.: </strong><span class="text-primary">'.$salesreturn->Customer->customer_name.'</span>';
                                                                echo '<br/><strong>Sales Return No.: </strong><span class="text-primary">'.$salesreturn->sales_return_id.'</span>';
                                                            @endphp

                                                        @elseif($value->type=='D' && $value->ref_type=='PR')
                                                            @php
                                                                $purchaseReturn=\App\PurchaseReturn::find($value->transaction_id);
                                                               echo '<strong>Customer.: </strong><span class="text-primary">'.$purchaseReturn->Customer->customer_name.'</span>';
                                                                echo '<br/><strong>Purchase Return No.: </strong><span class="text-primary">'.$purchaseReturn->purchase_return_id.'</span>';
                                                            @endphp
                                                        @endif
                                                    </td>
                                                    <td>{{$value->transaction_type=='C'?'Cash':(($value->transaction_type=='B')?'Bank':'')}}</td>
                                                    <td class="text-right">{{number_format($credit,2)}}</td>
                                                    <td class="text-right">{{number_format($debit,2)}}</td>
                                                    @php
                                                        $creditAmount +=$credit;
                                                        $debitAmount += $debit;
                                                    @endphp
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th class="text-right"></th>
                                                <th class="text-right"></th>
                                                <th class="text-right">Total</th>
                                                <th class="text-right">{{number_format($creditAmount,2)}}</th>
                                                <th class="text-right">{{number_format($debitAmount,2)}}</th>
                                                @php
                                                    $outStanding = floatval(($creditAmount - $debitAmount));
                                                @endphp
                                            </tr>
                                            <tr>
                                                <th class="text-right"></th>
                                                <th class="text-right"></th>
                                                <th class="text-right">OutStanding</th>
                                                <th class="text-right">{{number_format(abs($outStanding),2)}}</th>
                                                <th class="text-right">{{(($outStanding > 0)?'Credit':'Debit')}}</th>
                                            </tr>
                                            </tfoot>

                                        </table>
                                        <!--end: Datatable-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>


        </div>


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
                        title: 'Ledger Transaction',
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'PDF',
                        footer: true,
                        title: '{{$ledgerInfo->customer_name}}',
                        orientation: 'landscape',

                        exportOptions: {
                            stripNewlines: false,
                            header: true,
                            footer: true,
                            columns: [0, 1, 2, 3, 4],
                            format: {
                                body: function (data, column, row) {
                                    data = data.replace(/<br\s*\/?>/ig, "\r\n");
                                    data = data.replace(/<.*?>/g, "");
                                    data = data.replace("&amp;", "&");
                                    data = data.replace("&nbsp;", "");
                                    data = data.replace("&nbsp;", "");
                                    return data;
                                }
                            }
                        },
                        customize: function (doc) {
                            // Column width
                            doc.content[1].table.widths = ['10%', '50%', '10%', '15%', '15%'];
                            // Column AutoWidth

                            // doc.content[1].table.widths =
                            //     Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                            //Align Header
                            doc.content[1].table.body[0][0].alignment = 'left';
                            doc.content[1].table.body[0][1].alignment = 'left';
                            doc.content[1].table.body[0][2].alignment = 'left';
                            doc.content[1].table.body[0][3].alignment = 'right';
                            doc.content[1].table.body[0][4].alignment = 'right';
                            //Align Body
                            var rowCount = doc.content[1].table.body.length;
                            for (i = 1; i < rowCount; i++) {
                                doc.content[1].table.body[i][3].alignment = 'right';
                                doc.content[1].table.body[i][4].alignment = 'right';
                            }
// Change dataTable layout (Table styling)
// To use predefined layouts uncomment the line below and comment the custom lines below
// doc.content[0].layout = 'lightHorizontalLines'; // noBorders , headerLineOnly
                            var objLayout = {};
                            objLayout['hLineWidth'] = function (i) {
                                return .5;
                            };
                            objLayout['vLineWidth'] = function (i) {
                                return .5;
                            };
                            objLayout['hLineColor'] = function (i) {
                                return '#aaa';
                            };
                            objLayout['vLineColor'] = function (i) {
                                return '#aaa';
                            };
                            objLayout['paddingLeft'] = function (i) {
                                return 4;
                            };
                            objLayout['paddingRight'] = function (i) {
                                return 4;
                            };
                            doc.content[0].layout = objLayout;

                        }
                    }
                ],
            });
        });

    </script>


@endpush





