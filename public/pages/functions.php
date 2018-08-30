<?php
/**
 * File: Functions.php
 * Date: 18-08-2018
 * In this file we created all required functions.
 *
 *
 * ---------------------------------------------
 *                  [NOTE]
 * ---------------------------------------------
 *  Every Function performing Single Task. Which
 *  provide us facilty of loosely couppled setup.
 */
 CheckEnviornment();

if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $data = $_POST['data'];
    echo ProcessAction($action, $data);
} else {
    return;
}

function ProcessAction($action, $data)
{
    switch ($action) {
        case 'Search':
            $response = SearchProduct($data);
            break;
        case 'Create':
             $response = CreateProduct($data);
            break;
        case 'Update':
             $response = UpdateProduct($data);
            break;
        case 'Delete':
             $response = DeleteProduct($data);
            break;
        case 'Read':
            $response = ReadProducts($data);
            break;
        case 'CategorySelect':
            $response = CategorySelect($data);
            break;
        case 'Details':
            $response = GetProductDetails($data);
            break;
     }

    return  $response;
}

// ACTION FUNCTIONS CREATE ITEM

function CreateProduct($data)
{
    $db = database();
    if ($db['DB']) {
        $response = ProcessCreate($db['Message'], $data);
    } else {
        $response = ProcessDBConnectionError($db);
    }
    closeDatabse($db['Message']);

    return $response;
}

function ProcessCreate($con, $data)
{
    $name = $data['name'];
    $category = $data['category'];
    $description = $data['description'];
    $sql = "INSERT INTO product(Name, Category, Description) VALUES('$name','$category','$description')";
    if ($con->query($sql) == true) {
        $last_id = mysqli_insert_id($con);
        $title = 'Product Create Success';
        $message = '<span class="search-font"><b>'.$name.'</b> Created Successfully with ID :  <b><em>'.$last_id.'</em><b></span>';
        $response = ProcessInformationMessage($title, $message, 'success');
    } else {
        $title = 'Product Creation Error';
        $message = '<span class="search-font">An Error Occured While Creating Product :  <b>'.$name.'</b><br>'.$con->error.'</span>';
        $response = ProcessInformationMessage($title, $message, 'danger');
    }

    return $response;
}

// ACTION FUNCTIONS UPDATE ITEM

function GetProductDetails($data)
{
    $id = $data['id'];
    $db = database();
    if ($db['DB']) {
        $sql = "SELECT * FROM product WHERE Id= $id";
        $con = $db['Message'];
        $result = mysqli_query($con, $sql);
        $row = mysqli_num_rows($result);
        if ($row > 0) {
            $rows = mysqli_fetch_assoc($result);
            $response = ProductDetails($rows);
        }
    } else {
        $response = ProcessDBConnectionError($db);
    }

    closeDatabse($db['Message']);

    return $response;
}

function processProductDetails()
{
}

function GetCatogries()
{
    $db = database();
    if ($db['DB']) {
        $sql = 'SELECT DISTINCT Category FROM product WHERE Category IS NOT NULL';
        $con = $db['Message'];
        $result = mysqli_query($con, $sql);
        $row = mysqli_num_rows($result);
        if ($row > 0) {
            $response = mysqli_fetch_all($result);
        }
    } else {
        $response = ProcessDBConnectionError($db);
    }

    closeDatabse($db['Message']);

    return $response;
}

function UpdateProduct($data)
{
    $db = database();
    if ($db['DB']) {
        $response = processUpdate($db['Message'], $data);
    } else {
        $response = ProcessDBConnectionError($db);
    }
    closeDatabse($db['Message']);

    return $response;
}

function processUpdate($con, $data)
{
    $id = $data['id'];
    $name = $data['name'];
    $category = $data['category'];
    $description = $data['description'];
    $sql = "UPDATE product SET Name ='$name', Category = '$category', Description = '$description' WHERE Id = $id";
    if ($con->query($sql) === true) {
        $title = 'Success Update Record';
        $message = 'Database Record updated successfully';
        $response = ProcessInformationMessage($title, $message, 'info');
    } else {
        $title = 'Error Updating Record';
        $message = '<span class="search-font">'.$con->error.'</span>';

        $response = ProcessInformationMessage($title, $message, 'danger');
    }

    return $response;
}

