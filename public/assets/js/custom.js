

jQuery(function(){

	/*Tree expand and collapse */
	jQuery('.ledger_group_list').on('click', '.treebtn', function() {


	 	var spinner ='<div class="loading-spinner" style="width: 200px;">' +
            '<div class="progress progress-striped active">' +
            '<div class="progress-bar" style="width: 100%;"></div>' +
            '</div>' +
            '</div>';

	 	
	 	var action = jQuery(this).data('action');
	 	var group = jQuery(this).data('group');
	 	var depth = jQuery(this).data('depth');

	 

	 	if(action=='expand'){
	 		jQuery(".item_"+group).css({"display":"block"});
	 		jQuery('.loading').html(spinner);
	 		var site_url = jQuery('.site_url').val();
	 		var request_url =  site_url+'/journal/sub-group/'+group+'/group-'+depth;

	 		jQuery.ajax({
		          url: request_url,
		          type: "get",
		          success:function(data){

		          	jQuery('.item_'+group).html(data);
		          	jQuery('.loading').html('');
		            jQuery(".expand_"+group).css({"display":"none"});
	 				jQuery(".collapse_"+group).css({"display":"inline-block"});
		          }
		    });
		 	
	 	}else{
	 		
	 		jQuery(".item_"+group).css({"display":"none"});
	 		jQuery(".expand_"+group).css({"display":"inline-block"});
	 		jQuery(".collapse_"+group).css({"display":"none"});
	 	}
	 });

	 /* end Node Type */
});



/**Debit and Credit field add and remove*/
jQuery(function(){

	var max_fields_debit = 10; //maximum input boxes allowed
	var max_fields_credit = 10; //maximum input boxes allowed
    var wrapper_debit         = jQuery(".debit_body");
    var wrapper_credit         = jQuery(".credit_body");  //Fields wrapper
    var debit_add_btn      = jQuery(".debit_add_btn");
    var credit_add_btn      = jQuery(".credit_add_btn"); //Add button ID
    var site_url = jQuery('.site_url').val();
    
    var x = 1; //initlal text box count
    var y =1;
    jQuery(debit_add_btn).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields_debit){ //max input box allowed
            x++; //text box increment

	 		var request_url =  site_url+'/journal/ajax-field/debit';

	 		jQuery.ajax({
		          url: request_url,
		          type: "get",
		          success:function(data){
		          	jQuery(wrapper_debit).append(data);
		          }
		    });

             //add input box
        }
    });
    
    $(wrapper_debit).on("click",".debit_remove_btn", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('.debit_group').remove(); x--;

    });



    jQuery(credit_add_btn).click(function(e){ //on add input button click
        e.preventDefault();
        if(y < max_fields_credit){ //max input box allowed
            y++; //text box increment

	 		var request_url =  site_url+'/journal/ajax-field/credit';

	 		jQuery.ajax({
		          url: request_url,
		          type: "get",
		          success:function(data){
		          	jQuery(wrapper_credit).append(data);
		          }
		    });

             //add input box
        }
    });

    $(wrapper_credit).on("click",".credit_remove_btn", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('.credit_group').remove(); y--;
    });

});



/*Ajax form Sumit*/
$(document).ready(function() {
    // Ajax for our form
    $('.loginbtn').on('click', function(event) {
        event.preventDefault();


        var formData = $('form.form-login').serialize(); // form data as string
        var formAction = $('form.form-login').attr('action'); // form handler url
        var formMethod = $('form.form-login').attr('method'); // GET, POST

        $.ajaxSetup({
            headers: {
                'X-XSRF-Token': $('meta[name="_token"]').attr('content')
            }
        });

        $.ajax({
            type  : formMethod,
            url   : formAction,
            data  : formData,
            cache : false,

            beforeSend : function() {
                console.log(formData);
            },

            success : function(data) {
                    //alert(data.errormessage);


                    $.each(data.errormessage, function( key, value ) {
                      alert( key + ": " + value );
                    });
            },

            error : function() {

            }
        });

        return false; // prevent send form
    });
});

/*##########################################
# Change Status
############################################
*/

jQuery(function(){

    jQuery('.status').click(function(){
        var user_id = jQuery(this).data('id');
        var tab = jQuery(this).data('tab');
        var action = jQuery(this).data('action');
        var site_url = jQuery('.site_url').val();
        var request_url = site_url+'/dashboard/change-user-status/'+user_id+'/'+action;
    
        jQuery.ajax({
            url: request_url,
            type: "get",
            success:function(data){

                if(action==-1)
                 window.location.href =site_url+'/dashboard/admin/user/management?tab='+tab;

                if(action==1)
                    window.location.href =site_url+'/dashboard/admin/user/management?tab='+tab;
            }
        });
    });
});



/*##########################################
#TransactionList By CostCenter
############################################
*/

jQuery(function(){
    jQuery('.transaction_list').change(function(){
        var cost_center = jQuery(this).val();
        var site_url = jQuery('.site_url').val();

        var request_url = site_url+'/ajax/general/transaction-list-by-cost/'+cost_center;
        // alert(request_url);

        if(cost_center.length != 0){
            jQuery.ajax({
                url: request_url,
                type: 'get',
                success:function(data){
                    jQuery('.cost_list').html(data);
                }
            });
        }
    });
});


/*##########################################
#TransactionList By Posting
############################################
*/

jQuery(function(){
    jQuery('.transaction_post').change(function(){
        var post_type = jQuery(this).val();
        var site_url = jQuery('.site_url').val();

        var request_url = site_url+'/ajax/general/transaction-by-posting/'+post_type;
        // alert(request_url);

        if(post_type.length != 0){
            jQuery.ajax({
                url: request_url,
                type: 'get',
                success:function(data){
                    jQuery('.posting_list').html(data);
                }
            });
        }
    });
});




/*
########################
# Edit Customer
######################
*/
jQuery(function(){

  jQuery('.edit_customer_settings').click(function(){

      var id = jQuery(this).data('id');
      var site_url = jQuery('.site_url').val();
      if(id.length !=0){

       var request_url = site_url+'/customer/edit/'+id;
        jQuery.ajax({
          url: request_url,
          type: "get",
          success:function(data){

            jQuery('.edit_view').html(data);
          }
        });

      }else alert("Please Add To Edit");

  });
});




