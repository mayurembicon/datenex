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
                            Sales Return </h5>
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
                                    Sales Return
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
                    <a href="{{route('sales-return.index')}}" class="btn  font-weight-bolder btn-sm">
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
                            <h3 class="card-title">Sales Return</h3>
                            <div class="card-toolbar">
                                <div class="example-tools justify-content-center">

                                <span class="example-copy" data-toggle="tooltip" title=""
                                      data-original-title="Copy code"></span>
                                </div>
                            </div>
                        </div>
                        <!--begin::Form-->
                        <form
                            action="{{ (isset($salesReturn))?route('sales-return.update',$salesReturn->sales_return_id):route('sales-return.store')}}"
                            method="post">
                            @csrf
                            @if(isset($salesReturn))
                                @method('PUT')
                            @endif

                            {{--                            <input type="hidden" name="quotation_id" value="{{(isset($invoice->quotation_id))?$invoice->quotation_id:''}}">--}}

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="k-section k-section--first">
                                            <div class="k-section__body">
                                                <div class="form-group row">
                                                    <label class="col-4 col-form-label">Company Name<span
                                                            class="text-danger">*</span></label>
                                                    <div class="col-lg-6">
                                                        <select
                                                            class="client-clr form-control customer-select2 {{ $errors->has('customer_id') ? ' is-invalid' : '' }}"
                                                            name="customer_id" id="customer_id" data-live-search="true"
                                                            onchange="customermodelopen()">
                                                            @if(!empty($salesReturn))
                                                                <option
                                                                    value="{{(isset($salesReturn))?$salesReturn->customer_id:(old('customer_id')?old('customer_id'):0)}}">
                                                                    {{ isset($salesReturn->customer)?$salesReturn->customer->company_name:''}}
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
                                                <div class="form-group row">
                                                    <label class="col-4 col-form-label">Original Invoice No<span
                                                            class="text-danger">*</span></label>
                                                    <div class="col-lg-4">
                                                        <select
                                                            class="client-clr form-control invoice-no-select2 {{ $errors->has('original_invoice_no') ? ' is-invalid' : '' }}"
                                                            name="original_invoice_no" id="invoice_id" data-live-search="true">
                                                            @if(!empty($salesReturn) && $salesReturn->invoice_id!=0)
                                                                <option

                                                                    value="{{$salesReturn->invoice_id}}">
                                                                    {{ isset($salesReturn->salesInvoice)?$salesReturn->salesInvoice->invoice_no:''}}
                                                                </option>
                                                            @endif
                                                                @if(!empty($salesReturn) && $salesReturn->invoice_id==0)
                                                                <option
                                                                    value="NEW_{{$salesReturn->original_invoice_no}}">
                                                                    {{ isset($salesReturn->original_invoice_no)?$salesReturn->original_invoice_no:''}}
                                                                </option>
                                                            @endif
                                                        </select>
                                                        @if ($errors->has('original_invoice_no'))
                                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('original_invoice_no') }}</strong>
                                    </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-4 col-form-label">Original Invoice Date</label>
                                                    <div class="col-lg-4">
                                                        <div class="input-group date">
                                                            <input type="text"
                                                                   name="original_invoice_date" autocomplete="off"
                                                                   class="form-control  @error('original_invoice_date') is-invalid @enderror"
                                                                   placeholder="dd-mm-yyyy"
                                                                   id="kt_datepicker_3"
                                                                   value="{{(isset($salesReturn))?date('d-m-Y',strtotime($salesReturn->original_invoice_date)):old('original_invoice_date')}}">
                                                            <div class="input-group-append">
															<span class="input-group-text">
																<i class="la la-calendar"></i>
															</span>
                                                            </div>
                                                            @error('original_invoice_date')
                                                            <div class="invalid-feedback"
                                                                 role="alert">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-4 col-form-label">Sales Return Date</label>
                                                    <div class="col-lg-4">
                                                        <div class="input-group date">
                                                            <input type="text"
                                                                   name="sales_return_date" autocomplete="off"
                                                                   class="form-control  @error('sales_return_date') is-invalid @enderror"
                                                                   placeholder="dd-mm-yyyy"
                                                                   id="kt_datepicker_3"
                                                                   value="{{(isset($salesReturn))?date('d-m-Y',strtotime($salesReturn->sales_return_date)):old('sales_retun_date')}}">
                                                            <div class="input-group-append">
															<span class="input-group-text">
																<i class="la la-calendar"></i>
															</span>
                                                            </div>
                                                            @error('sales_return_date')
                                                            <div class="invalid-feedback"
                                                                 role="alert">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="k-section k-section--first">
                                            <div class="k-section__body">
                                                <div class="pb-5 font-size-h6-lg">Billing Address:</div>
                                                <div class="form-group row">
                                                    <label class="col-4 col-form-label ">Country</label>
                                                    <div class="col-lg-6">
                                                        <select
                                                            class="form-control country-select2 {{ $errors->has('country_id') ? ' is-invalid' : '' }}"
                                                            name="country_id">
                                                            @if(!empty($billing_address))
                                                                <option
                                                                    value="{{(isset($billing_address))?$billing_address->country_id:(old('country_id')?old('country_id'):0)}}">
                                                                    {{ isset($billing_address->country)?$billing_address->country->country_name:''}}
                                                                </option>
                                                            @endif

                                                        </select>
                                                        @if ($errors->has('country_id'))
                                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('country_id') }}</strong>
                                    </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-4 col-form-label">State</label>
                                                    <div class="col-lg-6">

                                                        <select
                                                            class="form-control state-select2  @error('state_id') is-invalid @enderror"
                                                            name="state_id"
                                                            id="billing_state_id">
                                                            @if(!empty($billing_address))
                                                                <option
                                                                    value="{{(isset($billing_address))?$billing_address->state_id:(old('state_id')?old('state_id'):0)}}">
                                                                    {{ isset($billing_address->state)?$billing_address->state->state_name:''}}
                                                                </option>
                                                            @endif
                                                        </select>
                                                        @error('state_id')
                                                        <div class="invalid-feedback"
                                                             role="alert">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-4 col-form-label">City</label>
                                                    <div class="col-lg-4">
                                                        <input
                                                            class="form-control  @error('city_name') is-invalid @enderror"
                                                            type="text"
                                                            placeholder="City Name" name="city_name"
                                                            value="{{(isset($billing_address))?$billing_address->city_name:old('city_name')}}"
                                                            id="city_name">
                                                        @error('city_name')
                                                        <div class="invalid-feedback"
                                                             role="alert">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-4 col-form-label">Zip / Postal
                                                        Code</label>
                                                    <div class="col-lg-4">
                                                        <input
                                                            class="form-control  @error('zip_code') is-invalid @enderror"
                                                            type="text"

                                                            placeholder="000000" name="zip_code"
                                                            value="{{(isset($billing_address))?$billing_address->zip_code:old('zip_code')}}"
                                                            id="billing_pincode">
                                                        @error('zip_code')
                                                        <div class="invalid-feedback"
                                                             role="alert">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-4 col-form-label">Address</label>
                                                    <div class="col-lg-6">
                                                    <textarea
                                                        class="form-control  @error('address') is-invalid @enderror "
                                                        name="address"
                                                        id="billing_address">{{(isset($billing_address))?$billing_address->address:old('address')}}</textarea>
                                                        @error('address')
                                                        <div class="invalid-feedback"
                                                             role="alert">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-4 col-form-label">Shipping Address same as
                                                        Billing</label>
                                                    <div class="col-2">

                                                           <span
                                                               class="switch switch-outline switch-icon switch-primary">
									<label>
									<input type="checkbox"
                                           class="" value="Y" {{(empty($billing_address))?'checked':'' }}
                                           {{(!empty($billing_address))&& ($billing_address->shipping_same_as_billing == 'Y')?'checked':'' }}
                                           name="shipping_same_as_billing"
                                           id="shipping_same_as_billing">
									<span></span>
								</label>
							</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="shipping k-section" style="display: none">
                                            <div class="k-section__body">
                                                <div class="pb-5 font-size-h6-lg">Shipping
                                                    Address:</div>
                                                <div class="form-group row">
                                                    <label class="col-4 col-form-label">Country</label>
                                                    <div class="col-lg-5">
                                                        <select
                                                            class="form-control  country-select2  @error('country_id') is-invalid @enderror"
                                                            name="shipping_country_id"
                                                            id="shipping_country_id">
                                                            @if(!empty($shipping_address))
                                                                <option
                                                                    value="{{(isset($shipping_address))?$shipping_address->country_id:(old('country_id')?old('country_id'):0)}}">
                                                                    {{ isset($shipping_address->country)?$shipping_address->country->country_name:''}}
                                                                </option>
                                                            @endif
                                                        </select>
                                                        @error('country_id')
                                                        <div class="invalid-feedback"
                                                             role="alert">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-4 col-form-label">State</label>
                                                    <div class="col-lg-5">
                                                        <select
                                                            class="form-control  state-select2  @error('state_id') is-invalid @enderror"
                                                            name="shipping_state_id"
                                                            id="shipping_state_id">
                                                            @if(!empty($shipping_address))
                                                                <option
                                                                    value="{{(isset($shipping_address))?$shipping_address->state_id:(old('state_id')?old('state_id'):0)}}">
                                                                    {{ isset($shipping_address->state)?$shipping_address->state->state_name:''}}
                                                                </option>
                                                            @endif
                                                        </select>
                                                        @error('state_id')
                                                        <div class="invalid-feedback"
                                                             role="alert">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-4 col-form-label">City</label>
                                                    <div class="col-lg-4">
                                                        <input
                                                            class="form-control  @error('shipping_city_name') is-invalid @enderror"
                                                            type="text"
                                                            placeholder="City Name" name="shipping_city_name"
                                                            value="{{(isset($shipping_address))?$shipping_address->city_name:old('shipping_city_name')}}"
                                                            id="shipping_city_name">
                                                        @error('shipping_city_name')
                                                        <div class="invalid-feedback"
                                                             role="alert">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-4 col-form-label">Zip / Postal
                                                        Code</label>
                                                    <div class="col-lg-4">
                                                        <input
                                                            class="form-control"
                                                            type="number"
                                                            value="{{(isset($shipping_address))?$shipping_address->shipping_pincode:old('shipping_pincode')}}"
                                                            placeholder="000000" name="shipping_pincode"
                                                            id="shipping_pincode">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-4 col-form-label">Address</label>
                                                    <div class="col-lg-6">
                                                    <textarea class="form-control"
                                                              name="shipping_address"
                                                              id="shipping_address">{{(isset($shipping_address))?$shipping_address->shipping_address:old('shipping_address')}}</textarea>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

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

                                                <tbody data-repeater-list="grid_items">
                                                <div class="k-repeater-grid-items">
                                                    @php $gridItem=!empty(old('grid_items'))?old('grid_items'):(empty($salesitems)?[]:$salesitems);
