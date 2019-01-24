<?php
    $page_tittle = "User Authentication - Confirm Order Page";
    require "partials/headers.php";
?>
<div class="container center z-depth-3">

<div class="flag">
    <h1 class="grey lighten-2 teal-text text-darken-3">Welcome To Admin Page</h1>
    
    <?php 
        //TODO: remove if statement and just display logged in username
        if(isset($_SESSION['username'])){
            echo '<p class="lead">You are logged in as ' .$_SESSION['username'] .'<a href="logout.php"> Logout</a></p>';
        }else{
            echo '<script type="text/javascript">window.location.href = "login.php"</script>';
        }
        
    ?>
</div>
</div><!-- /.container -->
<div class="container">
    <section class="col col-lg-7">
        <form action="#">
            <script src="https://js.paystack.co/v1/inline.js"></script>
            <div class="row">
                <div class="input-field col s6">
                    <button type="button" class="btn btn-primary orange darken-2 right" name="pay_now" id="pay-now" tittle="Pay Now" onClick="saveOrderThenPay()">Pay Now</button>
                </div>
            </div>
        </form>
    </section>
</div>
<script>
    /*function echoEmail(){
        window.alert('<?php echo explode("encodeUserEmail", base64_decode($_GET['mail']))[1]  ;?>');
    }*/
    var orderObj = {
        email: '<?php echo explode("encodeUserEmail", base64_decode($_GET['mail']))[1]  ;?>',
        userid: '<?php echo explode("encodeuserid", base64_decode($_GET['usrid']))[1] ;?>',
        username: '<?php echo explode("encodeUserName", base64_decode($_GET['usr']))[1] ;?>',
        paycategory: '<?php echo $_GET['payref'];?>'
        //other params you want to save
    };
    function echoOrderObj(){
        window.alert(orderObj.email);
    }
    function saveOrderThenPay(){
        //window.alert('trying to pay');
        //send the data to save to database using post
        window.alert('making payment');
        var posting = $.post('resource/saveorder.php', orderObj);

        posting.done(function(data){
            //check result from the attempt
            window.alert(data);
            payWithPayStack(data);
        });
        posting.fail(function(data){
            window.alert('Failed to save data');
            //and if it failed to save do this
        });
    };
    function payWithPayStack(data){
        window.alert(data);
        dataObj = JSON.parse(data);
        var handler = PaystackPop.setup({            
            //This assumes you already created a constant named
            //PAYSTACK_PUBLIC_KEY with your public key from the
            //Paystack dashboard. You can as well just paste it
            //instead of creating the constant
            key: 'pk_test_6a23d42a2ac9cd58a44a1d32c3e9a255d71b6418',
            email: orderObj.email,
            amount: dataObj.amount,
            metadata: {
                cartid: dataObj.cartid,
                orderid: dataObj.orderid,
                custom_fields: [
                    {
                        display_name: "Paid on",
                        Variable_name: "paid_on",
                    },
                    {
                        display_name: "Paid via",
                        variable_name: "paid_via",
                        value: "Online Payment"
                    }
                ]
            },
            callback: function(){
                //post to server to verify transaction before giving value
                var verifying = $.get('resource/verify.php?reference=' + response.reference);
                verifying.done(function(data){
                    //give value saved in data
                    dataObj = json.parse(data);
                    if(dataObj.verified == true){
                        alert("Your payment was successfull");
                    }
                });
            },
            onClose: function(){
                alert('Click "Pay Now" to retry payment');
            }
        });
        handler.openIframe();
    }
</script>
<?php include_once "partials/footers.php"; ?>
</body>
</html>