<?php
include_once 'include/header.php';
include_once 'include/nav.php';
?>

    <!-- Page Content -->
    <div class="container">
<div style="margin-top:48px;" class="notifications top-right"></div>
        <!-- Portfolio Item Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Details of a Proof-of-Existence
                    <small><?php echo $_GET['signature']; ?></small>
                </h1>

                <h3 class="page-header">Arranged in chronological order (Most recent first).
                </h1>
            </div>
        </div>
        <!-- /.row -->
<script>
            signature = '<?php echo $_GET['signature']; ?>';
  $.get( "api/v1/verify/"+ signature , function( data ) {

 Object.keys(data).forEach(function(k) {
      var items = [];
      items.push(
        '<table class="table table-striped table-hover"><thead><tr><th> Data </th><th> Value</th></tr></thead>');
       
        Object.keys(data[k]).forEach(function(key) {
            items.push('<tr><td>' + key+ '</td><td>' + data[k][key]+ '</td></tr>');
        });
items.push('</table>');
      
        container = $("#received_data");
      
      $('<div/>', {
        'class': 'table',
        html: items.join('')
      }).appendTo(container);
 
  
});
}); 
             </script>   
        <!-- Portfolio Item Row -->
        <div class="row">
        
            <div class="col-md-12">
            <div id="received_data"></div>
             

                </div>

        </div>
        <!-- /.row -->


<?php
include_once 'include/footer.php';
?>        