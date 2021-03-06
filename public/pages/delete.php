<?php 
require '../inc/header.php';
require_once 'functions.php';

    $response = GetCatogries();

?>
<div class=" bg-white page-font">
    <div class="page-title">
        <h2 class="search-font text-danger">Delete Products</h2>
        <hr>
    </div>
    <div class="col-md-12 product-info"> 
        <div class="alert alert-danger">
            <h4 class="Title logo-font">D of CRUD </h4><hr>
            <p class="search-font">This will show how to Delete  data from database  using php</p>
        </div>
    </div>
    <br><br>
    <div class="form-row">
        <div class="col-md-3">
        <?php if (!empty($response) && sizeof($response) > 0) {
    ?>
        <select class="custom-select Delete" id="categorySelect">
            <option value="0"  selected>Choose Category</option>
            
            <?php foreach ($response as $key => $value) {
        ?>
                <option><?php echo $value[0]; ?></option>
          <?php
    } ?>
           
        </select>
        <?php
} ?>
        </div>
    <div class="col-md-8 product" style="display:none"></div>
  </div>
</div>
<?php require '../inc/footer.php'; ?>