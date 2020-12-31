<div class="form-group row">

    <div class="col-lg-3">
        <label>Customer Type</label>
        <select
            class="form-control "
            name="customer_type" id="customer_type" {{ $errors->has('customer_type') ? ' is-invalid' : '' }}>
            <option value="">Select..</option>
            <option
                value="V"{{ !empty($customer->customer_type) && $customer->customer_type=='V' ? 'selected':''}} {{ ((old('customer_type')=='V')?'selected': '') }}>
                Vendor
            </option>
            <option
                value="C"{{ !empty($customer->customer_type) && $customer->customer_type=='C' ? 'selected':''}} {{ ((old('customer_type')=='C')?'selected': '') }}>
                Customer
            </option>
            <option
                value="B"{{ !empty($customer->customer_type) && $customer->customer_type=='B' ? 'selected':''}} {{ ((old('customer_type')=='B')?'selected': '') }}>
                Both
            </option>
        </select>
        <div id="customer_type_alert" class="invalid-feedback" role="alert"></div>

    @if ($errors->has('customer_type'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('customer_type') }}</strong>
            </span>
        @endif
    </div>
    <div class="col-lg-3">
        <label>Company Name<span class="text-danger">*</span></label>
        <input type="text" name="company_name" autocomplete="off" id="company_name"
               class="form-control   {{ $errors->has('company_name') ? ' is-invalid' : '' }}"
               placeholder="Company Name"
               value="{{(isset($customer))?$customer->company_name:old('company_name')}}">
        <div id="company_name_alert" class="invalid-feedback" role="alert"></div>
        @if ($errors->has('company_name'))
            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('company_name') }}</strong>
                                        </span>
        @endif
    </div>
    <div class="col-lg-3">
        <label>Contact Name<span
                class="text-danger">*</span></label>
        <input type="text" name="customer_name" autocomplete="off" id="customer_name"
               class="form-control {{ $errors->has('customer_name') ? ' is-invalid' : '' }}"
               placeholder="Contact Name"
               value="{{(isset($customer))?$customer->customer_name:old('customer_name')}}">
        <div id="customer_name_alert" class="invalid-feedback" role="alert"></div>

        @if ($errors->has('customer_name'))
            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('customer_name') }}</strong>
                                        </span>
        @endif
    </div>
</div>
<div class="form-group row">

    <div class="col-lg-3">

        <label>Opening Balance </label>
        <input type="number" name="opening_balance" step="any"
               class="form-control  @error('opening_balance') is-invalid @enderror"
               placeholder="Opening Balance"
               value="{{(!empty($customerOpeningBalance->opening_balance))?$customerOpeningBalance->opening_balance:old('opening_balance')}}">
        @error('opening_balance')
        <div class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-lg-3">
        <label>Open Balance Type </label>
        <select
            class="form-control {{ $errors->has('opening_balance_type') ? ' is-invalid' : '' }}"
            name="opening_balance_type">
            <option
                value="C" {{(!empty($customerOpeningBalance->opening_balance_type)&& $customerOpeningBalance->opening_balance_type=='C')?'selected':old('C')}}>
                Credit
            </option>
            <option
                value="D"{{(!empty($customerOpeningBalance->opening_balance_type)&& $customerOpeningBalance->opening_balance_type=='D')?'selected':old('D')}}>
                Debit
            </option>
        </select>
        @if ($errors->has('opening_balance_type'))
            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('opening_balance_type') }}</strong>
                                    </span>
        @endif
    </div>
    <div class="col-lg-3">
        <label> Email Address</label>
        <div class="input-group">

            <div class="input-group-prepend">
                <span class="input-group-text">@</span>
            </div>
            <input type="email" name="email" autocomplete="off" id="email"
                   class="form-control  @error('email') is-invalid @enderror"
                   placeholder="username@mail.com"
                   value="{{(isset($customer))?$customer->email:old('email')}}">
            <div id="email_alert" class="invalid-feedback" role="alert"></div>
        </div>
        @error('email')
        <div class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="form-group row">
    <div class="col-lg-3">
        <label>Phone No <span class="text-danger">*</span></label>
        <div class="input-group">

            <div class="input-group-prepend">
                    <span class="input-group-text">
                    <i class="la la-phone"></i>
                    </span>
            </div>
            <input type="text" name="f_phone_no" autocomplete="off" id="phone_no" maxlength="15" minlength="10"
                   class="form-control  {{ $errors->has('f_phone_no') ? ' is-invalid' : '' }}"
                   placeholder="+91 0000000000"
                   value="{{(isset($customer))?$customer->f_phone_no:old('f_phone_no')}}">
            <div id="phone_no_alert" class="invalid-feedback" role="alert"></div>
            @if ($errors->has('f_phone_no'))
                <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('f_phone_no') }}</strong>
            </span>
            @endif
        </div>

    </div>
    <div class="col-lg-3">
        <label>Phone No</label>
        <div class="input-group">
            <div class="input-group-prepend">
                    <span class="input-group-text">
                    <i class="la la-phone"></i>
                    </span>
            </div>
            <input type="text" name="s_phone_no" autocomplete="off" id="s_phone_no" maxlength="15" minlength="10"
                   class="form-control  {{ $errors->has('s_phone_no') ? ' is-invalid' : '' }}"
                   placeholder="+91 0000000000"
                   value="{{(isset($customer))?$customer->s_phone_no:old('s_phone_no')}}">
            <div id="phone_no_alert" class="invalid-feedback" role="alert"></div>
            @if ($errors->has('s_phone_no'))
                <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('s_phone_no') }}</strong>
            </span>
            @endif
        </div>
    </div>
    <div class="col-lg-3">
        <label>Website</label>
        <div class="input-group">
            <input type="text"
                   name="website" autocomplete="off"
                   class="form-control "
                   placeholder="https://wwww.website.com"
                   value="{{(isset($customer))?$customer->website:old('website')}}">
            <div class="input-group-append"><span
                    class="input-group-text">.com</span>
            </div>
        </div>

    </div>