// ACTION FUNCTIONS DELETE ITEM

function DeleteProduct($data)
{
    $db = database();
    if ($db['DB']) {
        $response = ProcessDelete($db['Message'], $data);
    } else {
        $response = ProcessDBConnectionError($db);
    }
    closeDatabse($db['Message']);

    return $response;
}

function ProcessDelete($con, $data)
{
    $id = $data['id'];
    $sql = "DELETE FROM product WHERE Id = $id";
    if ($con->query($sql) === true) {
        $title = 'Success Deleted Record';
        $message = 'Database Record Deleted successfully';
        $response = ProcessInformationMessage($title, $message, 'info');
    } else {
        $title = 'Error Deleting Record';
        $message = '<span class="search-font">'.$con->error.'</span>';

        $response = ProcessInformationMessage($title, $message, 'danger');
    }

    return $response;
}

// ACTION FUNCTIONS SEARCH

function SearchProduct($search)
{
    $db = database();
    if ($db['DB']) {
        $response = ProcessSearch($db['Message'], $search);
    } else {
        $response = ProcessDBConnectionError($db);
    }
    closeDatabse($db['Message']);

    return $response;
}

function ProcessSearch($con, $search)
{
    $sql = "SELECT * FROM product  WHERE Description LIKE '%$search%' OR  Name LIKE '%$search%' OR  Category LIKE '%$search%'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_num_rows($result);
    $title = 'Search Result';
    if ($row > 0) {
        $rows = mysqli_fetch_all($result);
        $message = '<span class="search-font"><b>'.$row.'</b> items  found for your search word:  <b><em>'.$search.'</em><b></span>';
        $response = ProcessInformationMessage($title, $message, 'info').$result = ProcessDispalySearch($rows);
    } else {
        $message = 'Nothing found for you Search Item: '.$search;
        $response = ProcessInformationMessage($title, $message, 'info');
    }

    return $response;
}

// ENVIORNMENT CHECK FUNCTION

function IsLocal()
{
    $read_settings = parse_ini_file('../settings/setting.ini');
    if ($read_settings['ENV'] === 'local') {
        $response = true;
    } else {
        $response = false;
    }

    return $response;
}

function CheckEnviornment()
{
    if (!IsLocal()) {
        error_reporting(0);
    }
}

/* ******************** DATABASE FUNCTIONS START ********************** */

function closeDatabse($con)
{
    mysqli_close($con);
}

function database()
{
    $db_settings = readSettings();
    $con = connectToDB($db_settings);

    return $con;
}

function connectToDB($db_settings)
{
    $db_host = $db_settings['DB_HOST'];
    $db_name = $db_settings['DB_NAME'];
    $db_user = $db_settings['DB_USER'];
    $db_pass = $db_settings['DB_PASS'];
    $con = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    if (mysqli_connect_errno()) {
        $response = array('DB' => false, 'Message' => 'Failed to connect to MySQL: <em><b>'.mysqli_connect_error().'</em><b>');
    } else {
        $response = array('DB' => true, 'Message' => $con);
    }

    return $response;
}
/* ******************** DATABASE FUNCTIONS  END ********************** */

// UTILITY FUNCTIONS

function CategorySelect($data)
{
    switch ($data['Type']) {
        case 'Read':
            $response = ReadSelect($data);
            break;
        case 'Update':
           $response = UpdateSelect($data);
            break;
        case 'Delete':
             $response = DeleteSelect($data);
            break;
     }

    return $response;
}

function DeleteSelect($data)
{
    $db = database();
    if ($db['DB']) {
        $response = processDeleteSelect($db['Message'], $data['category']);
    } else {
        $response = ProcessDBConnectionError($db);
    }
    closeDatabse($db['Message']);

    return $response;
}

