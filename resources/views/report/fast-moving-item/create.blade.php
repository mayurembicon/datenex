@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column-fluid">

        <div class=" container ">

            <div class="row">
                <div class="col-xl-6">
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
                                        <a class="nav-link active week-info" id="Week-tab" data-toggle="tab" href="#Week">
                                            {{--                                        <span class="nav-icon"><i class="flaticon2-chat-1"></i></span>--}}
                                            <span class="nav-text">Week</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link month-info" id="Month-tab" data-toggle="tab" href="#Month">


                                            <span class="nav-text">Month</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link six-month-info" id="six-Month-tab" data-toggle="tab" href="#six-Month"
                                           aria-controls="6 Month">

                                            <span class="nav-text"> 6 Month</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link year" id="one-year-tab" data-toggle="tab" href="#one-year"
                                           aria-controls="contact">

                                            <span class="nav-text"> 1 Year</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!--end::Header-->

                        <!--begin::Body-->
                        <div class="card-body">
                            <!--begin::Example-->


                            <div class="tab-content mt-5" id="myTabContent">
                                <div class="tab-pane fade show active" id="Week" role="tabpanel"
                                     aria-labelledby="Week-tab">


<div id="items"></div>

                                </div>
                                <div class="tab-pane fade" id="Month" role="tabpanel" aria-labelledby="Month-tab">

                                    <div id="month"></div>
                                </div>
                                <div class="tab-pane fade" id="six-Month" role="tabpanel"
                                     aria-labelledby="six-Month-tab">
                                    <div id="six-month"></div>
                                </div>
                                <div class="tab-pane fade" id="one-year" role="tabpanel" aria-labelledby="one-year-tab">
                                    <div id="year"></div>
                                </div>
                            </div>

                            <!--end::Example-->
                        </div>
                    </div>
                    <!--end::Base Table Widget 1-->
                </div>

            </div>


        </div>

    </div>
@endsection
@push('scripts')
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
                    $.each(data[0], function(key,value) {
                        $('#items').append('  <div class="d-flex align-items-center flex-wrap mb-10">  <div class="d-flex flex-column flex-grow-1 mr-2">  <a href="#"   class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">'+value.name+'</a>  </div><span class="label label-xl label-light label-inline my-lg-0 my-2 text-dark-50 font-weight-bolder">'+value.totalSalesQty+'</span></div>');
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
                    $.each(data[0], function(key,value) {
                        $('#month').append('  <div class="d-flex align-items-center flex-wrap mb-10">  <div class="d-flex flex-column flex-grow-1 mr-2">  <a href="#"   class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">'+value.name+'</a>  </div><span class="label label-xl label-light label-inline my-lg-0 my-2 text-dark-50 font-weight-bolder">'+value.totalSalesQty+'</span></div>');

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
                    $.each(data[0], function(key,value) {
                        $('#six-month').append('  <div class="d-flex align-items-center flex-wrap mb-10">  <div class="d-flex flex-column flex-grow-1 mr-2">  <a href="#"   class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">'+value.name+'</a>  </div><span class="label label-xl label-light label-inline my-lg-0 my-2 text-dark-50 font-weight-bolder">'+value.totalSalesQty+'</span></div>');

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
                    $.each(data[0], function(key,value) {
                        $('#year').append('  <div class="d-flex align-items-center flex-wrap mb-10">  <div class="d-flex flex-column flex-grow-1 mr-2">  <a href="#"   class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">'+value.name+'</a>  </div><span class="label label-xl label-light label-inline my-lg-0 my-2 text-dark-50 font-weight-bolder">'+value.totalSalesQty+'</span></div>');

                    });

                }
            });
        });
    </script>

@endpush
