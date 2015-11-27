<?php
include 'PDFMerger/PDFMerger.php';

$pdf = new PDFMerger;

$pdf->addPDF('PDFMerger/samplepdfs/1.pdf')
	->addPDF('PDFMerger/samplepdfs/4.pdf')
	->merge('file', 'PDFMerger/samplepdfs/ricardo.pdf');
	
	if($pdf)
		echo 'GErou pdf';
	
	//REPLACE 'file' WITH 'browser', 'download', 'string', or 'file' for output options
	//You do not need to give a file path for browser, string, or download - just the name.
?>