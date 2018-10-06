<div class="form-group col-md-8">
	<input type="text" class="form-control uppercase_name"  name="ssc_olevel_subject_{{$add_subject_count}}" placeholder="Subject {{$add_subject_count}}" value="">
</div>
<div class="form-group col-md-2">
<input type="text"  class="form-control uppercase_name"  name="ssc_olevel_subject_gpa_{{$add_subject_count}}" placeholder="Ex:- 5.00" value="">
</div>
<div class="form-group col-md-2">
	<input type="text" class="form-control uppercase_name"  name="ssc_olevel_subject_grade_{{$add_subject_count}}" placeholder="Ex:- A+" value="">
</div>

<input type="hidden" name="multi_ssc_subject_count_ajax" value="{{$add_subject_count}}" 