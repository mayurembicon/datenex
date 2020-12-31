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
                            Item </h5>
                        <!--end::Page Title-->

                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Master </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a class="text-muted">
                                    Item </a>
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
                    <a href="{{route('item-master.create')}}" class="btn btn-light-primary font-weight-bolder btn-sm">
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
            <!--begin::Notice-->
        @include('layouts.flash-message')

        <!--end::Notice-->
            <!--begin::Card-->
            <div class="card card-custom">
                <div class="card-body">
                    <form action="{{ url('search-item') }}" method="POST" role="search">
                        {{ csrf_field() }}
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label class="col-form-label ">Item Name</label>
                                <select
                                    class="form-control item-select2 @error('item_id') is-invalid @enderror "
                                    name="product_name">
                                    <?php if(!empty($selectedItem)){ ?>
                                    <option value="<?php echo $selectedItem; ?>"><?php echo $selectedItem; ?></option>
                                    <?php } ?>
                                </select>
                            </div>


                            <div class="col-lg-2">
                                <label class="col-form-label">Rating</label>
                                <select class="form-control  @error('type') is-invalid @enderror"
                                        name="rating">
                                    <option value="">Rating
                                    </option>
                                    <option
                                        value="1" {{(isset($search_item['rating'])&& $search_item['rating']=='1')?'selected':''}}>
                                        1
                                    </option>
                                    <option
                                        value="2" {{(isset($search_item['rating'])&& $search_item['rating']=='2')?'selected':''}}>
                                        2
                                    </option>
                                    <option
                                        value="3" {{(isset($search_item['rating'])&& $search_item['rating']=='3')?'selected':''}}>
                                        3
                                    </option>
                                    <option
                                        value="4" {{(isset($search_item['rating'])&& $search_item['rating']=='4')?'selected':''}}>
                                        4
                                    </option>
                                    <option
                                        value="5" {{(isset($search_item['rating'])&& $search_item['rating']=='5')?'selected':''}}>
                                        5
                                    </option>
                                </select>
                            </div>

                            <div class="col-lg-2">
                                <label class="col-form-label">Unit</label>
                                <select
                                    class="item-clr form-control @error('email') is-invalid @enderror " id="unit"
                                    name="unit">
                                    <?php if(!empty($selectedUnit)){ ?>
                                    <option value="<?php echo $selectedUnit; ?>"><?php echo $selectedUnit; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label class="col-form-label">Hsn</label>
                                <select
                                    class="item-clr form-control @error('email') is-invalid @enderror " id="hsn"
                                    name="hsn">
                                    <?php if(!empty($selectedHsn)){ ?>
                                    <option value="<?php echo $selectedHsn; ?>"><?php echo $selectedHsn; ?></option>
                                    <?php } ?>
                                </select>
                            </div>


                        </div>
                        <div class="card-footer mb-0">


                            <button type="submit" name="name"
                                    class="btn btn-primary btn-sm @error('name') is-invalid @enderror">
                                <i class="flaticon-search"></i> Search
                            </button>
                            <a href="{{ url('clear-item') }}"
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
                        <table class="table table-bordered table-hover table-checkable mt-10 col-form-label"  id="">
                            <thead>

                            <tr>
                                <th>Item Name</th>
                                <th>Rating</th>
                                <th>Sale Rate</th>
                                <th>Purchase Rate</th>
                                <th>Unit</th>
                                <th>Hsn</th>
                                <th>Description</th>
                                <th>Update</th>
                                <th>Delete</th>

                            </tr>
                            </thead>
                            <tbody>

                            @foreach($item as $key=>$value)
                                <tr>
                                    <td>{{$value->name}}</td>
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
</svg><!--end::Svg Icon--></span>{{$value->ratedIndex}}</td>
                                    <td>{{$value->sale_rate}}</td>
                                    <td>{{$value->purchase_rate}}</td>
                                    <td>{{$value->unit}}</td>
                                    <td>{{$value->hsn}}</td>
                                    <td>{{$value->descripation}}</td>
                                    <td>
                                        <a href="{{route('item-master.edit',$value->item_id)}}"
                                           class="btn btn-warning btn-sm">
                                            <i class="fa fa-edit"></i></a>

                                    <td>
                                        <form action="{{route('item-master.destroy',$value->item_id)}}" method="post">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger "
                                                    onclick="return confirm('Are you sure  want to delete?')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>

                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        {{ $item->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('styles')
    <link
        href="{{ env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/datatables/datatables.bundle.css?v=7.0.5'}}"
        rel="stylesheet"
        type="text/css"/>
@endpush
@push('scripts')
    <!--begin::Page Vendors(used by this page)-->
    <script
        src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/js/pages/crud/forms/widgets/select2.js'}}"></script>
    <script
        src="{{ env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/datatables/datatables.bundle.js?v=7.0.5'}}"></script>

    <!--end::Page Vendors-->
    <!--begin::Page Scripts(used by this page)-->
    <script>
        $('#kt_datatable').DataTable({});

    </script>
    <script>
        $('#kt_datatable').DataTable({});



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

        $("#unit").select2({
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
                        field_name: 'unit'
                    };
                }, processResults: function (response) {
                    return {results: response};
                }, cache: true
            }
        });

        $("#hsn").select2({
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
                        field_name: 'hsn'
                    };
                }, processResults: function (response) {
                    return {results: response};
                }, cache: true
            }
        });

    </script>

@endpush



