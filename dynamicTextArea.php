/***********************
 * Dynamic text area shortcode
 * Retrieves file with same slug as the url, gets the content
 * Shortcode Syntax = Revers to default text area[textload] Gets first of div class [textload type="textone"], textone,texttwo,textthree,textfour,textfive
 */

 /*************************
  * Docx to text conversion
  */
  //FUNCTION :: read a docx file and return the string
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

//[textload type="textone"]
function dynamicTextArea( $atts ){
        // current directory
        $currentDirectory = getcwd();
        $currentPage = $_SERVER['REQUEST_URI'];
         // File retrieve 
        $file = $currentDirectory . '/wp-content/themes/bb-theme-child' . $currentPage . ".docx";
        // Convert the document to text, and to html after that.
        $converted_document = readDocx($file);
        $html_document = html_entity_decode ($converted_document);
         // Get filename out of the url
        $dirName = basename($file);
        // Build the url
        $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        // Convert basename to string and add delimeters for preg_match
        $dirString = "/" . strval($dirName) . "/";
        // Replace the mime type for check against url
        $dirReplaced = str_replace('.docx', '', $dirString);
        // Convert Docx to text   
            // Check the current url of the page against the filename
            if (preg_match($dirReplaced, $url)) {
                // Retrieve the content out of the directory
                // $document = file_get_contents($file);
                $firstStep = explode('<div id="text1">', $html_document);
                $secondStep = explode('</div>', $firstStep[1]);

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
