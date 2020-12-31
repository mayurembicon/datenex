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
                                Inquiry </h5>
                            <!--end::Page Title-->

                            <!--begin::Breadcrumb-->
                            <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">

                                <li class="breadcrumb-item">
                                    <a class="text-muted">
                                        Transaction
                                    </a>
                                </li>

                                <li class="breadcrumb-item">
                                    <a class="text-muted">
                                        Inquiry
                                    </a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a class="text-muted">
                                        Create
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
                    <!--end::Toolbar-->
                </div>


            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-custom gutter-b example example-compact">
                            <div class="card-header">
                                <h3 class="card-title">Inquiry</h3>
                                <div class="card-toolbar">
                                    <div class="example-tools justify-content-center">

                                    <span class="example-copy" data-toggle="tooltip" title=""
                                          data-original-title="Copy code"></span>
                                    </div>
                                </div>
                            </div>
                            <form
                                action="{{ ($form == 'Update')?route('inquiry.update',$inquiry->inquiry_id):route('inquiry.store')}}"
                                method="post" id="my-form">
                                @csrf
                                @if($form == 'Update')
                                    @method('PUT')
                                @endif
                                <div class="card-body">
                                    <div class="mb-1">

                                        <div class="form-group row ">
                                            <div class="col-lg-3">
                                                <label>Date</label>
                                                <div class="input-group date">
                                                    <input type="text" name="date"
                                                           class="form-control "
                                                           placeholder="dd-mm-yyyy"
                                                           id="kt_datepicker_3"
                                                           value="{{ !empty(old('date'))?old('date'):(!empty($inquiry->date)?date('d-m-Y',strtotime($inquiry->date)):date('d-m-Y')) }}">
                                                    <div class="input-group-append">
                                                                <span class="input-group-text">
                                                                    <i class="la la-calendar"></i>
                                                                </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <label>Inquiry Form<span
                                                        class="text-danger">*</span></label>
                                                <select
                                                    class="form-control select2-control {{ $errors->has('inquiry_from') ? ' is-invalid' : '' }}"
                                                    name="inquiry_from">
                                                    <option
                                                        value="" {{(isset($inquiry))?$inquiry->inquiry_from:old('inquiry_from')}}>
                                                        select
                                                    </option>

                                                    <option
                                                        value="Sales" {{(isset($inquiry)&& $inquiry->inquiry_from=='Sales')?'selected':old('Sales')}}>
                                                        Sales
                                                    </option>
                                                    <option
                                                        value="IndiaMART" {{(isset($inquiry) && strtoupper($inquiry->inquiry_from)=='INDIAMART')?'selected':old('IndiaMART')}}>
                                                        IndiaMART
                                                    </option>
                                                    <option
                                                        value="TradeIndia" {{(isset($inquiry) && strtoupper($inquiry->inquiry_from)=='TRADEINDIA')?'selected':old('TradeIndia')}}>
                                                        TradeIndia
                                                    </option>
                                                    <option
                                                        value="Call" {{(isset($inquiry) && $inquiry->inquiry_from=='Call')?'selected':old('Call')}}>
                                                        Call
                                                    </option>
                                                    <option
                                                        value="Visit" {{(isset($inquiry) && $inquiry->inquiry_from=='Visit')?'selected':old('Visit')}}>
                                                        Visit
                                                    </option>
                                                    <option
                                                        value="Office" {{(isset($inquiry) && $inquiry->inquiry_from=='Office')?'selected':old('Office')}}>
                                                        Office
                                                    </option>
                                                    <option
                                                        value="Marketing" {{(isset($inquiry) && $inquiry->inquiry_from=='Marketing')?'selected':old('Marketing')}}>
                                                        Marketing
                                                    </option>
                                                </select>
                                                @if ($errors->has('inquiry_from'))
                                                    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('inquiry_from') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="col-lg-3">
                                                <label>Company Name<span
                                                        class="text-danger">*</span></label>
                                                <select
                                                    class="client-clr form-control customer-select2 {{ $errors->has('customer_id') ? ' is-invalid' : '' }}"
                                                    name="customer_id" id="customer_id" data-live-search="true"
                                                    onchange="customermodelopen()">
                                                    @if(!empty($inquiry))
                                                        <option
                                                            value="{{(isset($inquiry))?$inquiry->customer_id:(old('customer_id')?old('customer_id'):0)}}">
                                                            {{ isset($inquiry->customer)?$inquiry->customer->company_name:''}}
                                                        </option>
                                                    @endif
                                                </select>
                                                @if ($errors->has('customer_id'))
                                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('customer_id') }}</strong>
                                        </span>
                                                @endif

                                            </div>
                                        </div>

                                        <div class="form-group row ">
                                            <div class="col-lg-3">
                                                <label>Contact Person</label>
                                                <input type="text" name="contact_person"
                                                       class="form-control client-clr" id="contact_person_name"
                                                       placeholder="Contact Person" maxlength="150"
                                                       value="{{(isset($inquiry))?$inquiry->contact_person:old('contact_person')}}">
                                            </div>
                                            <div class="col-lg-3">
                                                <label>Phone No</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">
                                                                            <i class="la la-whatsapp"></i>
                                                                        </span>
                                                    </div>
                                                    <input type="text" name="phone_no" id="f_phone_no"
                                                           placeholder="Phone No" maxlength="15"
                                                           class="form-control client-clr"
                                                           value="{{(isset($inquiry))?$inquiry->phone_no:old('phone_no')}}">
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <label>Email Address </label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">@</span>
                                                    </div>
                                                    <input type="text" name="email" id="email" placeholder="Email Address"
                                                           class=" form-control client-clr"
                                                           value="{{(isset($inquiry))?$inquiry->email:old('email')}}">


                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <label>Subject</label>
                                                <input type="text" name="subject" placeholder="Subject"
                                                       class="form-control"
                                                       value="{{(isset($inquiry))?$inquiry->subject:old('subject')}}">
                                            </div>
                                        </div>
                                    </div>


                                    <div class="separator separator-dashed my-10"></div>
                                    <div class="k-repeater-grid-items">
                                        <div id="kt_repeater_1">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-sm">
                                                    <thead class="bg-light-light">
                                                    <tr>
                                                        <th colspan="1" rowspan="2" class="text-left">
                                                            Product Name<span class="text-danger">*</span>
                                                        </th>
                                                        <th colspan="1" rowspan="2" class="text-left">
                                                            Product Description
                                                        </th>
                                                        <th colspan="1" rowspan="2" class="text-center">
                                                            Unit
                                                        </th>
                                                        <th colspan="1" rowspan="2" class="text-center">
                                                            Qty<span class="text-danger">*</span>
                                                        </th>
                                                        <th colspan="1" rowspan="2" class="text-center">Rate
                                                            (Rs.)
                                                        </th>
                                                        <th colspan="1" rowspan="1" class="text-center">
                                                            Discount
                                                        </th>
                                                        <th colspan="1" rowspan="2" class="text-center">
                                                            Taxable Value
                                                        </th>
                                                        <th colspan="1" rowspan="2" class="text-center">
                                                            Taxable Rate
                                                        </th>
                                                        <th colspan="3" rowspan="1" class="text-center">Tax
                                                            Values (Rs.)
                                                        </th>
                                                        <th colspan="1" rowspan="2" class="text-center">
                                                            Total
                                                        </th>

                                                    </tr>
                                                    <tr>
                                                        <th class="discountType-header">
                                                            <div class="radio-inline">
                                                                <label class="radio">
                                                                    <input type="radio" name="discount_type"
                                                                           value="R"
                                                                    />
                                                                    <span></span>Rs.</label>
                                                                <label class="radio">
                                                                    <input type="radio" name="discount_type"
                                                                           value="P" checked/>
                                                                    <span></span>%</label>
                                                            </div>
                                                        </th>
                                                        <th class="text-center">CGST</th>
                                                        <th class="text-center">SGST</th>
                                                        <th class="text-center">IGST</th>

                                                    </tr>

                                                    </thead>
                                                    <tbody data-repeater-list="grid_items" id="grid_items">
                                                    <div class="k-repeater-grid-items">
                                                        @php $gridItem=!empty(old('grid_items'))?old('grid_items'):(empty($inquiryproductitems)?[]:$inquiryproductitems);
                                                         $i=0;
                                                        @endphp
                                                        @if(!$gridItem)

                                                            <tr data-repeater-item="">

                                                                <td class="p-0" width="20%">

                                                                    <select
                                                                        class="item-clr form-control item-select2   grid-item @error('item_id') is-invalid @enderror "
                                                                        data-live-search="true"
                                                                        name="item_id">

                                                                    </select>
                                                                    @if ($errors->has('grid_items.'.$i.'.item_id'))
                                                                        <span class="invalid-feedback"
                                                                              role="alert">
                                                                            <strong>{{ $errors->first('grid_items.'.$i.'.item_id') }}</strong>
                                                                            </span>
                                                                    @endif
                                                                </td>
                                                                <td class="p-0" width="20%">
                                                                    <input type="text"
                                                                           class="form-control grid-item"
                                                                           name="p_description" autocomplete="off"
                                                                           placeholder="Product Descripation">
                                                                </td>
                                                                <td class="p-0" width="10%">
                                                                    <select
                                                                        class="form-control select-control"
                                                                        name="unit" id="unit">
                                                                        <option value=""></option>
                                                                        @foreach($unit as $item)
                                                                            <option
                                                                                value="{{$item->unit_name}}">
                                                                                {{$item->unit_name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td class="p-0">
                                                                    <input type="number"
                                                                           class="form-control grid-item {{ $errors->has('grid_items.'.$i.'.qty') ? ' is-invalid' : '' }}"
                                                                           name="qty" autocomplete="off"
                                                                           placeholder="Qty"/>
                                                                    @if ($errors->has('grid_items.'.$i.'.qty'))
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $errors->first('grid_items.'.$i.'.qty') }}</strong>
                                                                            </span>
                                                                    @endif
                                                                </td>

                                                                <td class="p-0">
                                                                    <input type="number"
                                                                           class="form-control grid-item item-clr"
                                                                           name="rate" autocomplete="off"
                                                                           placeholder="rate"/>
                                                                </td>
                                                                <td class="p-0">
                                                                    <input type="text"
                                                                           class="form-control grid-item item-clr"
                                                                           autocomplete="off" name="discount_rate"
                                                                           placeholder=" "/>
                                                                </td>
                                                                <td class="p-0">
                                                                    <input type="text"
                                                                           class="form-control grid-item"
                                                                           name="taxable_value"
                                                                           autocomplete="off"
                                                                           placeholder=""/>
                                                                </td>

                                                                <td class="p-0" width="5%">
                                                                    <select
                                                                        class="form-control grid-item "
                                                                        name="taxrate">
                                                                        <option value=""></option>
                                                                        @foreach($taxrate  as $item)
                                                                            <option
                                                                                value="{{$item->tax_rate}}">
                                                                                {{$item->tax_rate}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td class="p-0"><input type="text"
                                                                                       class="form-control grid-item bg-light-light"
                                                                                       readonly
                                                                                       name="cgst_amount"
                                                                                       placeholder=""/>
                                                                </td>
                                                                <td class="p-0"><input type="text"
                                                                                       class="form-control grid-item bg-light-light"
                                                                                       readonly
                                                                                       name="sgst_amount"
                                                                                       placeholder=""/>
                                                                </td>
                                                                <td class="p-0"><input type="text"
                                                                                       class="form-control grid-item bg-light-light"
                                                                                       readonly
                                                                                       name="igst_amount"
                                                                                       placeholder=""/>
                                                                </td>


                                                                <td class="p-0">
                                                                    <input type="text"
                                                                           class="form-control grid-item bg-light-light"
                                                                           readonly
                                                                           name="item_total_amount"
                                                                           placeholder=""/>
                                                                </td>

                                                                <td class="p-0 align-middle border-0">
                                                                    <a href="javascript:;"
                                                                       data-repeater-delete=""
                                                                       class="btn btn-text-dark-50 btn-icon-danger font-weight-bold">
                                                                        <i class="la la-trash-o"></i></a>
                                                                </td>
                                                            </tr>
                                                        @else
                                                            @php $i=0;
                                                            @endphp

                                                            @foreach($gridItem as $key=>$value)
                                                                <tr data-repeater-item="">
                                                                    <td class="p-0" width="20%">

                                                                        <select
                                                                            class="item-clr form-control item-select2 grid-item  {{ $errors->has('grid_items.'.$i.'.item_id') ? ' is-invalid' : '' }}"
                                                                            name="item_id">
                                                                            @if(isset($value['item_id']) && isset($value['name']))
                                                                                <option
                                                                                    value="{{$value['item_id']}}">{{$value['name']}}</option>
                                                                            @endif
                                                                        </select>
                                                                        @if ($errors->has('grid_items.'.$i.'.item_id'))
                                                                            <span class="invalid-feedback"
                                                                                  role="alert">
                                            <strong>{{ $errors->first('grid_items.'.$i.'.item_id') }}</strong>
                                        </span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="p-0" width="10%">
                                                                        <input type="text"
                                                                               class="form-control grid-item"
                                                                               autocomplete="off"
                                                                               name="p_description"
                                                                               value="{{ $value['p_description'] }}"/>
                                                                    </td>
                                                                    <td class="p-0" width="20%">
                                                                        <select
                                                                            class="form-control select-control"
                                                                            name="unit" id="unit">
                                                                            <option value=""></option>
                                                                            @foreach($unit as $items)
                                                                                <option
                                                                                    value="{{$items->unit_name}}" {{ isset($value['unit']) && $value['unit']==$items->unit_name?'selected':''}}>
                                                                                    {{$items->unit_name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td class="p-0">
                                                                        <input type="number"
                                                                               class="form-control grid-item  {{ $errors->has('grid_items.'.$i.'.qty') ? ' is-invalid' : '' }}"
                                                                               autocomplete="off"
                                                                               name="qty"
                                                                               value="{{ isset($value['qty'])?$value['qty']:''}}"
                                                                               placeholder="1"/>
                                                                        @if ($errors->has('grid_items.'.$i.'.qty'))
                                                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('grid_items.'.$i.'.qty') }}</strong>
                                        </span>
                                                                        @endif
                                                                    </td>


                                                                    <td class="p-0">
                                                                        <input type="number"
                                                                               class="form-control grid-item item-clr"
                                                                               name="rate" autocomplete="off"
                                                                               value="{{ isset($value['rate'])?$value['rate']:0}}"
                                                                               placeholder=""/>
                                                                    </td>
                                                                    <td class="p-0">
                                                                        <input type="text"
                                                                               class="form-control grid-item "
                                                                               name="discount_rate"
                                                                               autocomplete="off"
                                                                               value="{{ isset($value['discount_rate'])?$value['discount_rate']:0}}"
                                                                               placeholder=""/>
                                                                    </td>
                                                                    <td class="p-0">
                                                                        <input type="text"
                                                                               class="form-control grid-item"
                                                                               name="taxable_value"
                                                                               autocomplete="off"
                                                                               value="{{ isset($value['taxable_value'])?$value['taxable_value']:0}}"
                                                                               placeholder=""
                                                                        />
                                                                    </td>
                                                                    <td class="p-0" width="5%">
                                                                        <select
                                                                            class="form-control grid-item"
                                                                            name="taxrate">
                                                                            <option value=""></option>
                                                                            @foreach($taxrate as $items)
                                                                                <option
                                                                                    value="{{$items->tax_rate}}" {{ isset($value['taxrate']) && $value['taxrate']==$items->tax_rate?'selected':''}}>
                                                                                    {{$items->tax_rate}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td class="p-0">
                                                                        <input type="text"
                                                                               class="form-control grid-item bg-light-light"
                                                                               readonly
                                                                               name="cgst_amount"
                                                                               value="{{ isset($value['cgst_amount'])?$value['cgst_amount']:0}}"
                                                                               placeholder=""/>
                                                                    </td>
                                                                    <td class="p-0">
                                                                        <input type="text"
                                                                               class="form-control grid-item bg-light-light"
                                                                               readonly
                                                                               name="sgst_amount"
                                                                               value="{{ isset($value['sgst_amount'])?$value['sgst_amount']:0}}"
                                                                               placeholder=""/>
                                                                    </td>
                                                                    <td class="p-0">
                                                                        <input type="text"
                                                                               class="form-control grid-item bg-light-light"
                                                                               readonly
                                                                               name="igst_amount"
                                                                               value="{{ isset($value['igst_amount'])?$value['igst_amount']:0}}"

                                                                               placeholder=""/>
                                                                    </td>

                                                                    <td class="p-0">
                                                                        <input type="text"
                                                                               class="form-control grid-item bg-light-light"
                                                                               readonly
                                                                               name="item_total_amount"
                                                                               autocomplete="off"
                                                                               value="{{ isset($value['item_total_amount'])?$value['item_total_amount']:0}}"

                                                                               placeholder=""/>
                                                                    </td>
                                                                    <td class="p-0 align-middle border-0">
                                                                        <a href="javascript:;"
                                                                           data-repeater-delete=""
                                                                           class="btn btn-text-dark-50 btn-icon-danger font-weight-bold">
                                                                            <i class="la la-trash-o"></i></a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="form-group row ">
                                                <label class="col-lg-2 col-form-label text-right"></label>
                                                <div class="col-lg-4">
                                                    <a href="javascript:;" data-repeater-create=""
                                                       class="btn btn-sm font-weight-bolder btn-light-primary add-new ">
                                                        <i class="la la-plus"></i>Add</a>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-lg-1 col-form-label">Notes</label>
                                                <div class="col-lg-6">

                                                    <textarea name="notes" rows="3"
                                                              class="form-control">{{(isset($inquiry))?$inquiry->notes:old('notes')}}</textarea>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>


                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-lg-4"></div>
                                        <div class="col-lg-4">
                                            <button type="submit" class="btn btn-primary mr-2" id="submit"><i
                                                    class="fas fa-save"></i>Save
                                            </button>
                                            <button type="reset" class="btn btn-secondary"><i
                                                    class="ki ki-bold-close icon-md"></i>Reset
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('customer.new_customer')
        @include('itemmaster.new_item')




    @endsection
    @push('styles')
        <style>
            .grid-item:focus {
                border-radius: 0 !important;
                border: 1px solid transparent;
            !important;
                outline: 1px solid #0099e5 !important;
            }

            .grid-item {
                outline: none;
            !important;
                border-radius: 0;
            !important;
                border: 0;
            !important;
                min-width: 80px !important;
            }
        </style>
    @endpush
    @push('scripts')


        <script
            src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/js/pages/crud/forms/widgets/bootstrap-touchspin.js'}}"></script>
        <script
            src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=7.0.5'}}"></script>
        <script>
            $(document).ready(function () {
                $('.select2-control').select2({
                    placeholder: "Select ...",
                    allowClear: false
                });
            });


        </script>
        <script>
            $(document).ready(function () {
                $('.select-control').select2({
                    placeholder: "Select ...",
                    allowClear: false
                });
            });


        </script>
        <script>


            $(document).on('keyup', ".grid-item", function () {
                calculateGridValues(this);
            });

            $(document).on('change', ".grid-item", function () {
                calculateGridValues(this);
            });

            $(document).on('click', ".grid-item", function () {
                calculateGridValues(this);
            });

            $(document).on('change', ".pf", function () {
                calculateGridValues(this);
            });
            $(document).on('click', ".pf", function () {
                calculateGridValues(this);
            });
            $(document).on('keyup', ".pf", function () {
                calculateGridValues(this);
            });

            calculateGridItemTotal = function (target) {
                //Calculate all grid items total
                var sum = 0;
                jQuery('input[name*="item_total_amount"]').each(function () {
                    if (!isNaN(this.value) && this.value.length != 0) {
                        sum += parseFloat($(this).val());
                    }
                });
                jQuery('input[name="total"]').val(sum);

                var pf = parseFloat(jQuery('input[name="pf"]').val());
                var pf_taxrate = parseFloat(jQuery('select[name="pf_taxrate"]').val());
                pf = isNaN(pf) ? 0 : pf;
                pf_taxrate = isNaN(pf_taxrate) ? 0 : pf_taxrate;
                var pfgstrate = ((pf * pf_taxrate) / 100);
                var totalPF = ((pf + pfgstrate) + sum);
                jQuery('input[name="total_with_pf"]').val(totalPF);

                var rounding_amount = parseFloat(jQuery('input[name="rounding_amount"]').val());
                rounding_amount = isNaN(rounding_amount) ? 0 : rounding_amount;
                var finalAmount = rounding_amount + totalPF;
                jQuery('input[name="grand_total"]').val(finalAmount);


            };
            calculateGridValues = function (target) {
                var inputName = $(target).attr('name');
                var matches = inputName.match(/\[(.*?)\]/);
                if (matches) {
                    var gridRowID = matches[1];
                    var qtyName = "grid_items[" + gridRowID + "][qty]";
                    var qty = parseFloat($("input[name='" + qtyName + "']").val());
                    qty = isNaN(qty) ? 0 : qty;
                    var rateName = "grid_items[" + gridRowID + "][rate]";
                    var rate = parseFloat($("input[name='" + rateName + "']").val());
                    rate = isNaN(rate) ? 0 : rate;
                    var discountType = $("input[name='discount_type']:checked").val();
                    var discountRateName = "grid_items[" + gridRowID + "][discount_rate]";
                    var discountRate = parseFloat($("input[name='" + discountRateName + "']").val());
                    discountRate = isNaN(discountRate) ? 0 : discountRate;
                    var taxableValueName = "grid_items[" + gridRowID + "][taxable_value]";
                    var taxRateName = "grid_items[" + gridRowID + "][taxrate]";
                    var taxRate = $("select[name='" + taxRateName + "']").val();
                    taxRate = isNaN(taxRate) ? 0 : taxRate;
                    var cgstAmountName = "grid_items[" + gridRowID + "][cgst_amount]";
                    var sgstAmountName = "grid_items[" + gridRowID + "][sgst_amount]";
                    var igstAmountName = "grid_items[" + gridRowID + "][igst_amount]";
                    var itemTotalAmountName = "grid_items[" + gridRowID + "][item_total_amount]";
                    var taxableWithOutDiscount = parseFloat(rate * qty);

                    var discountAmount = 0;
                    var taxableWithDiscount = 0;
                    if (discountType === 'P') {
                        discountAmount = parseFloat((taxableWithOutDiscount * discountRate) / 100);
                    } else if (discountType === 'R') {
                        discountAmount = parseFloat(discountRate);
                    }
                    taxableWithDiscount = taxableWithOutDiscount - discountAmount;
                    taxableWithDiscount = isNaN(taxableWithDiscount) ? 0 : taxableWithDiscount;
                    $("input[name*='" + taxableValueName + "']").val(taxableWithDiscount);
                    var gstTaxAmount = parseFloat((taxableWithDiscount * taxRate) / 100).toFixed(3);
                    gstTaxAmount = isNaN(gstTaxAmount) ? 0 : gstTaxAmount;
                    var cgstAmount = parseFloat(gstTaxAmount / 2).toFixed(3);
                    cgstAmount = isNaN(cgstAmount) ? 0 : cgstAmount;
                    var sgstAmount = parseFloat(gstTaxAmount / 2).toFixed(3);
                    sgstAmount = isNaN(sgstAmount) ? 0 : sgstAmount;
                    var igstAmount = parseFloat(gstTaxAmount).toFixed(3);
                    igstAmount = isNaN(igstAmount) ? 0 : igstAmount;
                    $("input[name*='" + cgstAmountName + "']").val(cgstAmount);
                    $("input[name*='" + sgstAmountName + "']").val(sgstAmount);
                    $("input[name*='" + igstAmountName + "']").val(igstAmount);
                    var totalItemAmount = (parseFloat(taxableWithDiscount) + parseFloat(gstTaxAmount)).toFixed(3);
                    totalItemAmount = isNaN(totalItemAmount) ? 0 : totalItemAmount;
                    $("input[name*='" + itemTotalAmountName + "']").val(totalItemAmount);


                }
                calculateGridItemTotal();
            };


        </script>
        <script>
            $(document).ready(function () {
                $('#kt_repeater_1').repeater({
                    initEmpty: false,

                    defaultValues: {
                        'text-input': 'foo'
                    },

                    show: function () {
                        $(this).slideDown();
                        $(".item-select2").select2({
                            placeholder: "Select Product",
                            allowClear: false,

                            ajax: {
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                url: "{{ url('item-list')}}",
                                type: "post",
                                dataType: 'json',
                                delay: 250,
                                data: function (params) {
                                    return {
                                        searchTerm: params.term
                                    };
                                }, processResults: function (response) {
                                    return {results: response};
                                }, cache: true
                            }
                        });
                    },

                    hide: function (deleteElement) {
                        $(this).slideUp(deleteElement);
                    }
                });
                $(".item-select2").select2({
                    placeholder: "Select Product",
                    allowClear: true,
                    ajax: {
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: "{{ url('item-list')}}",
                        type: "post",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                searchTerm: params.term
                            };
                        }, processResults: function (response) {
                            return {results: response};
                        }, cache: true
                    }
                });
                // $('select[name="grid_items[0][item_id]"]').trigger("change");
            });

            $(".customer-select2").select2({
                placeholder: "Select", ajax: {
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: "{{ url('customer-list')}}",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params.term
                        };
                    }, processResults: function (response) {
                        return {results: response};
                    }, cache: true
                }
            });

            $(document).on('change', "select.client-clr", function () {
                var customer_id = $(this).attr('name');
                var item_id = customer_id.replace('customer_id', 'customer_id');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ url('get-customer-data')}}',
                    type: 'POST',
                    data: {customer_id: $(this).val()},
                    success: function (data) {
                        $('#f_phone_no').val(data.f_phone_no),
                            $('#email').val(data.email);
                        $('#contact_person_name').val(data.contact_person_name);
                    }
                });
            });
            $(document).on('change', "select.item-clr", function () {
                if ($(this).val() !== 'NEW') {
                    var item_id = $(this).attr('name');
                    var saleRateName = item_id.replace('item_id', 'rate');
                    var taxRateName = item_id.replace('item_id', 'taxrate');
                    var UnitName = item_id.replace('item_id', 'unit');
                    var DiscountAmount = item_id.replace('item_id', 'discount_rate');
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '{{ url('get-item-data')}}',
                        type: 'POST',
                        data: {item_id: $(this).val()},
                        success: function (data) {

                            $("input[name='" + saleRateName + "']").val(data.sale_rate);
                            $("select[name='" + taxRateName + "']").val(data.taxrate);
                            $("input[name='" + DiscountAmount + "']").val(data.discount);
                            $("select[name='" + UnitName + "']").val(data.unit);

                        }
                    });
                }
            })

        </script>

        <script type="text/javascript">

            $(document).ready(function () {

                $("#my-form").submit(function (e) {

                    $("#submit").attr("disabled", true);

                    return true;

                });
            });

        </script>
    @endpush


















