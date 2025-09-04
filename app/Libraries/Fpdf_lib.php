<?php

namespace App\Libraries;

require_once APPPATH . 'ThirdParty/fpdf/fpdf.php';

class Fpdf_lib extends \FPDF
{
     protected $angle = 0;
     public function __construct()
     {
          parent::__construct();
          // $this->AddPage('P', 'A4');
     }

     public function header()
     {
          $this->Image('assets/img/logo-wep.png', 10, 5, 75);
          // Arial bold 15
          $this->SetFont('Arial', 'B', 15);
          // Move to the right
          $this->Cell(80);
     }

     public function footer()
     {
          // Implementasi footer (jika diperlukan)
     }

     // Fungsi untuk menambahkan watermark
     public function addWatermark($text)
     {
          // Dapatkan lebar dan tinggi halaman
          $pageWidth = $this->GetPageWidth();
          $pageHeight = $this->GetPageHeight();

          // Setel font dan warna untuk watermark
          $this->SetFont('Arial', 'B', 120); // Ukuran font diperbesar menjadi 100
          $this->SetTextColor(255, 192, 203); // Warna pink muda untuk watermark

          // Hitung panjang teks dan posisi tengah
          $textWidth = $this->GetStringWidth($text);
          $x = ($pageWidth - $textWidth) / 1.5;
          $y = $pageHeight / 1.5;

          // Tambahkan teks di posisi tengah dengan rotasi 45 derajat
          $this->RotatedText($x, $y, $text, 35);
     }


     // Fungsi untuk menambahkan teks berputar (rotated text)
     public function RotatedText($x, $y, $txt, $angle)
     {
          // Memutar koordinat teks
          $this->Rotate($angle, $x, $y);
          $this->Text($x, $y, strtoupper($txt));
          $this->Rotate(0);
     }
     // Fungsi untuk memutar teks (rotate)
     public function Rotate($angle, $x = -1, $y = -1)
     {
          if ($x == -1)
               $x = $this->x;
          if ($y == -1)
               $y = $this->y;
          if ($this->angle != 0)
               $this->_out('Q');
          $this->angle = $angle;
          if ($angle != 0) {
               $angle *= M_PI / 180;
               $c = cos($angle);
               $s = sin($angle);
               $cx = $x * $this->k;
               $cy = ($this->h - $y) * $this->k;
               $this->_out(sprintf('q %.3F %.3F %.3F %.3F %.3F %.3F cm 1 0 0 1 %.3F %.3F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
          }
     }

     function fact_dev($libelle, $num)
     {
          $r1  = $this->w - 80;
          $r2  = $r1 + 68;
          $y1  = 6;
          $y2  = $y1 + 2;
          $mid = ($r1 + $r2) / 2;

          $texte  = $libelle . $num;
          $szfont = 12;
          $loop   = 0;

          while ($loop == 0) {
               $this->SetFont("Arial", "B", $szfont);
               $sz = $this->GetStringWidth($texte);
               if (($r1 + $sz) > $r2)
                    $szfont--;
               else
                    $loop++;
          }

          $this->SetLineWidth(0.1);
          $this->SetFillColor(220, 220, 220);
          $this->RoundedRect($r1, $y1, ($r2 - $r1), $y2, 2.5, 'DF');
          $this->SetXY($r1 + 1, $y1 + 2);
          $this->Cell($r2 - $r1 - 1, 5, $texte, 0, 0, "C");
     }

     function sizeOfText($texte, $largeur)
     {
          $index    = 0;
          $nb_lines = 0;
          $loop     = TRUE;
          while ($loop) {
               $pos = strpos($texte, "\n");
               if (!$pos) {
                    $loop  = FALSE;
                    $ligne = $texte;
               } else {
                    $ligne  = substr($texte, $index, $pos);
                    $texte = substr($texte, $pos + 1);
               }
               $length = floor($this->GetStringWidth($ligne));
               $res = 1 + floor($length / $largeur);
               $nb_lines += $res;
          }
          return $nb_lines;
     }

     function addLine($ligne, $tab)
     {
          global $colonnes, $format;

          $ordonnee     = 10;
          $maxSize      = $ligne;

          foreach ($colonnes as $lib => $pos) {
               $longCell  = $pos - 2;
               $texte     = $tab[$lib];
               $length    = $this->GetStringWidth($texte);
               $tailleTexte = $this->sizeOfText($texte, $length);
               $formText  = $format[$lib];
               $this->SetXY($ordonnee, $ligne - 1);
               $this->MultiCell($longCell, 4, $texte, 0, $formText);
               if ($maxSize < ($this->GetY()))
                    $maxSize = $this->GetY();
               $ordonnee += $pos;
          }
          return ($maxSize - $ligne);
     }

     function RoundedRect($x, $y, $w, $h, $r, $style = '')
     {
          $k = $this->k;
          $hp = $this->h;
          if ($style == 'F')
               $op = 'f';
          elseif ($style == 'FD' || $style == 'DF')
               $op = 'B';
          else
               $op = 'S';
          $MyArc = 4 / 3 * (sqrt(2) - 1);
          $this->_out(sprintf('%.2F %.2F m', ($x + $r) * $k, ($hp - $y) * $k));
          $xc = $x + $w - $r;
          $yc = $y + $r;
          $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - $y) * $k));

