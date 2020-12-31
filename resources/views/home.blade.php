@extends('layouts.app')

@section('content')

    <div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="d-flex flex-column-fluid">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-xl-2">
                    @foreach($inquiry as $key=>$value)
                        <!--begin::Stats Widget 30-->
                            <div class="card card-cus   tom bg-info card-stretch gutter-b">
                                <!--begin::Body-->
                                <div class="card-body">
        <span class="svg-icon svg-icon-2x svg-icon-white"><!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Group.svg--><svg
                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <polygon points="0 0 24 0 24 24 0 24"/>
        <path
            d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z"
            fill="#000000" fill-rule="nonzero" opacity="0.3"/>
        <path
            d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z"
            fill="#000000" fill-rule="nonzero"/>
    </g>
</svg><!--end::Svg Icon--></span>
                                    <span
                                        class="card-title font-weight-bolder text-white font-size-h2 mb-0 mt-6 d-block">{{$value}}</span>
                                    <span class="font-weight-bold text-white  font-size-lg">Inquiry</span>
                                </div>
                                <!--end::Body-->
                            </div>
                    @endforeach
                    <!--end::Stats Widget 30-->
                    </div>
                    <div class="col-xl-2">
                    @foreach($inquiryIndiaMart as $key=>$value)

                        <!--begin::Stats Widget 32-->
                            <div class="card card-custom bg-success card-stretch gutter-b">
                                <!--begin::Body-->
                                <div class="card-body">
        <span class="svg-icon svg-icon-2x svg-icon-white"><!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Group-chat.svg--><svg
                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
       <rect x="0" y="0" width="24" height="24"/>
        <path
            d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z"
            fill="#000000" opacity="0.3"/>
        <path
            d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z"
            fill="#000000"/>
        <rect fill="#000000" opacity="0.3" x="7" y="10" width="5" height="2" rx="1"/>
        <rect fill="#000000" opacity="0.3" x="7" y="14" width="9" height="2" rx="1"/>
    </g>
</svg><!--end::Svg Icon--></span>
                                    <span
                                        class="card-title font-weight-bolder text-white font-size-h2 mb-0 mt-6  d-block">{{$value}}</span>
                                    <span class="font-weight-bold text-white  font-size-lg">    IndiaMART</span>
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Stats Widget 32-->
                        @endforeach
                    </div>
                    <div class="col-xl-2">
                    @foreach($inquiryTradeIndia as $key=>$value)

                        <!--begin::Stats Widget 32-->
                            <div class="card card-custom bg-warning card-stretch gutter-b">
                                <!--begin::Body-->
                                <div class="card-body">
        <span class="svg-icon svg-icon-2x svg-icon-white"><!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Group-chat.svg--><svg
                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
       <rect x="0" y="0" width="24" height="24"/>
        <path
            d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z"
            fill="#000000" opacity="0.3"/>
        <path
            d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z"
            fill="#000000"/>
        <rect fill="#000000" opacity="0.3" x="7" y="10" width="5" height="2" rx="1"/>
        <rect fill="#000000" opacity="0.3" x="7" y="14" width="9" height="2" rx="1"/>
    </g>
</svg><!--end::Svg Icon--></span>
                                    <span
                                        class="card-title font-weight-bolder text-white font-size-h2 mb-0 mt-6  d-block">{{$value}}</span>
                                    <span class="font-weight-bold text-white  font-size-lg">   TradeIndia</span>
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Stats Widget 32-->
                        @endforeach
                    </div>

                    <div class="col-xl-2">
                    @foreach($quotation as $key=>$value)
                        <!--begin::Stats Widget 31-->
                            <div class="card card-custom bg-danger card-stretch gutter-b">
                                <!--begin::Body-->
                                <div class="card-body">
        <span class="svg-icon svg-icon-2x svg-icon-white"><!--begin::Svg Icon | path:assets/media/svg/icons/Media/Equalizer.svg--><svg
                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
         <rect x="0" y="0" width="24" height="24"/>
        <polygon fill="#000000" points="11 7 9 13 11 13 11 18 6 18 6 13 8 7"/>
        <polygon fill="#000000" opacity="0.3" points="19 7 17 13 19 13 19 18 14 18 14 13 16 7"/>
   </g>
