<li class="{{(isset($page_title) &&($page_title=='Dashboard Account')) ? 'active':''}}">
	<a href="{{url('/dashboard/account/'.\Auth::user()->name_slug)}}"><i class="clip-home-3"></i>
		<span class="title"> Dashboard </span><span class="selected"></span>
	</a>
</li>

<li class="{{(isset($page_title) && (strpos($page_title,'Inventory')!== false )) ? 'open':''}}">
	<a href="javascript:void(0)"><i class="clip-cart"></i>
		<span class="title">Manage Inventory </span><i class="icon-arrow"></i>
		<span class="selected"></span>
	</a>
	<ul class="sub-menu" style="display: {{( isset($page_title) && (strpos($page_title,'Inventory') !== false) ) ? 'block':'none'}};">

		<li class="{{(isset($page_title) && ($page_title=='Inventory Categories')) ? 'active':''}}">
	      <a href="{{url('/inventory/category/settings')}}"></i>
	       <span class="title"> Inventory Categories</span>
	      </a>
	    </li>
		
		<li class="{{(isset($page_title) &&($page_title=='Inventory Items')) ? 'active':''}}">
			<a href="{{url('/inventory/item/settings')}}">
				<span class="title">Inventory Items Settings</span>
			</a>
		</li>
		
		<li class="{{(isset($page_title) &&($page_title=='Inventory Purchase Invoice')) ? 'active':''}}">
			<a href="{{url('/inventory/purchase/invoice')}}">
				<span class="title">Inventory Purchase</span>
			</a>
		</li>
<!-- 		<li class="{{(isset($page_title) &&($page_title=='Inventory Stocks Entry')) ? 'active':''}}">
			<a href="{{url('/inventory/stocks/on-production')}}">
				<span class="title">Stocks Outwards</span>
			</a>
		</li> -->
		<li class="{{(isset($page_title) &&($page_title=='Inventory Stocks Transaction')) ? 'active':''}}">
			<a href="{{url('/inventory/stocks/trasansaction/list')}}">
				<span class="title">Stocks Transaction</span>
			</a>
		</li>
		<li class="{{(isset($page_title) &&($page_title=='Inventory Stocks Summery')) ? 'active':''}}">
			<a href="{{url('/stock/summery/list')}}">
				<span class="title">Inventory Stocks Summery</span>
			</a>
		</li>
		<li class="{{(isset($page_title) &&($page_title=='Inventory Finish-goods Entry')) ? 'active':''}}">
			<a href="{{url('/finish-goods/list')}}">
				<span class="title">Finish-goods Stocks Journal</span>
			</a>
		</li>
		
	</ul>
</li>
<li class="{{(isset($page_title) && (strpos($page_title,'Supplier')!== false )) ? 'open':''}}">
	<a href="javascript:void(0)"><i class="clip-truck"></i>
		<span class="title">Manage Supplier </span><i class="icon-arrow"></i>
		<span class="selected"></span>
	</a>
	<ul class="sub-menu" style="display: {{( isset($page_title) && (strpos($page_title,'Supplier') !== false) ) ? 'block':'none'}};">
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
	</ul>
</li>


<li class="{{(isset($page_title) && (strpos($page_title,'Customer')!== false )) ? 'open':''}}">
	<a href="javascript:void(0)"><i class="clip-user-5"></i>
		<span class="title">Manage Customer </span><i class="icon-arrow"></i>
		<span class="selected"></span>
	</a>
	<ul class="sub-menu" style="display: {{( isset($page_title) && (strpos($page_title,'Order') !== false) ) ? 'block':'none'}};">
		 <li class="{{(isset($page_title) &&($page_title=='Customer Order')) ? 'active':''}}">
		    <a href="{{url('/customer/order')}}">
		     <span class="title">Customer Order</span>
		    </a>
		 </li>
		 <li class="{{(isset($page_title) &&($page_title=='Order Delivery')) ? 'active':''}}">
		    <a href="{{url('/customer/order/delivery')}}">
		     <span class="title">Order Delivery</span>
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
		 <li class="{{(isset($page_title) &&($page_title=='All Order List')) ? 'active':''}}">
		    <a href="{{url('/customer/all/order-list')}}">
		     <span class="title">All Order List</span>
		    </a>
		 </li>
	</ul>
</li>
<li class="{{(isset($page_title) &&($page_title=='Posting')) ? 'active':''}}">
	<a href="{{url('/journal/posting/type-general_journal')}}"><i class="clip-pencil"></i>
		<span class="title">Posting</span>
	</a>
</li>

<li class="{{(isset($page_title) &&($page_title=='General Transaction')) ? 'active':''}}">
	<a href="{{url('/general/transaction-list/by-user')}}"><i class="fa fa-tasks"></i>
		<span class="title">General Transaction</span>
	</a>
</li>
<li class="{{(isset($page_title) &&($page_title=='Journal')) ? 'active':''}}">
	<a href="{{url('/journal/transaction/by-user')}}"><i class="clip-grid-6"></i>
		<span class="title">Journal Transaction</span>
	</a>
</li>