          $this->_Arc($xc + $r * $MyArc, $yc - $r, $xc + $r, $yc - $r * $MyArc, $xc + $r, $yc);
          $xc = $x + $w - $r;
          $yc = $y + $h - $r;
          $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - $yc) * $k));
          $this->_Arc($xc + $r, $yc + $r * $MyArc, $xc + $r * $MyArc, $yc + $r, $xc, $yc + $r);
          $xc = $x + $r;
          $yc = $y + $h - $r;
          $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - ($y + $h)) * $k));
          $this->_Arc($xc - $r * $MyArc, $yc + $r, $xc - $r, $yc + $r * $MyArc, $xc - $r, $yc);
          $xc = $x + $r;
          $yc = $y + $r;
          $this->_out(sprintf('%.2F %.2F l', ($x) * $k, ($hp - $yc) * $k));
          $this->_Arc($xc - $r, $yc - $r * $MyArc, $xc - $r * $MyArc, $yc - $r, $xc, $yc - $r);
          $this->_out($op);
     }
     function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
     {
          $h = $this->h;
          $this->_out(sprintf(
               '%.2F %.2F %.2F %.2F %.2F %.2F c ',
               $x1 * $this->k,
               ($h - $y1) * $this->k,
               $x2 * $this->k,
               ($h - $y2) * $this->k,
               $x3 * $this->k,
               ($h - $y3) * $this->k
          ));
     }
     function addDate($date)
     {
          $r1  = $this->w - 61;
          $r2  = $r1 + 30;
          $y1  = 17;
          $y2  = $y1 - 5;
          $mid = $y1 + ($y2 / 2);
          $this->RoundedRect($r1, $y1, ($r2 - $r1), $y2, 3.5, 'D');
          $this->Line($r1, $mid, $r2, $mid);
          $this->SetXY($r1 + ($r2 - $r1) / 2 - 5, $y1 + 3);
          $this->SetFont("Arial", "B", 9);
          $this->Cell(10, 1, "DATE", 0, 0, "C");
          $this->SetXY($r1 + ($r2 - $r1) / 2 - 5, $y1 + 9);
          $this->SetFont("Arial", "", 9);
          $this->Cell(10, 1, $date, 0, 0, "C");
     }
     function addClient($ref)
     {
          $r1  = $this->w - 31;
          $r2  = $r1 + 19;
          $y1  = 17;
          $y2  = $y1 - 5;
          $mid = $y1 + ($y2 / 2);
          $this->RoundedRect($r1, $y1, ($r2 - $r1), $y2, 3.5, 'D');
          $this->Line($r1, $mid, $r2, $mid);
          $this->SetXY($r1 + ($r2 - $r1) / 2 - 5, $y1 + 3);
          $this->SetFont("Arial", "B", 9);
          $this->Cell(10, 1, "CLIENT", 0, 0, "C");
          $this->SetXY($r1 + ($r2 - $r1) / 2 - 5, $y1 + 9);
          $this->SetFont("Arial", "", 9);
          $this->Cell(10, 1, $ref, 0, 0, "C");
     }

     function addPageNumber($page)
     {
          $r1  = $this->w - 80;
          $r2  = $r1 + 19;
          $y1  = 17;
          $y2  = $y1 - 5;
          $mid = $y1 + ($y2 / 2);
          $this->RoundedRect($r1, $y1, ($r2 - $r1), $y2, 3.5, 'D');
          $this->Line($r1, $mid, $r2, $mid);
          $this->SetXY($r1 + ($r2 - $r1) / 2 - 5, $y1 + 3);
          $this->SetFont("Arial", "B", 9);
          $this->Cell(10, 1, "PAGE", 0, 0, "C");
          $this->SetXY($r1 + ($r2 - $r1) / 2 - 5, $y1 + 9);
          $this->SetFont("Arial", "", 9);
          $this->Cell(10, 1, $page, 0, 0, "C");
     }
     function addBillFrom($adresse)
     {
          $r1     = $this->w - 200;
          $r2     = $r1 + 68;
          $y1     = 33;
          $y2  = $y1;

          // $this->RoundedRect($r1, ($y1 - 2), ($r2 - $r1), ($y2 - 2), 3.5, 'D');
          $this->RoundedRect($r1, ($y1 - 2), ($r2 - $r1), 30, 3.5, 'D');

          $this->SetXY($r1, $y1);
          $this->MultiCell(68, 5, $adresse);
     }
     function addBillTo($adresse)
     {
          $r1     = $this->w - 80;
          $r2     = $r1 + 68;
          $y1     = 33;
          $y2  = $y1;
          // $this->RoundedRect($r1, ($y1 - 2), ($r2 - $r1), ($y2 - 2), 3.5, 'D');
          $this->RoundedRect($r1, ($y1 - 2), ($r2 - $r1), 30, 3.5, 'D');
          $this->SetXY($r1, $y1);
          $this->MultiCell(68, 5, $adresse);
     }
     function addCols($tab)
     {
          global $colonnes;

          $r1  = 10;
          $r2  = $this->w - ($r1 * 2);
          $y1  = 65;
          $y2  = $this->h - 45 - $y1;
          $this->SetXY($r1, $y1);
          $this->Rect($r1, $y1, $r2, $y2, "D");
          $this->Line($r1, $y1 + 5, $r1 + $r2, $y1 + 5);
          $colX = $r1;
          $colonnes = $tab;
          foreach ($tab as $lib => $pos) {
               $this->SetXY($colX, $y1 + 2);
               $this->Cell($pos, 1, $lib, 0, 0, "C");
               $colX += $pos;
               $this->Line($colX, $y1, $colX, $y1 + $y2);
          }
     }
     function addLineFormat($tab)
     {
          global $format, $colonnes;

          foreach ($colonnes as $lib => $pos) {
               if (isset($tab["$lib"]))
                    $format[$lib] = $tab["$lib"];
          }
     }
     function ttd($name)
     {
          $r1     = $this->w - 128;
          $r2     = $r1 + 68;
          $y1     = 40;
          $y2  = $y1;

          $this->SetXY($r1, $y1 + 231);
          $this->MultiCell(60, 5, $name);
     }
     function noteInvoice($adresse)
     {
          $r1     = $this->w - 200;
          $r2     = $r1 + 68;
          $y1     = 40;
          $y2  = $y1;

          $this->SetXY($r1, $y1 + 215);
          $this->MultiCell(80, 5, $adresse);
     }
     function subTotal($subtotal, $ppn, $pph = 2)
     {
          $r1  = $this->w - 70;
          $r2  = $r1 + 63;
          $y1  = $this->h - 40;
          $y2  = $y1 + 20; // Tetap 20 seperti sebelumnya
          $this->RoundedRect(($r1 - 2), $y1, ($r2 - $r1), ($y2 - $y1), 2.5, 'D');

          // GARIS PEMISAH
          $this->Line($r1 + 23,  $y1, $r1 + 23, $y2); // avant EUROS
          // GARIS DIBAWAH SUB TOTAL
          $this->Line($r1 - 2, $y1 + 5, $r2 - 2, $y1 + 5); // Sub Total
          // GARIS DIBAWAH PPN
          $this->Line($r1 - 2, $y1 + 10, $r2 - 2, $y1 + 10); // PPN
          // GARIS DIBAWAH PPh
          $this->Line($r1 - 2, $y1 + 15, $r2 - 2, $y1 + 15); // PPh

          // ================================================
          $this->SetFont("Arial", "B", 7); // Ukuran font lebih kecil
          $this->SetXY($r1, $y1 + 4.5);
          $this->Cell(20, -4, "SUB TOTAL", 0, 0, "C");
          $this->SetFont("Arial", "B", 8);
          // JUMLAH SUB TOTAL
          $this->Cell(45, -4, 'Rp. ' . number_format($subtotal, 0, ".", "."), 0, 0, "C");

          // ================================================
          $total_ppn = 'N/A';
          $grandTotal = $subtotal;
          if ($ppn > 0) {
               $total_ppn = number_format(($subtotal * $ppn) / 100, 0, ".", ".");
               $grandTotal = $subtotal + ($subtotal * $ppn) / 100;
          }
          $this->SetFont("Arial", "B", 7); // Ukuran font lebih kecil
          $this->SetXY($r1, $y1 + 8.5);
          $this->Cell(20, -2, "PPN $ppn%", 0, 0, "C");
          $this->SetFont("Arial", "B", 8);
          $this->Cell(45, -2, 'Rp. ' . $total_ppn, 0, 0, "C");

          // ================================================
          $total_pph = 'N/A';
          if ($pph > 0) {
               $total_pph = number_format(($subtotal * $pph) / 100, 0, ".", ".");
               $grandTotal = $grandTotal - (($subtotal * $pph) / 100);
          }
          $this->SetFont("Arial", "B", 7); // Ukuran font lebih kecil
          $this->SetXY($r1, $y1 + 13.5);
          $this->Cell(20, -2, "PPH " . $pph . "%", 0, 0, "C");
          $this->SetFont("Arial", "B", 8);
          $this->Cell(45, -2, 'Rp. ' . $total_pph, 0, 0, "C");

          // ================================================
          $this->SetFont("Arial", "B", 7); // Ukuran font lebih kecil
          $this->SetXY($r1, $y1 + 16.5);
          $this->Cell(20, 2, "GRAND TOTAL", 0, 0, "C");
          $this->SetFont("Arial", "B", 8);
          $this->Cell(45, 2, 'Rp. ' . number_format($grandTotal, 0, ".", "."), 0, 0, "C");
     }
}
