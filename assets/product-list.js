$('.tag_filter').on('click', function(e) {
  if($(this).hasClass('tag_filter_selected')){
    $(this).removeClass('tag_filter_selected')    
    const index = tags.indexOf($(this).data('tag-id'));
    tags.splice(index, 1);
  }else{
    tags.push($(this).data('tag-id'));
    $(this).addClass('tag_filter_selected')
  }
  productTable.ajax.reload();
});

$('.out_of_stock_filter').on('click', function(e) {
  if($(this).hasClass('out_of_stock_filter_selected')){
    out_of_stock = false;
    $(this).removeClass('out_of_stock_filter_selected')    
  }else{
    out_of_stock = true;
    $(this).addClass('out_of_stock_filter_selected')
  }
  productTable.ajax.reload();
});

let tags = [];
let out_of_stock = false;
var productTable = $('#product-datatable').DataTable( {
  columns: [
    {
      data: 'id'
    },
    {
      data: 'img',
      render: function (data) {
        if (data){
          return '<img style="max-width:100px;max-height:100px;" src="/upload/img/' + data + '">';
        }
        return '';
    }
    },
    {
      data: 'name',
      render: function (data) {
          let name = data.name;

          if (data.quantity !== null) {
            name = name + ' (x'+Number(data.quantity).toFixed()+')';
          }

          return '<a href="' + data.url + '">' + name + '</a>';c
      }
    },
    {
      data: 'tag',
      render: function (data) {
        let display = '';
        if (data){
          data.forEach((tag) => display = display + tag.name + ' / ');
        }
        return display;
      }
    },
    {
      data: 'recipe',
      render: function (data) {
        let display = '';
        if (data.url){
          display = "<a target=\"_blank\" href=\"" + data.url +"\">Modifier la recette</i></a>";
        }
        return display;
      }
    },
    {
      data: 'price',
      render: function (data) {
        
        return Number(data).toFixed(2) + '€';
      }
    },
    {
      data: 'stock',
      render: function (data) {
        let color = '';
        if (data.value < data.minimum){
          color = 'alert-danger';
        }else{
          color = 'alert-success';
        }
       return "<span class=\"" + color + "\">" + Number(data.value).toFixed() + "/" + Number(data.minimum).toFixed() + "</span>";
                
      }
    },
    {
      data: 'etiquette',
      render: function(data){
        return "<a target=\"_blank\" href=\"" + data.url +"\"><i class=\"fa-solid fa-tags\"></i></a>";
      }
    },
  ],
  dom: 'rftp  ',
  serverSide: true,
    ajax:  {
      url: "/product/data/table",
      data: function(data) {
        data.tags = tags,
        data.out_of_stock = out_of_stock
     }
   }
} );