/*
########################
# Edit Supplier
######################
*/
jQuery(function(){

  jQuery('.edit_supplier_settings').click(function(){

      var id = jQuery(this).data('sid');
      var site_url = jQuery('.site_url').val();
      // if(id.length !=0){

       var request_url = site_url+'/supplier/edit/'+id;
       
        jQuery.ajax({
          url: request_url,
          type: "get",
          success:function(data){

            jQuery('.edit_supplier_view').html(data);
          }
        });

      // }else alert("Please Add To Edit");

  });
});




/*
########################
# Edit Supplier
######################
*/
jQuery(function(){

  jQuery('.edit_customer').click(function(){

      var id = jQuery(this).data('id');
      var site_url = jQuery('.site_url').val();

       var request_url = site_url+'/ajax/customer/id-'+id;
       
        jQuery.ajax({
          url: request_url,
          type: "get",
          success:function(data){

            jQuery('.edit_customer_view').html(data);
          }
        });

  });
});





/*
##########################
# Stocks entry Field Add
##########################
*/

jQuery(function(){

    var max_fields_stocks_entry = 10; //maximum input boxes allowed
   
    var wrapper_stocks_entry  = jQuery(".stocks_entry_body");
   
    var stocks_entry_add_btn  = jQuery(".add_line_stocks");
    
    var site_url = jQuery('.site_url').val();
    
    var x = 1; //initlal text box count

    jQuery(stocks_entry_add_btn).click(function(e){ //on add input button click
        e.preventDefault();
        x = jQuery('.stocks_entry_field').val();

        if(x < max_fields_stocks_entry){ //max input box allowed
            x++; //text box increment

            var request_url =  site_url+'/inventory/stocks/field/'+x;



            jQuery.ajax({
                  url: request_url,
                  type: "get",
                  success:function(data){
                    jQuery(wrapper_stocks_entry).append(data);
                    jQuery('.stocks_entry_field').val(x);
                  }
            });

             //add input box
        }
    });
    
    $(wrapper_stocks_entry).on("click",".stocks_entry_remove_btn", function(e){ //user click on remove text
        e.preventDefault(); 
        var row = $(this).data('rowid');

        $('.stocks_entry_group_'+row).remove();
         x--;
        jQuery('.stocks_entry_field').val(x);

    });
   
});


/*
##########################
# Stocks Amount Calculate
##########################
*/

jQuery(function(){

    jQuery('.stocks_entry_body').on("change",".transaction_stocks_quantity", function(){ 
        var row = jQuery(this).data('rowid');
        var stocks_quantity = jQuery('.transaction_stocks_quantity_row_'+row).val();
        var stocks_quantity_rate = jQuery('.stocks_quantity_rate_row_'+row).val();


        if(!isNaN(stocks_quantity) && !isNaN(stocks_quantity_rate)){
            var amount = parseFloat(stocks_quantity * stocks_quantity_rate).toFixed(2);
          
              jQuery('.stocks_quantity_cost_row_'+row).val(amount);

        }
    });
});



jQuery(function(){
    jQuery('.stocks_entry_body').on("change",".stocks_quantity_rate", function(){ 
        var row = jQuery(this).data('rowid');
        var stocks_quantity = jQuery('.transaction_stocks_quantity_row_'+row).val();
        var stocks_quantity_rate = jQuery('.stocks_quantity_rate_row_'+row).val();


        if(!isNaN(stocks_quantity) && !isNaN(stocks_quantity_rate)){
            var amount = parseFloat(stocks_quantity * stocks_quantity_rate).toFixed(2);
              jQuery('.stocks_quantity_cost_row_'+row).val(amount);

        }
    });
});

/*
##########################
# Stocks Field Clear
##########################
*/

jQuery(function(){

    jQuery('.stocks_entry_body').on("click",".stocks_clear", function(){ 
        var row = jQuery(this).data('rowid');
        jQuery('.transaction_stocks_quantity_row_'+row).val('');
        jQuery('.stocks_quantity_rate_row_'+row).val('');
        jQuery('.stocks_quantity_cost_row_'+row).val('');
        jQuery('.stocks_transaction_desc_row_'+row).val('');
        jQuery(".inventory_stocks_row_"+row+" option:selected").removeAttr("selected");
       
    });
});


/*
##########################
# Production Stocks entry Field Add
##########################
*/

jQuery(function(){

    var max_fields_production_stocks_entry = 10; //maximum input boxes allowed
   
    var wrapper_production_stocks_entry  = jQuery(".production_stocks_entry_body");
   
    var production_stocks_entry_add_btn  = jQuery(".production_add_line_stocks");
    
    var site_url = jQuery('.site_url').val();
    
    var y = 1; //initlal text box count

    jQuery(production_stocks_entry_add_btn).click(function(e){ //on add input button click
        e.preventDefault();
        y = jQuery('.production_stocks_entry_field').val();

        if(y < max_fields_production_stocks_entry){ //max input box allowed
            y++; //text box increment

            var request_url =  site_url+'/inventory/stocks-production/field/'+y;



            jQuery.ajax({
                  url: request_url,
                  type: "get",
                  success:function(data){
                    jQuery(wrapper_production_stocks_entry).append(data);
                    jQuery('.production_stocks_entry_field').val(y);
                  }
            });

             //add input box
        }
    });
   
});

/*
##########################
# Stock On Hand
##########################
*/

jQuery(function(){
  jQuery('.production_stocks_entry_body').on("change",".production_inventory_stocks", function(e){ //user click on remove text
        e.preventDefault(); 

        var row = $(this).data('rowid');

        var inventory_stocks = jQuery('.production_inventory_stocks_row_'+row).val();

        if(inventory_stocks.length !=0){

            var site_url = jQuery('.site_url').val();

            var request_url =  site_url+'/inventory/stocks-production/info/'+inventory_stocks

            jQuery.ajax({
                  url: request_url,
                  type: "get",
                  success:function(data){
                   if(data.stocks_onhand.length !=0)
                     jQuery('.production_stocks_onhand_row_'+row).val(data.stocks_onhand);
                  }
            });
        }


    });  
});

/*
##########################
# Supplier Account Depth
##########################
*/

