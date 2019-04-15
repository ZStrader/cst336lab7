<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title> OtterMart Product Search </title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {

            $.ajax({
                type: "GET",
                url: "../api/getCategories.php",
                dataType: "json",
                success: function(data, status) {
                    data.forEach(function(key) {
                        $("#categories").append("<option value=" + key["catId"] + ">" + key["catName"] + "</option>");
                    });
                }
            });

            $("#searchForm").on("click", function() {
                $.ajax({
                    type: "GET",
                    url: "../api/getSearchResults.php",
                    dataType: "json",
                    data: {
                        "product": $("#product").val(),
                        "category": $("#categories").val(),
                        "priceFrom": $("[name=priceFrom]").val(),
                        "priceTo": $("[name=priceTo]").val(),
                        "orderBy": $("[name=orderBy]:checked").val(),
                    },
                    success: function(data, status) {
                        $("#results").html("<h3> Products Found: </h3>");
                        data.forEach(function(key) {
                            $("#results").append("<a href='#' class='historyLink' id='" + key['productId'] + "'>History</a> ");
                            $("#results").append(" | " + "<a href='#' class='descriptionLink' id='" + key['productId'] + "'>" + key['productName'] +"</a> |");
                            $("#results").append("$" + key['price'] + " | " + "<a href='#' class='deleteLink' id='" + key['productId'] + "'>Delete</a>" + "<br>");
                        });
                    }
                });
            }); //searchForm
            
            $("#finish").on("click", function() {
                $.ajax({
                    type: "GET",
                    url: "../api/getSearchResults.php",
                    dataType: "json",
                    data: {
                        "product": $("#product2").val(),
                        "category": $("#categories2").val(),
                        "price": $("[price2]").val(),
                        
                        
                    },
                    success: function(data, status) {
                        $("#results").html("<h3> Add Success </h3>");
                        data.forEach(function(key) {
                            $("#results").append("<a href='#' class='historyLink' id='" + key['productId'] + "'>History</a> ");
                            $("#results").append(" | " + "<a href='#' class='descriptionLink' id='" + key['productId'] + "'>" + key['productName'] +"</a> |");
                            $("#results").append("$" + key['price'] + " | " + "<a href='#' class='deleteLink' id='" + key['productId'] + "'>Delete</a>" + "<br>");
                        });
                    }
                });
            }); 

            $(document).on('click', '.historyLink', function() {
                $('#purchaseHistoryModal').modal("show");
                $.ajax({
                    type: "GET",
                    url: "../api/getPurchaseHistory.php",
                    dataType: "json",
                    data: { "productId": $(this).attr("id") },
                    success: function(data, status) {
                        if (data.length != 0) { // Checks if the API returned a non-empty list
                            $("#history").html(""); //clears content
                            $("#history").append(data[0]['productName'] + "<br />");
                            $("#history").append("<img src='" + data[0]['productImage'] + "' width='200' /> <br />");
                            data.forEach(function(key) {
                                $("#history").append("Purchase Date: " + key['purchaseDate'] + "<br />");
                                $("#history").append("Unit Price: " + key['unitPrice'] + "<br />");
                                $("#history").append("Quantity: " + key['quantity'] + "<br />");
                            });
                        }
                        else {
                            $("#history").html("No purchase history for this item.");
                        }
                    }
                });
            }); //historyLink
            
            $(document).on('click', '.descriptionLink', function() {
                $('#descriptionModal').modal("show");
                $.ajax({
                    type: "GET",
                    url: "../api/getDescription.php",
                    dataType: "json",
                    data: { "productId": $(this).attr("id") },
                    success: function(data, status) {
                        
                            $("#description").html(""); //clears content
                            $("#description").append(data[0]['productName'] + "<br />");
                            $("#description").append("<img src='" + data[0]['productImage'] + "' width='200' /> <br />");
                            data.forEach(function(key) {
                                $("#description").append("Description: <br />");
                                $("#description").append(key['productDescription'] + "<br />");
                            });
                        
                    }
                });
            });
            
            $(document).on('click', '.deleteLink', function() {
                $('#deleteModal').modal("show");
                $.ajax({
                    type: "GET",
                    url: "../api/delete.php",
                    dataType: "json",
                    data: { "productId": $(this).attr("id") },
                    success: function(data, status) {
                        
                            $("#deleteData").html(""); //clears content
                            $("#deleteData").append("Product deleted");
                        
                        
                    }
                });
            });
            
            $(document).on('click', '.addProductLink', function() {
                $('#addModal').modal("show");
                $.ajax({
                    type: "GET",
                    url: "../api/addProduct.php",
                    dataType: "json",
                    data: { "productId": $(this).attr("id") },
                    success: function(data, status) {
                        
                            $("#addData").html(""); //clears content
                            $("#addData").append("Product added");
                        
                        
                    }
                });
            });


        }); //documentReady
    </script>
    <style>
        body {
            text-align: center;
        }

        #results {
            text-align: left;
            padding-left: 20px;
        }
    </style>
</head>

<body>
    <div>
        <div class="jumbotron">
            <h1> OtterMart Product Editor </h1>
        </div>
        <div id="formdiv" style="display: none;">
        <form>
            Product: <input type="text" name="product" id="product" />
            <br> Category:
            <select name="category" id="categories">
                        <option value=""> Select One </option>
                    </select>
            <br> Price:  <input type="text" name="price" size="7" /> 
            <br> Description:
            <br>
            <input type="text" name="desc" /> <br>
            <br> 
            
            <br><br>
        </form>
        </div>
        <div id="editdiv" style="display: none;">
        <form>
            Product: <input type="text" name="product2" id="product2" />
            <br> Category:
            <select name="categoryProduct" id="categories2">
                        <option value=""> Select One </option>
                    </select>
            <br> Price:  <input type="text" name="price2" size="7" /> 
            <br> Description:
            <br>
            <input type="text" name="desc" /> <br>
            <br> 
            
            <br><br>
        </form>
        </div>
        <button id="searchForm">Display</button>
        <button id="showAdd">New Product</button>
        <button id="edit" >Edit Product</button>
        <button id="finish" style="display: none;">Commit</button>
        <button id="addProduct" style="display: none;">Add</button>
        <br>
    </div>
    <br>
    <hr>
    <div id="results"></div>

    <!-- Modal -->
    <div class="modal fade" id="purchaseHistoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div id="history"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="descriptionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div id="description"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div id="deleteData"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div id="addData"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <div>
        <button id="logout" class="btn btn-danger">Logout</button>
    </div>
    <script>
        $("#logout").on("click", function() {
            window.location = "../logout.php";
        });
        $("#showAdd").on("click", function() {
            $("#formdiv").show();
            $("#addProduct").show();
        });
        $("#edit").on("click", function() {
            $("#editdiv").show();
            $("#finish").show();
            var x = document.getElementById("edit");
            var y = document.getElementById("formdiv");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        });
        
        
    </script>
</body>



</html>