function processDeleteSelect($con, $category)
{
    $sql = "SELECT * FROM product WHERE Category = '$category' ";
    $result = mysqli_query($con, $sql);
    $row = mysqli_num_rows($result);
    if ($row > 0) {
        $rows = mysqli_fetch_all($result);
        $response = processDisplayDelete($category, $rows);
    } else {
        $title = 'Whoops...!';
        $message = 'Category <span style="font-size:1.5rem; ">'.$category.'</span> does not exist. Seems all record are being <span style="font-size:1.2rem; ">Deleted</span>. <br><h5 class="search-font">Please Refresh page to reload data.</h5>';
        $response = ProcessInformationMessage($title, $message, 'danger');
    }

    return $response;
}

function ReadSelect($data)
{
    $db = database();
    if ($db['DB']) {
        $response = processReadSelect($db['Message'], $data['category']);
    } else {
        $response = ProcessDBConnectionError($db);
    }
    closeDatabse($db['Message']);

    return $response;
}

function processReadSelect($con, $category)
{
    $sql = "SELECT * FROM product WHERE Category= '$category'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_num_rows($result);
    if ($row > 0) {
        $rows = mysqli_fetch_all($result);
        $response = processDisplayRead($category, $rows);
    }

    return $response;
}

function UpdateSelect($data)
{
    $db = database();
    if ($db['DB']) {
        $response = processUpdateSelect($db['Message'], $data['category']);
    } else {
        $response = ProcessDBConnectionError($db);
    }
    closeDatabse($db['Message']);

    return $response;
}

function processUpdateSelect($con, $category)
{
    $sql = "SELECT * FROM product WHERE Category = '$category' ";
    $result = mysqli_query($con, $sql);
    $row = mysqli_num_rows($result);
    if ($row > 0) {
        $rows = mysqli_fetch_all($result);
        $response = processDisplayUpdate($category, $rows);
    }

    return $response;
}

function readSettings()
{
    $read_settings = parse_ini_file('../settings/setting.ini');

    return $read_settings;
}

// GUI FUNCTIONS

function ProcessDBConnectionError($con)
{
    $title = 'Database Connection Error';

    return ProcessInformationMessage($title, $con['Message'], 'danger');
}

function ProcessInformationMessage($title, $msg, $gui)
{
    $message = '';
    $message .= '<div class="alert alert-'.$gui.'"> <h3 class="Title search-font">'.$title.'</h3><hr>';
    $message .= '<p>'.$msg.'<p>';

    return $message;
}

function ProcessDispalySearch($row)
{
    $table = '';
    $table .= '<table class="table table-bordered bg-light">';
    $table .= '<thead class="text-white bg-info">';
    $table .= '<tr><th>ID</th><th>Name</th><th>Category</th><th>Description</th></tr>';
    $table .= '</thead>';
    $table .= '<tbody>';
    foreach ($row as $item) {
        $table .= '<tr>';
        foreach ($item  as $value) {
            $table .= '<td>'.$value.'</td>';
        }
        $table .= '</tr>';
    }
    $table .= ' </tbody>';
    $table .= '</table>';

    return $table;
}

function productDetails($row)
{
    $form = '';
    $form .= '<div class="alert alert-info"> <h4 class="Title search-font"> Selected Record  <span class="text-danger"></span></h4><hr>';
    $form .= '<div class="search-font">';
    $form .= '<div class="form-row">';
    $form .= '<div class="form-group col-md-6">';
    $form .= '<label for="inputName">Name</label>';
    $form .= '<input type="text" class="form-control" id="inputName" value="'.$row['Name'].'">';
    $form .= '<div class="invalid-feedback">';
    $form .= 'Please Provide Product Name.';
    $form .= '</div>';
    $form .= '</div>';
    $form .= '<div class="form-group col-md-6">';
    $form .= '<label for="inputCategory">Category</label>';
    $form .= '<input type="text" class="form-control" id="inputCategory" value="'.$row['Category'].'">';
    $form .= '<div class="invalid-feedback">';
    $form .= 'Please Provide Product Category.';
    $form .= '</div>';
    $form .= '</div>';
    $form .= '</div>';
    $form .= '<div class="form-group">';
    $form .= '<label for="inputDescription">Description</label>';
    $form .= '<textarea type="text" class="form-control" id="inputDescription">'.$row['Description'].'</textarea>';
    $form .= '<div class="invalid-feedback">';
    $form .= 'Please Provide Product Description.';
    $form .= '</div>';
    $form .= '</div>';
    $form .= '<button type="button" class="btn btn-outline-info" id="update-product" onclick="UpdateProduct('.$row['Id'].')">Update Product</button>';
    $form .= '</div>';

    return $form;
}