</div>


<div class="example-preview">
    <ul class="nav nav-light-primary nav-pills" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home">
																	<span class="nav-icon">
																		<i class="flaticon2-chat-1"></i>
																	</span>
                <span class="nav-text">Other Details</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile"
               aria-controls="profile">
																	<span class="nav-icon">
																		<i class="flaticon2-layers-1"></i>
																	</span>
                <span class="nav-text">Address</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact"
               aria-controls="contact">
																	<span class="nav-icon">
																		<i class="flaticon2-user-1"></i>
																	</span>
                <span class="nav-text">Contact Person</span>
            </a>
        </li>

    </ul>

    <div class="tab-content mt-5" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel"
             aria-labelledby="home-tab">


            <div class="form-group row">
                <div class="col-lg-3">
                    <label>Contact Person</label>
                    <input type="text" name="contact_person_name" autocomplete="off" id="contact_person_name"
                           class="form-control  @error('contact_person_name') is-invalid @enderror"
                           placeholder="Contact Person  "
                           value="{{(isset($customer))?$customer->contact_person_name:old('contact_person_name')}}">
                    <div id="contact_person_name_alert" class="invalid-feedback" role="alert"></div>
                    @error('contact_person_name')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-lg-3">
                    <label>GST IN</label>
                    <input type="text" name="gst_no" autocomplete="off" id="gst_no"
                           class="form-control  @error('gst_no') is-invalid @enderror"
                           placeholder="GST IN"
                           pattern="^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$"
                           value="{{(isset($customer))?$customer->gst_no:old('gst_no')}}">
                    <div id="gst_no_alert" class="invalid-feedback" role="alert"></div>
                    @error('gst_no')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-lg-3">
                    <label>Place Of Supply</label>
                    <input type="text" name="place_of_supply" autocomplete="off" id="place_of_supply"
                           class="form-control  @error('place_of_supply') is-invalid @enderror"
                           placeholder="Place Of Supply"
                           value="{{(isset($customer))?$customer->place_of_supply:old('place_of_supply')}}">
                    <div id="place_of_supply_alert" class="invalid-feedback" role="alert"></div>
                    @error('place_of_supply')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-lg-3">
                    <label>Payment Terms</label>
                    <select
                        class="form-control select2-control @error('payment_terms_id') is-invalid @enderror"
                        name="payment_terms_id" id="payment_terms_id">
                        <option
                            value="" {{(isset($customer))?$customer->payment_terms_id:old('payment_terms_id')}}>
                            select
                        </option>
                        @foreach($payment as $key)
                            <option
                                value="{{$key->payment_terms_id}}" {{(isset($customer) &&  $customer->payment_terms_id==$key->payment_terms_id)?'selected':''}}
                                {{ ((old('payment_terms_id')==$key->payment_terms_id)?'selected': '') }}>
                                {{$key->payment_terms}}</option>
                        @endforeach
                    </select>
                    <div id="payment_terms_id_alert" class="invalid-feedback" role="alert"></div>
                    @error('payment_terms_id')
                    <div class="invalid-feedback" role="alert">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-5">
                    <label>Remark</label>
                    <textarea type="text" name="remark" autocomplete="off"
                              class="form-control"
                              placeholder="">{{(isset($customer))?$customer->remark:old('remark')}}</textarea>

                </div>
                <div class="col-lg-5">
                    <label>Notes</label>
                    <textarea type="text" name="notes" autocomplete="off"
                              class="form-control"
                              placeholder="">{{(isset($customer))?$customer->notes:old('notes')}}</textarea>

                </div>
            </div>


        </div>


        <div class="tab-pane fade" id="profile" role="tabpanel"
             aria-labelledby="profile-tab">

            <div class="container">
                <div class="row">
                    <div class="col-6">
                        <h5 style=" padding:10px;text-align: center">BILLING
                            ADDRESS</h5>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Attention</label>
                            <div class="col-lg-8">
                                <input type="text" name="billing_attention"
                                       autocomplete="off"
                                       class="form-control  @error('billing_attention') is-invalid @enderror"
                                       placeholder="Attention"
                                       value="{{(isset($customeraddress))?$customeraddress->billing_attention:old('billing_attention')}}">
                                @error('billing_attention')
                                <div class="invalid-feedback"
                                     role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Address</label>
                            <div class="col-xl-8">
                                <input type="text" name="billing_address1"
                                       autocomplete="off"
                                       class="form-control  @error('billing_address1') is-invalid @enderror"
                                       placeholder="Address "
                                       value="{{(isset($customeraddress))?$customeraddress->billing_address1:old('billing_address1')}}">
                                @error('billing_address1')
                                <div class="invalid-feedback"
                                     role="alert">{{ $message }}</div>
                                @enderror

                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label"></label>
                            <div class="col-xl-8">
                                <input type="text" name="billing_address2"
                                       autocomplete="off"
                                       class="form-control  @error('billing_address2') is-invalid @enderror"
                                       placeholder="Address "
                                       value="{{(isset($customeraddress))?$customeraddress->billing_address2:old('billing_address2')}}">


                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label"></label>
                            <div class="col-xl-8">
                                <input type="text" name="billing_address3"
                                       autocomplete="off"
                                       class="form-control  @error('billing_address3') is-invalid @enderror"
                                       placeholder="Address "
                                       value="{{(isset($customeraddress))?$customeraddress->billing_address3:old('billing_address3')}}">

                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Country / Region</label>
                            <div class="col-lg-6">
                                <select
                                    class="form-control country-select2 {{ $errors->has('country_id') ? ' is-invalid' : '' }}"
                                    name="country_id">

                                    @if(!empty($customeraddress))
                                        <option value="{{(isset($customeraddress))?$customeraddress->country_id:(old('country_id')?old('country_id'):0)}}">
                                            {{ isset($customeraddress->country)?$customeraddress->country->country_name:''}}
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
                            <label class="col-lg-4 col-form-label">State</label>
                            <div class="col-lg-5">
                                <select
                                    class="form-control state-select2 {{ $errors->has('state_id') ? ' is-invalid' : '' }}"
                                    name="state_id">

                                    @if(!empty($customeraddress))
                                        <option
                                            value="{{(isset($customeraddress))?$customeraddress->state_id:(old('state_id')?old('state_id'):0)}}">
                                            {{ isset($customeraddress->state)?$customeraddress->state->state_name:''}}
                                        </option>
                                    @endif

                                </select>
                                @if ($errors->has('state_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('state_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">City</label>
                            <div class="col-lg-8">
                                <input type="text" name="billing_city"
                                       autocomplete="off"
                                       class="form-control  @error('billing_city') is-invalid @enderror"
                                       placeholder="City"
                                       value="{{(isset($customeraddress))?$customeraddress->billing_city:old('billing_city')}}">
                                @error('billing_city')
                                <div class="invalid-feedback"
                                     role="alert">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Pincode</label>
                            <div class="col-xl-8">
                                <input type="text" name="billing_pincode"
                                       autocomplete="off" maxlength="10"
                                       class="form-control  @error('billing_pincode') is-invalid @enderror"
                                       placeholder="Pincode "
                                       value="{{(isset($customeraddress))?$customeraddress->billing_pincode:old('billing_pincode')}}">

                            </div>
                        </div>


                        {{--                        <div class="form-group row">--}}
{{--                            <label class="col-lg-4 col-form-label">Distinct</label>--}}
{{--                            <div class="col-lg-8">--}}
{{--                                <input type="text" name="billing_distinct"--}}
{{--                                       autocomplete="off"--}}
{{--                                       class="form-control  @error('billing_distinct') is-invalid @enderror"--}}
{{--                                       placeholder="Distinct"--}}
{{--                                       value="{{(isset($customeraddress))?$customeraddress->billing_distinct:old('billing_distinct')}}">--}}
{{--                                @error('billing_distinct')--}}
{{--                                <div class="invalid-feedback"--}}
{{--                                     role="alert">{{ $message }}</div>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}


                    </div>
                    <div class="col-6">

                        <h5 style="padding:10px;text-align: center">SHIPPING
                            ADDRESS</h5>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Attention</label>
                            <div class="col-lg-8">
                                <input type="text" name="shipping_attention"
                                       autocomplete="off"
                                       class="form-control  @error('shipping_attention') is-invalid @enderror"
                                       placeholder="Attention"
                                       value="{{(isset($customeraddress))?$customeraddress->shipping_attention:old('shipping_attention')}}">
                                @error('shipping_attention')
                                <div class="invalid-feedback"
                                     role="alert">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Address</label>
                            <div class="col-xl-8">
                                <input type="text" name="shipping_address1"
                                       autocomplete="off"
                                       class="form-control  @error('shipping_address1') is-invalid @enderror"
                                       placeholder="Address "
                                       value="{{(isset($customeraddress))?$customeraddress->shipping_address1:old('shipping_address1')}}">
                                @error('shipping_address1')
                                <div class="invalid-feedback"
                                     role="alert">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label"></label>
                            <div class="col-xl-8">
                                <input type="text" name="shipping_address2"
                                       autocomplete="off"
                                       class="form-control  @error('shipping_address2') is-invalid @enderror"
                                       placeholder="Address "
                                       value="{{(isset($customeraddress))?$customeraddress->shipping_address2:old('shipping_address2')}}">
                            </div>

                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label"></label>
                            <div class="col-xl-8">
                                <input type="text" name="shipping_address3"
                                       autocomplete="off"
                                       class="form-control  @error('shipping_address3') is-invalid @enderror"
                                       placeholder="Address "
                                       value="{{(isset($customeraddress))?$customeraddress->shipping_address3:old('shipping_address3')}}">
                            </div>

                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Country /
                                Region</label>
                            <div class="col-lg-6">
                                <select
                                    class="form-control country-select2 {{ $errors->has('shipping_country_id') ? ' is-invalid' : '' }}"
                                    name="shipping_country_id">

                                    @if(!empty($customeraddress))
                                        <option value="{{(isset($customeraddress))?$customeraddress->shipping_country_id:(old('shipping_country_id')?old('shipping_country_id'):0)}}">
                                            {{ isset($customeraddress->country)?$customeraddress->country->country_name:''}}
                                        </option>
                                    @endif

                                </select>
                                @if ($errors->has('shipping_country_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('shipping_country_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">State</label>
                            <div class="col-lg-5">
                                <select
                                    class="form-control state-select2 {{ $errors->has('shipping_state_id') ? ' is-invalid' : '' }}"
                                    name="shipping_state_id">

                                    @if(!empty($customeraddress))
                                        <option
                                            value="{{(isset($customeraddress))?$customeraddress->shipping_state_id:(old('shipping_state_id')?old('shipping_state_id'):0)}}">
                                            {{ isset($customeraddress->state)?$customeraddress->state->state_name:''}}
                                        </option>
                                    @endif

                                </select>
                                @if ($errors->has('shipping_state_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('shipping_state_id') }}</strong>
                                    </span>
                                @endif
                            </div>

                        </div>


                                                <div class="form-group row">
                                                    <label class="col-lg-4 col-form-label">City</label>
                                                    <div class="col-lg-8">
                                                        <input type="text" name="shipping_city"
                                                               autocomplete="off"
                                                               class="form-control  @error('shipping_city') is-invalid @enderror"
                                                               placeholder="City"
                                                               value="{{(isset($customeraddress))?$customeraddress->shipping_city:old('shipping_city')}}">
                                                        @error('shipping_city')
                                                        <div class="invalid-feedback"
                                                             role="alert">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>


                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label">Pincode</label>
                            <div class="col-xl-8">
                                <input type="text" name="shipping_pincode"
                                       autocomplete="off" maxlength="10"
                                       class="form-control  @error('shipping_pincode') is-invalid @enderror"
                                       placeholder="Pincode "
                                       value="{{(isset($customeraddress))?$customeraddress->shipping_pincode:old('shipping_pincode')}}">

                            </div>
                        </div>


{{--                        </div>--}}
{{--                        <div class="form-group row">--}}
{{--                            <label class="col-lg-4 col-form-label">Distinct</label>--}}
{{--                            <div class="col-lg-8">--}}
{{--                                <input type="text" name="shipping_distinct"--}}
{{--                                       autocomplete="off"--}}
{{--                                       class="form-control  @error('shipping_distinct') is-invalid @enderror"--}}
{{--                                       placeholder="Distinct"--}}
{{--                                       value="{{(isset($customeraddress))?$customeraddress->shipping_distinct:old('shipping_distinct')}}">--}}
{{--                                @error('shipping_distinct')--}}
{{--                                <div class="invalid-feedback"--}}
{{--                                     role="alert">{{ $message }}</div>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}


                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="contact" role="tabpanel"
             aria-labelledby="contact-tab">
            <div id="customer-form-repeter">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="bg-light-light">
                        <tr>
                            <th colspan="1" rowspan="2" class="text-left">Salutation</th>
                            <th colspan="1" rowspan="2" class="text-left">Contact Name</th>
                            <th colspan="1" rowspan="2" class="text-left">Email Address</th>
                            <th colspan="1" rowspan="2" class="text-left">Mobile No</th>
                            <th colspan="1" rowspan="2" class="text-left">Designation</th>
                            <th colspan="1" rowspan="2" class="text-left">Department</th>
                        </tr>
                        <tr>
                        </thead>
                        <tbody data-repeater-list="grid_items">
                        @php $gridItem=!empty(old('grid_items'))?old('grid_items'):(empty($contactperson)?[]:$contactperson);
                        @endphp
                        @if($gridItem)
                            <tr data-repeater-item="">
                                <td class="p-0">
                                    <select class="form-control grid-item"
                                            name="salutation">
                                        <option value=""></option>

                                        <option
                                            value="Mr." >
                                            Mr.
                                        </option>
                                        <option
                                            value="Mrs." >
                                            Mrs.
                                        </option>
                                        <option
                                            value="Ms." >
                                            Ms.
                                        </option>
                                        <option
                                            value="Miss." >
                                            Miss.
                                        </option>
                                        <option
                                            value="Dr." >
                                            Dr.
                                        </option>
                                    </select>

                                </td>


                                <td class="p-0">
                                    <input type="text"
                                           class="form-control grid-item"
                                           name="contact_person_name"
                                           autocomplete="off"
                                           placeholder="Contact Name"/>
                                </td>

                                <td class="p-0">
                                    <input type="text"
                                           class="form-control grid-item"
                                           name="email" autocomplete="off"
                                           placeholder="username@mail.com"/>
                                </td>
                                <td class="p-0">
                                    <input type="text"
                                           class="form-control grid-item"
                                           name="phone_no" autocomplete="off"
                                           placeholder="+91 0000000000"/>
                                </td>
                                <td class="p-0">
                                    <input type="text"
                                           class="form-control grid-item"
                                           name="designation" autocomplete="off"
                                           placeholder="Designation"/>
                                </td>
                                <td class="p-0">
                                    <input type="text"
                                           class="form-control   grid-item"
                                           name="department" autocomplete="off"
                                           placeholder="Department"/>
                                </td>


                                <td class="p-0 align-middle border-0">
                                    <a href="javascript:;" data-repeater-delete=""
                                       class="btn btn-text-dark-50 btn-icon-danger font-weight-bold">
                                        <i class="la la-trash-o"></i></a>
                                </td>
                            </tr>
                        @else
                            @php $i=0; @endphp
                            @foreach($gridItem as $key=>$value)

                                <tr data-repeater-item="">

                                    <td class="p-0">
                                        <select class="form-control grid-item "
                                                name="salutation" id="salutation">
                                            <option value=""></option>

                                            <option
                                                value="Mr." {{(isset($value) && $value['salutation']=='Mr.')?'selected':old('Mr.')}}>
                                                Mr.
                                            </option>
                                            <option
                                                value="Mrs." {{(isset($value) && $value['salutation']=='Mrs.')?'selected':old('Mrs.')}}>
                                                Mrs.
                                            </option>
                                            <option
                                                value="Ms." {{(isset($value) && $value['salutation']=='Ms.')?'selected':old('Ms.')}}>
                                                Ms.
                                            </option>
                                            <option
                                                value="Miss." {{(isset($value) && $value['salutation']=='Miss.')?'selected':old('Miss.')}}>
                                                Miss.
                                            </option>
                                            <option
                                                value="Dr." {{(isset($value) && $value['salutation']=='Dr.')?'selected':old('Dr.')}}>
                                                Dr.
                                            </option>
                                        </select>

                                    </td>

                                    <td class="p-0">
                                        <input type="text"
                                               class="form-control grid-item"
                                               name="contact_person_name"
                                               autocomplete="off"
                                               value="{{ isset($value['contact_person_name'])?$value['contact_person_name']:old('Dr.')}}"

                                               placeholder=""/>
                                    </td>

                                    <td class="p-0">
                                        <input type="text"
                                               class="form-control grid-item"
                                               autocomplete="off"
                                               name="email"

                                               value="{{ isset($value['email'])?$value['email']:''}}"
                                               placeholder=""/>
                                    </td>
                                    <td class="p-0">
                                        <input type="text"
                                               class="form-control grid-item"
                                               autocomplete="off"
                                               name="phone_no"
                                               value="{{ isset($value['phone_no'])?$value['phone_no']:''}}"
                                               placeholder=""/>
                                    </td>
                                    <td class="p-0">
                                        <input type="text"
                                               class="form-control grid-item"
                                               autocomplete="off"
                                               name="designation"
                                               value="{{ isset($value['designation'])?$value['designation']:''}}"
                                               placeholder=""/>
                                    </td>
                                    <td class="p-0">
                                        <input type="text"
                                               class="form-control grid-item"
                                               autocomplete="off"
                                               name="department"
                                               value="{{ isset($value['department'])?$value['department']:''}}"
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
                        </tbody>
                    </table>
                </div>
                <div class="form-group row ">
                    <label class="col-lg-2 col-form-label text-right"></label>
                    <div class="col-lg-4">
                        <a href="javascript:;" data-repeater-create=""
                           class="btn btn-sm font-weight-bolder btn-light-primary">
                            <i class="la la-plus"></i>Add</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</div>

@push('scripts')
    <script>


        jQuery(document).ready(function () {
            $('#customer-form-repeter').repeater({
                initEmpty: false,

                defaultValues: {
                    'text-input': 'foo'
                },

                show: function () {
                    $(this).slideDown();
                },

                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                }
            });

        });
        $('#submit').submit(function (e) {
            e.preventDefault();
            if (!$('#mobile').val().match('[0-9]{10}')) {
                alert("Please put 10 digit mobile number");
                return;
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
@endpush

