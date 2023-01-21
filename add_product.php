<?php
  $page_title = 'Add Product';
  require_once('includes/load.php');
  page_require_level(2);
  $all_categories = find_all('categories');
  $all_photo = find_all('media');
?>
<?php
 if(isset($_POST['add_product'])){
   $req_fields = array('product-title','product-categorie','product-quantity','buying-price', 'saleing-price' );
   validate_fields($req_fields);
   if(empty($errors)){
     $p_name  = remove_junk($db->escape($_POST['product-title']));
     $p_cat   = remove_junk($db->escape($_POST['product-categorie']));
     $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
     $p_buy   = remove_junk($db->escape($_POST['buying-price']));
     $p_sale  = remove_junk($db->escape($_POST['saleing-price']));
     if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
       $media_id = '0';
     } else {
       $media_id = remove_junk($db->escape($_POST['product-photo']));
     }
     $date    = make_date();
     $query  = "INSERT INTO products (";
     $query .=" name,quantity,buy_price,sale_price,categorie_id,media_id,date";
     $query .=") VALUES (";
     $query .=" '{$p_name}', '{$p_qty}', '{$p_buy}', '{$p_sale}', '{$p_cat}', '{$media_id}', '{$date}'";
     $query .=")";
     $query .=" ON DUPLICATE KEY UPDATE name='{$p_name}'";
     if($db->query($query)){
       $session->msg('s',"Product added ");
       redirect('add_product.php', false);
     } else {
       $session->msg('d',' Sorry failed to added!');
       redirect('product.php', false);
     }

   } else{
     $session->msg("d", $errors);
     redirect('add_product.php',false);
   }

 }




 if(isset($_POST['importSubmit'])){
  
  $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
  
  if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){
      if(is_uploaded_file($_FILES['file']['tmp_name'])){
          $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
          fgetcsv($csvFile);
          while(($line = fgetcsv($csvFile)) !== FALSE){
              $sl           = $line[0];
              $name         = $line[1];
              $quantity     = $line[2];
              $buy_price    = $line[3];
              $sale_price   = $line[4];
              $categorie_id = $line[5];
              $media_id     = $line[6];

              $date    = make_date();
              $query  = "INSERT INTO products (";
              $query .=" name,quantity,buy_price,sale_price,categorie_id,media_id,date";
              $query .=") VALUES (";
              $query .=" '{$name}', '{$quantity}', '{$buy_price}', '{$sale_price}', '{$categorie_id}', '{$media_id}', '{$date}'";
              $query .=")";
              $query .=" ON DUPLICATE KEY UPDATE name='{$name}'";
              $db->query($query);
          }
          
          fclose($csvFile);
          $qstring = '?status=succ';
      }else{
          $qstring = '?status=err';
      }
  }else{
      $qstring = '?status=invalid_file';
  }

  header("Location: product.php".$qstring);
}


?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
  <div class="row">
  <div class="col-md-8">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Add New Product</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="add_product.php" class="clearfix">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-title" placeholder="Product Title">
               </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <select class="form-control" name="product-categorie">
                      <option value="">Select Product Category</option>
                    <?php  foreach ($all_categories as $cat): ?>
                      <option value="<?php echo (int)$cat['id'] ?>">
                        <?php echo $cat['name'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <select class="form-control" name="product-photo">
                      <option value="">Select Product Photo</option>
                    <?php  foreach ($all_photo as $photo): ?>
                      <option value="<?php echo (int)$photo['id'] ?>">
                        <?php echo $photo['file_name'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>

              <div class="form-group">
               <div class="row">
                 <div class="col-md-4">
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                     </span>
                     <input type="number" class="form-control" name="product-quantity" placeholder="Product Quantity">
                  </div>
                 </div>
                 <div class="col-md-4">
                   <div class="input-group">
                     <span class="input-group-addon">
                       <i class="glyphicon glyphicon-usd"></i>
                     </span>
                     <input type="number" class="form-control" name="buying-price" placeholder="Buying Price">
                     <span class="input-group-addon">.00</span>
                  </div>
                 </div>
                  <div class="col-md-4">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-usd"></i>
                      </span>
                      <input type="number" class="form-control" name="saleing-price" placeholder="Selling Price">
                      <span class="input-group-addon">.00</span>
                   </div>
                  </div>
               </div>
              </div>
              <button type="submit" name="add_product" class="btn btn-danger">Add product</button>
              <a href="javascript:void(0);" class="btn btn-success" onclick="formToggle('importFrm');"><i class="plus"></i> Import</a>
          </form>
          <div class="col-md-12 mt-2" id="importFrm" style="display: none;">
              <form action="" method="post" enctype="multipart/form-data" id="importFrm" style="display: block;">
                <div class="form-group" style="margin-top: 20px;margin-left:-15px;">
                  <div class="input-group">
                    <span class="input-group-addon">
                    <i class="glyphicon glyphicon-file"></i>
                    </span>
                    <input type="file" class="form-control" required name="file" placeholder="Product Title">
                  </div>
                </div>
                
                <input type="submit" class="btn btn-primary" style="margin-left: -15px;" name="importSubmit" value="IMPORT">
              </form>
          </div>
         </div>
        </div>




      </div>
    </div>
  </div>


  <script>
    function formToggle(ID){
        var element = document.getElementById(ID);
        if(element.style.display === "none"){
            element.style.display = "block";
        }else{
            element.style.display = "none";
        }
    }
  </script>

<?php include_once('layouts/footer.php'); ?>