function processDisplayUpdate($category, $data)
{
    $table = '';
    $table = '<div class="alert alert-info"> <h4 class="Title search-font"> Selected Category  <span class="text-danger">'.$category.'</span></h4><hr>';
    $table .= '<h5 class="search-font">Details </h5>';
    $table .= '<table class="table table-bordered bg-light">';
    $table .= '<thead class=" text-white bg-info"><tr>';
    $table .= '<th>Name</th>';
    $table .= '<th>Category</th>';
    $table .= '<th>Description</th>';
    $table .= '<th>Action</th>';
    $table .= '</tr>';
    $table .= '</thead>';
    $table .= '<tbody>';
    foreach ($data as $key => $value) {
        $table .= '<tr>';
        $table .= '<td>'.$value[1].'</td>';
        $table .= '<td>'.$value[2].'</td>';
        $table .= '<td>'.$value[3].'</td>';
        $table .= '<td><button type="button" class="btn btn-outline-info" id="update-product" onclick="loadProduct(`'.$value[0].'`)">Edit</button></td>';
        $table .= '</tr>';
    }
    $table .= '</tbody>';
    $table .= '</table>';

    return $table;
}

function processDisplayRead($category, $data)
{
    $table = '';
    $table = '<div class="alert alert-primary"> <h4 class="Title search-font"> Selected Category  <span class="text-danger">'.$category.'</span></h4><hr>';
    $table .= '<h5 class="search-font">Details </h5>';
    $table .= '<table class="table table-bordered bg-light">';
    $table .= '<thead class=" text-white bg-primary"><tr>';
    $table .= '<th>Name</th>';
    $table .= '<th>Description</th>';
    $table .= '</tr>';
    $table .= '</thead>';
    $table .= '<tbody>';
    foreach ($data as $key => $value) {
        $table .= '<tr>';
        $table .= '<td>'.$value[1].'</td>';
        $table .= '<td>'.$value[3].'</td>';
        $table .= '</tr>';
    }
    $table .= '</tbody>';
    $table .= '</table>';

    return $table;
}

function processDisplayDelete($category, $data)
{
    $table = '';
    $table = '<div class="alert alert-danger"> <h4 class="Title search-font"> Selected Category  <span class="text-danger">'.$category.'</span></h4><hr>';
    $table .= '<h5 class="search-font">Details </h5>';
    $table .= '<table class="table table-bordered bg-light">';
    $table .= '<thead class=" text-white bg-danger"><tr>';
    $table .= '<th>Name</th>';
    $table .= '<th>Category</th>';
    $table .= '<th>Description</th>';
    $table .= '<th>Action</th>';
    $table .= '</tr>';
    $table .= '</thead>';
    $table .= '<tbody>';
    foreach ($data as $key => $value) {
        $table .= '<tr>';
        $table .= '<td>'.$value[1].'</td>';
        $table .= '<td>'.$value[2].'</td>';
        $table .= '<td>'.$value[3].'</td>';
        $table .= '<td><button type="button" class="btn btn-outline-danger" id="update-product" onclick="deleteProduct(`'.$value[0].'`)">Delete</button></td>';
        $table .= '</tr>';
    }
    $table .= '</tbody>';
    $table .= '</table>';

    return $table;
}

function debugArray($row)
{
    echo '<pre>';
    print_r($row);
    echo'</pre>';
}