jQuery(function(){

    jQuery('.supplier_modal').on("change",".supplier_account_group", function(e){ //user click on remove text
        e.preventDefault(); 

        var supplier_account_group_depth = jQuery('.supplier_account_group').find(':selected').data('depth');

        if(supplier_account_group_depth.length !=0)
        jQuery('.supplier_account_group_depth').val(supplier_account_group_depth);
    });
});


/*
##########################
# Stock New Account Depth
##########################
*/

jQuery(function(){

    jQuery('.stock_account_modal').on("change",".stock_in_hand_group", function(e){ //user click on remove text
        e.preventDefault(); 

        var account_stock_in_hand_group_depth = jQuery('.stock_in_hand_group').find(':selected').data('depth');

        if(account_stock_in_hand_group_depth.length !=0)
        jQuery('.account_stock_in_hand_group_depth').val(account_stock_in_hand_group_depth);
    });
});



/*
##########################
# Category entry Field Add
##########################
*/
jQuery(function(){

    var max_fields_category_entry = 1; //maximum input boxes allowed
   
    var wrapper_category_entry  = jQuery(".category_entry_body");
   
    var category_entry_add_btn  = jQuery(".add_line_category");
    
    var site_url = jQuery('.site_url').val();
    
    var x = 1; //initlal text box count



    jQuery(category_entry_add_btn).click(function(e){ //on add input button click
        e.preventDefault();
        x = jQuery('.category_entry_field').val();

        if(x <= max_fields_category_entry){ //max input box allowed
            x++; //text box increment

            var request_url =  site_url+'/ajax/category/settings';

            $(this).addClass('hidden');

            jQuery.ajax({
                  url: request_url,
                  type: "get",
                  success:function(data){
                    jQuery(wrapper_category_entry).append(data);
                    jQuery('.category_entry_field').val(x);
                  }
            });

             //add input box
        }
    });

    // $(wrapper_category_entry).on("click",".category_entry_remove_btn", function(e){ //user click on remove text
    //     e.preventDefault(); 

    //   var row = $(this).data('rowid');

    //     $('.category_entry_group').remove();
    //      x--;

    //     jQuery('.category_entry_field').val(x);

    // });
   
});





/********************************************
## CategoryEdit
*********************************************/

    jQuery(function(){
         $('.category_entry_body').on('click','.cat_edit',function(){
            var cat_id=$(this).attr('id').split("_")[2];
            $("#item_category_name_"+cat_id).prop('disabled',false);
            $("#item_quantity_unit_"+cat_id).prop('disabled',false);
            $(this).addClass('hidden');
            $("#cat_update_"+cat_id).removeClass('hidden');
        });

    });








/********************************************
## CategoryUpdate 
*********************************************/

    jQuery(function(){
        jQuery('.category_update').click(function(){

            var category_id = jQuery(this).data('id');
            var row_id = jQuery(this).data('rid');
            var site_url = jQuery('.site_url').val();
            var item_category_name = jQuery('#item_category_name_'+row_id).val();
            var item_quantity_unit = jQuery('#item_quantity_unit_'+row_id).val();

            if(item_category_name.length !=0 && item_quantity_unit.length !=0){

               var request_url=site_url+'/ajax/category/settings/update/'+category_id+'/'+item_category_name+'/'+item_quantity_unit;
                jQuery.ajax({
                    url: request_url,
                    type: 'get',
                    success:function(data){
                        window.location.href=site_url+'/inventory/category/settings';

                    }
                }); 

            }else alert("Category or Unit Type Not Empty.")
            

        });
    });

/********************************************
## CategoryDelete 
*********************************************/

    jQuery(function(){
         jQuery('.category_delete').click(function(){
            var r = confirm("Are you want to Delete Item Category??");

            var category_id = jQuery(this).data('id');
            var site_url = jQuery('.site_url').val();
            var request_url=site_url+'/ajax/category/settings/delete/'+category_id;
            jQuery.ajax({
                url: request_url,
                type: 'get',
                success:function(data){
                    if (r == true) {
                        window.location.href=site_url+'/inventory/category/settings';
                    } else {
                        return false;
                    } 

                }
            });

        });
    });



/********************************************
## ItemEdit
*********************************************/

    jQuery(function(){
        $('.item_entry_body').on('click','.item_edit',function(){
            var item_id=$(this).attr('id').split("_")[2];
            $("#item_name_"+item_id).prop('disabled',false);
            $("#item_category_id_"+item_id).prop('disabled',false);
            $("#item_quantity_unit_"+item_id).prop('disabled',false);
            $("#item_description_"+item_id).prop('disabled',false);
            $(this).addClass('hidden');
            $("#item_update_"+item_id).removeClass('hidden');
        });

    });

/*##########################
# Item entry Field Add
##########################*/

jQuery(function(){

    var max_fields_item_entry = 1; //maximum input boxes allowed
   
    var item_entry  = jQuery(".item_entry_body");
   
    var item_entry_add_btn  = jQuery(".add_line_item");
    
    var site_url = jQuery('.site_url').val();
    
    var x = 1; //initlal text box count

    jQuery(item_entry_add_btn).click(function(e){ //on add input button click
        e.preventDefault();
        x = jQuery('.item_entry_field').val();

        if(x <= max_fields_item_entry){ //max input box allowed
            x++; //text box increment

            var request_url =  site_url+'/ajax/item/settings';
            $(this).addClass('hidden');


            jQuery.ajax({
                  url: request_url,
                  type: "get",
                  success:function(data){
                    jQuery(item_entry).append(data);
                    jQuery('.item_entry_field').val(x);
                  }
            });
        }
    });
    
});



/********************************************
## ItemUpdate 
*********************************************/

    jQuery(function(){
        jQuery('.item_update').click(function(){

            var site_url = jQuery('.site_url').val();
            var item_id = jQuery(this).data('id');
            var row_id = jQuery(this).data('rid');
            var item_name = jQuery('#item_name_'+row_id).val();
            var item_category_id = jQuery('#item_category_id_'+row_id).val();
            var item_description = jQuery('#item_description_'+row_id).val();
            var item_quantity_unit = jQuery('#item_quantity_unit_'+row_id).val();

            if(item_name.length !=0 && item_category_id.length !=0 && item_description.length !=0){
                var request_url=site_url+'/ajax/item/settings/update/'+item_id+'/'+item_name+'/'+item_category_id+'/'+item_quantity_unit+'/'+item_description;
            
                jQuery.ajax({
                    url: request_url,
                    type: 'get',
                    success:function(data){
                        window.location.href=site_url+'/inventory/item/settings';

                    }
                });
            }else alert("Field not empty");

            

        });
    });


