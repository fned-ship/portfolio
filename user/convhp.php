<?php
// include autoloader
require_once 'dompdf/dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();
$html='
<style>
.hh{
    color:red;
}
</style>
<h1 class="hh" >hello biaaaatch</h1>
';
$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream("",array("Attachment" => false));
?>