</svg><!--end::Svg Icon--></span>
                                    <span
                                        class="card-title font-weight-bolder text-white font-size-h2 mb-0 mt-6 d-block">{{$value}}</span>
                                    <span class="font-weight-bold text-white  font-size-lg">Quotation</span>
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Stats Widget 31-->
                        @endforeach
                    </div>
                    <div class="col-xl-2">
                    @foreach($invoice as $key=>$value)

                        <!--begin::Stats Widget 32-->
                            <div class="card card-custom bg-info card-stretch gutter-b">
                                <!--begin::Body-->
                                <div class="card-body">
        <span class="svg-icon svg-icon-2x svg-icon-white"><!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Group-chat.svg--><svg
                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
       <rect x="0" y="0" width="24" height="24"/>
        <path
            d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z"
            fill="#000000" opacity="0.3"/>
        <path
            d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z"
            fill="#000000"/>
        <rect fill="#000000" opacity="0.3" x="7" y="10" width="5" height="2" rx="1"/>
        <rect fill="#000000" opacity="0.3" x="7" y="14" width="9" height="2" rx="1"/>
    </g>
</svg><!--end::Svg Icon--></span>
                                    <span
                                        class="card-title font-weight-bolder text-white font-size-h2 mb-0 mt-6  d-block">{{$value}}</span>
                                    <span class="font-weight-bold text-white  font-size-lg">Sales</span>
                                </div>
                                <!--end::Body-->
                            </div>
                            <!--end::Stats Widget 32-->
                        @endforeach
                    </div>


                    <div class="col-xl-4">
                        <div class="col-lg-6 col-xxl-4 order-1 order-xxl-1">
                            <!--begin::List Widget 1-->
                            <div class="card card-custom  gutter-b">
                                <!--begin::Header-->
                            </div>
                        </div>
                        <!--begin::List Widget 1-->
                        <div class="card card-custom">
                            <!--begin::Header-->
                            <div class="card-header">
                                <h3 class="card-title">Pending Inquiry &nbsp; <span
                                        class="label label-rounded label-primary small">{{count($pendingInquiryQuery)}}</span>
                                </h3>

                            </div>

                            <div class="card-body" style="padding-top:0px;!important;">
                                <div data-scroll="true" data-height="350">
                                    <div class="table-responsive">
                                        <table class="table table-head-custom table-vertical-center table-sm"
                                               id="kt_advance_table_widget_1">
                                            <thead>


                                            <th>No</th>
                                            <th>Company Name</th>

                                            </thead>
                                            <tbody>
                                            @foreach($pendingInquiryQuery as $item)


                                                <tr>


                                                    <td>

                                                        {{$item->inquiry_id}}
                                                    </td>
                                                    <td>
                                                        <a class=" inquiry_id text-dark text-hover-primary mb-1 font-size-lg"
                                                           data-toggle="modal"
                                                           data-id="{{$item->inquiry_id}}"
                                                           data-target="#InquieyInfo">{{$item->company_name}}</a>
                                                    </td>
                                                </tr>
                                            @endforeach


                                            </tbody>
                                        </table>
                                    </div>


                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="col-xl-4">
                        <div class="col-lg-6 col-xxl-4 order-1 order-xxl-1">
                            <!--begin::List Widget 1-->
                            <div class="card card-custom  gutter-b">
                                <!--begin::Header-->
                            </div>
                        </div>

                        <!--begin::Advance Table Widget 1-->
                        <div class="card card-custom card-stretch card-shadowless gutter-b">
                            <!--begin::Header-->
                            <div class="card-header border-0 py-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label font-weight-bolder text-dark">Star Product</span>

                                </h3>

                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            <div class="card-body py-0">
                                <div data-scroll="true" data-height="250">

                                    <!--begin::Table-->
                                    <div class="table-responsive">
                                        <table class="table table-head-custom table-vertical-center table-sm small"
                                               id="kt_advance_table_widget_1">
                                            <thead>


                                            <th>Item Name</th>
                                            <th>Rating</th>

                                            </thead>
                                            <tbody>
                                            @foreach($starItem as $item)

                                                <tr>

                                                    <td>
                                                        {{$item->name}}
                                                    </td>
                                                    <td>
                                        <span class="svg-icon svg-icon-danger svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\General\Star.svg--><svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                                viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <polygon points="0 0 24 0 24 24 0 24"/>
        <path
            d="M12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.3476862,4.32173209 11.9473121,4.11839309 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 Z"
            fill="#000000"/>
    </g>
