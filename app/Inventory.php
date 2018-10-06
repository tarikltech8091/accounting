<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/*******************************
#
## Inventory Model
#
*******************************/

class Inventory extends Model
{
    /********************************************
    ## StockTransactionInfo
    *********************************************/

	public static function StockTransactionInfo($stocks_transactions_id){

		$stock_transaction_info = \DB::table('ltech_inventory_stocks_transactions')
									->where('ltech_inventory_stocks_transactions.stocks_transactions_id',$stocks_transactions_id)
									->leftJoin('ltech_inventory_stocks','ltech_inventory_stocks_transactions.inventory_stock_id','=','ltech_inventory_stocks.inventory_stock_id')
									->first();

		return $stock_transaction_info;
	}


	/********************************************
    ## SupplierAllStockTransaction
    *********************************************/

	public static function SupplierAllStockTransaction($stocks_supplier_id){

		$supplier_stock_transaction = \DB::table('ltech_inventory_stocks_transactions')
									->where('ltech_inventory_stocks_transactions.stocks_supplier_id',$stocks_supplier_id)
									->leftJoin('ltech_inventory_stocks','ltech_inventory_stocks_transactions.inventory_stock_id','=','ltech_inventory_stocks.inventory_stock_id')
									->get();
		return $supplier_stock_transaction;
	}

	/********************************************
    ## SupplierAllStockTransactionForReturn
    *********************************************/

	public static function SupplierAllStockTransactionForReturn($stocks_supplier_id){

		$supplier_stock_transaction = \DB::table('ltech_inventory_stocks_transactions')
									->where('ltech_inventory_stocks_transactions.stocks_supplier_id',$stocks_supplier_id)
									->where('ltech_inventory_stocks_transactions.return_status','!=',1)
									->where('ltech_inventory_stocks_transactions.stocks_transaction_type','!=','return')
									->leftJoin('ltech_inventory_stocks','ltech_inventory_stocks_transactions.inventory_stock_id','=','ltech_inventory_stocks.inventory_stock_id')
									->get();
		return $supplier_stock_transaction;
	}


	/********************************************
    ## SupplierCreditTransaction
    *********************************************/

	public static function SupplierCreditTransaction($supplier_id){

		$supplier_credit_transaction = \DB::table('ltech_inventory_supplier_credit_transactions')
									->where('ltech_inventory_supplier_credit_transactions.supplier_id',$supplier_id)
									->leftJoin('ltech_suppliers','ltech_inventory_supplier_credit_transactions.supplier_id','ltech_suppliers.supplier_id')
									->leftJoin('ltech_inventory_stocks_transactions','ltech_inventory_supplier_credit_transactions.stocks_transactions_id','=','ltech_inventory_stocks_transactions.stocks_transactions_id')
									->leftJoin('ltech_inventory_stocks','ltech_inventory_stocks.inventory_stock_id','=','ltech_inventory_stocks_transactions.inventory_stock_id')
									->orderBy('ltech_inventory_supplier_credit_transactions.supplier_credit_transactions_id','asc')
									->get();
		return $supplier_credit_transaction;
	}


	/********************************************
    ## StocksCreditLastTransaction
    *********************************************/

	public static function StocksCreditLastTransaction($stocks_transactions_id){

		$stocks_last_credit_transaction = \DB::table('ltech_inventory_supplier_credit_transactions')
									->where('stocks_transactions_id',$stocks_transactions_id)
									->first();
		return $stocks_last_credit_transaction;
	}

	/********************************************
    ## StocksCreditTransactionInfo
    *********************************************/
	public static function StocksCreditTransactionInfo($stocks_transactions_id){

		$stocks_credit_transaction_info = \DB::table('ltech_inventory_supplier_credit_transactions')
									->where('stocks_transactions_id',$stocks_transactions_id)
									->latest()->first();
		return $stocks_credit_transaction_info;
	}
	

	 /********************************************
    ## CustomerAllOrderTransaction
    *********************************************/

	 public static function CustomerAllOrderTransaction($order_customer_id){

	  $customer_order_transaction = \DB::table('ltech_sales_order_details')
	         ->where('ltech_sales_order_details.order_customer_id',$order_customer_id)
	         //->leftJoin('ltech_inventory_stocks','ltech_sales_order_details.order_stocks_id','=','ltech_inventory_stocks.inventory_stock_id')
	         ->get();

	  return $customer_order_transaction;
	 }


	 /********************************************
	    ## CustomerCreditTransaction
	    *********************************************/

	 public static function CustomerCreditTransaction($customer_id){

	  $order_credit_transaction = \DB::table('ltech_sales_orders')
	         ->where('ltech_sales_orders.order_customer_id',$customer_id)
	         ->leftJoin('ltech_customers','ltech_sales_orders.order_customer_id','ltech_customers.customer_id')
	         ->get();
	  return $order_credit_transaction;
	 }



}
