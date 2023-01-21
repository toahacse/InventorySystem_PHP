<?php
  $page_title = 'All Product';
  require_once('includes/load.php');
   page_require_level(2);
  $products = join_product_table();


  if(!empty($_GET['status'])){
      switch($_GET['status']){
          case 'succ':
              $statusType = 'alert-success';
              $statusMsg = 'Products data has been imported successfully.';
              break;
          case 'err':
              $statusType = 'alert-danger';
              $statusMsg = 'Some problem occurred, please try again.';
              break;
          case 'invalid_file':
              $statusType = 'alert-danger';
              $statusMsg = 'Please upload a valid CSV file.';
              break;
          default:
              $statusType = '';
              $statusMsg = '';
      }
  }
?>
<?php include_once('layouts/header.php'); ?>
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>

        <?php if(!empty($statusMsg)){ ?>
          <div class="col-xs-12">
              <div class="alert <?php echo $statusType; ?>"><?php echo $statusMsg; ?></div>
          </div>
          <?php } 
        ?>

     </div>
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
         <div class="pull-right">
           <a href="add_product.php" class="btn btn-primary">Add New</a>
         </div>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="products_tbl">
              <thead>
                <tr>
                  <th class="text-center" style="width: 50px;">#</th>
                  <th> Photo</th>
                  <th> Product Title </th>
                  <th class="text-center" style="width: 10%;"> Categories </th>
                  <th class="text-center" style="width: 10%;"> In-Stock </th>
                  <th class="text-center" style="width: 10%;"> Buying Price </th>
                  <th class="text-center" style="width: 10%;"> Selling Price </th>
                  <th class="text-center" style="width: 10%;"> Product Added </th>
                  <th class="text-center" style="width: 100px;"> Actions </th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($products as $product):?>
                <tr>
                  <td class="text-center"><?php echo count_id();?></td>
                  <td>
                    <?php if($product['media_id'] === '0'): ?>
                      <img class="img-avatar img-circle" src="uploads/products/no_image.png" alt="">
                    <?php else: ?>
                    <img class="img-avatar img-circle" src="uploads/products/<?php echo $product['image']; ?>" alt="">
                  <?php endif; ?>
                  </td>
                  <td> <?php echo remove_junk($product['name']); ?></td>
                  <td class="text-center"> <?php echo remove_junk($product['categorie']); ?></td>
                  <td class="text-center"> <?php echo remove_junk($product['quantity']); ?></td>
                  <td class="text-center"> <?php echo remove_junk($product['buy_price']); ?></td>
                  <td class="text-center"> <?php echo remove_junk($product['sale_price']); ?></td>
                  <td class="text-center"> <?php echo read_date($product['date']); ?></td>
                  <td class="text-center">
                    <div class="btn-group">
                      <a href="edit_product.php?id=<?php echo (int)$product['id'];?>" class="btn btn-info btn-xs"  title="Edit" data-toggle="tooltip">
                        <span class="glyphicon glyphicon-edit"></span>
                      </a>
                      <a href="delete_product.php?id=<?php echo (int)$product['id'];?>" class="btn btn-danger btn-xs"  title="Delete" data-toggle="tooltip">
                        <span class="glyphicon glyphicon-trash"></span>
                      </a>
                    </div>
                  </td>
                </tr>
               <?php endforeach; ?>
              </tbody>
            </tabel>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include_once('layouts/footer.php'); ?>

  <script>

    searchDataTable()

    function searchDataTable(){
    $(document).ready(function () {
        // Setup - add a text input to each footer cell
        $('#products_tbl thead tr')
            .clone(true)
            .addClass('filters')
            .appendTo('#products_tbl thead');
    
        var table = $('#products_tbl').DataTable({
            orderCellsTop: true,
            fixedHeader: true,
            lengthMenu : [[10, 20, 50, 100, 1000], [10, 20, 50, 100, 1000]],
            initComplete: function () {
                var api = this.api();
    
                // For each column
                api
                    .columns()
                    .eq(0)
                    .each(function (colIdx) {
                        // Set the header cell to contain the input element
                        var cell = $('.filters th').eq(
                            $(api.column(colIdx).header()).index()
                        );
                        var title = $(cell).text();
                        $(cell).html('<input type="text" class="text-center" placeholder="' + title + '" />');
    
                        // On every keypress in this input
                        $(
                            'input',
                            $('.filters th').eq($(api.column(colIdx).header()).index())
                        )
                            .off('keyup change')
                            .on('keyup change', function (e) {
                                e.stopPropagation();
    
                                // Get the search value
                                $(this).attr('title', $(this).val());
                                var regexr = '({search})'; //$(this).parents('th').find('select').val();
    
                                var cursorPosition = this.selectionStart;
                                // Search the column for that value
                                api
                                    .column(colIdx)
                                    .search(
                                        this.value != ''
                                            ? regexr.replace('{search}', '(((' + this.value + ')))')
                                            : '',
                                        this.value != '',
                                        this.value == ''
                                    )
                                    .draw();
    
                                $(this)
                                    .focus()[0]
                                    .setSelectionRange(cursorPosition, cursorPosition);
                            });
                    });
            },
        });

        $(document).find('#products_tbl_filter').hide();
    });
}
  </script>