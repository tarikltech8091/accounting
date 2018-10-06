<ol class="breadcrumb">
	<li>
		<i class="clip-home-3"></i>
		<a href="{{(\Auth::check()) ? url('/dashboard/'.\Auth::user()->user_role.'/'.\Auth::user()->name_slug):'#'}}">
			Home
		</a>
	</li>
	<li class="active">
		{{isset($page_title) ? $page_title:''}}
	</li>
	
	@if(\Auth::check())
	<li class="posting-btn " data-toggle="tooltip" data-placement="bottom" title="Posting Form">
		<a class="btn btn-primary" href="{{url('/journal/posting/type-general_journal')}}">
			Posting <i class="clip-pencil"></i>
		</a>
	</li>
	@endif
</ol>
<div class="page-header">
	<h1>{{isset($page_title) ? $page_title:''}} <small>{{isset($page_desc) ? $page_desc:''}} </small></h1>
</div>