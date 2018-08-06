/***********************
 * Dynamic text area shortcode
 * Retrieves file with same page slug as the url, gets the content from within a .docx file
 * Shortcode Syntax = Default:[textload] || [textload type="textone"] || [textload type="texttwo"] || [textload type="textthree..."]
 * Version 1.0
 */

// Convert .docx file's to text
function readDocx($filePath) {
    // Create new ZIP archive
    $zip = new ZipArchive;
    $dataFile = 'word/document.xml';
    // Open received archive file
    if (true === $zip->open($filePath)) {
        // If done, search for the data file in the archive
        if (($index = $zip->locateName($dataFile)) !== false) {
            // If found, read it to the string
            $data = $zip->getFromIndex($index);
            // Close archive file
            $zip->close();
            // Load XML from a string
            // Skip errors and warnings
            $xml = DOMDocument::loadXML($data, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
            // Return data without XML formatting tags

            $contents = explode('\n',strip_tags($xml->saveXML()));
            $text = '';
            foreach($contents as $i=>$content) {
                $text .= $contents[$i];
            }
            return $text;
        }
        $zip->close();
    }
    // In case of failure return empty string
    return "";
}
// Retrieve text from .docx file from server
function dynamicTextArea( $atts ){
        // Get current directory of the site on server
        $currentDirectory = getcwd();
        $currentPage = $_SERVER['REQUEST_URI'];
         // File retrieve from directory with same name as the page slug.
        $file = $currentDirectory . "/teksten" . $currentPage . ".docx";
        // Convert the document to text, and to html after that. 
        $converted_document = readDocx($file);
        $html_document = html_entity_decode ($converted_document);
         // Get filename of the file out of the directory
        $dirName = basename($file);
        // Build the url
        $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        // Convert basename to string and add delimeters for preg_match
        $dirString = "/" . strval($dirName) . "/";
        // Replace the mime type for check against url
        $dirReplaced = str_replace('.docx', '', $dirString);
            // Check the current url of the page against the filename
            if (preg_match($dirReplaced, $url)) {
                // Retrieve the content and explode the array
                $firstStep = explode('<div id="text1">', $html_document);
                $secondStep = explode('</div>', $firstStep[1]);

            } else {
                echo "Basename and url dont match". "<br>";
                $secondStep = the_post();
            }
    
        // Check for shortcode attribute used, retrieve according text
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
            case 'textsix': 
                $output = $secondStep[6];
                break;
            case 'textseven': 
                $output = $secondStep[7];
                break;
            case 'texteight': 
                $output = $secondStep[8];
                break;
            case 'textnine': 
                $output = $secondStep[9];
                break;
            default:
                $output = $secondStep[0];
                break;
        }
        return $output;
}
add_shortcode( 'textload', 'dynamicTextArea' );
