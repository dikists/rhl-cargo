<?php

namespace App\Controllers;

use App\Models\InvoiceModel;
use App\Models\PengirimanModel;
use App\Models\DetailInvoiceModel;
use App\Controllers\BaseController;
use App\Models\TransactionExtraChargeModel;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Format;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ExcelController extends BaseController
{
    protected $invoiceModel;
    protected $pengirimanModel;
    protected $detailInvoiceModel;
    protected $addCost;
    public function __construct()
    {
        $this->invoiceModel = new InvoiceModel();
        $this->pengirimanModel = new PengirimanModel();
        $this->detailInvoiceModel = new DetailInvoiceModel();
        $this->addCost = new TransactionExtraChargeModel();
    }
    public function exportInvoice($id)
    {
        $db = \Config\Database::connect();

        // Mengambil data perusahaan
        $query = $db->table('tb_perusahaan')->get();
        $from = $query->getRowArray();

        // Mengambil data invoice
        $head = $this->invoiceModel->getInvoices($id);
        $date = date('d-m-Y', strtotime($head['issue_date']));

        // Mengambil detail pengiriman
        // $items = $this->pengirimanModel->getDetailInvoices($id);
        $items = $this->detailInvoiceModel->getByInvoice($id);
        // echo "<pre>";
        // print_r($items);
        // echo "</pre>";
        // die;
        // Membuat instance Spreadsheet
        $spreadsheet = new Spreadsheet();

        // Style
        $border = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000019'],
                ],

            ],
        ];

        $text_right = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
            ],
        ];

        $text_center = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
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
        $imagePath = getenv('COMPANY_LOGO_TEXT'); // Ganti dengan path gambar yang sesuai
        $drawing = new Drawing();
        $drawing->setName('Logo')
            ->setPath($imagePath)
            ->setHeight(100)
            ->setCoordinates('A1')
            ->setWorksheet($sheet);

        // NO INVOICE
        $sheet->mergeCells('E2:H3');
        $sheet->setCellValue('E2', 'INVOICE');
        $spreadsheet->getActiveSheet()->getStyle('E2')->applyFromArray($font_invoice);

        $sheet->mergeCells('E4:H4');
        $sheet->setCellValue('E4', '#' . $head['invoice_number']);
        $spreadsheet->getActiveSheet()->getStyle('E4')->applyFromArray($font_invoice);

        // HEADER
        $sheet->mergeCells('A7:D7');
        $sheet->mergeCells('E7:H7');

        $sheet->mergeCells('A8:D8');
        $sheet->mergeCells('E8:H8');
        $sheet->getStyle('A8')->getAlignment()->setWrapText(true);
        $sheet->getStyle('E8')->getAlignment()->setWrapText(true);

        $sheet->setCellValue('A6', 'Bill From :');
        $sheet->setCellValue('A7', $from['nama']);
        $sheet->setCellValue('A8', $from['alamat']);

        $sheet->setCellValue('E6', 'Bill To :');
        $sheet->setCellValue('E7', $head['nama_pelanggan']);
        $sheet->setCellValue('E8', $head['alamat_pelanggan']);

        $spreadsheet->getActiveSheet()->getStyle('A7')->applyFromArray($font_header);
        $spreadsheet->getActiveSheet()->getStyle('E7')->applyFromArray($font_header);
        $spreadsheet->getActiveSheet()->getStyle('E7')->applyFromArray($text_right);
        $spreadsheet->getActiveSheet()->getStyle('E8')->applyFromArray($text_right);

        $sheet->setCellValue('A10', 'Tanggal : ' . $date);

        // background
        $spreadsheet->getActiveSheet()->getStyle('A1:H10')->applyFromArray($background);

        // Tambahkan data ke sheet
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A11', 'Date')
            ->setCellValue('B11', 'No AWB')
            ->setCellValue('C11', 'Description')
            ->setCellValue('D11', 'Weight')
            ->setCellValue('E11', 'Price')
            ->setCellValue('F11', 'Biaya Lain')
            ->setCellValue('G11', 'Service')
            ->setCellValue('H11', 'Total');

        $spreadsheet->getActiveSheet()->getStyle('A11:H11')->applyFromArray($text_center);

        // Isi data ke dalam Excel
        $row = 12;
        $sub_total = 0;
        foreach ($items as $value) {
            $extraCharge = $this->addCost->extraCharge($value['id_pengiriman']);
            // $biaya_lain = '';
            $total_cost = (int)$value['price'];
            if ($value['bill_type'] === 'reguler') {
                $total_cost = (int)$value['berat'] * (int)$value['price'];
            }

            $biaya_lain_arr = [];
            $biaya_lain_arr_total = [];

            if ((int)$value['surcharge'] > 0) {
                $biaya_lain_arr[] = 'Surcharge : ' . (int)$value['surcharge'];
                $biaya_lain_arr_total[] = (int)$value['surcharge'];
            }

            if ((int)$value['biaya_packing'] > 0) {
                $biaya_lain_arr[] = 'Packing : ' . (int)$value['biaya_packing'];
                $biaya_lain_arr_total[] = (int)$value['biaya_packing'];
            }

            if ((int)$value['insurance'] > 0) {
                $biaya_lain_arr[] = 'Asuransi : ' . (int)$value['insurance'];
                $biaya_lain_arr_total[] = (int)$value['insurance'];
            }

            foreach ($extraCharge as $extra) {
                $biaya_lain_arr[] = $extra['jenis_biaya'] . ' : ' . formatRupiah($extra['charge_value']);
                $biaya_lain_arr_total[] = $extra['charge_value'];
            }

            $biaya_lain = !empty($biaya_lain_arr) ? implode("\n", $biaya_lain_arr) : '-';
            $biaya_lain_total = array_sum($biaya_lain_arr_total);

            $total_cost = $total_cost + $biaya_lain_total;

            $sub_total += $total_cost;

            $data = [
                'A' => date('d-m-Y', strtotime($value['tanggal_order'])),
                'B' => $value['no_surat_jalan'],
                'C' => $value['nama_penerima'],
                'D' => $value['berat'],
                'E' => $value['price'],
                'F' => $biaya_lain,
                'G' => $value['layanan'],
                'H' => $total_cost,
            ];

            foreach ($data as $col => $val) {
                $sheet->setCellValue($col . $row, $val);

                $sheet->getStyle($col . $row)->applyFromArray($text_center);
                if ($col == 'H') {
                    $sheet->getStyle($col . $row)->applyFromArray($text_right);
                }

                // Format wrap text untuk kolom F (biaya lainnya)
                if ($col === 'F') {
                    $sheet->getStyle($col . $row)->getAlignment()->setWrapText(true);
                }

                // Format rupiah untuk kolom E, G, I
                if (in_array($col, ['E', 'H'])) {
                    $sheet->getStyle($col . $row)->getNumberFormat()->setFormatCode('"Rp" #,##0');
                }
            }

            $row++;
        }

        $ppn = round($sub_total * $head['ppn'] / 100);
        $pph = round($sub_total * $head['pph'] / 100);
        $new_row = $row;

        // Data total summary
        $summaryRows = [
            ['label' => 'Sub Total', 'value' => $sub_total],
            ['label' => 'PPN ' . $head['ppn'], 'value' => $ppn],
            ['label' => 'PPH ' . $head['pph'], 'value' => $pph],
            ['label' => 'GRAND TOTAL', 'value' => $sub_total + $ppn - $pph],
        ];

        // Loop untuk baris total
        foreach ($summaryRows as $index => $data) {
            $rowIndex = $new_row + $index;
            $sheet->mergeCells("A{$rowIndex}:G{$rowIndex}");
            $sheet->setCellValue("A{$rowIndex}", $data['label']);
            $sheet->setCellValue("H{$rowIndex}", $data['value']);

            $style = $spreadsheet->getActiveSheet()->getStyle("A{$rowIndex}");
            $style->applyFromArray($text_right);

            $spreadsheet->getActiveSheet()->getStyle("H{$rowIndex}")
                ->getNumberFormat()->setFormatCode('"Rp" #,##0');
        }

        // Catatan dan info bank
        $notes = [
            'Note: ' . $head['notes'] ?? '####',
            'Pembayaran Via Transfer :',
            'REK ' . $head['bank_name'] . ' - ' . $head['bank_number'],
            'Atas Nama ' . $head['holder_name'],
        ];

        foreach ($notes as $i => $text) {
            $rowIndex = $new_row + 8 + $i;
            $sheet->mergeCells("A{$rowIndex}:D{$rowIndex}");
            $sheet->setCellValue("A{$rowIndex}", $text);
        }

        $sheet->mergeCells("E{$rowIndex}:H{$rowIndex}");
        $sheet->setCellValue("E{$rowIndex}", $head['signatory']);
        $sheet->getStyle("E{$rowIndex}")->applyFromArray($text_center);


        // Mengubah nama sheet
        $spreadsheet->getActiveSheet()->setTitle('Sheet1');

        // Set aktif sheet index ke sheet pertama
        $spreadsheet->setActiveSheetIndex(0);

        // Menerapkan gaya border pada sel
        $spreadsheet->getActiveSheet()->getStyle('A11:H' . ($row + 3))->applyFromArray($border);

        // Proses output file Excel
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $head['invoice_number'] . '.xls"');
        header('Cache-Control: max-age=0');

        $writer = new Xls($spreadsheet); // Menggunakan writer dengan format Excel 2003 (.xls)
        $writer->save('php://output');
        exit;
    }
    public function laporan_invoice()
    {
        // ambil data
        $get = $this->request->getGet() ?? [];
        $data = $this->invoiceModel->getInvoices(null, $get);
        $start = $this->request->getGet('date_start');
        $end = $this->request->getGet('date_end');

        // buat spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->setActiveSheetIndex(0);

        // Memasukkan gambar
        $imagePath = getenv('COMPANY_LOGO_TEXT'); // Ganti dengan path gambar yang sesuai
        $drawing = new Drawing();
        $drawing->setName('Logo')
            ->setPath($imagePath)
            ->setHeight(70)
            ->setCoordinates('A1')
            ->setWorksheet($sheet);

        // Header judul
        $title = 'Laporan Invoice';
        $sheet->setCellValue('E1', $title);
        $sheet->mergeCells('E1:K1');
        $sheet->getStyle('E1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('E1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('E2', 'Periode ' . date('d-m-Y', strtotime($start)) . ' - ' . date('d-m-Y', strtotime($end)));
        $sheet->mergeCells('E2:K2');
        $sheet->getStyle('E2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('E2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Header tabel
        $header = [
            'No',
            'Tanggal',
            'Invoice',
            'No Faktur',
            'Bill To',
            'Total',
            'PPN',
            'PPH',
            'Grand Total',
            'Status',
            'Note'
        ];

        $sheet->fromArray($header, null, 'A4');

        // Isi data
        $no = 1;
        $rowIndex = 5;
        $total_paid = 0;
        $total_unpaid = 0;

        foreach ($data as $item) {
            $total = $item['total_amount'];
            $ppn = $item['ppn'];
            $pph = $item['pph'];
            $totalppn = $total * $ppn / 100;
            $totalpph = $total * $pph / 100;
            $grand_total = $total + $totalppn - $totalpph;

            if (strtolower($item['status']) == 'paid') {
                $total_paid += $grand_total;
            } else {
                $total_unpaid += $grand_total;
            }

            $sheet->fromArray([
                $no++,
                $item['issue_date'],
                $item['invoice_number'],
                $item['tax_invoice_number'],
                $item['nama_pelanggan'],
                $total,
                $totalppn,
                $totalpph,
                $grand_total,
                $item['status'],
                $item['notes']
            ], null, 'A' . $rowIndex);

            $rowIndex++;
        }

        // Tambah total summary
        $sheet->setCellValue('J' . ($rowIndex + 1), 'Total Paid:');
        $sheet->setCellValue('K' . ($rowIndex + 1), $total_paid);
        $sheet->getStyle('K' . ($rowIndex + 1))->getNumberFormat()->setFormatCode('"Rp" #,##0');

        $sheet->setCellValue('J' . ($rowIndex + 2), 'Total Unpaid:');
        $sheet->setCellValue('K' . ($rowIndex + 2), $total_unpaid);
        $sheet->getStyle('K' . ($rowIndex + 2))->getNumberFormat()->setFormatCode('"Rp" #,##0');

        $sheet->getStyle('J' . ($rowIndex + 1) . ':K' . ($rowIndex + 2))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'font' => [
                'bold' => true,
                'size' => 13,
            ],
        ]);

        // Styling border
        $lastDataRow = $rowIndex - 1;
        $sheet->getStyle("A4:K$lastDataRow")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ]
        ]);

        // Format kolom tertentu sebagai rupiah
        foreach (['F', 'G', 'H', 'I'] as $col) {
            $sheet->getStyle("{$col}4:{$col}$lastDataRow")
                ->getNumberFormat()
                ->setFormatCode('"Rp" #,##0');
        }

        // Auto width kolom
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Export sebagai file Excel
        $filename = 'Laporan-Invoice-' . date('Ymd') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
