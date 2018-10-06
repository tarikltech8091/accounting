<div class="panel panel-info">
 <p style="margin:20px">
  <i><strong>Event Client IP : </strong>{{$event_log_view->event_client_ip}}</i>
 </p>
 <div class="panel-heading"><strong>URL:</strong> {{$event_log_view->event_request_url}}</div>
 <div class="panel-body">
  <p>
  		<strong>Type  : </strong> {{$all_type[0]}}<br/>
  		<strong>Table : </strong>{{$all_type[1]}}
  </p>
  <table border="1" class="table table-hover table-bordered table-striped">
    <thead>
  		<th>Field</th>
  		<th>Value</th>
    </thead>
  	<tbody>

  	 @foreach ($all_data as $key => $value)

    <?php 
      if($key == 'cost_center_id'){
        $cost_center_info=\DB::table('ltech_cost_centers')->where('cost_center_id',$value)->first();
      }

      if($key=='created_by'||$key=='updated_by'){
     
        $user_info=\DB::table('users')->where('user_id',$value)->first();
      }

    ?>

		  <tr>
	  		<td>{{$key}}</td>
        @if($key == 'cost_center_id')
	  		<td>{{(isset($cost_center_info->cost_center_name)? $cost_center_info->cost_center_name :$value)}}</td>
        @elseif($key=='created_by'||$key=='updated_by')
        <td>{{(isset($user_info->name)?$user_info->name :$value)}}</td>
        @else
          <td>{{$value}}</td>
        @endif    	
	    </tr>
    	@endforeach

  	</tbody>
  </table>
 </div>
</div>