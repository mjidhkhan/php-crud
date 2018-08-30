<?php require '../inc/header.php'; ?>


<div class=" bg-white page-font">
    <div class="page-title">
        <h2 class="search-font text-success">Create New Product</h2>
        <hr>
    </div>
    <div class="col-md-12 product-info"> 
        <div class="alert alert-dark">
            <h4 class="Title logo-font">C of CRUD </h4><hr>
            <p class="search-font">This will show how to create new record in database using php</p>
        </div>
    </div>
    <br><br>
        <form class="search-font product-create" >
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputName">Name</label>
                    <input type="text" class="form-control" id="inputName" placeholder="Enter Item Name">
                    <div class="invalid-feedback">
                        Please Provide Product Name.
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label for="inputCategory">Category</label>
                    <input type="text" class="form-control" id="inputCategory" placeholder="Enter Item Category">
                    <div class="invalid-feedback">
                        Please Provide Product Category.
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputDescription">Description</label>
                <textarea type="text" class="form-control" id="inputDescription" placeholder="Enter Item Description"></textarea>
                <div class="invalid-feedback">
                        Please Provide Product Description.
                </div>
            </div>
                <button type="submit" class="btn btn-outline-primary" id="create-product">Add Item</button>
        </form>
</div>
<?php require '../inc/footer.php'; ?>