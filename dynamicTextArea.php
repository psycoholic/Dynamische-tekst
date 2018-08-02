/***********************
 * Dynamic text area shortcode
 * Retrieves file with same slug as the url, gets the content
 * Shortcode Syntax = Revers to default text area[textload] Gets first of div class [textload type="textone"], textone,texttwo,textthree,textfour,textfive
 */
//[textload type="textone"]
function dynamicTextArea( $atts ){

        // current directory
        $currentDirectory = getcwd();
        $currentPage = $_SERVER['REQUEST_URI'];
         // File retrieve
        $file = $currentDirectory . '/wp-content/themes/bb-theme-child' . $currentPage . ".txt";
         // Get filename out of the url
        $dirName = basename($file);
        // Build the url
        $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        // Convert basename to string and add delimeters for preg_match
        $dirString = "/" . strval($dirName) . "/";
        // Replace the mime type for check against url
        $dirReplaced = str_replace('.txt', '', $dirString);
    
            // Check the current url of the page against the filename
            if (preg_match($dirReplaced, $url)) {
                echo "BaseName and url match" . "<br>";
                // Retrieve the content out of the directory
                $document = file_get_contents($file);
                $firstStep = explode('<div id="text1">', $document);
                $secondStep = explode('</div>', $firstStep[1]);
                
                // echo $secondStep[0];
            } else {
                echo "Basename and url dont match". "<br>";
                $document = the_post();
            }
    
        // Check for wich part of the text has to be retrieved.

        extract( shortcode_atts( array(
            'type' => 'myvalue'

        ), $atts ) );

        switch( $type ){
            case 'textone': 
                $output = $secondStep[0];
                break;
            case 'texttwo': 
                $output = $secondStep[1];
                break;
            case 'texttwo': 
                $output = $secondStep[2];
                break;
            case 'textthree': 
                $output = $secondStep[3];
                break;
            case 'textfour': 
                $output = $secondStep[4];
                break;
            case 'textfive': 
                $output = $secondStep[5];
                break;
            default:
                $output = $secondStep[0];
                break;
        }

        return $output;

}
add_shortcode( 'textload', 'dynamicTextArea' );
