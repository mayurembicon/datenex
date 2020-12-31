@extends('layouts.app')
@section('content')


    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Subheader-->
        <div class="subheader py-2 py-lg-6  subheader-transparent " id="kt_subheader">
            <div class=" container  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">

                    <!--begin::Page Heading-->
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <!--begin::Page Title-->
                        <h5 class="text-dark font-weight-bold my-1 mr-5">

                            FollowUp Timeline
                        </h5>
                        <!--end::Page Title-->

                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Master </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a class="text-muted">

                                    FollowUp Timeline
                                </a>
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
                    <div class="d-flex align-items-center">
                        <!--begin::Actions-->
                        <a href="{{route('inquiry.index')}}" class="btn  font-weight-bolder btn-sm">
                        <span class="svg-icon svg-icon-warning svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo9\dist/../src/media/svg/icons\Code\Backspace.svg--><svg
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path
            d="M8.42034438,20 L21,20 C22.1045695,20 23,19.1045695 23,18 L23,6 C23,4.8954305 22.1045695,4 21,4 L8.42034438,4 C8.15668432,4 7.90369297,4.10412727 7.71642146,4.28972363 L0.653241109,11.2897236 C0.260966303,11.6784895 0.25812177,12.3116481 0.646887666,12.7039229 C0.648995955,12.7060502 0.651113791,12.7081681 0.653241109,12.7102764 L7.71642146,19.7102764 C7.90369297,19.8958727 8.15668432,20 8.42034438,20 Z"
            fill="#000000" opacity="0.3"/>
    </g>
</svg><!--end::Svg Icon--></span>
                            Back
                        </a>
                        <!--end::Actions-->

                        <!--begin::Dropdown-->
                        <!--end::Dropdown-->
                    </div>


                </div>
            </div>
        </div>


        <div class="container">
            @include('layouts.flash-message')

            <div class="col-lg-8 col-lg-3">

                <!--begin::List Widget 9-->
                <div class="card card-custom card-stretch gutter-b">
                    <!--begin::Header-->
                    <div class="card-header align-items-center border-0 mt-4">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="font-weight-bolder text-dark">
FollowUp Timeline
</span>
                            <span class="text-muted mt-3 font-weight-bold font-size-sm"> </span>
                        </h3>

                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body pt-4" >
                        <div class="timeline timeline-4">

                            <div class="timeline-bar"></div>

                            <div class="timeline-items">
                                @php $direction='left'; @endphp
                                @foreach($followup as $key=>$value)
                                    <div class="timeline-item timeline-item-{{$direction}}">

                                        <div class="timeline-badge">

                                            <div class="bg-danger"></div>
                                        </div>
                                        <span class="text-muted">{{$value->name}}</span>
                                        <div class="timeline-label">
                                            <span class="text-primary font-weight-bold">{{date('M d,yy h:i A',strtotime($value->created_at))}}</span>
                                        </div>

                                        <div class="timeline-content mb-3">{{$value->remark}}</div>
                                    </div>


                                    @php $direction=($direction==='left')?'right':'left'; @endphp
                                @endforeach
                            </div>
                        </div>


                        <!--begin::Timeline-->

                        <!--end::Timeline-->
                    </div>

                    <!--end: Card Body-->
                </div>
                <!--end: List Widget 9-->
            </div>
        </div>
    </div>

@endsection