/********************************************
## ItemDelete 
*********************************************/

    jQuery(function(){
        jQuery('.item_delete').click(function(){

            var r = confirm("Are you want to Delete Item??");

            var item_id = jQuery(this).data('iid');
            var site_url = jQuery('.site_url').val();
            var request_url=site_url+'/ajax/item/settings/delete/'+item_id;
            jQuery.ajax({
                url: request_url,
                type: 'get',
                success:function(data){

                    if (r == true) {
                        window.location.href=site_url+'/inventory/item/settings';

                    } else {
                        return false;
                    } 


                }
            });


        });
    });
/*##########################################
# Item List By ajax
############################################
*/

jQuery(function(){

    jQuery('.stock_entry_body').on('change','.category_list', function(){

        var id = jQuery(this).data('id');

        var item_category_id = jQuery(this).val();
        var site_url = jQuery('.site_url').val();
        var request_url = site_url+'/ajax/category/list/'+item_category_id+'/'+id;


            jQuery.ajax({
                url: request_url,
                type: 'get',
                success:function(data){
                    jQuery('#item_details_id_'+id).html(data);
                }
            });

    });
});



/*
##########################
# Inventory stock entry Field Add
##########################
*/

jQuery(function(){

    var max_fields_stock_entry = 1; //maximum input boxes allowed
   
    var stock_entry  = jQuery(".stock_entry_body");
   
    var stock_entry_add_btn  = jQuery(".add_line_stock");
    
    var site_url = jQuery('.site_url').val();
    
    var x = 1;
     //initlal text box count
    jQuery(stock_entry_add_btn).click(function(e){ //on add input button click
        e.preventDefault();
        x = jQuery('.stock_entry_field').val();

        if(x <= max_fields_stock_entry){ //max input box allowed
            x++; //text box increment

            var request_url =  site_url+'/ajax/stock/settings';
            $(this).addClass('hidden');



            jQuery.ajax({
                  url: request_url,
                  type: "get",
                  success:function(data){
                    jQuery(stock_entry).append(data);
                    jQuery('.stock_entry_field').val(x);
                  }
            });

             //add input box
        }
    });

    
});

/*##########################################
# Stock edit
############################################
*/

jQuery(function(){
    $('.stock_entry_body').on('click','.edit',function(){
        var row_id=$(this).attr('id').split("_")[1];
        $("#item_category_id_"+row_id).prop('disabled',false);
        $("#item_name_"+row_id).prop('disabled',false);
        $("#item_description_"+row_id).prop('disabled',false);
        $(this).addClass('hidden');
        $("#update_"+row_id).removeClass('hidden');


    });
});


/********************************************
## InventoryStockUpdate 
*********************************************/

    jQuery(function(){
        jQuery('.stock_update').click(function(){

            var inventory_stock_id = jQuery(this).data('id');
            var site_url = jQuery('.site_url').val();
            var row_id = jQuery(this).data('rid');
            var item_category_id = jQuery('#item_category_id_'+row_id).val();
            var item_name = jQuery('#item_name_'+row_id).val();
            var item_description = jQuery('#item_description_'+row_id).val();
            if(item_name.length !=0 && item_category_id.length !=0 && item_description.length !=0){

                var request_url=site_url+'/ajax/inventory/settings/update/stock-'+inventory_stock_id+'/cat-'+item_category_id+'/item-'+item_name+'/desc-'+item_description;
                jQuery.ajax({
                    url: request_url,
                    type: 'get',
                    success:function(data){
                        window.location.href=site_url+'/inventory/item/settings';

                    }
                });
            }else alert("Please Fill all the field");
            

        });
    });



/********************************************
## StockDelete 
*********************************************/

    jQuery(function(){
        jQuery('.stock_delete').click(function(){

            var r = confirm("Are you want to Delete Stock??");

              if (r == true) {
                  var inventory_stock_id = jQuery(this).data('id');
                  var site_url = jQuery('.site_url').val();
                  var request_url=site_url+'/ajax/inventory/settings/delete/'+inventory_stock_id;
                  jQuery.ajax({
                      url: request_url,
                      type: 'get',
                      success:function(data){
                        alert(data.message);
                        window.location.href=site_url+'/inventory/item/settings';

                      }
                  });

                        

              } else {
                  return false;
              } 

        });
    });



/********************************************
## Supplier Payment Selectbox 
*********************************************/
jQuery(function() {
    // Ajax for our form
    jQuery('.supplier_account_id').on('change', function(event) {
        event.preventDefault();

        var site_url = jQuery('.site_url').val();
        var supplier_ref = jQuery('.supplier_account_id').val();
        var supplier = jQuery('.supplier_account_id').find(':selected').data('companynameslug');
        var supplier_id = jQuery('.supplier_account_id').find(':selected').data('supplierid');
        if(supplier_ref.length !=0 && supplier.length !=0 && supplier_id.length !=0)
            window.location.href=site_url+'/supplier/payment?supplier_ref='+supplier_ref+'&supplier_id='+supplier_id+'&supplier='+supplier;
    });
});


/********************************************
## SupplierPaymentAccountSelect
*********************************************/
jQuery(function() {
    jQuery('.supplier_payment_method').on('change', function(event) {
         event.preventDefault();
         
         var site_url = jQuery('.site_url').val();
         var method_type = jQuery('.supplier_payment_method').val();

         if(method_type.length !=0){
             var request_url=site_url+'/supplier/payment-method/'+method_type;
            jQuery.ajax({
                url: request_url,
                type: 'get',
                success:function(data){

                    jQuery('.supplier_paid_account').html(data);
                }
                    
            });
        }
         

    });
});


/*
##########################
# Suuplier Payment entry Field Add
##########################
*/

