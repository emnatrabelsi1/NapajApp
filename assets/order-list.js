
archiveOrder = function archiveOrder(url, orderId){
    $.ajax({
        type: 'POST',
        url : url,
        success: function (response) {
            if(response == "OK"){
                $('#orderElement'+orderId).remove();
            }
        },
    });
}

doOrderLine = function doOrderLine(url, orderId, orderLineId){
    $.ajax({
        type: 'POST',
        url : url,
        data: {
            done: $('#orderLineCheckboxDone'+orderId+'_'+orderLineId).is(':checked')
        },
        success: function (response) {
            
        },
    });
}
  
$('#order_delivery_customer_filter').select2({
    ajax: {
      url: '/customer/data/select',
      method: 'POST',
      dataType: 'json'
    }
  }).on('change', function(){
    if($(this).val()){
      $("tr[data-customer-id]").hide();
      $("tr[data-customer-id='"+$(this).val()+"']").show();
    }else{
      $("tr[data-customer-id]").show();
    }
});

$('#order_delivery_customerType_filter').on('change', function(){
  if(!$(this).val() || "all" == $(this).val()){
    $("tr[data-customerType]").show();
  }else{
    $("tr[data-customerType]").hide();
    $("tr[data-customerType='"+$(this).val()+"']").show();
  }
});

