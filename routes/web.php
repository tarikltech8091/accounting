<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

/*
#########################
## Live-Accounting
########################
*/
	





  #Login
  Route::get('/',array('as'=>'LogIn' , 'uses' =>'SystemAuthController@LoginPage'));
  Route::get('/login',array('as'=>'LogIn' , 'uses' =>'SystemAuthController@LoginPage'));
  Route::post('/login',array('as'=>'LogIn' , 'uses' =>'SystemAuthController@AuthenticationCheck'));

  #Admin logut
  Route::get('/logout/{name_slug}',array('as'=>'Logout' , 'uses' =>'SystemAuthController@Logout'));

  #Enternal Error Page
  Route::get('/error/request',array('as'=>'Error 404', 'desc'=>'internal & data error', 'uses'=>'SystemAuthController@ErrorRequestPage'));
  #Errors Page
  Route::get('/errors/page',array('as'=>'Errors Page', 'desc'=>'view & detail', 'uses'=>'SystemAuthController@Page404'));


 

    /*
  #####################
  ## Admins Module
  ######################
  */
  Route::group(['middleware' => ['admin_auth']], function () {

      #Admin Dashboard Page
      Route::get('/dashboard/admin/{name_slug}',array('as'=>'Dashboard' , 'uses' =>'AdminController@AdminDashboardPage'));


      #Donut Graph For Admin Today Chart 
      Route::get('/dashboard/admin/today/all-report/summary',array('as'=>'All Report Summary', 'desc'=>'view & detail', 'uses'=>'ReportController@AjaxAllSummaryReport'));

      #Donut Graph For Admin weekly
      Route::get('/dashboard/admin/line-graph/chart',array('as'=>'Line Chart Report', 'desc'=>'view & detail', 'uses'=>'ReportController@AjaxLineChartReport'));


      #Company Page
      Route::get('/dashboard/company/info',array('as'=>'Company Info', 'desc'=>'view & detail', 'uses'=>'AdminController@CompanyPage'));

      #Company Detail Insert Update
      Route::post('/dashboard/company/info',array('as'=>'Company Info', 'desc'=>'view & detail','uses'=>'AdminController@CompanyDetailInsert'));



      #Access Log List
      Route::get('/system-admin/access-logs',array('as'=>"Access Logs", 'desc'=>'view & detail', 'uses'=>'AdminController@AccessLogs'));

      # Error Log List
      Route::get('/system-admin/error-logs', array('as'=>"Error Logs", 'desc'=>'view & detail', 'uses'=>'AdminController@ErrorLogs'));

      #Event Log List
      Route::get('/system-admin/event-logs',array('as'=>"Event Logs", 'desc'=>'view & detail', 'uses'=>'AdminController@EventLogs'));

      #Event Log Details
      Route::get('/event-logs/details/{event_id}',array('as'=>'Event Logs Details','desc'=>'view & detail','uses'=>'AdminController@EventLogsDetails'));

      #Auth Log List
      Route::get('/system-admin/auth-logs',array('as'=>"Auth Logs", 'desc'=>'view & detail', 'uses'=>'AdminController@AuthLogs'));



      /*################
      ## Reports
      #################
      */

      #Report Balance Sheet
      Route::get('/reports/balance-sheet',array('as'=>'Reports Balance Sheet', 'desc'=>'view & detail', 'uses'=>'ReportController@ReportBalanceSheetPage'));

      #Report Balance Sheet PDF
      Route::get('/reports/balance-sheet/pdf/from-{search_from}/to-{search_to}/cid-{cost_center}',array('as'=>'PDF::Balance Sheet', 'desc'=>'view & detail', 'uses'=>'ReportController@BalanceSheetPdf'));

      #Report Balance Sheet Print
      Route::get('/reports/balance-sheet/print/from-{search_from}/to-{search_to}/cid-{cost_center}',array('as'=>'Print::Balance Sheet', 'desc'=>'view & detail', 'uses'=>'ReportController@BalanceSheetPrint'));

      #Report Cashflow
      Route::get('/reports/cash-flow',array('as'=>'Reports of Cash Flow', 'desc'=>'view & detail', 'uses'=>'ReportController@ReportCahsFlowPage'));

      #Report Cashflow 
      Route::get('/reports/cash-flow/ledger',array('as'=>'Reports of Cash Flow', 'desc'=>'ledger & detail', 'uses'=>'ReportController@ReportCahsFlowLedgerPage'));



      #Trail Balance
      Route::get('/trail/balance/report',array('as'=>'Report of Trail Balance','desc'=>'view & detail', 'uses'=>'ReportController@TrailBalancePage'));
      #Trail Balance Pdf
      Route::get('/trail/balance/report/pdf/from-{search_from}/to-{search_to}/ccid-{cost_center_id}',array('as'=>'Trail Balance PDF', 'uses'=>'ReportController@TrailBalancePDF'));
      #Trail Balance Print
      Route::get('/trail/balance/report/print/from-{search_from}/to-{search_to}/ccid-{cost_center_id}',array('as'=>'Trail Balance Print', 'uses'=>'ReportController@TrailBalancePrint'));


      #Account Pyable Report
      Route::get('/account-payable/balance/report',array('as'=>'A/C Payable Report', 'uses'=>'ReportController@AccountPayableReport'));
      #Account Pyable Pdf
      Route::get('/account-payable/report/pdf/from-{search_from}/to-{search_to}/ccid-{cost_center_id}',array('as'=>'Account Pyable Report PDF', 'uses'=>'ReportController@AccountPayableReportPDF'));
      #Account Pyable Print
      Route::get('/account-payable/report/print/from-{search_from}/to-{search_to}/ccid-{cost_center_id}',array('as'=>'Account Pyable Report Print', 'uses'=>'ReportController@AccountPayableReportPrint'));


      #Account Receivable Report
      Route::get('/account-receivable/balance/report',array('as'=>'A/C Receivable Report', 'uses'=>'ReportController@AccountReceivableReport'));
      #Account Receivable Pdf
      Route::get('/account-receivable/report/pdf/from-{search_from}/to-{search_to}/ccid-{cost_center_id}',array('as'=>'Account Receivable Report PDF', 'uses'=>'ReportController@AccountReceivableReportPDF'));
      #Account Receivable Print
      Route::get('/account-receivable/report/print/from-{search_from}/to-{search_to}/ccid-{cost_center_id}',array('as'=>'Account Receivable Report Print', 'uses'=>'ReportController@AccountReceivableReportPrint'));



      #Purchase Report Page
      Route::get('/purchase/balance/report',array('as'=>'Purchase Report', 'uses'=>'ReportController@PurchaseReportPage'));
      #Purchase Report Page Pdf
      Route::get('/purchase/report/pdf/from-{search_from}/to-{search_to}/ccid-{cost_center_id}',array('as'=>'Purchase Report Page PDF', 'uses'=>'ReportController@PurchaseReportPagePDF'));
      #Purchase Report Page Print
      Route::get('/purchase/report/print/from-{search_from}/to-{search_to}/ccid-{cost_center_id}',array('as'=>'Purchase Report Page Print', 'uses'=>'ReportController@PurchaseReportPagePDFPrint'));


      #Sales Report Page
      Route::get('/sales/balance/report',array('as'=>'Sales Report', 'uses'=>'ReportController@SalesReportPage'));
      #Sales Report Page Pdf
      Route::get('/sales/report/pdf/from-{search_from}/to-{search_to}/ccid-{cost_center_id}',array('as'=>'Sales Report Page PDF', 'uses'=>'ReportController@SalesReportPagePDF'));
      #Sales Report Page Print
      Route::get('/sales/report/print/from-{search_from}/to-{search_to}/ccid-{cost_center_id}',array('as'=>'Sales Report Page Print', 'uses'=>'ReportController@SalesReportPagePDFPrint'));


      #Manufacturing Report
      Route::get('/manufacturing/report',array('as'=>'Manufacturing Report', 'uses'=>'ReportController@ManufacturingReport'));

      #Manufacturing Report PDF
      Route::get('/manufacturing/report/pdf/from-{search_from}/to-{search_to}/ccid-{cost_center_id}',array('as'=>'Manufacturing Report PDF', 'uses'=>'ReportController@ManufacturingReportPDF'));

      #Manufacturing Report Print
      Route::get('/manufacturing/report/print/from-{search_from}/to-{search_to}/ccid-{cost_center_id}',array('as'=>'Manufacturing Report Print', 'uses'=>'ReportController@ManufacturingReportPrint'));



      #Stock Summery List
      Route::get('/stock/summery/list',array('as'=>'Inventory Stocks Summery List','desc'=>'total & details', 'uses'=>'ReportController@StockSummeryList'));
      #Inventory Stock Summery PDF
      Route::get('/stock/summery/pdf/from-{search_from}/to-{search_to}/ccid-{cost_center}',array('as'=>'Inventory Stock SummeryPDF','desc'=>'total & details', 'uses'=>'ReportController@InventoryStockSummaryPDF'));
      #Inventory Stock Summery Print
      Route::get('/stock/summery/print/from-{search_from}/to-{search_to}/ccid-{cost_center}',array('as'=>'Inventory Stock Summery Print','desc'=>'total & details', 'uses'=>'ReportController@InventoryStockSummaryPrint'));



      #Finish Goods Summery List
      Route::get('/finish-goods/summery/list',array('as'=>'Finish Goods Summery List','desc'=>'total & details', 'uses'=>'ReportController@FinishGoodsSummeryList'));
      #Finish Goods Summery PDF
      Route::get('/finish-goods/pdf/from-{search_from}/to-{search_to}/ccid-{cost_center}',array('as'=>'Finish Goods PDF','desc'=>'total & details', 'uses'=>'ReportController@FinishGoodsReportPDF'));
      #Finish Goods Summery Print
      Route::get('/finish-goods/print/from-{search_from}/to-{search_to}/ccid-{cost_center}',array('as'=>'Finish Goods Print','desc'=>'total & details', 'uses'=>'ReportController@FinishGoodsReportPrint'));



      #Income Statement Report 
      Route::get('/income-statement/report',array('as'=>'Income Statement', 'desc'=>'revenues & expenses', 'uses'=>'ReportController@IncomeStatementReport'));

      #Income Statement PDF 
      Route::get('/income-statement/pdf/from-{search_from}/to-{search_to}/ccid-{cost_center}',array('as'=>'Income Statement PDF', 'desc'=>'revenues & expenses', 'uses'=>'ReportController@IncomeStatementReportPDF'));


      #Income Statement Print 
      Route::get('/income-statement/print/from-{search_from}/to-{search_to}/ccid-{cost_center}',array('as'=>'Income Statement Print', 'desc'=>'revenues & expenses', 'uses'=>'ReportController@IncomeStatementReportprint'));



      /*################
      ## User 
      #################
      */

       #Admin User Managemenet Page
      Route::get('/dashboard/admin/user/management',array('as'=>'User Managemenet' , 'uses' =>'AdminController@AdminUserManagement'));

      #Admin User Managemenet Page
      Route::post('/dashboard/admin/user/registration',array('as'=>'User Registration' , 'uses' =>'AdminController@AdminUserRegistration'));


      #ChangeUserStatus
      Route::get('/dashboard/change-user-status/{user_id}/{status}',array('as'=>'Change User Status' , 'uses' =>'AdminController@ChangeUserStatus'));
  
      #User Profile
      Route::get('/user/profile',array('as'=>'User Profile', 'uses'=>'AdminController@UserProfile'));

      #User Profile View By Id
      Route::get('/user/profile/view/id-{user_id}',array('as'=>'User Profile', 'desc'=>'view & update', 'uses'=>'AdminController@UserProfileUpdatePage'));

      #User Profile Update BY ID
      Route::post('/user/profile/view/id-{user_id}',array('as'=>'User Profile', 'desc'=>'view & update', 'uses'=>'AdminController@UserProfileUpdateSubmit'));
      #User Profile Update BY ID
      Route::post('/user/change/password/id-{user_id}',array('as'=>'User Profile', 'desc'=>'view & update', 'uses'=>'AdminController@UserProfileUpdatePassword'));


      #User Profile Update
      Route::post('/user/profile/update',array('as'=>'User Profile Update', 'uses'=>'AdminController@ProfileUpdate'));

      #User Password Change
      Route::post('/user/change/password',array('as'=>'User Password Change', 'uses'=>'AdminController@UserChangePassword'));

      #User Profile
      Route::get('/user/profile/delete/{user_id}',array('as'=>'User Profile', 'uses'=>'AdminController@UserProfileDelete'));



      /*################
      ## Cost Center
      #################*/

      #Cost Add
      Route::get('/dashboard/cost-center',array('as'=>'Cost Center', 'uses'=>'AdminController@CostCenterPage'));
      Route::post('/dashboard/cost-center',array('as'=>'Cost Insert', 'uses'=>'AdminController@CostCenterInsert'));
      #Cost Edit
      Route::get('/dashboard/cost-center/edit/{cost_center_id}',array('as'=>'Cost Edit', 'uses'=>'AdminController@CostCenterEditPage'));
      #Cost Update
      Route::post('/dashboard/cost-center/edit/{cost_center_id}',array('as'=>'Cost Edit', 'uses'=>'AdminController@CostCenterUpdate'));
      #Cost Delete
      Route::get('/dashboard/cost-center/delete/{cost_center_id}',array('as'=>'Cost Delete', 'uses'=>'AdminController@CostCenterDelete'));


      /*################
      ## Inventory
      #################*/

      #InventoryCategorySettingPage
      Route::get('/inventory/category/settings',array('as'=>'Category Settings' , 'desc'=>'entry & Edit', 'uses' =>'InventoryController@CategorySettingPage'));
      #InventoryCategoryInsert
      Route::post('/inventory/category/settings',array('as'=>'Category Settings' , 'desc'=>'entry & edit', 'uses' =>'InventoryController@CategorySettingInsert'));
      #AjaxInventoryCategoryEntry
      Route::get('/ajax/category/settings',array('as'=>'Ajax Category Setting' , 'desc'=>'entry & payment', 'uses' =>'InventoryController@AjaxCategoryEntry'));
      #InventoryCategoryDelete
      Route::get('/ajax/category/settings/delete/{item_category_id}',array('as'=>'Ajax Category Setting Delete' , 'desc'=>'entry & payment', 'uses' =>'InventoryController@AjaxCategoryDelete'));
      #InventoryCategoryUpdate
      Route::get('/ajax/category/settings/update/{item_category_id}/{item_category_name}/{item_quantity_unit}',array('as'=>'Ajax Category Setting Update' , 'desc'=>'entry & payment', 'uses' =>'InventoryController@AjaxCategoryUpdate'));


      #InventoryItemPage
      Route::get('/inventory/item/settings',array('as'=>'Inventory Item' , 'desc'=>'entry & Edit', 'uses' =>'InventoryController@InventorySettings'));
      #InventoryItemInsert
      Route::post('/inventory/item/settings',array('as'=>'Inventory Item' , 'desc'=>'entry', 'uses' =>'InventoryController@InventoryStockInsert'));
      #AjaxInventoryItemEntry
      Route::get('/ajax/stock/settings',array('as'=>'Ajax Inventory Setting' , 'desc'=>'entry & payment', 'uses' =>'InventoryController@AjaxStockEntry'));
      #InventoryItemUpdate
      Route::get('/ajax/inventory/settings/update/stock-{inventory_stock_id}/cat-{item_category_id}/item-{item_name}/desc-{item_description}',array('as'=>'Ajax Inventory Setting Update' , 'desc'=>'entry & payment', 'uses' =>'InventoryController@AjaxInventoryStockUpdate'));
      #InventoryItemDelete
      Route::get('/ajax/inventory/settings/delete/{inventory_stock_id}',array('as'=>'Ajax Inventory Setting Delete' , 'desc'=>'entry & payment', 'uses' =>'InventoryController@AjaxInventoryDelete'));

      #Inventory purchase
      Route::get('/inventory/purchase/invoice',array('as'=>'Inventory Purchase Invoice' , 'desc'=>'entry & payment', 'uses' =>'InventoryController@InventoryStocksPurchasePage'));

      #Inventory purchase Field
      Route::get('/inventory/stocks/field/{filed_count}',array('as'=>'Inventory Stocks Entry' , 'desc'=>'entry & payment', 'uses' =>'InventoryController@AjaxInventoryStocksFieldEntry'));

      #Inventory purchase Submit
      Route::post('/inventory/purchase/invoice',array('as'=>'Inventory Stocks Entry' , 'desc'=>'entry & payment', 'uses' =>'InventoryController@InventoryStocksPurchaseInsert'));

      #Inventory purchase View
      Route::get('/inventory/purchase/invoice/view',array('as'=>'Purchase Invoice' , 'desc'=>'view', 'uses' =>'InventoryController@InventoryStocksPurchaseBillPage'));

      #Inventory purchase Print
      Route::get('/inventory/purchase/invoice/print',array('as'=>'Purchase Invoice' , 'desc'=>'view', 'uses' =>'InventoryController@InventoryStocksPurchaseBillPrint'));

      #Inventory purchase PDF
      Route::get('/inventory/purchase/invoice/download/pdf',array('as'=>'Purchase Invoice' , 'desc'=>'view', 'uses' =>'InventoryController@InventoryStocksPurchaseBillDownloadPDF'));

      #Stock Ledger account Create
      Route::post('/inventory/ledger/new-account',array('as'=>'Inventory Ledger Account' , 'desc'=>'entry & payment', 'uses' =>'InventoryController@InventoryLedgerAccountCreate'));

      // #Inventory on Production
      // Route::get('/inventory/stocks/on-production',array('as'=>'Inventory Stocks On Production' , 'desc'=>'production & stocks', 'uses' =>'InventoryController@InventoryStocksOnProductionPage'));

      // #Inventory on Production
      // Route::post('/inventory/stocks/on-production',array('as'=>'Inventory Stocks On Production' , 'desc'=>'production & stocks', 'uses' =>'InventoryController@InventoryStocksOnProductionInsert'));

      #Inventory Production Entry Field
      Route::get('/inventory/stocks-production/field/{filed_count}',array('as'=>'Inventory Stocks On Production' , 'desc'=>'production & stocks', 'uses' =>'InventoryController@AjaxInventoryStocksProductionFieldEntry'));
      #Inventory Production Entry Field
      Route::get('/inventory/stocks-production/info/{inventory_stock_id}',array('as'=>'Inventory Stocks On Production' , 'desc'=>'production & stocks', 'uses' =>'InventoryController@AjaxInventoryStocksInfo'));
      #Inventory Stocks Transaction List
      Route::get('/inventory/stocks/trasansaction/list',array('as'=>'Inventory Stocks Transaction' , 'desc'=>'list & details', 'uses' =>'InventoryController@InventoryStocksTransactionList'));
      #Inventory Stocks Transaction Bill view
      Route::get('/inventory/stocks/trasansaction/{stocks_transactions_id}/view',array('as'=>'Inventory Stocks Transaction' , 'desc'=>'bill & details', 'uses' =>'InventoryController@InventoryStocksTransactionView'));
       #Inventory Stocks Transaction Bill print
      Route::get('/inventory/stocks/trasansaction/{stocks_transactions_id}/print',array('as'=>'Inventory Stocks Transaction' , 'desc'=>'bill & details', 'uses' =>'InventoryController@InventoryStocksTransactionPrint'));
       #Inventory Stocks Transaction Bill download
      Route::get('/inventory/stocks/trasansaction/{stocks_transactions_id}/download',array('as'=>'Inventory Stocks Transaction' , 'desc'=>'bill & details', 'uses' =>'InventoryController@InventoryStocksTransactionDownload'));
      #Inventory Stocks Transaction Bill download
      Route::get('/inventory/stocks/trasansaction/{stocks_transactions_id}/excel',array('as'=>'Inventory Stocks Transaction' , 'desc'=>'bill & details', 'uses' =>'InventoryController@InventoryStocksTransactionExcel'));
      /*#Stock Summery PDF
      Route::get('/stock/summery',array('as'=>'Inventory Stocks Summery', 'uses'=>'InventoryController@StockSummery'));*/

      #Inventory Stock Item List
      Route::get('/inventory/stock/item/list',array('as'=>'Inventory Stocks Item List', 'uses'=>'InventoryController@InventoryStockItemList'));
      # Inventory Stock Item List PDF
      Route::get('/inventory/stock-item/download',array('as'=>'Inventory Stock Item List PDF', 'desc'=>'order & pdf', 'uses'=>'InventoryController@InventoryStockItemListPDF'));

      # Inventory Stock Item List Print
      Route::get('/inventory/stock-item/print',array('as'=>'Inventory Stock Item List Print', 'desc'=>'order & pdf', 'uses'=>'InventoryController@InventoryStockItemListPrint'));
      #finish-goods List
      Route::get('/finish-goods/list',array('as'=>'Inventory Finish-goods Entry','desc'=>'entry & view', 'uses'=>'InventoryController@FinshGoodsListPage'));
      #finish-goods List
      Route::post('/finish-goods/list',array('as'=>'Inventory Finish-goods Entry','desc'=>'entry & view', 'uses'=>'InventoryController@FinshGoodsSubmit'));
       #Inventory Production Entry Field
      Route::get('/finish-goods/field/{filed_count}',array('as'=>'Inventory Stocks Finish goods' , 'desc'=>'production & stocks', 'uses' =>'InventoryController@AjaxInventoryStocksFinsihgoodsFieldEntry'));
      #Inventory Production Entry Field
      Route::get('/finish-goods/stocks-info/{inventory_stock_id}',array('as'=>'Inventory Stocks Finish goods' , 'desc'=>'production & stocks', 'uses' =>'InventoryController@AjaxInventoryStocksInfoFinishgoods'));
      #Delivery finish-goods List
      Route::get('/delivery/finish-goods/list',array('as'=>'Waste Goods Summery','desc'=>'entry & view', 'uses'=>'InventoryController@DeliveryFinishGoodsList'));
      #Waste finish-goods
      Route::get('/waste/finish-goods/id-{finish_goods_id}',array('as'=>'Waste Finish-goods','desc'=>'entry & view', 'uses'=>'InventoryController@WasteFinishGoods'));



      /*################
      ## Journal
      #################
      */

      #All Journal Debit-Cerdit
      Route::get('/all/journal/ledger',array('as'=>'All Ledger Transaction', 'desc'=>'All Journal List', 'uses'=>'JournalController@AllJournalList'));

      #Ledger Opening Balance
      Route::get('/ledger/opening/balance',array('as'=>'Ledger Opening Balance', 'desc'=>'Debit Credit', 'uses'=>'JournalController@LedgerOpeningBalance'));

      #Ajax Ledger Opening Balance
      Route::get('/ajax/opening/balance/id-{ledger_id}/depth-{depth}',array('as'=>'Ajax Ledger Opening Balance', 'desc'=>'Debit Credit', 'uses'=>'JournalController@AjaxLedgerOpeningBalance'));

      #Ledger Opening Balance Confirm
      Route::post('/ledger/opening/balance-confirm',array('as'=>'Ledger Opening Balance Confirm', 'desc'=>'Debit Credit', 'uses'=>'JournalController@LedgerOpeningBalanceConfirm'));

      #All Journal Debit-Cerdit Details
      Route::get('/journal/debit-cerdit/details/id-{ledger_id}',array('as'=>'Ledger Transaction Details', 'desc'=>' Journal Details', 'uses'=>'JournalController@JournalListDetails'));

      #Journal Details PDF
      Route::get('/journal/details/pdf/id-{ledger_id}',array('as'=>'Journal Details PDF', 'desc'=>' Journal Details PDF', 'uses'=>'JournalController@JournalDetailsPDF'));

      #Journal Details PDF Print
      Route::get('/journal/details/pdf/print/id-{ledger_id}',array('as'=>'Journal Details PDF Print', 'desc'=>' Journal Details Print', 'uses'=>'JournalController@JournalDetailsPDFPrint'));

      #Journal Transaction Table
      Route::get('/journal/transaction',array('as'=>'Journal', 'desc'=>'view & detail', 'uses'=>'JournalController@JuournalTransactionView'));
      
      #Transaction List
      Route::get('/general/transaction-list',array('as'=>'General Transaction', 'uses'=>'JournalController@GeneralAllTransactionList'));
      #Transaction List By cost
      Route::get('/ajax/general/transaction-list-by-cost/{cost_center_id}',array('as'=>'Cost General Transaction', 'uses'=>'JournalController@AjaxCostCenterPage'));
      #Transaction List By Posting
      Route::get('/ajax/general/transaction-by-posting/{posting_type_id}',array('as'=>'Posting General Transaction', 'uses'=>'JournalController@AjaxPostingPage'));

      Route::get('/general/transaction-list/pdf/from-{search_from}/to-{search_to}/ccid-{cost_center}/posting-{posting_type}/user-{created_by}',array('as'=>'General Transaction PDF', 'uses'=>'JournalController@GeneralAllTransactionListPDF'));

      
      #Edit Transaction List
      // Route::get('/general/transaction-list/edit/{transactions_id}',array('as'=>'General Transaction Edit', 'uses'=>'JournalController@EditGeneralTransaction'));

      Route::get('/general/transaction-list/edit/id-{transactions_id}/type-{posting_type}',array('as'=>'General Transaction Edit', 'uses'=>'JournalController@EditGeneralTransaction'));

      #Inventory purchase Field
      Route::get('/general/transaction/field/{filed_count}',array('as'=>'General Transaction Entry' , 'desc'=>'entry & payment', 'uses' =>'JournalController@AjaxEditGeneralTransactionFieldEntry'));

      #Update Transaction List
      Route::post('/general/transaction-list/update',array('as'=>'General Transaction Update', 'uses'=>'JournalController@UpdateGeneralTransaction'));

      #Delete Transaction List
      Route::get('/general/transaction-list/delete/id-{transactions_id}/type-{posting_type}',array('as'=>'General Transaction Delete', 'uses'=>'JournalController@DeleteGeneralTransaction'));



      
  });


