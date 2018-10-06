<div class="panel panel-default">
	<div class="panel-heading">
		<i class="clip-users-2"></i>
		Posting Type
		<div class="panel-tools">
			<a class="btn btn-xs btn-link panel-collapse collapses" data-toggle="tooltip" data-placement="top" title="Show / Hide" href="#">
			</a>
			
			<a class="btn btn-xs btn-link panel-close red-tooltip" data-toggle="tooltip" data-placement="top" title="Close" href="#">
				<i class="fa fa-times"></i>
			</a>
		</div>
	</div>
	<div class="panel-body posting_sidebar">
		<ul id="" class="nav">
		<li class="{{(isset($posting_type)&& ($posting_type=='general_journal')) ? 'active':''}}">
				<a href="{{url('/journal/posting/type-general_journal')}}" >
					<i class="pink fa fa-file-code-o"></i> General Journal
				</a>
			</li>
			<li class="{{(isset($posting_type)&& ($posting_type=='others_receipt')) ? 'active':''}}">
				<a href="{{url('/journal/posting/type-others_receipt')}}">
					<i class="blue fa fa-ticket"></i> Others Receipt
				</a>
			</li>
			<li class="{{(isset($posting_type)&& ($posting_type=='others_payment')) ? 'active':''}}">
				<a href="{{url('/journal/posting/type-others_payment')}}" >
					<i class="fa fa-credit-card"></i> Others Payment
				</a>
			</li>
		<!--<li class="{{(isset($posting_type)&& ($posting_type=='general_sales')) ? 'active':''}}">
				<a href="{{url('/journal/posting/type-general_sales')}}" >
					<i class="fa fa-money"></i> General Sales
				</a>
			</li>
			<li class="{{(isset($posting_type)&& ($posting_type=='general_purchase')) ? 'active':''}}">
				<a href="{{url('/journal/posting/type-general_purchase')}}" >
					<i class="fa fa-dollar"></i> General  Purchase
				</a>
			</li>
			<li class="{{(isset($posting_type)&& ($posting_type=='general_sales_return')) ? 'active':''}}">
				<a href="{{url('/journal/posting/type-general_sales_return')}}" >
					<i class="fa fa-strikethrough"></i> General  Sales Return
				</a>
			</li>
			<li class="{{(isset($posting_type)&& ($posting_type=='general_purchase_return')) ? 'active':''}}">
				<a href="{{url('/journal/posting/type-general_purchase_return')}}" >
					<i class="fa fa-caret-square-o-left"></i> General Purchase Return
				</a>
			</li>

			
			<li class="{{(isset($page_title) &&($page_title=='Inventory Purchase Invoice')) ? 'active':''}}">
				<a href="{{url('/inventory/purchase/invoice')}}">
					<span class="title">Inventory Purchase </span>
				</a>
			</li>

			<li class="{{(isset($page_title) &&($page_title=='Inventory Finish-goods Entry')) ? 'active':''}}">
				<a href="{{url('/finish-goods/list')}}">
					<span class="title">Finish-goods Entry</span>
				</a>
			</li>

			<li class="{{(isset($page_title) &&($page_title=='Supplier Payment')) ? 'active':''}}">
				<a href="{{url('/supplier/payment')}}">
					<span class="title">Supplier Payment</span>
				</a>
			</li>
			<li class="{{(isset($page_title) &&($page_title=='Supplier Purchase Return')) ? 'active':''}}">
				<a href="{{url('/supplier/purchase/return')}}">
					<span class="title">Supplier Purchase Return</span>
				</a>
			</li>

			<li class="{{(isset($page_title) &&($page_title=='Sales Receipt')) ? 'active':''}}">
			    <a href="{{url('/customer/payment')}}">
			     <span class="title">Sales Receipt</span>
			    </a>
			 </li>
			  <li class="{{(isset($page_title) &&($page_title=='Customer Sales Return')) ? 'active':''}}">
			    <a href="{{url('/customer/sales/return')}}">
			     <span class="title">Customer Sales Return</span>
			    </a>
			</li>
			-->
			
		</ul>
	</div>
</div>
</div>