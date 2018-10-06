<div class="navbar-content">
	<!-- start: SIDEBAR -->
	<div class="main-navigation navbar-collapse collapse">
		<!-- start: MAIN MENU TOGGLER BUTTON -->
		<div class="navigation-toggler">
			<i class="clip-chevron-left"></i>
			<i class="clip-chevron-right"></i>
		</div>
		<!-- end: MAIN MENU TOGGLER BUTTON -->
		<!-- start: MAIN NAVIGATION MENU -->
		<ul class="main-navigation-menu">
			@if(\Auth::check() && (\Auth::user()->user_type)== 'account')
				@include('layout.account-user-list')
			@endif

			@if(\Auth::check() && (\Auth::user()->user_type)== 'inventory')
				@include('layout.inventory-user-list')
			@endif


			@if(\Auth::check() && (\Auth::user()->user_type)== 'admin')

			<li class="{{(isset($page_title) &&($page_title=='Dashboard')) ? 'active':''}}">
				<a href="{{url('/dashboard/admin/'.\Auth::user()->name_slug)}}"><i class="clip-home-3"></i>
					<span class="title"> Dashboard </span></span>
				</a>
			</li>

			<li class="{{(isset($page_title) &&($page_title=='Company Info')) ? 'active':''}}">
				<a href="{{url('/dashboard/company/info')}}"><i class="fa fa-building"></i>
					<span class="title"> Company Info </span></span>
				</a>
			</li>

			<li class="{{(isset($page_title) &&($page_title=='User Managemenet')) ? 'active':''}}">
				<a href="{{url('/dashboard/admin/user/management')}}"><i class="fa fa-users"></i>
					<span class="title">Manage User</span>
				</a>
			</li>
			<li class="{{(isset($page_title) &&($page_title=='Cost Center')) ? 'active':''}}">
				<a href="{{url('/dashboard/cost-center')}}"><i class="fa fa-university"></i>
					<span class="title">Manage Cost Center</span>
				</a>
			</li>

			<li  class="{{(isset($page_title) && (strpos($page_title,'Reports')!== false )) ? 'open':''}}">
				<a href="javascript:void(0)"><i class="fa fa-bar-chart-o"></i>
					<span class="title">Management Reports </span><i class="icon-arrow"></i>
					<span class="selected"></span>
				</a>
				<ul class="sub-menu" style="display: {{( isset($page_title) && (strpos($page_title,'Reports') !== false) ) ? 'block':'none'}};">
					<li class="{{(isset($page_title) &&($page_title=='Balance Sheet')) ? 'active':''}}">
						<a href="{{url('/reports/balance-sheet')}}">
							<span class="title">Balance Sheet</span>
						</a>
					</li>
					<li class="{{(isset($page_title) &&($page_title=='Reports of Cash Flow')) ? 'active':''}}">
						<a href="{{url('/reports/cash-flow')}}">
							<span class="title">Cash Flow</span>
						</a>
					</li>
					<li class="{{(isset($page_title) &&($page_title=='Sales Report')) ? 'active':''}}">
						<a href="{{url('/sales/balance/report')}}">
							<span class="title">Sales Report</span>
						</a>
					</li>
					<li class="{{(isset($page_title) &&($page_title=='A/C Receivable Report')) ? 'active':''}}">
						<a href="{{url('/account-receivable/balance/report')}}">
							<span class="title">A/C Receivable Report</span>
						</a>
					</li>
					
					<li class="{{(isset($page_title) &&($page_title=='Purchase Report')) ? 'active':''}}">
						<a href="{{url('/purchase/balance/report')}}">
							<span class="title">Purchase Report</span>
						</a>
					</li>
					<li class="{{(isset($page_title) &&($page_title=='A/C Payable Report')) ? 'active':''}}">
						<a href="{{url('/account-payable/balance/report')}}">
							<span class="title">A/C Payable Report</span>
						</a>
					</li>

					<li class="{{(isset($page_title) &&($page_title=='Inventory Stocks Summery List')) ? 'active':''}}">
						<a href="{{url('/stock/summery/list')}}">
							<span class="title">Inventory Stocks Summery</span>
						</a>
					</li>
					<li class="{{(isset($page_title) &&($page_title=='Finish Goods Summery List')) ? 'active':''}}">
						<a href="{{url('/finish-goods/summery/list')}}">
							<span class="title">Finish Goods Summery</span>
						</a>
					</li>
					<li class="{{(isset($page_title) &&($page_title=='Report of Trail Balance')) ? 'active':''}}">
						<a href="{{url('/trail/balance/report')}}">
							<span class="title">Trial Balance</span>
						</a>
					</li>

					<li class="{{(isset($page_title) &&($page_title=='Manufacturing Report')) ? 'active':''}}">
						<a href="{{url('/manufacturing/report')}}">
							<span class="title">Manufacturing Report</span>
						</a>
					</li>

					<li class="{{(isset($page_title) &&($page_title=='Income Statement')) ? 'active':''}}">
						<a href="{{url('/income-statement/report')}}">
							<span class="title">Income Statement</span>
						</a>
					</li>
					
				</ul>
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
							<span class="title">Inventory Purchase </span>
						</a>
					</li>

					<li class="{{(isset($page_title) &&($page_title=='Inventory Stocks Item List')) ? 'active':''}}">
						<a href="{{url('/inventory/stock/item/list')}}">
							<span class="title">Inventory Stocks Item List </span>
						</a>
					</li>
					
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
							<span class="title">Finish-goods Stock Journal</span>
						</a>
					</li>

					<li class="{{(isset($page_title) &&($page_title=='Waste Goods Summery')) ? 'active':''}}">
						<a href="{{url('/delivery/finish-goods/list')}}">
							<span class="title">Waste Goods Summery</span>
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

					<li class="{{(isset($page_title) &&($page_title=='All Supplier')) ? 'active':''}}">
						<a href="{{url('/supplier/list')}}">
							<span class="title">All Supplier</span>
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
					 <li class="{{(isset($page_title) &&($page_title=='All Customer')) ? 'active':''}}">
						<a href="{{url('/customer/list')}}">
							<span class="title">All Customer</span>
						</a>
					</li>
				</ul>
			</li>

			<li class="{{(isset($page_title) && (strpos($page_title,'Logs')!== false )) ? 'open':''}}">
				<a href="javascript:void(0)"><i class="clip-truck"></i>
					<span class="title">Manage Logs </span><i class="icon-arrow"></i>
					<span class="selected"></span>
				</a>
				<ul class="sub-menu" style="display: {{( isset($page_title) && (strpos($page_title,'Logs') !== false) ) ? 'block':'none'}};">

					<li class="{{(isset($page_title) &&($page_title=='Access Logs')) ? 'active':''}}">
						<a href="{{url('/system-admin/access-logs')}}">
							<span class="title">Access Logs</span>
						</a>
					</li>

					<li class="{{(isset($page_title) &&($page_title=='Auth Logs')) ? 'active':''}}">
						<a href="{{url('/system-admin/auth-logs')}}">
							<span class="title">Auth Logs</span>
						</a>
					</li>


					<li class="{{(isset($page_title) &&($page_title=='Event Logs')) ? 'active':''}}">
						<a href="{{url('/system-admin/event-logs')}}">
							<span class="title">Event Logs</span>
						</a>
					</li>


					<li class="{{(isset($page_title) &&($page_title=='Error Logs')) ? 'active':''}}">
						<a href="{{url('/system-admin/error-logs')}}">
							<span class="title">Error Logs</span>
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
				<a href="{{url('/general/transaction-list')}}"><i class="fa fa-tasks"></i>
					<span class="title">General Transaction</span>
				</a>
			</li>
			<li class="{{(isset($page_title) &&($page_title=='Journal')) ? 'active':''}}">
				<a href="{{url('/journal/transaction')}}"><i class="clip-grid-6"></i>
					<span class="title">Journal Transaction</span>
				</a>
			</li>

			<li class="{{(isset($page_title) &&($page_title=='All Ledger Transaction')) ? 'active':''}}">
				<a href="{{url('/all/journal/ledger')}}"><i class="clip-bars"></i>
					<span class="title">All Ledger Transaction</span>
				</a>
			</li>

			<li class="{{(isset($page_title) &&($page_title=='Ledger Opening Balance')) ? 'active':''}}">
				<a href="{{url('/ledger/opening/balance')}}"><i class="fa fa-money" aria-hidden="true"></i><span class="title"> Ledger Opening Balance</span>
				</a>
			</li>

			@endif

			

			
			
		</ul>
		<!-- end: MAIN NAVIGATION MENU -->
	</div>
	<!-- end: SIDEBAR -->
</div>