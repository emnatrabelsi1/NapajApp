function switchMeasureUnitField(compoSelect){
  let option = compoSelect.children('option[value='+compoSelect.val()+']');
  if(option.attr('data-measure_unit') == 'Unité'){
    compoSelect.parents('.collection_item').children('div').children('[data-input-type=weight]').val('').attr('disabled','disabled');
    compoSelect.parents('.collection_item').children('div').children('[data-input-type=volume]').val('').attr('disabled','disabled');
    compoSelect.parents('.collection_item').children('div').children('[data-input-type=quantity]').removeAttr('disabled');
  }else if(option.attr('data-measure_unit') == 'L'){
    compoSelect.parents('.collection_item').children('div').children('[data-input-type=quantity]').val('').attr('disabled','disabled');
    compoSelect.parents('.collection_item').children('div').children('[data-input-type=weight]').val('').attr('disabled','disabled');
    compoSelect.parents('.collection_item').children('div').children('[data-input-type=volume]').removeAttr('disabled');
  }else{
    compoSelect.parents('.collection_item').children('div').children('[data-input-type=weight]').removeAttr('disabled');
    compoSelect.parents('.collection_item').children('div').children('[data-input-type=quantity]').val('').attr('disabled','disabled');
    compoSelect.parents('.collection_item').children('div').children('[data-input-type=volume]').val('').attr('disabled','disabled');
  }
}
function manageChange(ingredientSelect, preparationSelect, type){
  if(type == 'ingredient'){
    if(ingredientSelect.val()){
      ingredientSelect.next('.select2').children('.selection').children('.select2-selection').children('.select2-selection__rendered').removeClass('deactivated');

      preparationSelect.val(null).trigger('change');
      preparationSelect.next('.select2').children('.selection').children('.select2-selection').children('.select2-selection__rendered').addClass('deactivated');

      switchMeasureUnitField(ingredientSelect);
    }
  }

  if(type == 'preparation'){
    if(preparationSelect.val()){
      preparationSelect.next('.select2').children('.selection').children('.select2-selection').children('.select2-selection__rendered').removeClass('deactivated');

      ingredientSelect.val(null).trigger('change');
      ingredientSelect.next('.select2').children('.selection').children('.select2-selection').children('.select2-selection__rendered').addClass('deactivated');

      switchMeasureUnitField(preparationSelect);
    }
  }
}
$('#addRecipeCompositionBtn').on("click", function(){
  ingredientSelect = $( "select[name*='recipe[recipeCompositions]'][name$='[ingredient]']").last();
  preparationSelect = $( "select[name*='recipe[recipeCompositions]'][name$='[preparation]']").last();

  ingredientSelect.on("change", function(){
    manageChange(ingredientSelect, preparationSelect, 'ingredient');
  });
  preparationSelect.on("change", function(){
    manageChange(ingredientSelect, preparationSelect, 'preparation');
  });
});


$("select[name*='recipe[recipeCompositions]'][name$='[ingredient]']").each(function(){
  let preparationSelect = $(this).parents('.collection_item').find("select[name*='recipe[recipeCompositions]'][name$='[preparation]']");
  if($(this).val()){
    switchMeasureUnitField($(this));
  }else{
    $(this).next('.select2').children('.selection').children('.select2-selection').children('.select2-selection__rendered').addClass('deactivated');
  }
  $(this).on("change", function(){
    manageChange($(this), preparationSelect, 'ingredient');
  });
});

$("select[name*='recipe[recipeCompositions]'][name$='[preparation]']").each(function(){
  let ingredientSelect = $(this).parents('.collection_item').find("select[name*='recipe[recipeCompositions]'][name$='[ingredient]']");
  if($(this).val()){
    switchMeasureUnitField($(this));
  }else{
    $(this).next('.select2').children('.selection').children('.select2-selection').children('.select2-selection__rendered').addClass('deactivated');
  }  
  $(this).on("change", function(){
    manageChange(ingredientSelect, $(this), 'preparation');
  });
});
