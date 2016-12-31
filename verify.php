<?php
include_once 'include/header.php';
include_once 'include/nav.php';
?>
<script src="js/verify.js" type="text/javascript"/></script>
    <!-- Page Content -->
    <div class="container">

        <!-- Portfolio Item Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Verify Proof-of-Existence
                    <small>Upload the file to verify the PoE</small>
                </h1>
            </div>
        </div>
        <!-- /.row -->

        <!-- Portfolio Item Row -->
        <div class="row">
            <div class="col-md-8">
                <form id="upload_form" method="POST" enctype="multipart/form-data" action="api/v1/publish">

                  <div class="form-group">
                    <label for="poeFile">File to Verify PoE</label>
                    <div id="filedrag" style="display: none;" class="dropbox">
          Click here or drag and drop your document in the box.
          <br /> The file will NOT be uploaded. The cryptographic proof is calculated client-side.
        </div>
        <div id="explain"></div>
        <div class="progress">
          <div class="progress-bar progress-bar-success progress-bar-striped"></div>
        </div>
        <fieldset>
            <input id='file' type="file" name="d" required/>
        </fieldset>
                 </fieldset>
                    <!--<input type="file" id="poeFile">-->
                  </div>
                <input type="hidden" id="signature" value="">
                  <button type="submit" class="btn btn-primary">Verify PoE Now</button>
                </form>
                
            </div>

            <div class="col-md-4">
            <?php
                include_once 'include/product_description.php';
            ?>        
                
            </div>

        </div>
        <!-- /.row -->


<?php
include_once 'include/footer.php';
?>        