@extends('layout.master')
@section('content')

<div class="row">
	<h3 class="page_heading" style="padding-left:30px;">{{$page_title}}</h3> 
</div>

<div class="col-md-12">
	<div class="panel panel-default">
	    <div class="row" style="margin-left:10px;">
	      <form method="get" action="{{url('/system-admin/event-logs')}}">
	      <input type="hidden" name="_token" value="{{csrf_token()}}">
	      <div class="col-md-4">
	        <div class="form-group ">
	          <label for="form-field-23">
	            From<span class="symbol required"></span>
	          </label>
	          <div class="input-group">
                <input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker" name="form_search_date" value="{{(isset($_GET['form_search_date']) ? $_GET['form_search_date'] : date("Y-m-d"))}}" placeholder="">
                <span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
              </div>
	        </div>
	      </div>

	      <div class="col-md-4">
	        <div class="form-group ">
	          <label for="form-field-23">
	            To<span class="symbol required"></span>
	          </label>
	          <div class="input-group">
                <input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker" name="to_search_date" value="{{(isset($_GET['to_search_date']) ? $_GET['to_search_date'] : date("Y-m-d"))}}" placeholder="">
                <span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
              </div>
	        </div>
	      </div>


			<?php
				$all_users=\DB::table('users')->get();
			?>

			<div class="col-md-2">
				<div class="form-group ">
					<label for="form-field-23">
						User<span class="symbol required"></span>
					</label>
					<div class="input-group">
						<select name="user_name" class="form-control">
							<option value=""> Select User</option>
							<!-- <option {{(isset($_GET['user_name']) && ($_GET['user_name'] == 'guest')) ? 'selected':''}}  value="guest"> Guest</option> -->
							
							@if(!empty($all_users) && count($all_users)>0)
							@foreach ($all_users as $key => $list){

							<option {{(isset($_GET['user_name']) && ($_GET['user_name'] == $list->user_id)) ? 'selected':''}} value="{{$list->user_id}}">{{$list->name}}</option>
				
							@endforeach
							@endif
							
						</select>
					</div>
				</div>
			</div>

		    <div class="col-md-2" style="margin-top:22px;">
		        <div class="form-group">
		          <input type="submit" class="btn btn-primary" data-toggle1="tooltip" title="Search Logs" value="View">
		        </div>
		    </div>

	    </form>
	    </div>
	</div>
</div>


<div class="panel panel-body">
	<div class="row">
		<div class="col-md-12 ">
			<table class="table table-hover table-bordered table-striped">
			<!-- <caption><strong>Event Logs</strong></caption> -->
				<thead>
					<tr>
						<th>SL</th>
						<th>Date & Time</th>
						<th>Client IP</th>
						<th>USER</th>
						<th>Page URL</th>
						<th>Event Type</th>
						<th>Event Data</th>
					</tr>
				</thead>

				<tbody>
					@if(count($event_log_list) > 0)
					@foreach($event_log_list as $key => $list)
					<tr>
						<td>{{$key+1}}</td>
						<td>{{$list->created_at}}</td>
						<td>{{$list->event_client_ip}}</td>
						<td>{{$list->event_user_id =='guest' ? 'Guest' : $list->name}}</td>
						<td>{{$list->event_request_url}}</td>
						<td>{{$list->event_type}}</td>
						<td>
						<a data-toggle="modal" data-target="#EventLogModal"  data-id="{{$list->event_id}}" class="text_none event_log_show btn btn-success" href="">View</a>
						</td>

					</tr>
					@endforeach
					@else
					<tr>
						<td colspan="7" class="text-center">No data available</td>
					</tr>
					@endif
				</tbody>
			</table>
			{{isset($event_pagination) ? $event_pagination:""}}
		</div>
	</div>
</div>



<!-- Modal -->
<div id="EventLogModal" class="modal fade " rtabindex="-1" role="dialog">
	<div class="modal-dialog ">
		<div class="modal-content">
			
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Event Data</h4>
			</div>
			<div class="modal-body">

				<div class="event_log_details">

				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default"  data-dismiss="modal">OK</button>
			</div>
		</div>
	</div>
</div>

@stop