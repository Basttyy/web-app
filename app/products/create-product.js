$(document).ready(function(){
 
    // show html form when 'create product' button was clicked
    $(document).on('click', '.create-product-button', function(){
        // load list of categories
        $.getJSON("http://localhost/php_rest_api/api/category/read.php", function(data){
            // build categories option html
            // loop through returned list of data
            var categories_options_html="";
            categories_options_html+="<select name='category_id' class='form-control'>";
            $.each(data.records, function(key, val){
                categories_options_html+="<option value='" + val.id + "'>" + val.name + "</option>";
            });
            categories_options_html+="</select>";
            // we have our html form here where product information will be entered
            // we used the 'required' html5 property to prevent empty fields
            var create_product_html="";
            
            // 'read products' button to show list of products
            create_product_html+="<div id='read-products' class='btn btn-primary pull-right m-b-15px read-products-button'>";
                create_product_html+="<span class='glyphicon glyphicon-list'></span> Read Products";
            create_product_html+="</div>";
            // 'create product' html form
            create_product_html+="<form id='create-product-form' action='#' method='post' border='0'>";
            create_product_html+="<table class='table table-hover table-responsive table-bordered'>";

                // name field
                create_product_html+="<tr>";
                    create_product_html+="<td>Name</td>";
                    create_product_html+="<td><input type='text' name='name' class='form-control' required /></td>";
                create_product_html+="</tr>";

                // price field
                create_product_html+="<tr>";
                    create_product_html+="<td>Price</td>";
                    create_product_html+="<td><input type='number' min='1' name='price' class='form-control' required /></td>";
                create_product_html+="</tr>";

                // description field
                create_product_html+="<tr>";
                    create_product_html+="<td>Description</td>";
                    create_product_html+="<td><textarea name='description' class='form-control' required></textarea></td>";
                create_product_html+="</tr>";

                // categories 'select' field
                create_product_html+="<tr>";
                    create_product_html+="<td>Category</td>";
                    create_product_html+="<td>" + categories_options_html + "</td>";
                create_product_html+="</tr>";

                // button to submit form
                create_product_html+="<tr>";
                    create_product_html+="<td></td>";
                    create_product_html+="<td>";
                        create_product_html+="<button type='submit' class='btn btn-primary'>";
                            create_product_html+="<span class='glyphicon glyphicon-plus'></span> Create Product";
                        create_product_html+="</button>";
                    create_product_html+="</td>";
                create_product_html+="</tr>";

            create_product_html+="</table>";
            create_product_html+="</form>";
            // inject html to 'page-content' of our app
            $("#page-content").html(create_product_html);
            
            // chage page title
            changePageTitle("Create Product");
        });
    });
 
    // will run if create product form was submitted
    $(document).on('submit', '#create-product-form', function(){
        // form data will be here
        // get form data
        var form_data=JSON.stringify($(this).serializeObject());
        // submit form data to api
        $.ajax({
            url: "http://localhost/php_rest_api/api/product/create.php",
            type : "POST",
            contentType : 'application/json',
            data : form_data,
            success : function(result) {
                console.log(result);
                // product was created, go back to products list
                showProducts();
            },
            error: function(xhr, resp, text) {
                // show error to console
                console.log(xhr, resp, text);
            }
        });
        
        return false;
    });
});