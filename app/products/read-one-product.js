$(document).ready(function(){
 
    // handle 'read one' button click
    $(document).on('click', '.read-one-product-button', function(){
        // get product id
        var id = $(this).attr('data-id');
        // read product record based on given ID
        $.getJSON("http://localhost/php_rest_api/api/product/read_one.php?id=" + id, function(data){
            // start html
            var read_one_product_html="";
            
            // when clicked, it will show the product's list
            read_one_product_html+="<div id='read-products' class='btn btn-primary pull-right m-b-15px read-products-button'>";
                read_one_product_html+="<span class='glyphicon glyphicon-list'></span> Read Products";
            read_one_product_html+="</div>";
            // product data will be shown in this table
            read_one_product_html+="<table class='table table-bordered table-hover'>";
            
            // product name
            read_one_product_html+="<tr>";
                read_one_product_html+="<td class='w-30-pct'>Name</td>";
                read_one_product_html+="<td class='w-70-pct'>" + data.name + "</td>";
            read_one_product_html+="</tr>";

            // product price
            read_one_product_html+="<tr>";
                read_one_product_html+="<td>Price</td>";
                read_one_product_html+="<td>" + data.price + "</td>";
            read_one_product_html+="</tr>";

            // product description
            read_one_product_html+="<tr>";
                read_one_product_html+="<td>Description</td>";
                read_one_product_html+="<td>" + data.description + "</td>";
            read_one_product_html+="</tr>";

            // product category name
            read_one_product_html+="<tr>";
                read_one_product_html+="<td>Category</td>";
                read_one_product_html+="<td>" + data.category_name + "</td>";
            read_one_product_html+="</tr>";

            read_one_product_html+="</table>";
            // inject html to 'page-content' of our app
            $("#page-content").html(read_one_product_html);
            
            // chage page title
            changePageTitle("Create Product");
        });
    }); 
});