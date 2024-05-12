function switchMeasureUnitField(ingredientSelect){
  let option = ingredientSelect.children('option[value='+ingredientSelect.val()+']');
  if(option.attr('data-measure_unit') == 'Unité'){
    ingredientSelect.parents('.collection_item').children('div').children('[data-input-type=weight]').val('').attr('disabled','disabled');
    ingredientSelect.parents('.collection_item').children('div').children('[data-input-type=volume]').val('').attr('disabled','disabled');
    ingredientSelect.parents('.collection_item').children('div').children('[data-input-type=quantity]').removeAttr('disabled');
  }else if(option.attr('data-measure_unit') == 'L'){
    ingredientSelect.parents('.collection_item').children('div').children('[data-input-type=quantity]').val('').attr('disabled','disabled');
    ingredientSelect.parents('.collection_item').children('div').children('[data-input-type=weight]').val('').attr('disabled','disabled');
    ingredientSelect.parents('.collection_item').children('div').children('[data-input-type=volume]').removeAttr('disabled');
  }else{
    ingredientSelect.parents('.collection_item').children('div').children('[data-input-type=weight]').removeAttr('disabled');
    ingredientSelect.parents('.collection_item').children('div').children('[data-input-type=quantity]').val('').attr('disabled','disabled');
    ingredientSelect.parents('.collection_item').children('div').children('[data-input-type=volume]').val('').attr('disabled','disabled');
  }
}

$('#addIngredientBtn').on("click", function(){
  ingredientSelect = $( "select[name*='preparation[preparationIngredients]']").last();
  ingredientSelect.on("change", function(){
    switchMeasureUnitField(ingredientSelect)
  });
});

$('select[name*=preparation\\[preparationIngredients]').each(function(){
  switchMeasureUnitField($(this));
  $(this).on("change", function(){
    switchMeasureUnitField($(this))
  });
});