jQuery(function(){

    var max_fields_supplier_payment_entry = 10; //maximum input boxes allowed
   
    var supplier_payment_entry  = jQuery(".supplier_payemnt_entry_body");
   
    var supplier_payment_entry_add_btn  = jQuery(".suplier_add_payment");
    
    var site_url = jQuery('.site_url').val();
    
    var x = 0;
     //initlal text box count
    jQuery(supplier_payment_entry_add_btn).click(function(e){ //on add input button click

        
        e.preventDefault();
        x = jQuery('.supplier_payment_entry_field').val();
       // (this).prop('disabled', true);


        var stocks_tran_id = jQuery(this).data('id');
        jQuery('.suplier_add_payment_row_'+stocks_tran_id).prop("disabled", true);


        if(x <= max_fields_supplier_payment_entry){ //max input box allowed
            x++; //text box increment

            var request_url =  site_url+'/supplier/payment/field/'+x+'/stocks-'+stocks_tran_id;
            
            jQuery.ajax({
                  url: request_url,
                  type: "get",
                  success:function(data){
                    if(x==1)
                      jQuery(supplier_payment_entry).html(data);
                    else
                     jQuery(supplier_payment_entry).append(data);

                    jQuery('.supplier_payment_entry_field').val(x);


                    /*total field*/
                    var total_pay=0;
                    for(i=1;i<= x;i++){
                        var current = jQuery('.stocks_payment_amount_'+i).val();
                      

                         if(!isNaN(current))
                            total_pay =  parseInt(total_pay, 10) + parseInt(current,10);
                     }
                     jQuery('.supplier_total_payment_amount').val(total_pay);


                  }
            });

             //add input box
        }
    });

    
});

/*
###############################
# Suuplier Total Payment Field
##############################
*/

jQuery(function(){

    jQuery('.supplier_payemnt_entry_body').on('change','.stocks_payment_amount_row', function(e){

         e.preventDefault();

         
         var field_count =  jQuery('.supplier_payment_entry_field').val();

         var total_pay = 0;
         var i;
         for(i=1;i<= field_count;i++){
            var current = jQuery('.stocks_payment_amount_'+i).val();
    
             if(!isNaN(current))
                total_pay =  parseInt(total_pay, 10) + parseInt(current,10);
         }

         jQuery('.supplier_total_payment_amount').val(total_pay);
        
         
    });


});


/********************************************
## Supplier Purchase Return Selectbox 
*********************************************/
jQuery(function() {
    // Ajax for our form
    jQuery('.return_supplier_account_id').on('change', function(event) {
        event.preventDefault();

        var site_url = jQuery('.site_url').val();
        var supplier_ref = jQuery('.return_supplier_account_id').val();
        var supplier = jQuery('.return_supplier_account_id').find(':selected').data('companynameslug');
        var supplier_id = jQuery('.return_supplier_account_id').find(':selected').data('supplierid');
        if(supplier_ref.length !=0 && supplier.length !=0 && supplier_id.length !=0)
            window.location.href=site_url+'/supplier/purchase/return?supplier_ref='+supplier_ref+'&supplier_id='+supplier_id+'&supplier='+supplier;
    });
});


/*
##########################
# Customer Order entry Field Add
##########################
*/

    jQuery(function(){

        var max_fields_sales_order_entry = 10; //maximum input boxes allowed
       
        var wrapper_sales_order_entry  = jQuery(".sales_order_entry_body");
       
        var sales_order_entry_add_btn  = jQuery(".sales_add_line_orders");
        
        var site_url = jQuery('.site_url').val();
        
        var y = 1; //initlal text box count

        jQuery(sales_order_entry_add_btn).click(function(e){ //on add input button click
            e.preventDefault();
            y = jQuery('.sales_order_entry_field').val();

            if(y < max_fields_sales_order_entry){ //max input box allowed
                y++; //text box increment

                var request_url =  site_url+'/sales/order/field/'+y;

                jQuery.ajax({
                      url: request_url,
                      type: "get",
                      success:function(data){
                        jQuery(wrapper_sales_order_entry).append(data);
                        jQuery('.sales_order_entry_field').val(y);
                      }
                });

                 //add input box
            }
        });

        $(wrapper_sales_order_entry).on("click",".sales_order_entry_remove_btn", function(e){ //user click on remove text
          e.preventDefault(); 
          var row = $(this).data('rowid');

          $('.sales_order_entry_group_'+row).remove();
           y--;
          jQuery('.sales_order_entry_field').val(y);

        });

    });

    /*
    ##########################
    # Order amount count by qty and rate
    ##########################
    */

        jQuery(function(){

            jQuery('.sales_order_entry_body').on("change",".sales_order_quantity", function(){ 
                var row = jQuery(this).data('rowid');
                var order_quantity = jQuery('.sales_order_quantity_row_'+row).val();
                var order_quantity_rate = jQuery('.sales_order_rate_row_'+row).val();
                if(!isNaN(order_quantity) && !isNaN(order_quantity_rate)){
                    var amount = parseFloat(order_quantity * order_quantity_rate).toFixed(2);
                  
                      jQuery('.order_quantity_cost_row_'+row).val(amount);

                }
            });
        });


    
/*
##########################
# Customer Account Depth
##########################
*/

jQuery(function(){

    jQuery('.customer_modal').on("change",".customer_account_group", function(e){ //user click on remove text
        e.preventDefault(); 

        var customer_account_group_depth = jQuery('.customer_account_group').find(':selected').data('depth');
        
        if(customer_account_group_depth.length !=0)
        jQuery('.customer_account_group_depth').val(customer_account_group_depth);
    });
});


/********************************************
## Customer Order Delivery Selectbox 
*********************************************/
jQuery(function() {
    jQuery('.customer_order_delivery_account_id').on('change', function(event) {
        event.preventDefault();

        var site_url = jQuery('.site_url').val();
        var customer_ref = jQuery('.customer_order_delivery_account_id').val();
        var customer = jQuery('.customer_order_delivery_account_id').find(':selected').data('companynameslug');
        var customer_id = jQuery('.customer_order_delivery_account_id').find(':selected').data('customerid');
        
        if(customer_ref.length !=0 && customer.length !=0 && customer_id.length !=0)
            window.location.href=site_url+'/customer/order/delivery?customer_ref='+customer_ref+'&customer_id='+customer_id+'&customer='+customer;
    });
});


