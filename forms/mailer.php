<?php

/**
 * Simple helper to debug to the console
 *
 * @param $data object, array, string $data
 * @param $context string  Optional a description.
 *
 * @return string
 */
function debug_to_console($data, $context = 'Debug in Console') {

    // Buffering to solve problems frameworks, like header() in this and not a solid return.
    ob_start();

    $output  = 'console.info(\'' . $context . ':\');';
    $output .= 'console.log(' . json_encode($data) . ');';
    $output  = sprintf('<script>%s</script>', $output);

    echo $output;
}
debug_to_console('TEST');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
//Load composer's autoloader
require_once __DIR__."/../config.php";
require_once SITE_ROOT.'/vendor/autoload.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the form fields and remove whitespace.
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $collection = trim($_POST["collection"]);
        $delivery = trim($_POST["delivery"]);
        $phone = trim($_POST["phone"]);
        $dimensions =$_POST["dimensions"];
        $Weight = $_POST["Weight"];
        $DeliveryType = $_POST["DeliveryType"];
        // Check that data was sent to the mailer.
        if ( !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Set a 400 (bad request) response code and exit.
            http_response_code(400);
            echo "<script>console.log(Oops! There was a problem with your submission. Please complete the form and try again.)</script>";
            exit;
        }
    $mail = new PHPMailer(true);

    //Enable SMTP debugging.
    $mail->SMTPDebug = 3;                               
    //Set PHPMailer to use SMTP.
    $mail->isSMTP();            
    //Set SMTP host name                          
    $mail->Host = "mailout.one.com";
    //Set this to true if SMTP host requires authentication to send email
    $mail->SMTPAuth = true;                          
    //Provide username and password     
    $mail->Username = "sales@harrula.co.uk";                 
    $mail->Password = "Fuckyousales1";                           
    //If SMTP requires TLS encryption then set it
    $mail->SMTPSecure = "tls";                           
    //Set TCP port to connect to
    $mail->Port = 465;

    $mail->From = "sales@harrula.co.uk";
    $mail->FromName = "Harrula.co.uk";

    $mail->addAddress("sales@harrula.co.uk", "Harrula.co.uk");

    $mail->isHTML(true);

    $mail->Subject = "Quote from: $email";
    $mail->Body .= "quote Details:\n\nCollection: $collection\n\nDelivery: $delivery\n\nTelephone Number: $phone\n\nEmail: $email\n\nDimensions LxWxHmm: $dimensions\n\n Weight: $Weight\n\n DeliveryType: $DeliveryType";
    $mail->AltBody .= "quote Details:\n\nCollection: $collection\n\nDelivery: $delivery\n\nTelephone Number: $phone\n\nEmail: $email\n\nDimensions LxWxHmm: $dimensions\n\n Weight: $Weight\n\n DeliveryType: $DeliveryType";

    try {
        $mail->send();
        http_response_code(200);
        echo '<script>console.log("Message has been sent successfully");</script>';
    } catch (Exception $e) {
        debug_to_console('TEST');
        http_response_code(400);
        debug_to_console($e);
    }
}

?>