$i=0;
                                                    @endphp
                                                    @if(!$gridItem)

                                                        <tr data-repeater-item="">

                                                            <td class="p-0" width="20%">

                                                                <select
                                                                    class="item-clr form-control item-select2   grid-item {{ $errors->has('grid_items.'.$i.'.item_id') ? ' is-invalid' : '' }} "
                                                                    data-live-search="true"
                                                                    name="item_id">


                                                                </select>
                                                                @if ($errors->has('grid_items.'.$i.'.item_id'))
                                                                    <span class="invalid-feedback" role="alert">
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
                                                                       class="form-control grid-item"
                                                                       name="rate" autocomplete="off"
                                                                       placeholder="rate"/>
                                                            </td>
                                                            <td class="p-0">
                                                                <input type="text"
                                                                       class="form-control grid-item"
                                                                       name="discount_rate"
                                                                       autocomplete="off"
                                                                       placeholder=" "/>
                                                            </td>
                                                            <td class="p-0">
                                                                <input type="text"
                                                                       class="form-control grid-item"
                                                                       name="taxable_value"
                                                                       autocomplete="off"
                                                                       placeholder=""/>
                                                            </td>

                                                            <td class="p-0">
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
                                                                   class="btn btn-text-dark-50 btn-icon-danger font-weight-bold clickDelete">
                                                                    <i class="la la-trash-o"></i></a>
                                                            </td>
                                                        </tr>
                                                    @else
                                                        @php $i=0 @endphp
                                                        @foreach($gridItem as $key=>$value)

                                                            <tr data-repeater-item="">
                                                                <td class="p-0" width="20%">

                                                                    <select
                                                                        class="form-control item-select2   grid-item  {{ $errors->has('grid_items.'.$i.'.item_id') ? ' is-invalid' : '' }}"
                                                                        name="item_id">

                                                                        @if(isset($value['item_id']) && isset($value['name']))
                                                                            <option
                                                                                value="{{$value['item_id']}}">{{$value['name']}}</option>
                                                                        @endif
                                                                    </select>
                                                                    @if ($errors->has('grid_items.'.$i.'.item_id'))
                                                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('grid_items.'.$i.'.item_id') }}</strong>
                                    </span>
                                                                    @endif
                                                                </td>
                                                                <td class="p-0" width="20%">
                                                                    <input type="text"
                                                                           class="form-control grid-item"
                                                                           autocomplete="off"
                                                                           name="p_description"
                                                                           value="{{ isset($value['p_description'])?$value['p_description']:""}}"/>
                                                                </td>
                                                                <td class="p-0">
                                                                    <input type="number"
                                                                           class="form-control grid-item {{ $errors->has('grid_items.'.$i.'.qty') ? ' is-invalid' : '' }}"
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
                                                                           class="form-control grid-item"
                                                                           name="rate" autocomplete="off"
                                                                           value="{{ isset($value['rate'])?$value['rate']:0}}"
                                                                           placeholder=""/>
                                                                </td>
                                                                <td class="p-0">
                                                                    <input type="text"
                                                                           class="form-control grid-item"
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
                                                                <td class="p-0">
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
                                                                       class="btn btn-text-dark-50 btn-icon-danger font-weight-bold clickDelete">
                                                                        <i class="la la-trash-o"></i></a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        @php $i++; @endphp
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
                                            <label
                                                class="col-form-label text-right col-lg-8 col-sm-5">Total </label>
                                            <div class="col-lg-2 ">
                                                <input type="text" name="total" readonly
                                                       class="pf form-control  @error('total') is-invalid @enderror "
                                                       placeholder=""
                                                       value="{{(isset($salesReturn))?$salesReturn->total:old('total')}}">
                                                @error('total')
                                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class=" col-form-label text-right col-lg-8 col-sm-5 ">P & F /
                                                Freight </label>
                                            <div class="col-lg-2 ">
                                                <input type="text" name="pf"
                                                       class="pf form-control  @error('pf') is-invalid @enderror "
                                                       placeholder=""
                                                       value="{{(isset($salesReturn))?$salesReturn->pf:old('pf')}}">
                                                @error('pf')
                                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label text-right col-lg-8 col-sm-5">Tax
                                                Rate</label>
                                            <div class="col-lg-2 ">
                                                <select
                                                    class="pf form-control  @error('pf_taxrate') is-invalid @enderror"
                                                    name="pf_taxrate">
                                                    <option
                                                        value="">
                                                        select
                                                    </option>
                                                    @foreach($taxrate  as $item)
                                                        <option
                                                            value="{{$item->tax_rate}}" {{(isset($salesReturn) &&  $salesReturn->pf_taxrate==$item->tax_rate)?'selected':''}}
                                                            {{ ((old('pf_taxrate')==$item->tax_rate)?'selected': '') }}>
                                                            {{$item->tax_rate}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-form-label text-right col-lg-8 col-sm-5">Total
                                                PF </label>
                                            <div class="col-lg-2">
                                                <input type="text" name="total_with_pf" readonly
                                                       class="form-control pf  @error('total_with_pf') is-invalid @enderror "
                                                       placeholder=""
                                                       value="{{(isset($salesReturn))?$salesReturn->total_with_pf:old('total_with_pf')}}">
                                                @error('total_with_pf')
                                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                class="col-form-label text-right col-lg-8 col-sm-5">Rounding</label>
                                            <div class="col-lg-2">
                                                <input type="number" step="any"
                                                       name="rounding_amount" class="form-control pf
                                                    @error('rounding_amount') is-invalid @enderror " id="rounding"
                                                       value="{{(isset($salesReturn))?$salesReturn->rounding_amount:old('rounding_amount')}}"
                                                       placeholder="Rounding Amount"/>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class=" col-form-label text-right col-lg-8 col-sm-5">Grand
                                                Total</label>
                                            <div class="col-lg-2 ">
                                                <input type="text" name="grand_total" readonly
                                                       class=" form-control pf  @error('grand_total') is-invalid @enderror "
                                                       placeholder=" " disabled
                                                       value="{{(isset($salesReturn))?$salesReturn->grand_total:old('grand_total')}}">
                                                @error('grand_total')
                                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="separator separator-dashed my-10"></div>
                                    <div class="pb-5 font-size-h6-lg"> Receiver Material Docket & Transport Deteils:</div>
                                    <div class="form-group row">

                                        <div class="col-lg-3">
                                            <label>Delivery Location</label>
                                            <input type="text" name="delivery_location"
                                                   class="form-control @error('delivery_location') is-invalid @enderror"
                                                   placeholder="Delivery Location" autocomplete="off"
                                                   value="{{(!empty($docketdeteils->delivery_location))?$docketdeteils->delivery_location:old('delivery_location')}}">
                                            @error('delivery_location')
                                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3">
                                            <label>Courier Name</label>
                                            <input type="text" name="courier_name"
                                                   class="form-control @error('courier_name') is-invalid @enderror"
                                                   placeholder="Courier Name" autocomplete="off"
                                                   value="{{(!empty($docketdeteils->courier_name))?$docketdeteils->courier_name:old('courier_name')}}">
                                            @error('courier_name')
                                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3">
                                            <label>Tracking Number</label>
                                            <input type="text" name="tracking_no"
                                                   class="form-control @error('tracking_no') is-invalid @enderror"
                                                   placeholder="Tracking Number" autocomplete="off"
                                                   value="{{(!empty($docketdeteils->tracking_no))?$docketdeteils->tracking_no:old('tracking_no')}}">
                                            @error('tracking_no')
                                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="separator separator-dashed my-10"></div>
{{--                                    <div class="pb-5 font-size-h6-lg">Transport Deteils:</div>--}}
                                    <div class="form-group row">
                                        <div class="col-lg-3">
                                            <label>Desp Through</label>
                                            <input type="text" name="desp_through"
                                                   class="form-control @error('desp_through') is-invalid @enderror"
                                                   placeholder="Desp Through" autocomplete="off"
                                                   value="{{(isset($transportdeteils))?$transportdeteils->desp_through:old('desp_through')}}">
                                            @error('desp_through')
                                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3">
                                            <label>Transport GST ID</label>
                                            <input type="text" name="transport_id"
                                                   class="form-control @error('transport_id') is-invalid @enderror"
                                                   placeholder="Transport GSTID" autocomplete="off"
                                                   pattern="^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$"
                                                   value="{{(isset($transportdeteils))?$transportdeteils->transport_id:old('transport_id')}}">
                                            @error('transport_id')
                                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-lg-3">
                                            <label>Lorry No</label>
                                            <input type="text" name="lorry_no"
                                                   class="form-control @error('lorry_no') is-invalid @enderror"
                                                   placeholder="Lorry No" autocomplete="off"
                                                   value="{{(isset($transportdeteils))?$transportdeteils->lorry_no:old('lorry_no')}}">
                                            @error('lorry_no')
                                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="separator separator-dashed my-10"></div>

                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label>Place Of Supply</label>
                                        <input type="text" name="place_of_supply"
                                               class="form-control @error('place_of_supply') is-invalid @enderror"
                                               placeholder="Place Of Supply" autocomplete="off"
                                               value="{{(!empty($transportdeteils->place_of_supply))?$transportdeteils->place_of_supply:old('place_of_supply')}}">
                                        @error('place_of_supply')
                                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label>Notes</label>
                                        <textarea name="notes" id="notes"
                                                  class=" form-control ">{{(isset($salesReturn))?$salesReturn->notes:old('notes')}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-5"></div>
                                    <div class="col-lg-5">
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
    <div class="modal fade" id="invoice-no-modal" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Invoice No

                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="quotation_id" name="quotation_id" value="">

                    <div class="form-group row">
                        <label class="col-lg-12 col-form-label">Invoice  No</label>
                        <div class="col-lg-6">
                            <input type="text"  id="original_invoice_no"
                                   class="form-control"
                                   name="original_invoice_no" value="">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Close
                    </button>
                    <button type="submit" class="btn btn-primary font-weight-bold" id="save-invoice-no">
                        Save

                    </button>
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

@push('styles')
    <link
        href="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/datatables/datatables.bundle.css?v=7.0.5'}}"
        rel="stylesheet"
        type="text/css"/>
@endpush
@push('scripts')
    <script
        src="{{ env('ASSET_URL','http://localhost/datenex/public/').'assets/plugins/custom/prismjs/prismjs.bundle.js?v=7.0.4'}}"></script>
    <script
        src="{{ env('ASSET_URL','http://localhost/datenex/public/').'assets/js/scripts.bundle.js?v=7.0.4'}}"></script>
    <script
        src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/js/pages/crud/forms/widgets/select2.js'}}"
        type="text/javascript"></script>

    <script
        src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/js/pages/crud/forms/widgets/bootstrap-touchspin.js'}}"></script>
    <script
        src="{{env('ASSET_URL','http://localhost/datenex/public/').'assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js?v=7.0.5'}}"></script>

    <script>



        $(document).on('click', ".clickDelete", function () {
            setTimeout(function () {
                calculateGridItemTotal();
            }, 500);

        });


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

        $(document).on('change', "select.client-clr", function () {
            var customer_id = $(this).attr('name');
            var item_id = customer_id.replace('customer_id', 'customer_id');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ url('get-customer-items')}}',
                type: 'POST',
                data: {customer_id: $(this).val()},
                success: function (data) {
                    $('#customer_notes').val(data.customer_notes);
                }
            });
        });

        $(document).on('change', "select.item-clr", function () {
            var item_id = $(this).attr('name');
            var saleRateName = item_id.replace('item_id', 'rate');
            var taxRateName = item_id.replace('item_id', 'taxrate');
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

                }
            });
        })

        $(window).on('load', function () {
            calculateGridItemTotal();
        });


    </script>





    <script>
        $(document).ready(function () {
            $('.select2-control').select2({
                placeholder: "Select ...",
                allowClear: true
            });
        });

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
            placeholder: "Select a Customer", ajax: {
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

        $(".country-select2").change(function () {
            let countryID = $(this).val();
            $(".state-select2").select2({
                placeholder: "Select..", ajax: {
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: "{{ url('state-list')}}",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params.term,
                            country_id: countryID,
                        };
                    }, processResults: function (response) {
                        return {results: response};
                    }, cache: true
                }
            });
        });
        $(".country-select2").select2({
            placeholder: "Select..", ajax: {
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ url('country-list')}}",
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
    </script>

    <script>
        $(document).ready(function () {

            $(".invoice-no-select2").select2({
                placeholder: "Select..", ajax: {
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: "{{ url('invoice-no-list')}}",
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
            $('.invoice-no-select2').on('select2:select', function (e) {
                var data = e.params.data;
                if (data.id === 'NEW') {
                    $('#invoice-no-modal').modal('show')
                }
            });
            $('#save-invoice-no').click(function () {
                $('#customer-modal-body').removeClass('is-invalid');
                $("#new_category_name").removeClass("is-invalid");


                $("#new_category_name_alert").html("");


                $('#original_invoice_no').val();

                var newOption = new Option($('#original_invoice_no').val(), 'NEW_'+$('#original_invoice_no').val(), false, true);
                $('#invoice_id').append(newOption).trigger('change');
                $('#invoice-no-modal').modal('hide');
            });
            //Disable Shipping Address
            $("#shipping_same_as_billing").change(function () {
                console.log(this.checked);
                if (this.checked) {
                    $(".shipping").hide();

                } else {
                    $(".shipping").show();

                }
            });

            @php
                if(!empty($billing_address->shipping_same_as_billing) && $billing_address->shipping_same_as_billing == 'N'){
            @endphp
            $(".shipping").show();
            $("#shipping_same_as_billing").change();
            @php
                }
            @endphp
        });
    </script>

@endpush
