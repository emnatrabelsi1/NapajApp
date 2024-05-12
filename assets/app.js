/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import './styles/sb-admin-2.css';
import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';

const $ = require('jquery');
global.$ = global.jQuery = $;

global.ajaxCallFct= function ajaxCallFct(url, done){
  $.ajax({
      type: 'POST',
      url : url,
      data: {
          done: function() { done; }
      },
      success: function (response) {
      },
  });
}

import DataTable from 'datatables.net-dt';
import datepicker from 'js-datepicker'

require('select2')

const addFormToCollection = (e) => {  
  const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);
  const item = document.createElement('li');

  item.innerHTML = collectionHolder
    .dataset
    .prototype
    .replace(
      /__name__/g,
      collectionHolder.dataset.index
    );

  collectionHolder.appendChild(item);

  collectionHolder.dataset.index++;
  
  initialize(item);
};

function ProductTemplateResult(data) {
  let html_content = "";
  if (data.element && $(data.element).val()) {
    let piece_quantity =  Number($(data.element).attr('data-piece_quantity')).toFixed();
    let piece_price =  Number($(data.element).attr('data-piece_price')).toFixed(2);

    html_content = 
      '<div class="productInSelect">'+
        '<div class="productImgInSelect">'+
            ($(data.element).attr('data-img') ? '<img src="/upload/img/'+$(data.element).attr('data-img')+'"/>' : '' ) + 
        '</div>'+
        '<div class="productDetailInSelect">'+
            '<span class="productNameInSelect">'+$(data.element).attr('data-name')+'</span>'+(piece_quantity > 0 ? ' x'+piece_quantity:'')+'</span><br/>'+
            '<span class="productDescriptionInSelect">'+
                $(data.element).attr('data-description')+'<br/>'+
            '</span>'+
            '<span class="productPriceInSelect">'+
                '<b>Prix unitaire : ' + (piece_price > 0 ? piece_price + ' € HT' : 'NC') +'</b>'+
            '</span>'+
        '</div>'+
      '</div>';
      return $(html_content);
  }else{
    return data.text;
  }
  return;
}


function initialize(domElem){
  domElem.querySelectorAll('.productOrderLine').forEach(newSelect => {
    $(newSelect).select2({
      templateResult: function(data, container){
        return ProductTemplateResult(data);
      },
      dropdownCssClass: "large_select2Dropdown"
    });
  });

  domElem.querySelectorAll('.select2_autoload').forEach(newSelect => {
    $(newSelect).select2();
  });

  domElem.querySelectorAll('.add_item_link').forEach(btn => {
    btn.addEventListener("click", addFormToCollection)
  });
  
  domElem.querySelectorAll('.delete_collection_item').forEach(btn => {
    $(btn).on('click',function(){
      $(btn).parents('.collection_item').parents('li').remove();
    });
  });

  domElem.querySelectorAll('.flashMsg > .alert').forEach(flashMsg => {
    $(flashMsg).on('click',function(){
      $(flashMsg).remove();
    });
  });

  domElem.querySelectorAll('.datepicker').forEach(datepickerInput => {
    const picker = datepicker(datepickerInput, {
      formatter: (input, date, instance) => {
        const value = date.toLocaleDateString()
        input.value = value
      }
    })
  });
}

Object.assign(DataTable.defaults, {
  "searching": true,
  "ordering": true,
  "order": [],
  "pagingType": 'simple',
  "pageLength": 50,
  language: {
    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json',
  },
});
$('.datatable').dataTable( {} );
initialize(document);