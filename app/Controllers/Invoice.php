<?php

namespace App\Controllers;

use App\Models\InvoiceModel;
use App\Controllers\BaseController;
use App\Models\PengirimanModel;
use App\Libraries\Fpdf_lib;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Border;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class Invoice extends BaseController
{
     protected $invoiceModel;
     protected $pengirimanModel;

     public function __construct()
     {
          $this->invoiceModel = new InvoiceModel();
          $this->pengirimanModel = new PengirimanModel();
     }
     public function index()
     {
          $user = $this->currentUser;

          $data = [
               'title' => 'Invoice',
               'user' => $user
          ];
          echo view('invoice/index', $data);
     }

     public function get_invoices()
     {
          $id = session()->get('id');
          $invoices = $this->invoiceModel->getInvoicePelanggan($id);
          $no = 1;

          foreach ($invoices as &$invoice) {
               $invoice['no'] = $no++;
               $invoice['action'] = '
               <div class="text-center">
               <a href="' . base_url('invoice/generate_pdf/' . $invoice['id']) . '" class="btn btn-sm btn-danger m-1" target="_blank"><i class="fa fa-file-pdf" aria-hidden="true"></i> Pdf</a>
               <a href="' . base_url('invoice/generate_excel/' . $invoice['id']) . '" class="btn btn-sm btn-success m-1" target="_blank"><i class="fa fa-file-excel" aria-hidden="true"></i> Excel</a>
               </div>
               ';
          }

          return $this->response->setJSON($invoices);
     }
     public function generate_pdf($id)
     {

          $db = \Config\Database::connect();

          // Mengambil data perusahaan
          $query = $db->table('tb_perusahaan')->get();
          $from = $query->getRowArray();

          // Mengambil data invoice
          $head = $this->invoiceModel->getDataInvoices($id);
          $date = date('d-m-Y', strtotime($head['issue_date']));

          // Mengambil detail pengiriman
          $items = $this->pengirimanModel->getDetailInvoices($id);
          $pdf = new Fpdf_lib();

          $pdf->SetTitle('Invoice Document');
          $pdf->SetAuthor('Diki Romadoni');
          $pdf->AddPage('P', 'A4');
          $pdf->Header();
          $pdf->SetDrawColor(31, 49, 111);
          $pdf->addWatermark($head['status']);

          // Tambahkan watermark LUNAS
          // if($head['status'] == "Paid"){
          // }
          // $pdf->addWatermark($head['status']);
          $pdf->SetTextColor(0, 0, 0);

          $pdf->SetFont('Arial', 'B', 12);
          $pdf->fact_dev("#{$head['invoice_number']}", "");
          $pdf->addDate($date);

          $pdf->SetFont('Arial', '', 10);
          $pdf->addClient("CL{$head['id_pelanggan']}");
          $pdf->addPageNumber($pdf->PageNo());

          $pdf->SetFont('Arial', '', 10);
          $pdf->addBillFrom("Bill From : \n" . $from['nama'] . "\n" . $from['alamat'] . "");
          $pdf->addBillTo("Bill To : \n" . $head['nama_pelanggan'] . "\n" . $head['alamat_pelanggan'] . "");

          $pdf->SetFont('Arial', '', 8);
          $cols = array(
               "DATE"    => 19,
               "NO AWB"    => 15,
               "DESCRIPTION"  => 35,
               "WEIGHT"     => 15,
               "VOLUME"     => 15,
               "PRICE"      => 19,
               "SURCHARGE"      => 20,
               "PACKING"      => 15,
               "SERVICE"      => 15,
               "TOTAL" => 22,
          );
          $pdf->addCols($cols);

          $cols = array(
               "DATE"    => "L",
               "NO AWB"    => "L",
               "DESCRIPTION"  => "L",
               "WEIGHT"     => "C",
               "VOLUME"     => "C",
               "PRICE"      => "C",
               "SURCHARGE"      => "C",
               "PACKING"      => "C",
               "SERVICE"      => "C",
               "TOTAL" => "C",
          );
          $pdf->addLineFormat($cols);

          $y    = 95;
          $total    = 0;
          $pdf->SetFont('Arial', '', 8);
          foreach ($items as $item) {
               $layanan_explode = explode('/', $item['layanan']);
               $date = date('d-m-Y', strtotime($item['tanggal_order']));
               $total += $item['total_cost'];
               $berat = $item['total_hitung'];
               $line = array(
                    "DATE"    => "{$date}",
                    "NO AWB"    => "{$item['no_surat_jalan']}",
                    "DESCRIPTION"  => "{$item['dest']}",
                    "WEIGHT"     => $berat,
                    "VOLUME"     => $item['total_volume'],
                    "PRICE"      => format_rupiah($item['cost']),
                    "SURCHARGE"      => ($item['surcharge']) ? format_rupiah($item['surcharge']) : 0,
                    "PACKING"      => format_rupiah($item['total_biaya_packing']),
                    "SERVICE"      => $layanan_explode[0],
                    "TOTAL" => format_rupiah($item['total_cost'])
               );

               $size = $pdf->addLine($y, $line);
               $y   += $size + 4;

               if ($pdf->getY() <= 230) {
                    $pdf->Line(10, $y - 3, 200, $y - 3);
               }

               if ($pdf->getY() > 230) {
                    $y   = 95;
                    $pdf->AddPage();
                    $date = date('d-m-Y', strtotime($head['issue_date']));
                    $pdf->addWatermark($head['status']);
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetFont('Arial', 'B', 12);
                    $pdf->fact_dev("#{$head['invoice_number']}", "");
                    $pdf->addDate("{$date}");

                    $pdf->SetFont('Arial', '', 10);
                    $pdf->addClient("CL{$head['id_pelanggan']}");
                    $pdf->addPageNumber($pdf->PageNo());

                    $pdf->SetFont('Arial', '', 10);
                    $pdf->addBillFrom("Bill From : \n" . $from['nama'] . "\n" . $from['alamat'] . "");
                    $pdf->addBillTo("Bill To : \n" . $head['nama_pelanggan'] . "\n" . $head['alamat_pelanggan'] . "");

                    $pdf->SetFont('Arial', '', 8);
                    $cols = array(
                         "DATE"    => 19,
                         "NO AWB"    => 15,
                         "DESCRIPTION"  => 35,
                         "WEIGHT"     => 15,
                         "VOLUME"     => 15,
                         "PRICE"      => 19,
                         "SURCHARGE"      => 20,
                         "PACKING"      => 15,
                         "SERVICE"      => 15,
                         "TOTAL" => 22,
                    );
                    $pdf->addCols($cols);

                    $cols = array(
                         "DATE"    => "L",
                         "NO AWB"    => "L",
                         "DESCRIPTION"  => "L",
                         "WEIGHT"     => "C",
                         "VOLUME"     => "C",
                         "PRICE"      => "C",
                         "SURCHARGE"      => "C",
                         "PACKING"      => "C",
                         "SERVICE"      => "C",
                         "TOTAL" => "C",
                    );
                    $pdf->addLineFormat($cols);
               }
          }


          $pdf->SetFont('Arial', '', 10); // Ukuran font 10 untuk subtotal dan catatan
          $pdf->noteInvoice("Pembayaran Via Transfer :\nREK BCA 7600278473\nAtas Nama PT. WAHANA ELANGCARGO PERKASA");
          $pdf->ttd("Wardjoyo Hasan");
          $pdf->subTotal($total, $head['ppn'], $head['pph']);

          $pdf->Output();
          exit;
     }
     public function generate_excel($id)
     {
          $db = \Config\Database::connect();

          // Mengambil data perusahaan
          $query = $db->table('tb_perusahaan')->get();
          $from = $query->getRowArray();

          // Mengambil data invoice
          $head = $this->invoiceModel->getDataInvoices($id);
          $date = date('d-m-Y', strtotime($head['issue_date']));

          // Mengambil detail pengiriman
          $items = $this->pengirimanModel->getDetailInvoices($id);
          // Membuat instance Spreadsheet
          $spreadsheet = new Spreadsheet();

          // Style
          $border = [
               'borders' => [
                    'allBorders' => [ // Huruf B besar di sini
                         'style' => Border::BORDER_THIN,
                         'color' => ['argb' => 'FF000000'], // Warna hitam
                    ],
               ],
          ];


          $text_right = [
               'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_RIGHT,
               ],
          ];

          $font_header = [
               'font' => [
                    'bold' => true,
                    'size' => 14,
                    'color' => ['argb' => 'FF000000'],
               ],
          ];

          $font_invoice = [
               'font' => [
                    'bold' => true,
                    'size' => 20,
                    'color' => ['argb' => 'FF000000'],
               ],
               'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
               ],
          ];

          $background = [
               'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFFFFFFF'],
               ],
          ];

          $sheet = $spreadsheet->setActiveSheetIndex(0);

          // Memasukkan gambar
          $imagePath = 'assets/img/logo-rhl.png'; // Ganti dengan path gambar yang sesuai
          $drawing = new Drawing();
          $drawing->setName('Logo')
               ->setPath($imagePath)
               ->setHeight(100)
               ->setCoordinates('A1')
               ->setWorksheet($sheet);

          // NO INVOICE
          $sheet->mergeCells('F2:J3');
          $sheet->setCellValue('F2', 'INVOICE');
          $spreadsheet->getActiveSheet()->getStyle('F2')->applyFromArray($font_invoice);

          $sheet->mergeCells('F4:J4');
          $sheet->setCellValue('F4', '#' . $head['invoice_number']);
          $spreadsheet->getActiveSheet()->getStyle('F4')->applyFromArray($font_invoice);

          // HEADER
          $sheet->mergeCells('A7:E7');
          $sheet->mergeCells('F7:J7');

          $sheet->mergeCells('A8:E8');
          $sheet->mergeCells('F8:J8');
          $sheet->getStyle('A8')->getAlignment()->setWrapText(true);
          $sheet->getStyle('F8')->getAlignment()->setWrapText(true);

          $sheet->setCellValue('A6', 'Bill From :');
          $sheet->setCellValue('A7', $from['nama']);
          $sheet->setCellValue('A8', $from['alamat']);

          $sheet->setCellValue('F6', 'Bill To :');
          $sheet->setCellValue('F7', $head['nama_pelanggan']);
          $sheet->setCellValue('F8', $head['alamat_pelanggan']);

          $spreadsheet->getActiveSheet()->getStyle('A7')->applyFromArray($font_header);
          $spreadsheet->getActiveSheet()->getStyle('F7')->applyFromArray($font_header);
          $spreadsheet->getActiveSheet()->getStyle('F7')->applyFromArray($text_right);
          $spreadsheet->getActiveSheet()->getStyle('F8')->applyFromArray($text_right);

          // background
          $spreadsheet->getActiveSheet()->getStyle('A1:J10')->applyFromArray($background);

          // Tambahkan data ke sheet
          $spreadsheet->setActiveSheetIndex(0)
               ->setCellValue('A11', 'Date')
               ->setCellValue('B11', 'No AWB')
               ->setCellValue('C11', 'Description')
               ->setCellValue('D11', 'Weight')
               ->setCellValue('E11', 'Volume')
               ->setCellValue('F11', 'Price')
               ->setCellValue('G11', 'Surcharge')
               ->setCellValue('H11', 'Packing')
               ->setCellValue('I11', 'Service')
               ->setCellValue('J11', 'Total');

          $ppn = 'N/A';
          $pph = 'N/A';

          // Isi data ke dalam Excel
          $row = 12;
          $sub_total = 0;
          foreach ($items as $value) {
               $sub_total += $value['total_cost'];
               $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $row, $value['tanggal_order'])
                    ->setCellValue('B' . $row, $value['no_surat_jalan'])
                    ->setCellValue('C' . $row, $value['dest'])
                    ->setCellValue('D' . $row, $value['total_hitung'])
                    ->setCellValue('E' . $row, $value['total_volume'])
                    ->setCellValue('F' . $row, $value['cost'])
                    ->setCellValue('G' . $row, $value['surcharge'])
                    ->setCellValue('H' . $row, $value['total_biaya_packing'])
                    ->setCellValue('I' . $row, $value['layanan'])
                    ->setCellValue('J' . $row, $value['total_cost']);
               $row++;
          }

          if ($head['ppn'] == 1) {
               $ppn = round($sub_total * 1.1 / 100);
          }
          if ($head['pph'] == 2) {
               $pph = round($sub_total * 2 / 100);
          }

          $new_row = $row;

          // TOTAL
          $sheet->mergeCells('A' . $new_row . ':I' . $new_row);
          $sheet->setCellValue('A' . $new_row, 'Sub Total');
          $sheet->setCellValue('J' . $new_row, $sub_total);
          $spreadsheet->getActiveSheet()->getStyle('A' . $new_row)->applyFromArray($text_right);

          // PPN
          $sheet->mergeCells('A' . ($new_row + 1) . ':I' . ($new_row + 1));
          $sheet->setCellValue('A' . ($new_row + 1), 'PPN 1,1%');
          $sheet->setCellValue('J' . ($new_row + 1), $ppn);
          $spreadsheet->getActiveSheet()->getStyle('A' . $new_row + 1)->applyFromArray($text_right);

          // PPH
          $sheet->mergeCells('A' . ($new_row + 2) . ':I' . ($new_row + 2));
          $sheet->setCellValue('A' . ($new_row + 2), 'PPH 2%');
          $sheet->setCellValue('J' . ($new_row + 2), $pph);
          $spreadsheet->getActiveSheet()->getStyle('A' . $new_row + 2)->applyFromArray($text_right);

          // GRAND TOTAL
          $sheet->mergeCells('A' . ($new_row + 3) . ':I' . ($new_row + 3));
          $sheet->setCellValue('A' . ($new_row + 3), 'GRAND TOTAL');
          $sheet->setCellValue('J' . ($new_row + 3), $head['total_amount']);
          $spreadsheet->getActiveSheet()->getStyle('A' . $new_row + 3)->applyFromArray($text_right);

          // Mengubah nama sheet
          $spreadsheet->getActiveSheet()->setTitle('Sheet1');

          // Set aktif sheet index ke sheet pertama
          $spreadsheet->setActiveSheetIndex(0);

          // Menerapkan gaya border pada sel
          $spreadsheet->getActiveSheet()->getStyle('A11:J' . ($row - 1))->applyFromArray($border);

          // Proses output file Excel
          header('Content-Type: application/vnd.ms-excel');
          header('Content-Disposition: attachment;filename="' . $head['invoice_number'] . '.xls"');
          header('Cache-Control: max-age=0');

          $writer = new Xls($spreadsheet); // Menggunakan writer dengan format Excel 2003 (.xls)
          $writer->save('php://output');
          exit;
     }
}
