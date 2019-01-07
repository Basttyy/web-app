// product list html
function readProductsTemplate(data, keywords){
 
    var read_products_html="";
 
    // search products form
    read_products_html+="<form id='search-product-form' action='#' method='post'>";
    read_products_html+="<div class='input-group pull-left w-30-pct'>";
 
        read_products_html+="<input type='text' value=\"" + keywords + "\" name='keywords' class='form-control product-search-keywords' placeholder='Search products...' />";
 
        read_products_html+="<span class='input-group-btn'>";
            read_products_html+="<button type='submit' class='btn btn-default' type='button'>";
                read_products_html+="<span class='glyphicon glyphicon-search'></span>";
            read_products_html+="</button>";
        read_products_html+="</span>";
 
    read_products_html+="</div>";
    read_products_html+="</form>";
 
    // when clicked, it will load the create product form
    read_products_html+="<div id='create-product' class='btn btn-primary pull-right m-b-15px create-product-button'>";
        read_products_html+="<span class='glyphicon glyphicon-plus'></span> Create Product";
    read_products_html+="</div>";
 
    // start table
    read_products_html+="<table class='table table-bordered table-hover'>";
 
        // creating our table heading
        read_products_html+="<tr>";
            read_products_html+="<th class='w-25-pct'>Name</th>";
            read_products_html+="<th class='w-10-pct'>Price</th>";
            read_products_html+="<th class='w-15-pct'>Category</th>";
            read_products_html+="<th class='w-25-pct text-align-center'>Action</th>";
        read_products_html+="</tr>";
 
    // loop through returned list of data
    $.each(data.records, function(key, val) {
 
        // creating new table row per record
        read_products_html+="<tr>";
 
            read_products_html+="<td>" + val.name + "</td>";
            read_products_html+="<td>$" + val.price + "</td>";
            read_products_html+="<td>" + val.category_name + "</td>";
 
            // 'action' buttons
            read_products_html+="<td>";
                // read product button
                read_products_html+="<button class='btn btn-primary m-r-10px read-one-product-button' data-id='" + val.id + "'>";
                    read_products_html+="<span class='glyphicon glyphicon-eye-open'></span> Read";
                read_products_html+="</button>";
 
                // edit button
                read_products_html+="<button class='btn btn-info m-r-10px update-product-button' data-id='" + val.id + "'>";
                    read_products_html+="<span class='glyphicon glyphicon-edit'></span> Edit";
                read_products_html+="</button>";
 
                // delete button
                read_products_html+="<button class='btn btn-danger delete-product-button' data-id='" + val.id + "'>";
                    read_products_html+="<span class='glyphicon glyphicon-remove'></span> Delete";
                read_products_html+="</button>";
            read_products_html+="</td>";
 
        read_products_html+="</tr>"; 
    });
 
    // end table
    read_products_html+="</table>";
    // pagination
    if(data.paging){
        read_products_html+="<ul class='pagination pull-left margin-zero padding-bottom-2em'>";
    
            // first page
            if(data.paging.first!=""){
                read_products_html+="<li><a data-page='" + data.paging.first + "'>First Page</a></li>";
            }
    
            // loop through pages
            $.each(data.paging.pages, function(key, val){
                var active_page=val.current_page=="yes" ? "class='active'" : "";
                read_products_html+="<li " + active_page + "><a data-page='" + val.url + "'>" + val.page + "</a></li>";
            });
    
            // last page
            if(data.paging.last!=""){
                read_products_html+="<li><a data-page='" + data.paging.last + "'>Last Page</a></li>";
            }
        read_products_html+="</ul>";
    }
 
    // inject to 'page-content' of our app
    $("#page-content").html(read_products_html);
}