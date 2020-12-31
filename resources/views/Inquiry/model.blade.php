<div class="form-group row">
    <label class="col-lg-12 col-form-label">Company Name </label>
    <div class="col-lg-6">
        <input type="text" disabled id="company_id"
               class="form-control select2-control"
               name="inquiry_id" value="">
    </div>
</div>
<div class="form-group row">
    <label class="col-lg-12 col-form-label ">Customer Name :</label>
    <div class="col-lg-6">

        <input type="text" name="inquiry_id" disabled
               class="form-control" id="contact_id"
               value="">
    </div>
</div>
<div class="form-group row">
    <label class="col-lg-12  col-form-label">Remark</label>
    <div class="col-lg-12">

                                            <textarea name="remark" placeholder="Remark" id="remark"
                                                      class="form-control @error('remark') is-invalid @enderror"></textarea>
        <div id="remark_alert" class="invalid-feedback" role="alert"></div>
        @error('remark')
        <div class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="form-group row">
    <label class="col-lg-12 col-form-label">Next FollowUp Date</label>
    <div class="col-lg-6">
        <div class="input-group date">
            <input type="text"
                   name="next_followup_date" autocomplete="off"
                   class="form-control @error('next_followup_date') is-invalid @enderror"
                   placeholder="dd-mm-yyyy"
                   id="next_followup_date"
                   value="{{ !empty(old('next_followup_date'))?old('next_followup_date'):(!empty($transportdeteils->next_followup_date)?date('d-m-Y',strtotime($transportdeteils->next_followup_date)):date('d-m-Y')) }}">
            <div class="input-group-append">
															<span class="input-group-text">
																<i class="la la-calendar"></i>
															</span>
            </div>
            <div id="next_followup_date_alert" class="invalid-feedback" role="alert"></div>
            @error('next_followup_date')
            <div class="invalid-feedback" role="alert">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
