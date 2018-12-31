<?php 
$fileName = $name;
 
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header('Content-Description: File Transfer');
header("Content-type: text/csv ; charset=utf-8");
header("Content-Disposition: attachment; filename={$fileName}");
header("Expires: 0");
header("Pragma: public");

$fh = @fopen( 'php://output', 'w' );

ob_end_clean();
$headerDisplayed = false;
foreach ( $mk as $data ) {
    // Add a header row if it hasn't been added yet
    if ( !$headerDisplayed ) {
        // Use the keys from $data as the titles
        $key=array("Customer","Suite Number","Num","Total","Date");
        fputcsv($fh, $key);
        $headerDisplayed = true;
    }
 
    // Put the data into the stream
    fputcsv($fh, $data);
}
// Close the file
fclose($fh);
// Make sure nothing else is sent, our file is done
exit;