#####################START OF Common Auth######################################
  Route::group(['middleware' => ['auth']], function () {
      


      #################
      ## USER
      ################

      #User Profile
      Route::get('/user/profile',array('as'=>'User Profile', 'uses'=>'AdminController@UserProfile'));
      #User Profile Update
      Route::post('/user/profile/update',array('as'=>'User Profile Update', 'uses'=>'AdminController@ProfileUpdate'));
      #User Password Change
      Route::post('/user/change/password',array('as'=>'User Password Change', 'uses'=>'AdminController@UserChangePassword'));


      #################
      ## Journal
      ################

      #Journal Ajax Group Data
      Route::get('/journal/ledger-{ledger_id}/depth-{depth}',array('as'=>'Accounts', 'desc'=>'add ledger & sub ledger', 'uses'=>'JournalController@JuournalGroupDataAddPage'));

      Route::post('/journal/ledger-{ledger_id}/depth-{depth}',array('as'=>'Ledger', 'desc'=>'add ledger & sub ledger', 'uses'=>'JournalController@JuournalGroupDataInsert'));
      #Journal Ajax Sub Group Data
      Route::get('/journal/sub-group/{group_id}/group-{group_number}',array('as'=>'Journal Posting', 'uses'=>'JournalController@JuournalAjaxSubnodeList'));




      #Journal posting page
      Route::get('/journal/posting/type-{posting_type}',array('as'=>'Posting', 'desc'=>'view & detail', 'uses'=>'JournalController@JuournalPostingPage'));
      #Journal posting page
      Route::post('/journal/posting/type-{posting_type}',array('as'=>'Posting', 'desc'=>'view & detail', 'uses'=>'JournalController@JuournalPostingSubmit'));

      Route::get('/journal/posting/print/{transaction_id}',array('as'=>'Posting Print', 'desc'=>'view & detail', 'uses'=>'JournalController@JuournalPostingPrint'));

      Route::get('/journal/posting/download/{transaction_id}',array('as'=>'Posting Print', 'desc'=>'view & detail', 'uses'=>'JournalController@JuournalPostingDownload'));

      #Journal Transaction Table
      Route::get('/journal/ajax-field/{add_type}',array('as'=>'Journal', 'desc'=>'view & detail', 'uses'=>'JournalController@JuournalAjaxPostingField'));

      #Journal Transaction Table
      Route::get('/profit/loss',array('as'=>'Income', 'desc'=>'profil & loss', 'uses'=>'ReportController@ProfitandLossReport'));




      /*################
      ## Supplier
      #################
      */

      #Supplier Registration Confirm
      Route::post('/supplier/add',array('as'=>'Supplier Registration Confirm', 'uses'=>'SupplierController@SupplierModalRegistrationConfirm'));

      # Supplier List 
      Route::get('/supplier/list',array('as'=>'All Supplier', 'desc'=>'List & View', 'uses'=>'SupplierController@SupplierListPage'));

      # Edit Supplier List 
      Route::get('/edit/supplier/id-{customer_id}',array('as'=>'Edit Supplier List', 'desc'=>'List & View', 'uses'=>'SupplierController@EditSupplierPage'));

      # Update Supplier 
      Route::post('/update/supplier/id-{customer_id}',array('as'=>'Update Supplier List', 'desc'=>'List & View', 'uses'=>'SupplierController@UpdateSupplier'));

      #Supplier Payemnt
      Route::get('/supplier/payment',array('as'=>'Supplier Payment', 'desc'=>'Credit & Payment', 'uses'=>'SupplierController@SupplierPaymentPage'));


      #Supplier Payemnt field add
      Route::get('/supplier/payment/field/{filed_count}/stocks-{stocks_transactions_id}',array('as'=>'Supplier Payment', 'desc'=>'Credit & Payment', 'uses'=>'SupplierController@AjaxSupplierPaymentField'));


      #Supplier Payemnt Submit
      Route::post('/supplier/payment',array('as'=>'Supplier Payment', 'desc'=>'Credit & Payment', 'uses'=>'SupplierController@SupplierPaymentSubmit'));

      #Supplier Payemnt account
      Route::get('/supplier/payment-method/{method_type}',array('as'=>'Supplier Payment', 'desc'=>'Credit & Payment', 'uses'=>'SupplierController@SupplierPaymentAccountSelectBox'));


      #Supplier Payemnt voucher
      Route::get('/supplier/payment/voucher/view',array('as'=>'Supplier Payment Voucher', 'desc'=>'Payment & Voucher', 'uses'=>'SupplierController@SupplierPaymentVoucherPage'));

      #Supplier Payemnt voucher Print
      Route::get('/supplier/payment/voucher/print',array('as'=>'Supplier Payment Voucher', 'desc'=>'Payment & Voucher', 'uses'=>'SupplierController@SupplierPaymentVoucherPrint'));

      #Supplier Payemnt voucher Download
      Route::get('/supplier/payment/voucher/download/pdf',array('as'=>'Supplier Payment Voucher', 'desc'=>'Payment & Voucher', 'uses'=>'SupplierController@SupplierPaymentVoucherDownloadPDF'));



      #Supplier Sales Return
      Route::get('/supplier/purchase/return',array('as'=>'Supplier Purchase Return', 'desc'=>'Return & Voucher', 'uses'=>'SupplierController@SupplierPurchaseReturnPage'));
      #Supplier Sales Return Submit
      Route::post('/supplier/purchase/return',array('as'=>'Supplier Purchase Return', 'desc'=>'Return & Voucher', 'uses'=>'SupplierController@SupplierPurchaseReturnSubmit'));
      #Supplier Sales Return Submit
      Route::get('/supplier/purchase/return/invoice/stocks-tran-{stocks_transactions_id}',array('as'=>'Purchase Return', 'desc'=>'Invoice ', 'uses'=>'SupplierController@SupplierPurchaseReturnInvoicePage'));
      #Supplier Sales Return Download
      Route::get('/supplier/purchase/return/invoice/download/pdf/stocks-tran-{stocks_transactions_id}',array('as'=>'Purchase Return', 'desc'=>'Invoice ', 'uses'=>'SupplierController@SupplierPurchaseReturnInvoiceDownloadPDF'));
      #Supplier Sales Return Print
      Route::get('/supplier/purchase/return/invoice/print/stocks-tran-{stocks_transactions_id}',array('as'=>'Purchase Return', 'desc'=>'Invoice ', 'uses'=>'SupplierController@SupplierPurchaseReturnInvoicePrint'));



      /*################
      ## Customer
      #################*/


      # Customer List 
      Route::get('/customer/list',array('as'=>'All Customer', 'desc'=>'List & View', 'uses'=>'CustomerController@CustomerListPage'));
      # Edit Customer List 
      Route::get('/edit/customer/id-{customer_id}',array('as'=>'Edit Customer List', 'desc'=>'List & View', 'uses'=>'CustomerController@EditCustomerPage'));
      # Update Customer 
      Route::post('/update/customer/id-{customer_id}',array('as'=>'Update Customer List', 'desc'=>'List & View', 'uses'=>'CustomerController@UpdateCustomer'));
      # Customer Order
      Route::get('/customer/order',array('as'=>'Customer Order', 'desc'=>'sales & order', 'uses'=>'CustomerController@CustomerOrderPage'));
      #Sales Order Confirm
      Route::post('/customer/order',array('as'=>'Customer Order Confirm' , 'desc'=>'sales & order', 'uses' =>'CustomerController@CustomerOrderInsert'));
      #Customer Order PDF
      Route::get('/customer/order/download/{order_id}',array('as'=>'Customer Order PDF', 'desc'=>'order & pdf', 'uses'=>'CustomerController@CustomerOrderPDF'));
      # Customer Order PDF Print
      Route::get('/customer/order/print/{order_id}',array('as'=>'Customer Order PDF Print', 'desc'=>'order & pdf', 'uses'=>'CustomerController@CustomerOrderPDFPrint'));
      # Customer Order Individual List
      Route::get('/customer/order-list/{order_id}',array('as'=>'Customer Order Individual List', 'desc'=>'order & pdf', 'uses'=>'CustomerController@CustomerOrderIndividualList'));
     #Customer Registration Confirm
        Route::post('/customer/registration',array('as'=>'Customer Registration Confirm', 'uses'=>'CustomerController@CustomerRegistrationConfirm'));


      # Customer Deleivery
      Route::get('/customer/order/delivery',array('as'=>'Order Delivery', 'desc'=>'order & delivery ', 'uses'=>'CustomerController@CustomerOrderDeliveryPage'));
     
     Route::get('/customer/order/delivery/ajax/order/{order_id}/field/{field_count}',array('as'=>'Order Delivery', 'desc'=>'order & delivery ', 'uses'=>'CustomerController@CustomerOrderAjaxDelivery'));

      Route::post('/customer/order/delivery',array('as'=>'Order Delivery', 'desc'=>'order & delivery ', 'uses'=>'CustomerController@CustomerOrderDeliveryConfirm'));


      #Sales Order Entry Field
      Route::get('/sales/order/field/{filed_count}',array('as'=>'Customer Sales Order Entry' , 'desc'=>'Sales & Order', 'uses' =>'CustomerController@AjaxSalesOrderFieldEntry'));
      #Sales Order Entry Field
      Route::get('/sales/order/info/{inventory_stock_id}',array('as'=>'Customer Sales Order Info' , 'desc'=>'Sales & Order Info', 'uses' =>'CustomerController@AjaxSalesOrderInfo'));

      #Sales Invoice Page
      Route::get('/customer/sales/invoice/order-{order_id}',array('as'=>'Sales Invoice' , 'desc'=>'Sales & Order Info', 'uses' =>'CustomerController@CustomerSalesInvoicePage'));
      #Sales Invoice Page
      Route::get('/customer/sales/invoice/print/order-{order_id}/',array('as'=>'Sales Invoice' , 'desc'=>'Sales & Order Info', 'uses' =>'CustomerController@CustomerSalesInvoicePrint'));

      Route::get('/customer/sales/invoice/download/pdf/order-{order_id}/',array('as'=>'Sales Invoice' , 'desc'=>'Sales & Order Info', 'uses' =>'CustomerController@CustomerSalesInvoiceDownloadPDF'));


      #Customer Receipt
      Route::get('/customer/payment',array('as'=>'Order Receipt', 'desc'=>'Debit & Receipt', 'uses'=>'CustomerController@CustomerPaymentPage'));

      #Customer Receipt Submit
      Route::post('/customer/payment',array('as'=>'Receipt', 'desc'=>'Debit & Receipt', 'uses'=>'CustomerController@CustomerPaymentSubmit'));

      #Customer Receipt account
      Route::get('/customer/payment-method/{method_type}',array('as'=>'Customer Receipt', 'desc'=>'Debit & Receipt', 'uses'=>'CustomerController@CustomerPaymentAccountSelectBox'));

      #Customer Receipt Order Balance account
      Route::get('/customer/order-balance/{customer_order_id}',array('as'=>'Customer Order Balance', 'desc'=>'Get & Balance', 'uses'=>'CustomerController@AjaxCustomerOrderAmount'));


      #Customer Order Payemnt 
      Route::get('/ajax/payment/entry/{customer_order_id}/{rid}',array('as'=>'Customer Order Payment', 'desc'=>'Get & Balance', 'uses'=>'CustomerController@AjaxCustomerOrderPayment'));

      #Customer Payemnt voucher 
      Route::get('/customer/payment/voucher/view',array('as'=>'Customer Payment Voucher', 'desc'=>'Payment & Voucher', 'uses'=>'CustomerController@CustomerPaymentVoucherPage'));

      #Customer Payemnt voucher PDF 
      Route::get('/customer/payment/voucher/pdf',array('as'=>'Customer Payment PDF', 'desc'=>'Payment & Voucher', 'uses'=>'CustomerController@CustomerPaymentPDFPage'));

      #Customer Payemnt voucher Print 
      Route::get('/customer/payment/voucher/print',array('as'=>'Customer Payment Voucher Print', 'desc'=>'Payment & Voucher', 'uses'=>'CustomerController@CustomerPaymentPDFPrintPage'));



      # Customer All Order List
      Route::get('/customer/all/order-list',array('as'=>'All Order List', 'desc'=>'sales & order', 'uses'=>'CustomerController@CustomerAllOrderList'));
      # Customer Order List PDF
      Route::get('/customer/order-list/download/from-{search_from}/to-{search_to}/cost-{cost_center}/customer-{customer}',array('as'=>'Customer Order List PDF', 'desc'=>'order & pdf', 'uses'=>'CustomerController@CustomerOrderListPDF'));

      # Customer Order List PDF Print
      Route::get('/customer/order-list/print/from-{search_from}/to-{search_to}/cost-{cost_center}/customer-{customer}',array('as'=>'Customer Order Details PDF Print', 'desc'=>'order & pdf', 'uses'=>'CustomerController@CustomerOrderListPrint'));

      # Customer Order Details List
      Route::get('/customer/order-details-list/{order_id}',array('as'=>'Customer Order Details List', 'desc'=>'order & pdf', 'uses'=>'CustomerController@CustomerOrderDetailsList'));

      # Customer Order Details PDF
      Route::get('/customer/order-details/download/{order_id}',array('as'=>'Customer Order Details PDF', 'desc'=>'order & pdf', 'uses'=>'CustomerController@CustomerOrderDetailsPDF'));

      # Customer Order Details PDF Print
      Route::get('/customer/order-details/print/{order_id}',array('as'=>'Customer Order Details PDF Print', 'desc'=>'order & pdf', 'uses'=>'CustomerController@CustomerOrderDetailsPDFPrint'));

      
      # Customer Sales Return
      Route::get('/customer/sales/return',array('as'=>'Customer Sales Return', 'desc'=>'sales return ', 'uses'=>'CustomerController@CustomerSalesReturnPage'));
      # Customer Sales Return
      Route::post('/customer/sales/return',array('as'=>'Customer Sales Return', 'desc'=>'sales return ', 'uses'=>'CustomerController@CustomerSalesReturnSubmit'));

      # Customer Sales Return Invoice
      Route::get('/customer/sales/return/invoice',array('as'=>'Customer Sales Return', 'desc'=>'sales return ', 'uses'=>'CustomerController@CustomerSalesReturnInvoice'));

      # Customer Sales Return Invoice Print
      Route::get('/customer/sales/return/invoice/print',array('as'=>'Customer Sales Return', 'desc'=>'sales return ', 'uses'=>'CustomerController@CustomerSalesReturnInvoicePrint'));

      # Customer Sales Return Invoice Print
      Route::get('/customer/sales/return/invoice/download/pdf',array('as'=>'Customer Sales Return', 'desc'=>'sales return ', 'uses'=>'CustomerController@CustomerSalesReturnInvoiceDownloadPDF'));



      /*################
      ## Inventory
      #################
      */

      #InventoryCategorySettingPage
      Route::get('/inventory/category/settings',array('as'=>'Category Settings' , 'desc'=>'entry & Edit', 'uses' =>'InventoryController@CategorySettingPage'));
      #InventoryCategoryInsert
      Route::post('/inventory/category/settings',array('as'=>'Category Settings' , 'desc'=>'entry & edit', 'uses' =>'InventoryController@CategorySettingInsert'));
      #AjaxInventoryCategoryEntry
      Route::get('/ajax/category/settings',array('as'=>'Ajax Category Setting' , 'desc'=>'entry & payment', 'uses' =>'InventoryController@AjaxCategoryEntry'));
      #InventoryCategoryDelete
      Route::get('/ajax/category/settings/delete/{item_category_id}',array('as'=>'Ajax Category Setting Delete' , 'desc'=>'entry & payment', 'uses' =>'InventoryController@AjaxCategoryDelete'));
      #InventoryCategoryUpdate
      Route::get('/ajax/category/settings/update/{item_category_id}/{item_category_name}/{item_quantity_unit}',array('as'=>'Ajax Category Setting Update' , 'desc'=>'entry & payment', 'uses' =>'InventoryController@AjaxCategoryUpdate'));


      #InventoryItemPage
      Route::get('/inventory/item/settings',array('as'=>'Inventory Item' , 'desc'=>'entry & Edit', 'uses' =>'InventoryController@InventorySettings'));
      #InventoryItemInsert
      Route::post('/inventory/item/settings',array('as'=>'Inventory Item' , 'desc'=>'entry', 'uses' =>'InventoryController@InventoryStockInsert'));
      #AjaxInventoryItemEntry
      Route::get('/ajax/stock/settings',array('as'=>'Ajax Inventory Setting' , 'desc'=>'entry & payment', 'uses' =>'InventoryController@AjaxStockEntry'));
      #InventoryItemUpdate
      Route::get('/ajax/inventory/settings/update/stock-{inventory_stock_id}/cat-{item_category_id}/item-{item_name}/desc-{item_description}',array('as'=>'Ajax Inventory Setting Update' , 'desc'=>'entry & payment', 'uses' =>'InventoryController@AjaxInventoryStockUpdate'));
      #InventoryItemDelete
      Route::get('/ajax/inventory/settings/delete/{inventory_stock_id}',array('as'=>'Ajax Inventory Setting Delete' , 'desc'=>'entry & payment', 'uses' =>'InventoryController@AjaxInventoryDelete'));


      #Inventory purchase
      Route::get('/inventory/purchase/invoice',array('as'=>'Inventory Purchase Invoice' , 'desc'=>'entry & payment', 'uses' =>'InventoryController@InventoryStocksPurchasePage'));

      #Inventory purchase Field
      Route::get('/inventory/stocks/field/{filed_count}',array('as'=>'Inventory Stocks Entry' , 'desc'=>'entry & payment', 'uses' =>'InventoryController@AjaxInventoryStocksFieldEntry'));

      #Inventory purchase Submit
      Route::post('/inventory/purchase/invoice',array('as'=>'Inventory Stocks Entry' , 'desc'=>'entry & payment', 'uses' =>'InventoryController@InventoryStocksPurchaseInsert'));

      #Inventory purchase
      Route::get('/inventory/purchase/invoice/view',array('as'=>'Purchase Invoice' , 'desc'=>'view', 'uses' =>'InventoryController@InventoryStocksPurchaseBillPage'));

      #Inventory purchase
      Route::get('/inventory/purchase/invoice/print',array('as'=>'Purchase Invoice' , 'desc'=>'view', 'uses' =>'InventoryController@InventoryStocksPurchaseBillPrint'));

      #Inventory purchase
      Route::get('/inventory/purchase/invoice/download/pdf',array('as'=>'Purchase Invoice' , 'desc'=>'view', 'uses' =>'InventoryController@InventoryStocksPurchaseBillDownloadPDF'));

      #Stock Ledger account Create
      Route::post('/inventory/ledger/new-account',array('as'=>'Inventory Ledger Account' , 'desc'=>'entry & payment', 'uses' =>'InventoryController@InventoryLedgerAccountCreate'));


      // #Inventory on Production
      // Route::get('/inventory/stocks/on-production',array('as'=>'Inventory Stocks On Production' , 'desc'=>'production & stocks', 'uses' =>'InventoryController@InventoryStocksOnProductionPage'));

      // #Inventory on Production
      // Route::post('/inventory/stocks/on-production',array('as'=>'Inventory Stocks On Production' , 'desc'=>'production & stocks', 'uses' =>'InventoryController@InventoryStocksOnProductionInsert'));

      #Inventory Production Entry Field
      Route::get('/inventory/stocks-production/field/{filed_count}',array('as'=>'Inventory Stocks On Production' , 'desc'=>'production & stocks', 'uses' =>'InventoryController@AjaxInventoryStocksProductionFieldEntry'));


      #Inventory Production Entry Field
      Route::get('/inventory/stocks-production/info/{inventory_stock_id}',array('as'=>'Inventory Stocks On Production' , 'desc'=>'production & stocks', 'uses' =>'InventoryController@AjaxInventoryStocksInfo'));


      #Inventory Stocks Transaction List
      Route::get('/inventory/stocks/trasansaction/list',array('as'=>'Inventory Stocks Transaction' , 'desc'=>'list & details', 'uses' =>'InventoryController@InventoryStocksTransactionList'));

      #Inventory Stocks Transaction Bill view
      Route::get('/inventory/stocks/trasansaction/{stocks_transactions_id}/view',array('as'=>'Inventory Stocks Transaction' , 'desc'=>'bill & details', 'uses' =>'InventoryController@InventoryStocksTransactionView'));

       #Inventory Stocks Transaction Bill print
      Route::get('/inventory/stocks/trasansaction/{stocks_transactions_id}/print',array('as'=>'Inventory Stocks Transaction' , 'desc'=>'bill & details', 'uses' =>'InventoryController@InventoryStocksTransactionPrint'));

       #Inventory Stocks Transaction Bill download
      Route::get('/inventory/stocks/trasansaction/{stocks_transactions_id}/download',array('as'=>'Inventory Stocks Transaction' , 'desc'=>'bill & details', 'uses' =>'InventoryController@InventoryStocksTransactionDownload'));


      #Inventory Stocks Transaction Bill download
      Route::get('/inventory/stocks/trasansaction/{stocks_transactions_id}/excel',array('as'=>'Inventory Stocks Transaction' , 'desc'=>'bill & details', 'uses' =>'InventoryController@InventoryStocksTransactionExcel'));

      #Stock Summery PDF
      Route::get('/stock/summery',array('as'=>'Inventory Stocks Summery', 'uses'=>'InventoryController@StockSummery'));
      #Stock Summery List
      Route::get('/stock/summery/list',array('as'=>'Inventory Stocks Summery List', 'uses'=>'ReportController@StockSummeryList'));

      #finish-goods List
      Route::get('/finish-goods/list',array('as'=>'Inventory Finish-goods Entry','desc'=>'entry & view', 'uses'=>'InventoryController@FinshGoodsListPage'));
      #finish-goods List
      Route::post('/finish-goods/list',array('as'=>'Inventory Finish-goods Entry','desc'=>'entry & view', 'uses'=>'InventoryController@FinshGoodsSubmit'));

       #Inventory Production Entry Field
      Route::get('/finish-goods/field/{filed_count}',array('as'=>'Inventory Stocks Finish goods' , 'desc'=>'production & stocks', 'uses' =>'InventoryController@AjaxInventoryStocksFinsihgoodsFieldEntry'));
      #Inventory Production Entry Field
      Route::get('/finish-goods/stocks-info/{inventory_stock_id}',array('as'=>'Inventory Stocks Finish goods' , 'desc'=>'production & stocks', 'uses' =>'InventoryController@AjaxInventoryStocksInfoFinishgoods'));


      #Transaction List By User
      Route::get('/general/transaction-list/by-user',array('as'=>'General Transaction', 'desc'=>'debit & credit', 'uses'=>'JournalController@GeneralAllTransactionListByUser'));
      #Journal Transaction Table
      Route::get('/journal/transaction/by-user',array('as'=>'Journal', 'desc'=>'view & detail', 'uses'=>'JournalController@JuournalTransactionViewByUser'));

      Route::get('/general/transaction-list/pdf/from-{search_from}/to-{search_to}/ccid-{cost_center}/posting-{posting_type}/user-{created_by}',array('as'=>'General Transaction PDF', 'uses'=>'JournalController@GeneralAllTransactionListPDF'));



});

##################### END OF Common Auth #######################################

 /*
  #####################
  ## Accounts Module
  ######################
  */
  Route::group(['middleware' => ['account_auth']], function () {
      
      #Accounts Dashboard Page
      Route::get('/dashboard/account/{name_slug}',array('as'=>'Dashboard' , 'uses' =>'AdminController@AdminDashboardAccounts'));

  });


  /*
  #####################
  ## Inventory Module
  ######################
  */
  Route::group(['middleware' => ['inventory_auth']], function () {

        #Dashboard Inventory
        Route::get('/dashboard/inventory/{name_slug}',array('as'=>'Dashboard' , 'uses' =>'AdminController@InventoryDashbordPage'));
       
  });