/********************************************
## Customer Sales Return
*********************************************/
jQuery(function() {
    // Ajax for our form
    jQuery('.customer_sales_retunr_account_id').on('change', function(event) {
        event.preventDefault();

        var site_url = jQuery('.site_url').val();
        var customer_ref = jQuery('.customer_sales_retunr_account_id').val();
        var customer = jQuery('.customer_sales_retunr_account_id').find(':selected').data('companynameslug');
        var customer_id = jQuery('.customer_sales_retunr_account_id').find(':selected').data('customerid');
        
        if(customer_ref.length !=0 && customer.length !=0 && customer_id.length !=0)
            window.location.href=site_url+'/customer/sales/return?customer_ref='+customer_ref+'&customer_id='+customer_id+'&customer='+customer;
    });
});

/********************************************
## Customer Payment Selectbox 
*********************************************/
jQuery(function() {
    // Ajax for our form
    jQuery('.customer_account_id').on('change', function(event) {
        event.preventDefault();

        var site_url = jQuery('.site_url').val();
        var customer_ref = jQuery('.customer_account_id').val();
        var customer = jQuery('.customer_account_id').find(':selected').data('companynameslug');
        var customer_id = jQuery('.customer_account_id').find(':selected').data('customerid');
        
        if(customer_ref.length !=0 && customer.length !=0 && customer_id.length !=0)
            window.location.href=site_url+'/customer/payment?customer_ref='+customer_ref+'&customer_id='+customer_id+'&customer='+customer;
    });
});


/********************************************
## CustomerPaymentAccountSelect
*********************************************/
jQuery(function() {
    jQuery('.customer_payment_method').on('change', function(event) {
         event.preventDefault();
         
         var site_url = jQuery('.site_url').val();
         var method_type = jQuery('.customer_payment_method').val();

         if(method_type.length !=0){
             var request_url=site_url+'/customer/payment-method/'+method_type;
            jQuery.ajax({
                url: request_url,
                type: 'get',
                success:function(data){

                    jQuery('.customer_paid_account').html(data);
                }
                    
            });
        }
         

    });
});


/********************************************
## CustomerOrderSelect
*********************************************/
jQuery(function() {
    jQuery('.customer_order_select').on('change', function(event) {
         event.preventDefault();
         
         var site_url = jQuery('.site_url').val();
         var customer_order_id = jQuery('.customer_order_select').val();
        if(customer_order_id.length !=0){
             var request_url=site_url+'/customer/order-balance/'+customer_order_id;

            jQuery.ajax({
                url: request_url,
                type: 'get',
                success:function(data){

                    jQuery('.customer_balance_amount').html(data);
                }
                    
            });
        }
         

    });
});




/*
##########################
# Customer Payment Add Lines
##########################
*/
jQuery(function(){

        var max_fields_payment_entry = 10; //maximum input boxes allowed
       
        var wrapper_payment_entry  = jQuery(".payment_entry_body");
       
        var payment_entry_add_btn  = jQuery(".customer_payment");
        
        var site_url = jQuery('.site_url').val();
        
        var y = 0; //initlal text box count

        jQuery(payment_entry_add_btn).click(function(events){ //on add input button click
            events.preventDefault();
            y = jQuery('.payment_entry_field').val();

            if(y < max_fields_payment_entry){ //max input box allowed
                y++; //text box increment

                var customer_order_id = jQuery(this).data('id');
                $(this).prop('disabled', true);

                var request_url =  site_url+'/ajax/payment/entry/'+customer_order_id+'/'+y;

                jQuery.ajax({
                      url: request_url,
                      type: "get",
                      success:function(data){
                        if(y==1)
                            jQuery(wrapper_payment_entry).html(data);
                        else
                            jQuery(wrapper_payment_entry).append(data);

                        jQuery('.payment_entry_field').val(y);

                        GrandTotalByRow('.customer_paid_amount_row_','.total_oreder_paid_amount',y);

                      }
                });

                 //add input box
            }
        });
        
        $(wrapper_payment_entry).on("click",".customert_payment_entry_remove_btn", function(e){ //user click on remove text
            e.preventDefault(); 
            var row = $(this).data('rowid');

            $('.customert_payment_entry_row_'+row).remove();
             y--;
            jQuery('.payment_entry_field').val(y);

            GrandTotalByRow('.customer_paid_amount_row_','.total_oreder_paid_amount',y);

        });


    });



/*
###################################
# Customer Payment Order grand Toatal
###################################
*/
function GrandTotalByRow(row_name,total_row,row){

         var total_pay = 0;
         var i;
         for(i=1;i<= row;i++){
            var current = jQuery(row_name+i).val();
    
             if(!isNaN(current))
                total_pay =  parseInt(total_pay, 10) + parseInt(current,10);
         }

         jQuery(total_row).val(total_pay);
}

/*
##########################
# Stock On Hand for Finish goods
##########################
*/

jQuery(function(){
  jQuery('.payment_entry_body').on("change",".customer_paid", function(e){ //user click on remove text
        e.preventDefault();
        var y = jQuery('.payment_entry_field').val();
        GrandTotalByRow('.customer_paid_amount_row_','.total_oreder_paid_amount',y);
    });
});



/*
##########################
# Customer Payment Add Lines
##########################
*/

/*jQuery(function(){
    jQuery('.customer_payment').click(function(){
        var customer_order_id = jQuery(this).data('id');
        jQuery('.customer_payment'+customer_order_id).prop('disabled', true);

        
        var row =  jQuery('.payment_entry_field').val();

        var request_url =  site_url+'/ajax/payment/entry/'+customer_order_id+'/'+row;

        jQuery.ajax({
              url: request_url,
              type: "get",
              success:function(data){
                if(row==1)
                        jQuery('.payment_entry_body').html(data);
                else
                    jQuery('.payment_entry_body').append(data);

                    jQuery('.delivery_confirm_entry_field').val(jQuery('.delivery_confirm >tbody >tr').length);
                jQuery(wrapper_payment_entry).append(data);
                jQuery('.payment_entry_field').val(y);
              }
        });


    });
});*/

