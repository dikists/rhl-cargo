<?php

use Dompdf\Dompdf;
use Dompdf\Options;

function create_pdf($html, $filename = '', $stream = true, $paper = [0, 0, 650, 936], $orientation = 'portrait')
{
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper($paper, $orientation);
    $dompdf->render();

    if ($stream) {
        $dompdf->stream($filename, array('Attachment' => 0));
    } else {
        return $dompdf->output();
    }
}
function create_pengiriman_pdf($html, $filename = '', $stream = true, $paper = 'A4', $orientation = 'landscape')
{
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper($paper, $orientation);

    // Render the HTML as PDF
    $dompdf->render();

    // Add page numbers
    $canvas = $dompdf->get_canvas();
    $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
        $text = "Page $pageNumber of $pageCount";
        $font = $fontMetrics->get_font('Arial, Helvetica, sans-serif', 'normal');
        $size = 8;
        $width = $fontMetrics->getTextWidth($text, $font, $size);

        // Set the position at the bottom center
        $x = ($canvas->get_width() - $width) / 2;
        $y = $canvas->get_height() - 15; // 15 units from the bottom
        $canvas->text($x, $y, $text, $font, $size);
    });

    // Output the generated PDF (1 = download and 0 = preview)
    if ($stream) {
        $dompdf->stream($filename, array('Attachment' => 0));
    } else {
        return $dompdf->output();
    }
}
// function create_resi_pdf($html, $filename = '', $stream = true, $paper = [0, 0, 410.28, 841.89], $orientation = 'landscape')
// {
//     $options = new Options();
//     $options->set('isHtml5ParserEnabled', true);
//     $options->set('isRemoteEnabled', true);

//     $dompdf = new Dompdf($options);
//     $dompdf->loadHtml($html);
//     $dompdf->setPaper($paper, $orientation);

//     // Render the HTML as PDF
//     $dompdf->render();

//     // Output the generated PDF (1 = download and 0 = preview)
//     if ($stream) {
//         $dompdf->stream($filename, array('Attachment' => 0));
//     } else {
//         return $dompdf->output();
//     }
// }
function create_resi_pdf($html, $filename = '', $stream = true, $paper = [0, 0, 612, 396], $orientation = 'portrait')
{
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);

    // Set ukuran kertas 8.5 x 5.5 inch (half letter) portrait
    $dompdf->setPaper($paper, $orientation);

    // Render HTML ke PDF
    $dompdf->render();

    // Output PDF (1 = download, 0 = preview di browser)
    if ($stream) {
        $dompdf->stream($filename, array('Attachment' => 0));
    } else {
        return $dompdf->output();
    }
}


function create_label_pdf($html, $filename = '', $stream = true, $paper = [0, 0, 410.28, 581.89], $orientation = 'portrait')
{
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper($paper, $orientation);

    // Render the HTML as PDF
    $dompdf->render();

    // Output the generated PDF (1 = download and 0 = preview)
    if ($stream) {
        $dompdf->stream($filename, array('Attachment' => 0));
    } else {
        return $dompdf->output();
    }
}
function pdf_landscape($html, $filename = '', $stream = true, $paper = 'A4', $orientation = 'landscape')
{
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper($paper, $orientation);

    // Render the HTML as PDF
    $dompdf->render();

    // Add page numbers
    $canvas = $dompdf->get_canvas();
    $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
        $text = "Page $pageNumber of $pageCount";
        $font = $fontMetrics->get_font('Arial, Helvetica, sans-serif', 'normal');
        $size = 8;
        $width = $fontMetrics->getTextWidth($text, $font, $size);

        // Set the position at the bottom center
        $x = ($canvas->get_width() - $width) / 2;
        $y = $canvas->get_height() - 15; // 15 units from the bottom
        $canvas->text($x, $y, $text, $font, $size);
    });

    // Output the generated PDF (1 = download and 0 = preview)
    if ($stream) {
        $dompdf->stream($filename, array('Attachment' => 0));
    } else {
        return $dompdf->output();
    }
}
function pdf_portrait($html, $filename = '', $stream = true, $paper = 'A4', $orientation = 'portrait')
{
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper($paper, $orientation);

    // Render the HTML as PDF
    $dompdf->render();

    // Add page numbers
    $canvas = $dompdf->get_canvas();
    $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
        $text = "Page $pageNumber of $pageCount";
        $font = $fontMetrics->get_font('Arial, Helvetica, sans-serif', 'normal');
        $size = 8;
        $width = $fontMetrics->getTextWidth($text, $font, $size);

        // Set the position at the bottom center
        $x = ($canvas->get_width() - $width) / 2;
        $y = $canvas->get_height() - 15; // 15 units from the bottom
        $canvas->text($x, $y, $text, $font, $size);
    });

    // Output the generated PDF (1 = download and 0 = preview)
    if ($stream) {
        $dompdf->stream($filename, array('Attachment' => 0));
    } else {
        return $dompdf->output();
    }
}

function create_uang_jalan_pdf($html, $filename = '', $stream = true, $paper = 'A4', $orientation = 'portrait')
{
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper($paper, $orientation);

    // Render the HTML as PDF
    $dompdf->render();

    // Output the generated PDF (1 = download and 0 = preview)
    if ($stream) {
        $dompdf->stream($filename, array('Attachment' => 0));
    } else {
        return $dompdf->output();
    }
}
