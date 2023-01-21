<?php
  $page_title = 'Monthly Purchase';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
?>
<?php
 $year = date('Y');
 $sales = monthlyPurchase($year);
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Monthly Purchase</span>
          </strong>
          <a href="javascript:void(0);" id="export_as_excel" class="btn btn-sm btn-success float-right"><i class="plus"></i> Export</a>
        </div>
        <div class="panel-body">
          <table class="table table-bordered table-striped" id="monthly-purchase-table">
            <thead>
              <tr>
                <th class="text-center" style="width: 50px;">#</th>
                <th> Product name </th>
                <th class="text-center" style="width: 15%;"> Quantity Purchase</th>
                <th class="text-center" style="width: 15%;"> Total </th>
                <th class="text-center" style="width: 15%;"> Date </th>
             </tr>
            </thead>
           <tbody>
             <?php foreach ($sales as $sale):?>
             <tr>
               <td class="text-center"><?php echo count_id();?></td>
               <td><?php echo remove_junk($sale['name']); ?></td>
               <td class="text-center"><?php echo (int)$sale['quantity']; ?></td>
               <td class="text-center"><?php echo remove_junk($sale['total_buy_price']); ?></td>
               <td class="text-center"><?php echo $sale['date']; ?></td>
             </tr>
             <?php endforeach;?>
           </tbody>
         </table>
        </div>
      </div>
    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>

<script>
    searchDataTable();

      $(document).on("click","#export_as_excel",function(){
          if(confirm("Are You Want To Export It as a Excel ?")){
              html_table_to_excel('xlsx', 'Monthly Purchase');
          }
      });

      
      function html_table_to_excel(type, file_name)
      {
          var data = document.getElementById('monthly-purchase-table');

          var file = XLSX.utils.table_to_book(data, {sheet: file_name});

          XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });

          XLSX.writeFile(file, file_name+'.'+ type);
      }



  function searchDataTable(){
    $(document).ready(function () {
        // Setup - add a text input to each footer cell
        $('#monthly-purchase-table thead tr')
            .clone(true)
            .addClass('filters')
            .appendTo('#monthly-purchase-table thead');
    
        var table = $('#monthly-purchase-table').DataTable({
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

        $(document).find('#monthly-purchase-table_filter').hide();
    });
  }
      
  </script>