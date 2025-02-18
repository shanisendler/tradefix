<?php

/**
 * Helper Functions
 *
 * This file contains a lot of miscellaneous functions that are used throughout the app.
 *
 * @package    jQuery Mobile PHP MVC Micro Framework
 * @author     Monji Dolon <md@devgrow.com>
 * @copyright  2011-2012 Monji Dolon
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License (GPL) v3
 * @link       http://devgrow.com/jquery-mobile-php-mvc-framework/
 */

/**
 * Load the controller file automatically if it is referenced in a function.
 *
 * @param   $name	The name of the controller.
 */



spl_autoload_register(function ($name) {
    require_once LIB_DIR . '/controllers/' . strtolower($name) . '.php';
});
/**
 * Redirect the user to any page on the site.
 *
 * @param   $location	URL of where you want to return the user to.
 */
function return_to($location)
{
    $location = '/' . $location;
    header("Location: $location");
    exit();
}

/**
 * Check to see if user is logged in and if not, redirect them to the login page.
 * If they're logged in, let them proceed.
 */
function login_required()
{
    global $user, $template;
    if (!$user->is_logged) {
        $template->set_msg("You must be logged in to access this section.", false);
        User::login();
        exit();
    }
}

/**
 * This function prints variables in the template, after setting them with the
 * $template->set_variable() function. Possible to extend this to support multiple
 * languages, as well as optionally returning a value (instead of echoing).
 *
 * @param   $id	The name of the variable, prints the value passed from controller.
 */
function __($id)
{
    global $template;

    echo $template->variables[$id];
}

function ___($id)
{
    global $template;

    return (isset($template->variables[$id]) ? $template->variables[$id] : null);
}

function url()
{
    return sprintf(
        "%s://%s",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['SERVER_NAME'],
        //$_SERVER['REQUEST_URI']
    );
}

function shortUrl($longUrl)
{

    // TinyURL API endpoint
    $apiUrl = "http://tinyurl.com/api-create.php?url=" . urlencode($longUrl);

    // Initialize cURL session
    $curl = curl_init();

    // Set cURL options
    curl_setopt($curl, CURLOPT_URL, $apiUrl);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL request
    $response = curl_exec($curl);

    // Check for errors
    if ($response === false) {
        //echo "Error: " . curl_error($curl);
        return $longUrl;
    } else {
        // Extract the shortened URL from the response
        $shortUrl = trim($response);
        return $shortUrl;
    }
}

//--------------------//
function checkIsraeliPhoneNumberType($phoneNumber)
{
    // Remove any non-numeric characters from the phone number
    $phoneNumber = preg_replace('/\D/', '', $phoneNumber);

    // Check for mobile number patterns
    $mobilePatterns = [
        '/^(\+972|0)([5][0-9]{8})$/',
        '/^(\+972|0)([5][0-9]{8})$/',
        '/^(\+972|0)([5][0-9]{8})$/',
        // Add more patterns as needed for different mobile number formats
    ];

    // Check for landline number patterns
    $landlinePatterns = [
        '/^(\+972|0)([2-9][0-9]{7})$/',
        '/^(\+972|0)([2-9][0-9]{7})$/',
        '/^(\+972|0)([2-9][0-9]{7})$/',
        // Add more patterns as needed for different landline number formats
    ];

    // Check against mobile patterns
    foreach ($mobilePatterns as $pattern) {
        if (preg_match($pattern, $phoneNumber)) {
            return 'mobile';
        }
    }

    // Check against landline patterns
    foreach ($landlinePatterns as $pattern) {
        if (preg_match($pattern, $phoneNumber)) {
            return 'landLine';
        }
    }

    // Default to unknown if not matched
    return 'unknown';
}




// Function to filter records by free text
function filterByFreeSearch($record)
{
    return strpos($record->FullName, $_REQUEST['search']) !== false || strpos($record->ContactName, $_REQUEST['search']) !== false || strpos($record->MerchantName, $_REQUEST['search']) !== false || strpos($record->Telephone, $_REQUEST['search']) !== false;
}

function arrayToObject($array)
{
    if (is_array($array)) {
        return (object) array_map(__FUNCTION__, $array);
    } else {
        return $array;
    }
}


function sendEmail($to, $subject, $message, $file = null)
{


    // Attachment
    if ($file !== null && is_file($file)) {

        // Boundary
        $semi_rand = md5(time());
        $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

        // Headers
        $headers = "From: no_reply@cardate.co.il\r\n";
        $headers .= "Reply-To: no_reply@cardate.co.il\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed;\r\n";
        $headers .= " boundary=\"{$mime_boundary}\"";

        // Message
        $email_message = "This is a multi-part message in MIME format.\r\n\r\n";
        $email_message .= "--{$mime_boundary}\r\n";
        $email_message .= "Content-Type:text/html; charset=\"UTF-8\"\r\n";
        $email_message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $email_message .= $message . "\r\n";

        $file_size = filesize($file);
        $handle = fopen($file, "r");
        $content = fread($handle, $file_size);
        fclose($handle);
        $content = chunk_split(base64_encode($content));

        $filename = basename($file);

        $email_message .= "--{$mime_boundary}\r\n";
        $email_message .= "Content-Type: application/octet-stream;\r\n";
        $email_message .= " name=\"{$filename}\"\r\n";
        $email_message .= "Content-Transfer-Encoding: base64\r\n";
        $email_message .= "Content-Disposition: attachment;\r\n";
        $email_message .= " filename=\"{$filename}\"\r\n\r\n";
        $email_message .= $content . "\r\n";

        $email_message .= "--{$mime_boundary}--";
    } else {

        $headers = "From: no_reply@cardate.co.il\r\n";
        $headers .= "Reply-To: no_reply@cardate.co.il\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $email_message = $message;
    }



    // Send email
    return mail($to, $subject, $email_message, $headers);

    /*$headers = "From: support@cardate.co.il\r\n";
   $headers .= "Reply-To: support@cardate.co.il\r\n";
   $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

   // Send email
   if (mail($to, $subject, $message, $headers)) {
      return "OK";
   } else {
      return "Error";
   }*/
}

function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generateRandomCode($length = 5)
{
    $characters = '123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function is_mobile()
{

    $useragent = $_SERVER['HTTP_USER_AGENT'];

    return (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4)));
}


function correctImageOrientation($filename)
{

    if (function_exists('exif_read_data')) {
        $exif = exif_read_data($filename);

        if ($exif && isset($exif['Orientation'])) {
            $orientation = $exif['Orientation'];

            echo $orientation;

            if ($orientation != 1) {
                $img = imagecreatefromjpeg($filename);
                $deg = 0;

                switch ($orientation) {
                    case 3:
                        $deg = 180;
                        break;
                    case 6:
                        $deg = 270;
                        break;
                    case 8:
                        $deg = 90;
                        break;
                }

                if ($deg) {
                    $img = imagerotate($img, $deg, 0);
                }

                // then rewrite the rotated image back to the disk as $filename
                imagejpeg($img, str_replace(".jpg", "_6777.jpg", $filename), 95);
            } // if there is some rotation necessary
        } // if have the exif orientation info
    } // if function exists
}



function cleanPhoneNumber($phoneNumber)
{
    return preg_replace('/\D/', '', $phoneNumber);
}




function getUserIP()
{
    // Check if IP is from shared internet
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    // Check for IP passed from proxy
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]; // Take the first IP in case of multiple
    }
    // Otherwise, use remote address
    else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    // Validate the IP address
    if (filter_var($ip, FILTER_VALIDATE_IP)) {
        return $ip;
    } else {
        return 'UNKNOWN';
    }
}


