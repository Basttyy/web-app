<?php
    $page_tittle = "Change Payment - Status";
    require "partials/headers.php";
    require "partials/parse_test_payment.php"
?>
    <div class="container center-align">
        <section>
            <h2 class="grey-text text-darken-2">Payment Status (<?php echo $_SESSION['paidstatus']; ?>)</h2>
            <div class="z-depth-1">
            <hr>
            <h5 class="grey-text text-darken-2">Change Payment</h5>
            <div>
                <?php
                    if(isset($result)) echo $result;
                    if(!empty($form_errors)) echo show_errors($form_errors);
                ?>
            </div>
            <div class="clearfix"></div>
            <div class="row">

            <form action="" method="post" class="z-depth-1 col s7 offset-s3">
                <div class="input-field col s3">
                    <select name="status" id="" class="select">
                        <option value="" disabled selected>Status</option>
                        <option value="paid">Paid</option>
                        <option value="debted">Debted</option>
                    </select>
                    <label>Select Status</label>
                </div>               
                <input name="token" value="<?php if(function_exists('_token')) echo _token(); ?>" type="hidden">
                <button type="submit" name="statusBtn" class="btn btn-primary orange darken-2">Update Status</button>
                <br><br>
            </form></div>
        </section>
    </div><br><br>
    <div class="container">
        <div class="divider"></div>
    </div>
</div>
    
    <?php include_once "partials/footers.php"; ?>
</body>
</html>