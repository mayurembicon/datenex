<div class="form-group row">
    <label class="col-lg-3 col-form-label">Item Type</label>
    <div class="col-lg-2">

        <select class="form-control {{ $errors->has('type') ? ' is-invalid' : '' }}"
                name="type">
            <option
                value="good"
                selected {{(isset($item->type)&& $item->type=='good')?'selected':old('good')}}>
                Goods
            </option>
            <option
                value="service"{{(isset($item->type)&& $item->type=='service')?'selected':old('service')}}>
                Service
            </option>
        </select>
        @if ($errors->has('type'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('type') }}</strong>
            </span>
        @endif
    </div>
</div>
<div class="form-group row">
    <label class="col-lg-3 col-form-label ">Item Name<span
            class="text-danger">*</span></label>
    <div class="col-lg-3">

        <input type="text" name="name" placeholder="Item Name"
               class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
               value="{{(isset($item->name))?$item->name:old('name')}}">
        @if ($errors->has('name'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif
    </div>
</div>
<div class="form-group row">
    <label class="col-lg-3 col-form-label">Unit</label>
    <div class="col-lg-2">

        <select
            class="form-control select2-control @error('unit') is-invalid @enderror"
            name="unit" id="unit">
            <option value=""></option>
            <option
                value="" {{(isset($item->unit))?$item->unit:old('unit')}}>>
                select
            </option>

            @foreach($unit as $key)
                <option
                    value="{{$key->unit_name}}"{{(isset($item->unit) &&  $item->unit==$key->unit_name)?'selected':''}}
                    {{ ((old('unit')==$key->unit_name)?'selected': '') }}>
                    {{$key->unit_name}}</option>
            @endforeach


        </select>
        @error('unit')
        <div class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-lg-3 col-form-label">Hsn Code</label>
    <div class="col-lg-2">

        <input type="text" name="hsn" placeholder="Hsn Code"
               class="form-control @error('hsn') is-invalid @enderror"
               value="{{(isset($item->hsn))?$item->hsn:old('hsn')}}">
        @error('hsn')
        <div class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-lg-3 col-form-label">Sku</label>
    <div class="col-lg-2">

        <input type="text" name="sku" placeholder="Sku"
               class="form-control @error('sku') is-invalid @enderror"
               value="{{(isset($item->sku))?$item->sku:old('sku')}}">
        @error('sku')
        <div class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-lg-3 col-form-label">Opening Stock</label>
    <div class="col-lg-2">

        <input type="number" name="opening_stock" placeholder="Opening Stock"
               class="form-control @error('opening_stock') is-invalid @enderror"
               value="{{(!empty($opningstock->opening_stock))?$opningstock->opening_stock:0}}">

        @error('opening_stock')
        <div class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-lg-3 col-form-label">Sale Rate</label>
    <div class="col-lg-2">

        <input type="number" step="any" name="sale_rate" placeholder="Sale Rate"
               class="form-control @error('sale_rate') is-invalid @enderror"
               value="{{(isset($item->sale_rate))?$item->sale_rate:old('sale_rate')}}">
        @error('sale_rate')
        <div class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-lg-3 col-form-label">Purchase Rate</label>
    <div class="col-lg-2">

        <input type="number" step="any" name="purchase_rate" placeholder="Purchase Rate"
               class="form-control @error('purchase_rate') is-invalid @enderror"
               value="{{(isset($item->purchase_rate))?$item->purchase_rate:old('purchase_rate')}}">
        @error('purchase_rate')
        <div class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-lg-3 col-form-label">Discount (%)</label>
    <div class="col-lg-2">

        <input type="number" step="any" name="discount_amount" placeholder="Discount"
               class="form-control @error('discount_amount') is-invalid @enderror"
               value="{{(isset($item->discount_amount))?$item->discount_amount:old('discount_amount')}}">
        @error('discount_amount')
        <div class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-lg-3 col-form-label">Tax Rate</label>
    <div class="col-lg-2">
        <select
            class="form-control select2-control @error('taxrate') is-invalid @enderror"
            name="taxrate" id="taxrate">
            <option value=""></option>
            <option
                value="" {{(isset($item->taxrate))?$item->taxrate:old('taxrate')}}>
                select
            </option>

            @foreach($taxrate as $items)
                <option
                    value="{{$items->tax_rate}}"{{(isset($item->taxrate) &&  $item->taxrate==$items->tax_rate)?'selected':''}}
                    {{ ((old('taxrate')==$items->tax_rate)?'selected': '') }}>
                    {{$items->tax_rate}}</option>
            @endforeach
        </select>
        @error('taxrate_id')
        <div class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-lg-3 col-form-label">Description</label>
    <div class="col-lg-5">

                                            <textarea name="descripation" placeholder="Description"
                                                      class="form-control">
                                                {{(isset($item->descripation))?$item->descripation:old('descripation')}}
                                            </textarea>


    </div>
</div>
<input type="hidden" id="ratedIndex" name="ratedIndex">

<div class="form-group row">
    <label class="col-lg-3 col-form-label">Rating</label>
    <div class="col-lg-5">
        <div align="center">
            <i class="fa fa-star fa-2x" data-index="0"></i>
            <i class="fa fa-star fa-2x" data-index="1"></i>
            <i class="fa fa-star fa-2x" data-index="2"></i>
            <i class="fa fa-star fa-2x" data-index="3"></i>
            <i class="fa fa-star fa-2x" data-index="4"></i>
            <br><br>

        </div>
    </div>
</div>
