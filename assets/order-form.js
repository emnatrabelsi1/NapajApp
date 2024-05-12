$('form').on('submit', function(e) {
  $('#missing_order_line').addClass('display-none');
  $('#missing_details').addClass('display-none');

  if($('ul.orderLines').children('li').length <= 0){
      e.preventDefault();
      $('#missing_order_line').removeClass('display-none');
  }
  $('ul.orderLines').children('li').each(function(elem){
    if($(this).find('.productOrderLine').val() == "" && $(this).find('.precisionOrderLine').val() == ""){
      $(this).find('.productOrderLine').parent().find('.select2-selection').css('border', 'solid 1px red');
      $(this).find('.precisionOrderLine').css('border', 'solid 1px red');
      e.preventDefault();
      $('#missing_details').removeClass('display-none');
    }
  });
});

if($('ul.orderLines').children('li').length == 0){
  for (var i = 0; i<4; i++) {
  $('button.add_item_link[data-collection-holder-class="orderLines"]').trigger( "click" );
  }
}
