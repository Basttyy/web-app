<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // If you're using Composer (recommended)
class Utils{
    function getToken($length=32){
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        for($i=0;$i<$length;$i++){
            $token .= $codeAlphabet[random_int(0,strlen($codeAlphabet)-1)];
        }
        return $token;
    }
    //generate random number
    function crypto_rand_secure($min, $max) {
        $range = $max - $min;
        if ($range < 0) return $min; // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
    }
    //send emial using PHPMailer
    public function sendEmailViaPhpMailer($send_to_email, $receiver_name, $subject, $body){
        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            //$mail->SMTPDebug = 2;                                       // Enable verbose debug output
            $mail->isSMTP();                                            // Set mailer to use SMTP
            $mail->Host       = 'mail.powerstove.com.ng';  // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'noreply@powerstove.com.ng';                     // SMTP username
            $mail->Password   = 'eqo371Tx9ZAU';                               // SMTP password eqo371Tx9ZAU
            $mail->Port       = 587;                                   // TCP port to connect to
            //$mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
            $mail->isHTML(true);

            //Recipients
            $mail->setFrom('noreply@powerstove.com.ng', 'Powerstove Notice');
            $mail->addAddress($send_to_email, $receiver_name);     // Add a recipient
            $mail->addReplyTo('info@powerstove.com.ng', 'Powerstove Info');

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    // send email using sendgrid
    public function sendEmailViaSendgrid($send_to_email, $subject, $body){
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom("noreply@powerstove.com.ng", "Abdulbasit Mamman");
        $email->setSubject($subject);
        $email->addTo($send_to_email, "Example User");
        //$email->addContent("text/plain", "and easy to do anywhere, even with PHP");
        $email->addContent(
            "text/html", $body
        );
        $sendgrid = new \SendGrid('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');
        try {
            $response = $sendgrid->send($email);
            print $response->statusCode() . "\n";
            print_r($response->headers());
            print $response->body() . "\n";
            return $response;
        } catch (Exception $e) {
            echo 'Caught exception: '. $e->getMessage() ."\n";
            return false;
        }
    }
    // send email using built in php mailer
    public function sendEmailViaPhpMail($send_to_email, $subject, $body){
    
        $from_name="Abdulbasit Mamman";
        $from_email="basttyydev@gmail.com";
        
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        $headers .= "From: {$from_name} <{$from_email}> \n";
    
        if(mail($send_to_email, $subject, $body, $headers)){
            return true;
        }else{
            return false;
        }
    }
    // calculate pagination parameters
    public function getPaging($page, $total_rows, $records_per_page, $page_url){
 
        // paging array
        $paging_arr=array();
 
        // button for first page
        $paging_arr["first"] = $page>1 ? "{$page_url}page=1" : "";
 
        // count all products in the database to calculate total pages
        $total_pages = ceil($total_rows / $records_per_page);
 
        // range of links to show
        $range = 2;
 
        // display links to 'range of pages' around 'current page'
        $initial_num = $page - $range;
        $condition_limit_num = ($page + $range)  + 1;
 
        $paging_arr['pages']=array();
        $page_count=0;
        
        for($x=$initial_num; $x<$condition_limit_num; $x++){
            // be sure '$x is greater than 0' AND 'less than or equal to the $total_pages'
            if(($x > 0) && ($x <= $total_pages)){
                $paging_arr['pages'][$page_count]["page"]=$x;
                $paging_arr['pages'][$page_count]["url"]="{$page_url}page={$x}";
                $paging_arr['pages'][$page_count]["current_page"] = $x==$page ? "yes" : "no";
                $page_count++;
            }
        }
 
        // button for last page
        $paging_arr["last"] = $page<$total_pages ? "{$page_url}page={$total_pages}" : "";
 
        // json format
        return $paging_arr;
    }
}