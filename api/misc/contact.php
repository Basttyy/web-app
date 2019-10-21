<?php
    $page_tittle = "Powerstove - Contact";
    include_once 'partials/headers.php';
    include_once 'resource/database.php';
    include_once 'resource/session.php';
    include_once 'resource/utilities.php';

    if(isset($_POST['feedbackBtn'])){
        //array to hold errors
        $form_errors = array();

        //validate
        $required_fields = array('username', 'email', 'message');
        $form_errors = array_merge($form_errors, check_empty_fields($required_fields));
    
        if(empty($form_errors)){
            //collect form data
            $sender_name = $_POST['username'];
            $sender_email = $_POST['email'];
            $message = $_POST['message'];
            //save form values to the database
            try{
                //create sql statements
                $sqlInsert = "INSERT INTO feedback (sender_name, sender_email, message, send_date)
                                Values (:sender_name, :sender_email, :message, now())";

                //use PDO to sanitize data                    
                $statement = $db->prepare($sqlInsert);

                //add data into the database
                $statement->execute(array('sender_name' => $sender_name, 'sender_email' => $sender_email, 'message' => $message));

                //check if one row was created
                if($statement->rowCount() == 1){
                    $result = "<p style='padding: 20px; border: 1px solid gray; color: green;'>Feedback succesfully sent</p>";
                }
            }
            catch(PDOexception $ex){
                $result = "<p style='padding: 20px; color: red;'> {$ex->getMessage()} Sorry!!! Your message couldn't be sent at the moment.</p>";
            }

        }
        else{
            if(count($form_errors) == 1){
                $result = "<p style='color: red;'> There was one error in the form </p>";
            }
            else{
                $result = "<p style='color: red;'> There were " .count($form_errors). " errors in the form </p>";
            }
        }
    }
?>
<div class="container center-align">
<section>
    <div class="z-depth-3">
        <h2 class="grey lighten-2 teal-text text-darken-3">Submit A Complain</h2><hr>
    </div>    
    <div>
    <?php
        if(isset($result)) echo $result;
        if(!empty($form_errors)) echo show_errors($form_errors);
    ?>
    </div>
    <div class="clearfix"></div>
    <form action="" method="post" class="z-depth-2">
                <div class="row">
                <div class="input-field col s6">
                    <i class="material-icons prefix light">contact_mail</i>
                    <label for="emailField">Email</label>
                    <input type="email" name="email" id="emailField" value="<?php if(isset($email)) echo $email; ?>" class="validate">
                </div>
                <div class="input-field col s6">
                    <i class="material-icons prefix light">account_circle</i>
                    <label for="usernameField">Username</label>
                    <input type="text" name="username" id="usernameField" value="<?php if(isset($username)) echo $username; ?>" class="validate">
                </div>
                    <div class="input-field col s12">
                        <i class="material-icons prefix light">message</i>
                        <textarea id="messageField" name="message" class="materialize-textarea" data-length="300"></textarea>
                        <label for="messageField">Tell us what you feel</label>
                    </div>
                </div>
                <input type="hidden" name="token" value="<?php if(function_exists('_token')) echo _token(); ?>">
                <input name="hidden_id" value="<?php if(isset($id)) echo $id; ?>" type="hidden">
                <button type="submit" name="feedbackBtn" class="btn orange darken-2 btn-primary right">Send Feedback <i class="material-icons light">send</i></button>
    </form>
</section>
</div><br><br>
    <div class="container">
        <div class="divider"></div>
    </div>
<?php include_once 'partials/footers.php'; ?>
</body>
</html>