/********************************************
## Add OrderConfirm
*********************************************/
/*jQuery(function() {
    jQuery('.customer_delivery').on('click', function(event) {
         event.preventDefault();
         
         var site_url = jQuery('.site_url').val();
         var customer_order_id = jQuery(this).data('id');
         var field_count = jQuery('.delivery_confirm >tbody >tr').length;

        if(customer_order_id.length !=0){
            jQuery('.customer_delivery_row_'+customer_order_id).prop("disabled", true);
             var request_url=site_url+'/customer/order/delivery/ajax/order/'+customer_order_id+'/field/'+field_count;

            jQuery.ajax({
                url: request_url,
                type: 'get',
                success:function(data){

                    if(field_count==1)
                        jQuery('.order_delivery_entry_body').html(data);
                    else
                       jQuery('.order_delivery_entry_body').append(data);

                    jQuery('.delivery_confirm_entry_field').val(jQuery('.delivery_confirm >tbody >tr').length);

                    /*Grand Total*/
                     /*var row =  jQuery('.delivery_confirm >tbody >tr').length;
                     var total_pay = 0;
                     var i;
                     for(i=1;i<= row;i++){
                        var current = jQuery('.delivery_amount_row_'+i).val();
                
                         if(!isNaN(current))
                            total_pay =  parseInt(total_pay, 10) + parseInt(current,10);
                     }

                     jQuery('.order_delivery_total').val(total_pay);
                }
                    
            });
        }
         

    });
});*/
/*
##########################
# Oreder Delivery quntity and rate row change
##########################
*/
/*jQuery(function() {
   
        jQuery('.delivery_confirm').on("change",".delivery_quantity", function(){


         var row = jQuery('.delivery_quantity').data('id');
         var quantity =  jQuery('.delivery_quantity_row_'+row).val();
         var rate = jQuery('.delivery_quantity_rate_row_'+row).val();

         if(!isNaN(row) && !isNaN(quantity)){
            jQuery('.delivery_amount_row_'+row).val(rate*quantity);
            OrderDeliveryGrandTotal();
         }
          
    });
});


jQuery(function() {
   
        jQuery('.delivery_confirm').on("change",".delivery_quantity_rate", function(){



         var row = jQuery('.delivery_quantity_rate').data('id');
         var quantity =  jQuery('.delivery_quantity_row_'+row).val();
         var rate = jQuery('.delivery_quantity_rate_row_'+row).val();

         if(!isNaN(row) && !isNaN(quantity)){
            jQuery('.delivery_amount_row_'+row).val(rate*quantity);
            OrderDeliveryGrandTotal();
         }
          
    });
});

jQuery(function() {
   
        jQuery('.delivery_confirm').on("change",".delivery_amount", function(){
         OrderDeliveryGrandTotal();
         
    });
});

function OrderDeliveryGrandTotal(){

        var row =  jQuery('.delivery_confirm >tbody >tr').length;
         var total_pay = 0;
         var i;
         for(i=1;i<= row;i++){
            var current = jQuery('.delivery_amount_row_'+i).val();
    
             if(!isNaN(current))
                total_pay =  parseInt(total_pay, 10) + parseInt(current,10);
         }

         jQuery('.order_delivery_total').val(total_pay);
}
*/

/*
###################################
# Order Delivery Confirm Grandtotal
###################################
*/
function OrderDeliveryGrandTotal(){

        var row =  jQuery('.delivery_confirm >tbody >tr').length;
         var total_pay = 0;
         var i;
         for(i=1;i<= row;i++){
            var current = jQuery('.delivery_amount_row_'+i).val();
    
             if(!isNaN(current))
                total_pay =  parseInt(total_pay, 10) + parseInt(current,10);
         }

         jQuery('.order_delivery_total').val(total_pay);
}

/*
##########################
# Dashboard Donut Chart Respose
##########################
*/

jQuery(function(){
    
    jQuery('.graph_donut_cost_center_id').on('change',function(){
        
            var cost_center_id = jQuery('.graph_donut_cost_center_id').val();
            var site_url = jQuery('.site_url').val();
            var request_url = site_url+'/dashboard/admin/today/all-report/summary?cost_center_id='+cost_center_id;

            jQuery.ajax({
                url: request_url, 
                dataType: 'JSON',
                type: 'GET',
                success: function(response) {
                    Morris.Donut({
                        element: 'today-chart',
                        data: response,
                       
                    });
                }
            });
    });
    
});

/*
##########################
# Delivery Confirm entry Field Add
##########################
*/

jQuery(function(){

    var max_fields_finishgoods_entry = 10; //maximum input boxes allowed
   
    var wrapper_finishgoods_entry  = jQuery(".finishgoods_entry_body");
   
    var finishgoods_add_btn  = jQuery(".finishgoods_add_line_stocks");
    
    var site_url = jQuery('.site_url').val();
    
    var y = 1; //initlal text box count

    jQuery(finishgoods_add_btn).click(function(e){ //on add input button click
        e.preventDefault();
        y = jQuery('.finishgoods_stocks_entry_field').val();

        if(y < max_fields_finishgoods_entry){ //max input box allowed
            y++; //text box increment

            var request_url =  site_url+'/finish-goods/field/'+y;
            jQuery.ajax({
                  url: request_url,
                  type: "get",
                  success:function(data){
                    jQuery(wrapper_finishgoods_entry).append(data);
                    jQuery('.finishgoods_stocks_entry_field').val(y);
                  }
            });

             //add input box
        }
    });

    $(wrapper_finishgoods_entry).on("click",".finishgoods_entry_remove_btn", function(e){ //user click on remove text
          e.preventDefault(); 
          var row = $(this).data('rowid');

          $('.finishgoods_stocks_entry_group_'+row).remove();
           y--;
          jQuery('.finishgoods_stocks_entry_field').val(y);

        });
   
});

/*
##########################
# Stock On Hand for Finish goods
##########################
*/

jQuery(function(){
  jQuery('.finishgoods_entry_body').on("change",".finishgoods_inventory_stocks", function(e){ //user click on remove text
        e.preventDefault(); 
        var row = $(this).data('rowid');

        var inventory_stocks = jQuery('.finishgoods_inventory_stocks_row_'+row).val();

        if(inventory_stocks.length !=0){

            var site_url = jQuery('.site_url').val();

            var request_url =  site_url+'/finish-goods/stocks-info/'+inventory_stocks

            jQuery.ajax({
                  url: request_url,
                  type: "get",
                  success:function(data){
                   if(data.stocks_onhand.length !=0)
                     jQuery('.finishgoods_stocks_onhand_row_'+row).val(data.stocks_onhand);
                     jQuery('.finishgoods_stocks_onhand_quantity_row_'+row).val(data.stocks_onhand);
                  }
            });
        }
    });  
});