</svg><!--end::Svg Icon--></span>
                                                        {{$item->ratedIndex}}
                                                    </td>
                                                </tr>
                                            @endforeach


                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--end::Table-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Advance Table Widget 1-->
                    </div>
                    <div class="col-xl-4">
                        <div class="col-lg-6 col-xxl-4 order-1 order-xxl-1">
                            <!--begin::List Widget 1-->
                            <div class="card card-custom  gutter-b">
                                <!--begin::Header-->
                            </div>
                        </div>

                        <!--begin::Base Table Widget 1-->
                        <div class="card card-custom card-stretch gutter-b">
                            <!--begin::Header-->
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label font-weight-bolder text-dark">Fast Moving Product</span>
                                    {{--                            <span class="text-muted mt-3 font-weight-bold font-size-sm">More than 400+ new members</span>--}}
                                </h3>
                                <div class="card-toolbar">
                                    <ul class="nav nav-pills nav-pills-sm nav-dark-75">

                                        <li class="nav-item">
                                            <a class="nav-link  week-info" id="Week-tab" data-toggle="tab" href="#Week">
                                                {{--                                        <span class="nav-icon"><i class="flaticon2-chat-1"></i></span>--}}
                                                <span class="nav-text">Week</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link month-info" id="Month-tab" data-toggle="tab"
                                               href="#Month">


                                                <span class="nav-text">Month</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link six-month-info" id="six-Month-tab" data-toggle="tab"
                                               href="#six-Month"
                                               aria-controls="6 Month">

                                                <span class="nav-text"> 6 Month</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link year" id="one-year-tab" data-toggle="tab"
                                               href="#one-year"
                                               aria-controls="contact">

                                                <span class="nav-text"> 1 Year</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!--end::Header-->

                            <!--begin::Body-->
                            <div class="card-body" style="padding-top:0px;!important;">
                                <!--begin::Example-->
                                <div data-scroll="true" data-height="250">


                                    <div class="tab-content mt-5" id="myTabContent">
                                        <div class="tab-pane fade" id="Week" role="tabpanel"
                                             aria-labelledby="Week-tab">


                                            <div class="table-responsive">
                                                <table
                                                    class="table table-head-custom table-vertical-center table-sm ">
                                                    <thead>
                                                    <tr>
                                                        <th>Item Name</th>
                                                        <th>total Sales</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="items">
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                        <div class="tab-pane fade" id="Month" role="tabpanel"
                                             aria-labelledby="Month-tab">
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-head-custom table-vertical-center table-sm ">
                                                    <thead>
                                                    <tr>
                                                        <th>Item Name</th>
                                                        <th>total Sales</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="six-month">
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                        <div class="tab-pane fade" id="six-Month" role="tabpanel"
                                             aria-labelledby="six-Month-tab">
                                            <div id="six-month"></div>
                                        </div>
                                        <div class="tab-pane fade" id="one-year" role="tabpanel"
                                             aria-labelledby="one-year-tab">
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-head-custom table-vertical-center table-sm ">
                                                    <thead>
                                                    <th>Item Name</th>
                                                    <th>total Sales</th>
                                                    </thead>
                                                    <tbody id="year">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Example-->
                            </div>
                        </div>
                        <!--end::Base Table Widget 1-->


                    </div>


                    <div class="col-xl-4">
                        <div class="col-lg-6 col-xxl-4 order-1 order-xxl-1">
                            <!--begin::List Widget 1-->
                            <div class="card card-custom  gutter-b">
                                <!--begin::Header-->
                            </div>
                        </div>

                        <!--begin::Example-->
                        <!--begin::Card-->
                        <div class="card card-custom">
                            <div class="card-header card-header-tabs-line">
                                <div class="card-title">
                                    <h3 class="card-label">Inquiry FollowUp</h3>
                                </div>
                                <div class="card-toolbar">
                                </div>
                            </div>
                            <div class="card-body" style="padding:0px 2.25rem;!important;">
                                <ul class="nav nav-tabs nav-bold nav-tabs-line">
                                    <li class="nav-item">
                                        <a class="nav-link active font-weight-bold   font-size-lg" data-toggle="tab"
                                           href="#inquiry_1_2">Todays's
                                             &nbsp;&nbsp;<span
                                                class="label label-rounded label-success small">{{count($InquirytodayFollowUp)}}</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link font-weight-bold   font-size-lg" data-toggle="tab"
                                           href="#inquiry_2_2">Missed
                                             &nbsp;&nbsp; <span
                                                class="label label-rounded label-danger small">{{count($InquirymissedFollowUp)}}</span></a>
                                    </li>
                                </ul>

                                <div data-scroll="true" data-height="250">
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="inquiry_1_2" role="tabpanel">
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-head-custom table-vertical-center table-sm small">
                                                    <thead>

                                                    <tr>
                                                        <th>No</th>
                                                        <th>Company Name</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($InquirytodayFollowUp as $item)


                                                        <tr>

                                                            <td class="font-weight-bold font-size-sm">
                                                                {{$item->inquiry_id}}
                                                            </td>
                                                            <td class="font-weight-bold font-size-sm">
                                                                <a class=" inquiry_id text-dark text-hover-primary mb-1 font-size-lg"
                                                                   data-toggle="modal"
                                                                   data-id="{{$item->inquiry_id}}"
                                                                   data-target="#InquieyInfo">{{$item->company_name}}</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach


                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="inquiry_2_2" role="tabpanel">
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-head-custom table-vertical-center table-sm small">
                                                    <thead>


                                                    <th>No</th>
                                                    <th>Company Name</th>

                                                    </thead>
                                                    <tbody>
                                                    @foreach($InquirymissedFollowUp as $item)


                                                        <tr>

                                                            <td class="font-weight-bold font-size-sm">
                                                                {{$item->inquiry_id}}
                                                            </td>
                                                            <td class="font-weight-bold font-size-sm">
                                                                <a class=" inquiry_id text-dark text-hover-primary mb-1 font-size-lg"
                                                                   data-toggle="modal"
                                                                   data-id="{{$item->inquiry_id}}"
                                                                   data-target="#InquieyInfo">{{$item->company_name}}</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach


                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-xl-4">
                        <div class="col-lg-6 col-xxl-4 order-1 order-xxl-1">
                            <!--begin::List Widget 1-->
                            <div class="card card-custom  gutter-b">
                                <!--begin::Header-->
                            </div>
                        </div>

                        <!--begin::Example-->
                        <!--begin::Card-->
                        <div class="card card-custom">
                            <div class="card-header card-header-tabs-line">
                                <div class="card-title">
                                    <h3 class="card-label">Quotation FollowUp</h3>
                                </div>
                                <div class="card-toolbar">
                                </div>
                            </div>
                            <div class="card-body" style="padding:0px 2.25rem;!important;">
                                <ul class="nav nav-tabs nav-bold nav-tabs-line">
                                    <li class="nav-item">
                                        <a class="nav-link active font-weight-bold   font-size-lg" data-toggle="tab"
                                           href="#quotation_1_2">Todays's
                                             &nbsp;&nbsp;&nbsp;<span
                                                class="label label-rounded label-success small">{{count($QuotodayFollowUp)}}</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link font-weight-bold   font-size-lg" data-toggle="tab"
                                           href="#quotation_2_2">Missed
                                             &nbsp;&nbsp;<span
                                                class="label label-rounded label-danger small">{{count($QuomissedFollowUp)}}</span></a>
                                    </li>
                                </ul>

                                <div data-scroll="true" data-height="250">
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="quotation_1_2" role="tabpanel">
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-head-custom table-vertical-center table-sm small">
                                                    <thead>

                                                    <tr>
                                                        <th>No</th>
                                                        <th>Company Name</th>
                                                    </tr>

                                                    </thead>
                                                    <tbody>
                                                    @foreach($QuotodayFollowUp as $item)


                                                        <tr>

                                                            <td class="font-weight-bold font-size-sm">
                                                                {{$item->quotation_id}}
                                                            </td>
                                                            <td class="font-weight-bold font-size-sm">
                                                                <a class=" quotation_id text-dark text-hover-primary mb-1 font-size-lg"
                                                                   data-toggle="modal"
                                                                   data-id="{{$item->quotation_id}}"
                                                                   data-target="#Qfollowup">{{$item->company_name}}</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach


                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="quotation_2_2" role="tabpanel">
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-head-custom table-vertical-center table-sm small">
                                                    <thead>


                                                    <th>No</th>
                                                    <th>Company Name</th>

                                                    </thead>
                                                    <tbody>
                                                    @foreach($QuomissedFollowUp as $item)


                                                        <tr>

                                                            <td class="font-weight-bold font-size-sm">
                                                                {{$item->quotation_id}}
                                                            </td>
                                                            <td class="font-weight-bold font-size-sm">
                                                                <a class=" quotation_id text-dark text-hover-primary mb-1 font-size-lg"
                                                                   data-toggle="modal"
                                                                   data-id="{{$item->quotation_id}}"
                                                                   data-target="#Qfollowup">{{$item->company_name}}</a>
                                                            </td>
                                                        </tr>

                                                    @endforeach


                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="col-xl-4">
                        <div class="col-lg-6 col-xxl-4 order-1 order-xxl-1">
                            <!--begin::List Widget 1-->
                            <div class="card card-custom  gutter-b">
                                <!--begin::Header-->
                            </div>
                        </div>

                        <!--begin::Example-->
                        <!--begin::Card-->
                        <div class="card card-custom">
                            <div class="card-header card-header-tabs-line">
                                <div class="card-title">
                                    <h3 class="card-label">Proforma Invoice FollowUp</h3>
                                </div>
                                <div class="card-toolbar">
                                </div>
                            </div>
                            <div class="card-body" style="padding:0px 2.25rem;!important;">
                                <ul class="nav nav-tabs nav-bold nav-tabs-line">
                                    <li class="nav-item">
                                        <a class="nav-link active font-weight-bold   font-size-lg" data-toggle="tab"
                                           href="#pi_1_2">Todays's
                                             &nbsp;&nbsp;&nbsp;<span
                                                class="label label-rounded label-success small">{{count($PitodayFollowUp)}}</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link font-weight-bold   font-size-lg" data-toggle="tab"
                                           href="#pi_2_2">Missed
                                             &nbsp;&nbsp;&nbsp;<span
                                                class="label label-rounded label-danger small">{{count($PimissedFollowUp)}}</span></a>
                                    </li>
                                </ul>

                                <div data-scroll="true" data-height="250">
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="pi_1_2" role="tabpanel">
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-head-custom table-vertical-center table-sm small">
                                                    <thead>

                                                    <tr>
                                                        <th>No</th>
                                                        <th>Company Name</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($PitodayFollowUp as $item)


                                                        <tr>

                                                            <td class="font-weight-bold font-size-sm">
                                                                {{$item->pi_id}}
                                                            </td>
                                                            <td class="font-weight-bold font-size-sm">
                                                                <a class=" pi_id text-dark text-hover-primary mb-1 font-size-lg"
                                                                   data-toggle="modal"
                                                                   data-id="{{$item->pi_id}}"
                                                                   data-target="#Pfollowup">{{$item->company_name}}</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach


                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="pi_2_2" role="tabpanel">
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-head-custom table-vertical-center table-sm small">
                                                    <thead>


                                                    <th>No</th>
                                                    <th>Company Name</th>

                                                    </thead>
                                                    <tbody>
                                                    @foreach($PimissedFollowUp as $item)


                                                        <tr>

                                                            <td class="font-weight-bold font-size-sm">
                                                                {{$item->pi_id}}
                                                            </td>
                                                            <td class="font-weight-bold font-size-sm">
                                                                <a class=" pi_id text-dark text-hover-primary mb-1 font-size-lg"
                                                                   data-toggle="modal"
                                                                   data-id="{{$item->pi_id}}"
                                                                   data-target="#Pfollowup">{{$item->company_name}}</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach


                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="col-xl-4">
                        <div class="col-lg-6 col-xxl-4 order-1 order-xxl-1">
                            <!--begin::List Widget 1-->
                            <div class="card card-custom  gutter-b">
                                <!--begin::Header-->
                            </div>
                        </div>

                        <!--begin::Example-->
                        <!--begin::Card-->
                        <div class="card card-custom">
                            <div class="card-header card-header-tabs-line">
                                <div class="card-title">
                                    <h3 class="card-label">Customer Inquiry FollowUp</h3>
                                </div>
                                <div class="card-toolbar">
                                </div>
                            </div>
                            <div class="card-body" style="padding:0px 2.25rem;!important;">
                                <ul class="nav nav-tabs nav-bold nav-tabs-line">
                                    <li class="nav-item">
                                        <a class="nav-link active font-weight-bold   font-size-lg" data-toggle="tab"
                                           href="#ci_1_2">Todays's
                                             &nbsp;&nbsp;&nbsp;<span
                                                class="label label-rounded label-success small">{{count($CitodayFollowUp)}}</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link font-weight-bold   font-size-lg" data-toggle="tab"
                                           href="#ci_2_2">Missed
                                             &nbsp;&nbsp; &nbsp;<span
                                                class="label label-rounded label-danger small">{{count($CimissedFollowUp)}}</span></a>
                                    </li>
                                </ul>

                                <div data-scroll="true" data-height="250">
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="ci_1_2" role="tabpanel">
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-head-custom table-vertical-center table-sm small">
                                                    <thead>

                                                    <tr>
                                                        <th>No</th>
                                                        <th>Company Name</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($CitodayFollowUp as $item)


                                                        <tr>

                                                            <td class="font-weight-bold font-size-sm">
                                                                {{$item->customer_inquiry_id}}
                                                            </td>
                                                            <td class="font-weight-bold font-size-sm">
                                                                <a class=" ci_id text-dark text-hover-primary mb-1 font-size-lg"
                                                                   data-toggle="modal"
                                                                   data-id="{{$item->customer_inquiry_id}}"
                                                                   data-target="#Cifollowup">{{$item->company_name}}</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach


                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="ci_2_2" role="tabpanel">
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-head-custom table-vertical-center table-sm small">
                                                    <thead>


                                                    <th>No</th>
                                                    <th>Company Name</th>

                                                    </thead>
                                                    <tbody>
                                                    @foreach($CimissedFollowUp as $item)


                                                        <tr>

                                                            <td class="font-weight-bold font-size-sm">
                                                                {{$item->customer_inquiry_id}}
                                                            </td>
                                                            <td class="font-weight-bold font-size-sm">
                                                                <a class=" ci_id text-dark text-hover-primary mb-1 font-size-lg"
                                                                   data-toggle="modal"
                                                                   data-id="{{$item->customer_inquiry_id}}"
                                                                   data-target="#Cifollowup">{{$item->company_name}}</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach


                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-xl-4">
                        <div class="col-lg-6 col-xxl-4 order-1 order-xxl-1">
                            <!--begin::List Widget 1-->
                            <div class="card card-custom  gutter-b">
                                <!--begin::Header-->
                            </div>
                        </div>

                        <!--begin::Example-->
                        <!--begin::Card-->
                        <div class="card card-custom">
                            <div class="card-header card-header-tabs-line">
                                <div class="card-title">
                                    <h3 class="card-label">Online Inquiry FollowUp</h3>
                                </div>
                                <div class="card-toolbar">
                                </div>
                            </div>
                            <div class="card-body" style="padding:0px 2.25rem;!important;">
                                <ul class="nav nav-tabs nav-bold nav-tabs-line">
                                    <li class="nav-item ">
                                        <a class="nav-link active font-weight-bold   font-size-lg" data-toggle="tab"
                                           href="#oi_1_2">Todays's
                                             &nbsp;&nbsp;<span
                                                class="label label-rounded label-success small">{{count($OitodayFollowUp)}}</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link font-weight-bold   font-size-lg" data-toggle="tab"
                                           href="#oi_2_2">Missed
                                             &nbsp;&nbsp; &nbsp;<span
                                                class="label label-rounded label-danger small">{{count($OimissedFollowUp)}}</span></a>
                                    </li>
                                </ul>

                                <div data-scroll="true" data-height="250">
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="oi_1_2" role="tabpanel">
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-head-custom table-vertical-center table-sm small">
                                                    <thead>

                                                    <tr>
                                                        <th>No</th>
                                                        <th>Company Name</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($OitodayFollowUp as $item)


                                                        <tr>

                                                            <td class="font-weight-bold font-size-sm">
                                                                {{$item->o_id}}
                                                            </td>
                                                            <td class="font-weight-bold font-size-sm">
                                                                <a class=" o_id text-dark text-hover-primary mb-1 font-size-lg"
                                                                   data-toggle="modal"
                                                                   data-id="{{$item->o_id}}"
                                                                   data-target="#Ofollowup">{{$item->sender_company}}</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach


                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="oi_2_2" role="tabpanel">
                                            <div class="table-responsive">
                                                <table
                                                    class="table table-head-custom table-vertical-center table-sm small">
                                                    <thead>


                                                    <th>No</th>
                                                    <th>Company Name</th>

                                                    </thead>
                                                    <tbody>
                                                    @foreach($OimissedFollowUp as $item)


                                                        <tr>

                                                            <td>
                                                                {{$item->o_id}}
                                                            </td>
                                                            <td class="font-weight-bold font-size-sm">
                                                                <a class=" o_id text-dark text-hover-primary mb-1 font-size-lg"
                                                                   data-toggle="modal"
                                                                   data-id="{{$item->o_id}}"
                                                                   data-target="#Ofollowup">{{$item->sender_company}}</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach


                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="InquieyInfo" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Inquiry

                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>

                <div class="modal-body">
                    <div data-scroll="true" data-height="400">

                        <div class="modal-body" id="inquiry_detail">


                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">
                            Close
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="Qfollowup" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Quotation


                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div data-scroll="true" data-height="600">

                        <div class="modal-body" id="quotation_detail">


                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">
                            Close
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="Pfollowup" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Proforma Invoice


                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div data-scroll="true" data-height="600">

                        <div class="modal-body" id="pi_detail">


                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">
                            Close
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="Cifollowup" data-backdrop="static" tabindex="-1" role="dialog"
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
                <div class="modal-body">
                    <div data-scroll="true" data-height="600">

                        <div class="modal-body" id="ci_detail">


                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">
                            Close
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="Ofollowup" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-xl " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Online Inquiry


                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div data-scroll="true" data-height="600">

                        <div class="modal-body" id="oi_detail">


                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">
                            Close
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script
        src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/global/plugins.bundle.js'}}"></script>
    <script
        src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/prismjs/prismjs.bundle.js'}}"></script>
    <script
        src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/js/scripts.bundle.js'}}"></script>
    <script src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/js/pages/widgets.js'}}"></script>

    <script>
        $(document).on("click", ".quotation_id", function () {
            var quotationID = $(this).data('id');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ url('get-quotation-info') }}',
                data: {'quotationID': quotationID},
                success: function (data) {
                    $('#quotation_detail').html(data);
                }
            });
        });
        $(document).on("click", ".inquiry_id", function () {
            var inquiryID = $(this).data('id');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ url('get-inquiry-info') }}',
                data: {'inquiryID': inquiryID},
                success: function (data) {
                    $('#inquiry_detail').html(data);
                }
            });
        });
        $(document).on("click", ".pi_id", function () {
            var piID = $(this).data('id');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ url('get-pi-info') }}',
                data: {'piID': piID},
                success: function (data) {
                    $('#pi_detail').html(data);
                }
            });
        });
        $(document).on("click", ".ci_id", function () {
            var ciID = $(this).data('id');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ url('get-ci-info') }}',
                data: {'ciID': ciID},
                success: function (data) {
                    $('#ci_detail').html(data);
                }
            });
        });
        $(document).on("click", ".o_id", function () {
            var oiID = $(this).data('id');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ url('get-oi-info') }}',
                data: {'oiID': oiID},
                success: function (data) {
                    $('#oi_detail').html(data);
                }
            });
        });
    </script>
    <script>

        // var fruits = ["apple", "orange", "cherry"];
        // fruits.forEach(myFunction);
        //
        // function myFunction(item, index) {
        //     document.getElementById("demo").innerHTML += index + ":" + item + "<br>";
        // }

        $(document).on("click", ".week-info", function () {
            var weekID = $(this).data('id');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ url('week') }}',
                data: {'weekID': weekID},
                success: function (data) {

                    $('#items').empty();
                    $.each(data[0], function (key, value) {
                        $('#items').append(' <tr> <td class="font-weight-bold font-size-sm">' + value.name + ' </td> <td class="font-weight-bold font-size-sm text-center ">' + value.totalSales + ' </td> </tr> ');
                    });


                }
            });
        });

        $(document).on("click", ".month-info", function () {
            var monthID = $(this).data('id');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ url('month') }}',
                data: {'monthID': monthID},
                success: function (data) {

                    $('#month').empty();
                    $.each(data[0], function (key, value) {
                        $('#month').append(' <tr> <td class="font-weight-bold font-size-sm">' + value.name + ' </td> <td class="font-weight-bold font-size-sm text-center">' + value.totalSales + ' </td> </tr> ');

                    });

                }
            });
        });

        $(document).on("click", ".six-month-info", function () {
            var monthID = $(this).data('id');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ url('six-month') }}',
                data: {'monthID': monthID},
                success: function (data) {

                    $('#six-month').empty();
                    $.each(data[0], function (key, value) {
                        $('#six-month').append(' <tr> <td class="font-weight-bold font-size-sm">' + value.name + ' </td> <td class="font-weight-bold font-size-sm text-center">' + value.totalSales + ' </td> </tr> ');

                    });

                }
            });
        });
        $(document).on("click", ".year", function () {
            var monthID = $(this).data('id');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ url('year') }}',
                data: {'monthID': monthID},
                success: function (data) {

                    $('#year').empty();
                    $.each(data[0], function (key, value) {
                        $('#year').append(' <tr> <td class="font-weight-bold font-size-sm ">' + value.name + ' </td> <td class="font-weight-bold font-size-sm text-center">' + value.totalSales + ' </td> </tr> ');

                    });

                }
            });
        });
    </script>
@endpush