/*
##########################
#  Finish goods Cost
##########################
*/
jQuery(function(){
  jQuery('.finish_goods_change').on("change", function(e){ //user click on remove text
        e.preventDefault(); 

        var finish_goods_quantity = jQuery('.finish_goods_quantity').val();
        var finish_goods_rate = jQuery('.finish_goods_rate').val();
        if(!isNaN(finish_goods_quantity) && !isNaN(finish_goods_rate))
            jQuery('.finish_goods_cost').val(finish_goods_quantity*finish_goods_rate);
    });  
});

/*
##########################
#  Finish goods Inventoty Total Cost
##########################
*/
jQuery(function(){
  jQuery('.finishgoods_entry_body').on("change", ".finishgoods_stocks_transaction_amount", function(e){ //user click on remove text
        e.preventDefault(); 

        var row =  jQuery('.finishgoods_stocks_entry_field').val();
         var total_pay = 0;
         var i;
         for(i=1;i<= row;i++){
            var current = jQuery('.finishgoods_stocks_transaction_amount_row_'+i).val();
    
             if(!isNaN(current))
                total_pay =  parseInt(total_pay, 10) + parseInt(current,10);
         }

         jQuery('.finishgoods_stocks_transaction_total').val(total_pay);
    });  
});



/*spinner ='<div class="loading-spinner" style="width: 200px; margin-left: -100px;">' +
            '<div class="progress progress-striped active">' +
            '<div class="progress-bar" style="width: 100%;"></div>' +
            '</div>' +
            '</div>';*/


            /*jQuery('.loading').html(spinner);
            <div class="loading-spinner" style="width: 200px;">
            	<div class="progress progress-striped active">
            		<div class="progress-bar" style="width: 100%;">
            		</div>
            	</div>
            </div>*/




/*
##########################
# Stocks entry Field Add
##########################
*/

jQuery(function(){

    var max_fields_journal_entry = 10; //maximum input boxes allowed
   
    var wrapper_journal_entry  = jQuery(".journal_entry_body");
   
    var journal_entry_add_btn  = jQuery(".add_line_journal");
    
    var site_url = jQuery('.site_url').val();
    
    var x = 1; //initlal text box count

    jQuery(journal_entry_add_btn).click(function(e){ //on add input button click
        e.preventDefault();
        x = jQuery('.journal_entry_field').val();

        if(x < max_fields_journal_entry){ //max input box allowed
            x++; //text box increment

            var request_url =  site_url+'/general/transaction/field/'+x;



            jQuery.ajax({
                  url: request_url,
                  type: "get",
                  success:function(data){
                    jQuery(wrapper_journal_entry).append(data);
                    jQuery('.journal_entry_field').val(x);
                  }
            });

             //add input box
        }
    });
    
    $(wrapper_journal_entry).on("click",".journal_entry_remove_btn", function(e){ //user click on remove text
        e.preventDefault(); 
        var row = $(this).data('rowid');

        $('.journal_entry_group_'+row).remove();
         x--;
        jQuery('.journal_entry_field').val(x);

    });
   
});



/********************************************
## TransactionListDelete 
*********************************************/

    jQuery(function(){
         jQuery('.transactions_delete').click(function(){
            var r = confirm("Are you want to delete this transaction??");
            var delete_message = " You can not  delete this type transaction";

            var transactions_id = jQuery(this).data('id');
            var posting_type = jQuery(this).data('type');


            var site_url = jQuery('.site_url').val();
            var request_url=site_url+'/general/transaction-list/delete/id-'+transactions_id+'/type-'+posting_type;
            jQuery.ajax({
                url: request_url,
                type: 'get',
                success:function(data){

                    if (r == true) {
                        window.location.href=site_url+'/general/transaction-list';
                    } else {
                        return false;
                    } 

                }
            });

        });
    });




/*###########################
# Event Log Modal
############################
*/

jQuery(function(){
  jQuery('.event_log_show').click(function(){
    var event_id = jQuery(this).data('id');
    var site_url = jQuery('.site_url').val();
    var request_url  = site_url+'/event-logs/details/'+event_id;
    jQuery.ajax({
      url: request_url,
      type: 'get',
      success:function(data){

          jQuery('.event_log_details').html(data);

      }
    });

  });
});




/*###########################
# Ledger Opening Balance 
############################
*/

jQuery(function(){
  jQuery('.ledger_data_show').click(function(){
    var ledger_id = jQuery(this).data('id');
    var depth = jQuery(this).data('depth');
    var site_url = jQuery('.site_url').val();
    var request_url  = site_url+'/ajax/opening/balance/id-'+ledger_id+'/depth-'+depth;
    jQuery.ajax({
      url: request_url,
      type: 'get',
      success:function(data){

          jQuery('.ledger_data_details').html(data);

      }
    });

  });
});



    /*
    ##########################
    # Purchase Return count by qty and rate
    ##########################
    */

        jQuery(function(){

            jQuery('.purchase_return_entry_body').on("change",".purchase_return_quantity", function(){ 
                var row = jQuery(this).data('rowid');
                var purchase_return_quantity = jQuery('.purchase_return_quantity').val();
                var purchase_return_quantity_rate = jQuery('.purchase_return_quantity_rate').val();
                if(!isNaN(purchase_return_quantity) && !isNaN(purchase_return_quantity_rate)){
                    var amount = parseFloat(purchase_return_quantity * purchase_return_quantity_rate).toFixed(2);
                  
                      jQuery('.purchase_return_quantity_cost').val(amount);

                }
            });
        });


    /*
    ##########################
    # Sales Return count by qty and rate
    ##########################
    */

        jQuery(function(){

            jQuery('.sales_return_entry_body').on("change",".sales_return_quantity", function(){ 
                var all_amount = 0;
                var row = jQuery(this).data('id');
                var sales_return_quantity = jQuery('.sales_return_quantity_row_'+row).val();
                var sales_return_quantity_rate = jQuery('.sales_return_quantity_rate_row_'+row).val();
                if(!isNaN(sales_return_quantity) && !isNaN(sales_return_quantity_rate)){
                    var amount = parseFloat(sales_return_quantity * sales_return_quantity_rate).toFixed(2);
                    var all_amount=parseFloat(all_amount+amount).toFixed(2);
                      jQuery('.sales_return_quantity_cost_row_'+row).val(amount);
                      jQuery('.sales_return_all_quantity_cost').val(all_amount);

                }
            });
        });








