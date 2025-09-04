<?php

namespace App\Controllers;

// use CoretaxParser;
use App\Libraries\Fpdf_lib;
use App\Models\InvoiceModel;
use App\Models\PelangganModel;
use CodeIgniter\HTTP\Response;
use App\Models\ReceivableModel;
use App\Libraries\CoreTaxParser;
use App\Models\DetailInvoiceModel;
use App\Models\ReceiptExportModel;
use App\Models\ReceivablePaymentModel;
use App\Models\TransactionExtraChargeModel;

class InvoiceController extends BaseController
{
    protected $invoiceModel;
    protected $detailInvoiceModel;
    protected $pelangganModel;
    protected $receivableModel;
    protected $receivablePaymentModel;
    protected $receiptExportModel;
    protected $addCost;

    public function __construct()
    {
        $this->invoiceModel = new InvoiceModel();
        $this->detailInvoiceModel = new DetailInvoiceModel();
        $this->pelangganModel = new PelangganModel();
        $this->receivableModel = new ReceivableModel();
        $this->receivablePaymentModel = new ReceivablePaymentModel();
        $this->receiptExportModel = new ReceiptExportModel();
        $this->addCost = new TransactionExtraChargeModel();
    }

    // Method untuk menampilkan semua data invoice
    public function index()
    {
        $data['invoices'] = $this->invoiceModel->getInvoices();
        return view('invoices/index', $data);
    }

    // Method untuk menampilkan detail invoice berdasarkan ID
    public function show($id)
    {
        $data['invoice'] = $this->invoiceModel->getInvoices($id);

        if (empty($data['invoice'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Invoice tidak ditemukan dengan ID: ' . $id);
        }

        return view('invoices/show', $data);
    }

    // Method untuk menambah invoice baru
    public function create()
    {
        $bln   = getRomawiBulan();
        $no    = ($this->invoiceModel->getLastInvoiceNumberOnly())
            ? $this->invoiceModel->getLastInvoiceNumberOnly()
            : array('last_number' => 0);

        $tahun = date('Y');

        // pastikan integer
        $lastNumber = (int) $no['last_number'];

        $invoice_number = sprintf("%03d", $lastNumber + 1) . '/' . $bln . '/' . $tahun;


        // Mendapatkan top pelanggan
        $pelanggan = $this->pelangganModel->getPelanggan($this->request->getPost('pelanggan_id'));
        $top = ($pelanggan) ? $pelanggan['top'] : 0;

        $issueDateStr = $this->request->getPost('issue_date'); // Misalnya: '2025-06-09'
        $dueDate = new \DateTime($issueDateStr);
        $dueDate->modify("+{$top} days"); // Tambah top (misalnya 30 hari)

        $data = [
            'invoice_number'        => $invoice_number,
            'id_pelanggan'          => $this->request->getPost('pelanggan_id'),
            'account_id'          => $this->request->getPost('no_rekening'),
            'issue_date'            => $issueDateStr,
            'due_date'              => $dueDate->format('Y-m-d'),
            'tax_invoice_number'    => $this->request->getPost('tax_invoice_number'),
            'rtax_id'               => $this->request->getPost('rtax') ?: null,
            'rtaxpph_id'            => $this->request->getPost('rtax_pph') ?: null,
            'notes'            => $this->request->getPost('notes'),
            'status'                => 'Pending'
        ];

        $dataReceivables = [
            'customer_id' => $this->request->getPost('pelanggan_id'),
            'invoice_number' => $invoice_number,
            'invoice_date' => $this->request->getPost('issue_date'),
            'due_date' => $dueDate->format('Y-m-d'),
            'description' => $this->request->getPost('notes')
        ];

        // Insert data ke database
        $this->receivableModel->insert($dataReceivables);

        $this->generateJurnalPiutang();

        $insert = $this->invoiceModel->insert($data);

        if ($insert) {
            return redirect()->to('admin/invoice/')->with('success', 'Data berhasil disimpan.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal mengubah data.');
        }
    }

    // Method untuk mengupdate invoice berdasarkan ID
    public function update($id)
    {
        $data = [
            'id_pelanggan'          => $this->request->getPost('pelanggan_id'),
            'account_id'            => $this->request->getPost('no_rekening'),
            'issue_date'            => $this->request->getPost('issue_date'),
            'tax_invoice_number'    => $this->request->getPost('tax_invoice_number'),
            'rtax_id'               => $this->request->getPost('rtax') ?: null,
            'rtaxpph_id'            => $this->request->getPost('rtax_pph') ?: null,
            'notes'            => $this->request->getPost('notes'),
        ];

        $update = $this->invoiceModel->update($id, $data);

        if ($update) {
            return redirect()->to('admin/invoice/')->with('success', 'Data berhasil diubah.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal mengubah data.');
        }
    }

    // Method untuk menghapus invoice
    public function delete($id)
    {
        $update = $this->invoiceModel->delete($id);
        if ($update) {
            return redirect()->to('admin/invoice/')->with('success', 'Data berhasil dihapus.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menghapus data.');
        }
    }

    public function getInvoice()
    {
        $get = $this->request->getGet() ?? [];
        $data = $this->invoiceModel->getInvoices(null, $get);

        if ($this->request->getGet('type') == 'export_coretax') {
            return $this->response->setJSON([
                'rows' => $data
            ]);
            exit;
        }

        foreach ($data as $i => $item) {
            $hapus  = $status = '';
            $linkpdf = '/admin/invoice/' . $item['id'] . '/print';
            $linkpdf2 = '/admin/invoice/' . $item['id'] . '/print-02';
            if ($item['jumlah_detail'] == 0) {
                $hapus = '<a class="dropdown-item" href="/admin/invoice/' . $item['id'] . '/delete" onclick="return confirm(\'Apakah anda yakin?\')"><i class="fa fa-trash mr-2"></i> Delete</a> ';
            }

            // DETAIL
            $detail = '<a class="dropdown-item" href="/admin/invoice/' . $item['id'] . '/detail"><i class="fa fa-info mr-3"> Detail (' . $item['jumlah_detail'] . ')</i></a>';
            // EXCEL
            $excel = '<a class="dropdown-item" target="_blank" href="/admin/invoice/' . $item['id'] . '/excel"><i class="fa fa-file-excel mr-2 text-success"> Export Excel</i></a>';

            if ($item['source'] == 'Offer') {
                $linkpdf = '/admin/invoice_offer/' . $item['id'] . '/invoice/print';
                $linkpdf2 = '/admin/invoice_offer/' . $item['id'] . '/debit_note/print';
                $detail = '';
                $excel = '';
                $hapus = '';
            }
            $cetak = '
            <div class="dropdown">
            <button class="btn btn-danger btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-file-pdf"></i> PDF
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" target="_blank" href="' . $linkpdf . '">PDF 01</a>
                <a class="dropdown-item" target="_blank" href="' . $linkpdf2 . '">PDF 02</a>
            </div>
            </div>
            ';
            if ($item['status'] == 'Pending') {
                $status = '<a class="dropdown-item" href="/admin/invoice/' . $item['id'] . '/bayar"><i class="fa fa-credit-card mr-2"> Bayar</i></a>';
            }
            $data[$i]['no'] = $i + 1;
            $data[$i]['aksi'] = '<div class="btn-group dropleft">
                                            <a href="#" data-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-ellipsis-v fa-2x" aria-hidden="true"></i>
                                            </a>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" target="_blank" href="' . $linkpdf . '"><i class="fa fa-file-pdf mr-2 text-danger"> PDF 01</i></a>
                                                <a class="dropdown-item" target="_blank" href="' . $linkpdf2 . '"><i class="fa fa-file-pdf mr-2 text-danger"> PDF 02</i></a>
                                                ' . $excel . '
                                                ' . $detail . '
                                                <a class="dropdown-item" href="/admin/invoice/' . $item['id'] . '"><i class="fa fa-edit mr-2"> Edit</i></a>
                                                ' . $status . '
                                                ' . $hapus . '
                                            </div>
                                    </div>
                                ';
            $role = session()->get('role');
            if ($role == 'PIC RELASI') {
                $data[$i]['aksi'] = $cetak;
            }
        }
        return $this->response->setJSON($data);
    }

    public function printInvoice($id)
    {

        $db = \Config\Database::connect();

        // Mengambil data perusahaan
        $query = $db->table('tb_perusahaan')->get();
        $from = $query->getRowArray();

        // Mengambil data invoice
        $head = $this->invoiceModel->getInvoices($id);
        $date = date('d-m-Y', strtotime($head['issue_date']));

        // Mengambil detail pengiriman
        $items = $this->detailInvoiceModel->getByInvoice($id);
        $pdf = new Fpdf_lib();

        $pdf->SetTitle('INV_' . $head['invoice_number']);
        $pdf->SetAuthor('Diki Romadoni');
        $pdf->AddPage('P', 'A4');
        $pdf->Header();
        $pdf->SetDrawColor(31, 49, 111);
        //  $pdf->addWatermark($head['status']);

        // Tambahkan watermark LUNAS
        if ($head['status'] == "Paid") {
            $pdf->addWatermark($head['status']);
        }
        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->fact_dev("#{$head['invoice_number']}", "");

        $pdf->SetFont('Arial', '', 10);
        $pdf->addPageNumber($pdf->PageNo());
        $pdf->addDate($date);
        $pdf->addClient("CL{$head['id_pelanggan']}");

        $pdf->SetFont('Arial', '', 10);
        $pdf->addBillFrom("Bill From : \n" . $from['nama'] . "\n" . $from['alamat'] . "");
        $pdf->addBillTo("Bill To : \n" . $head['nama_pelanggan'] . "\n" . $head['alamat_pelanggan'] . "");

        $pdf->SetFont('Arial', '', 8);
        $cols = [
            "DATE"        => 17,
            "NO AWB"      => 15,
            "DESCRIPTION" => 39,
            "WEIGHT"      => 14,
            "PRICE"       => 22,
            // "SURCHARGE"   => 20,
            "BIAYA LAIN"     => 30,
            "SERVICE"     => 28,
            "TOTAL"       => 25,
        ];
        $pdf->addCols($cols);

        $cols = [
            "DATE"            => "C",
            "NO AWB"          => "C",
            "DESCRIPTION"     => "C",
            "WEIGHT"          => "C",
            "PRICE"           => "C",
            // "SURCHARGE"       => "C",
            "BIAYA LAIN"      => "C",
            "SERVICE"         => "C",
            "TOTAL"           => "C",
        ];
        $pdf->addLineFormat($cols);

        $y     = 73;
        $total = 0;
        $tot   = 0;
        $pdf->SetFont('Arial', '', 7);
        foreach ($items as $item) {
            $extraCharge = $this->addCost->extraCharge($item['id_pengiriman']);
            $date = date('d-m-Y', strtotime($item['tanggal_order']));
            $tot = ((int)$item['berat']) * ((int)$item['price']) + (int)$item['biaya_packing'] + (int)$item['surcharge'] + (int)$item['insurance'];
            if ($item['bill_type'] == 'flat') {
                $tot = (int)$item['price'];
            }
            $biaya_lain_arr = [];
            $biaya_lain_arr_total = [];

            if ($item['biaya_packing'] > 0) {
                array_push($biaya_lain_arr, "Packing");
                array_push($biaya_lain_arr_total, $item['biaya_packing']);
            }
            if ($item['surcharge'] > 0) {
                array_push($biaya_lain_arr, "Surcharge");
                array_push($biaya_lain_arr_total, $item['surcharge']);
            }
            if ($item['insurance'] > 0) {
                array_push($biaya_lain_arr, "Insurance");
                array_push($biaya_lain_arr_total, $item['insurance']);
            }

            foreach ($extraCharge as $extra) {
                $biaya_lain_arr[] = $extra['jenis_biaya'] . ' : ' . formatRupiah($extra['charge_value']);
                $biaya_lain_arr_total[] = $extra['charge_value'];
            }

            $biaya_lain = !empty($biaya_lain_arr) ? implode("\n", $biaya_lain_arr) : '-';
            $biaya_lain_total = array_sum($biaya_lain_arr_total);

            $tot = $tot + $biaya_lain_total;

            $total += $tot;
            $line = array(
                "DATE"           => "{$date}",
                "NO AWB"         => "{$item['no_surat_jalan']}",
                "DESCRIPTION"    => "{$item['nama_penerima']}",
                "WEIGHT"         => round($item['berat']),
                "PRICE"          => format_rupiah($item['price']),
                // "SURCHARGE"      => format_rupiah((int)$item['surcharge']),
                "BIAYA LAIN"     => $biaya_lain,
                "SERVICE"        => $item['layanan'],
                "TOTAL"          => format_rupiah($tot)
            );

            $size = $pdf->addLine($y, $line);
            $y   += $size + 4;

            if ($pdf->getY() <= 250) {
                $pdf->Line(10, $y - 3, 200, $y - 3);
            }

            if ($pdf->getY() > 250) {
                $y   = 73;
                $pdf->AddPage();
                $date = date('d-m-Y', strtotime($head['issue_date']));
                // $pdf->addWatermark($head['status']);
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
                $cols = [
                    "DATE"        => 17,
                    "NO AWB"      => 15,
                    "DESCRIPTION" => 39,
                    "WEIGHT"      => 14,
                    "PRICE"       => 22,
                    "SURCHARGE"   => 20,
                    "PACKING"     => 18,
                    "SERVICE"     => 20,
                    "TOTAL"       => 25,
                ];
                $pdf->addCols($cols);

                $cols = [
                    "DATE"            => "C",
                    "NO AWB"          => "C",
                    "DESCRIPTION"     => "C",
                    "WEIGHT"          => "C",
                    "PRICE"           => "C",
                    "SURCHARGE"       => "C",
                    "PACKING"         => "C",
                    "SERVICE"         => "C",
                    "TOTAL"           => "C",
                ];
                $pdf->addLineFormat($cols);
            }
            $pdf->SetFont('Arial', '', 7);
        }


        $pdf->SetFont('Arial', '', 9); // Ukuran font 10 untuk subtotal dan catatan
        $pdf->noteInvoice("Note : " . $head['notes'] . "\nPembayaran Via Transfer :\nREK " . $head['bank_name'] . " " . $head['bank_number'] . "\nAtas Nama " . $head['holder_name'] . "");
        $pdf->ttd($head['signatory']);
        $pdf->subTotal($total, $head['ppn'], $head['pph']);

        $pdf->Output();
        exit;
    }
    public function printInvoice_02($id)
    {

        $db = \Config\Database::connect();

        // Mengambil data perusahaan
        $query = $db->table('tb_perusahaan')->get();
        $from = $query->getRowArray();

        // Mengambil data invoice
        $head = $this->invoiceModel->getInvoices($id);
        $date = date('d-m-Y', strtotime($head['issue_date']));

        // Mengambil detail pengiriman
        $items = $this->detailInvoiceModel->getByInvoice($id);
        $pdf = new Fpdf_lib();

        $pdf->SetTitle('INV_' . $head['invoice_number']);
        $pdf->SetAuthor('Diki Romadoni');
        $pdf->AddPage('P', 'A4');
        $pdf->Header();
        $pdf->SetDrawColor(31, 49, 111);
        //  $pdf->addWatermark($head['status']);

        // Tambahkan watermark LUNAS
        if ($head['status'] == "Paid") {
            $pdf->addWatermark($head['status']);
        }
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
        $cols = [
            "DATE"        => 25,
            "NO AWB"      => 25,
            "DESCRIPTION" => 50,
            "PRICE"       => 30,
            "SERVICE"     => 30,
            "TOTAL"       => 30,
        ];
        $pdf->addCols($cols);

        $cols = [
            "DATE"            => "C",
            "NO AWB"          => "C",
            "DESCRIPTION"     => "C",
            "PRICE"           => "C",
            "SERVICE"         => "C",
            "TOTAL"           => "C",
        ];
        $pdf->addLineFormat($cols);

        $y     = 73;
        $total = 0;
        $tot   = 0;
        $pdf->SetFont('Arial', '', 8);
        foreach ($items as $item) {
            $date = date('d-m-Y', strtotime($item['tanggal_order']));
            $tot = ((int)$item['berat']) * ((int)$item['price']) + (int)$item['biaya_packing'] + (int)$item['surcharge'] + (int)$item['insurance'];
            if ($item['bill_type'] == 'flat') {
                $tot = (int)$item['price'];
            }
            $total += $tot;
            $line = array(
                "DATE"           => "{$date}",
                "NO AWB"         => "{$item['no_surat_jalan']}",
                "DESCRIPTION"    => "{$item['nama_penerima']}",
                "PRICE"          => format_rupiah($item['price']),
                "SERVICE"        => $item['layanan'],
                "TOTAL"          => format_rupiah($tot)
            );

            $size = $pdf->addLine($y, $line);
            $y   += $size + 4;

            if ($pdf->getY() <= 250) {
                $pdf->Line(10, $y - 3, 200, $y - 3);
            }

            if ($pdf->getY() > 250) {
                $y   = 73;
                $pdf->AddPage();
                $date = date('d-m-Y', strtotime($head['issue_date']));
                // $pdf->addWatermark($head['status']);
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
                $cols = [
                    "DATE"        => 25,
                    "NO AWB"      => 25,
                    "DESCRIPTION" => 50,
                    "PRICE"       => 30,
                    "SERVICE"     => 30,
                    "TOTAL"       => 30,
                ];
                $pdf->addCols($cols);

                $cols = [
                    "DATE"            => "C",
                    "NO AWB"          => "C",
                    "DESCRIPTION"     => "C",
                    "PRICE"           => "C",
                    "SERVICE"         => "C",
                    "TOTAL"           => "C",
                ];
                $pdf->addLineFormat($cols);
            }
            $pdf->SetFont('Arial', '', 7);
        }

        $pdf->SetFont('Arial', '', 10); // Ukuran font 10 untuk subtotal dan catatan
        $pdf->noteInvoice("Note : " . $head['notes'] . "\nPembayaran Via Transfer :\nREK " . $head['bank_name'] . " " . $head['bank_number'] . "\nAtas Nama " . $head['holder_name'] . "");
        $pdf->ttd($head['signatory']);
        $pdf->subTotal($total, $head['ppn'], $head['pph']);

        $pdf->Output();
        exit;
    }
    public function payInvoice($id)
    {
        $invoice = $this->invoiceModel->getInvoices($id);
        $receivable = $this->receivableModel->where('invoice_number', $invoice['invoice_number'])->first();

        $data = [
            'receivable_id'   => $receivable['id'],
            'payment_date'    => get_time(),
            'amount'          => $invoice['total_amount'],
            'payment_method'  => 'Transfer',
            'notes'           => 'Generate From Invoice'
        ];

        $this->receivablePaymentModel->insert($data);

        $this->generateJurnalPembayaranPiutang();

        $data = [
            'status' => 'Paid',
            'paid_amount' => $invoice['total_amount']
        ];
        $this->receivableModel->update($receivable['id'], $data);
        $this->invoiceModel->update($id, $data);

        return redirect()->to('admin/invoice/')->with('success', 'Data berhasil dibayar.');
    }
    public function exportInvoiceCoretax()
    {
        $invoice_id = $this->request->getPost('id');

        $insert_data = [
            'receipt_export_source'        => 'coretax',
            'receipt_export_date'        => date('Y-m-d'),
            'receipt_export_detail'        => implode('|', $invoice_id),
            'receipt_export_create_at'    => get_time(),
            'receipt_export_create_by'    => session()->get('id'),
        ];

        $this->receiptExportModel->insert($insert_data);
        return redirect()->to('admin/invoice/export')->with('success', count($invoice_id) . ' Invoice berhasil ditambahkan kedalam export !');
    }

    public function getReceiptTax()
    {

        $export_receipt_id = $this->request->getGet('receipt_export_id');
        $data = $this->receiptExportModel->find($export_receipt_id);
        $invoice_ids = array_map(function ($item) {
            return $item;
        }, explode('|', $data['receipt_export_detail']));

        $invoice = $this->invoiceModel->getInvoiceExport($invoice_ids);
        $rows = [];
        foreach ($invoice as $key => $value) {
            $rows[] = $value;
        }
        $result['status']     = true;
        $result['message']     = 'ok';
        $result['rows']     = $rows;
        return json_encode($result);
    }
    public function cancelReceiptExport()
    {
        $receipt_id = $this->request->getPost('receipt_id');
        $receipt_export_id = $this->request->getPost('receipt_export_id');
        $fetch_receipt_export = $this->receiptExportModel->find($receipt_export_id);
        $receipt_export_detail = $fetch_receipt_export['receipt_export_detail'];

        $receipt_export_detail = array_map(function ($item) {
            return $item;
        }, explode('|', $receipt_export_detail));
        $receipt_export_detail = array_diff($receipt_export_detail, [$receipt_id]);

        $data = [
            'receipt_export_detail' => implode('|', $receipt_export_detail)
        ];

        $this->receiptExportModel->update($receipt_export_id, $data);

        $result['status']     = true;
        $result['message']     = 'Invoice berhasil dihapus';
        $result['rows']     = $receipt_export_detail;
        return json_encode($result);
    }
    public function receiptExportToXml($id)
    {

        $data = $this->receiptExportModel->find($id);
        $invoice_ids = array_map(function ($item) {
            return $item;
        }, explode('|', $data['receipt_export_detail']));
        $parser = new CoreTaxParser($invoice_ids);

        $db = \Config\Database::connect();
        $builder = $db->table('tb_perusahaan');
        $query = $builder->get();
        $ph = $query->getResultArray();
        $website_npwp_number = $ph[0]['npwp'];

        $npwp_number = $website_npwp_number;
        $npwp_number_replace = str_replace('.', '', $npwp_number);
        $npwp_number_replace = str_replace('-', '', $npwp_number_replace);
        $data = $parser->setNPWPNumber($npwp_number_replace)->buildArray();

        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // die;

        // Mulai buat XML
        $xml = new \SimpleXMLElement('<TaxInvoiceBulk/>');
        $xml->addChild('TIN', $ph[0]['npwp']);

        $list = $xml->addChild('ListOfTaxInvoice');

        foreach ($data['ListOfTaxInvoice'] as $invoice) {
            $inv = $list->addChild('TaxInvoice');
            foreach ($invoice as $key => $val) {
                if ($key === 'ListOfGoodService') {
                    $goods = $inv->addChild('ListOfGoodService');
                    // Cek apakah $val adalah array numerik atau asosiatif
                    if (!isset($val[0])) {
                        $val = [$val]; // bungkus jadi array of array
                    }
                    foreach ($val as $good) {
                        $item = $goods->addChild('Item');
                        foreach ($good as $gKey => $gVal) {
                            $item->addChild($gKey, htmlspecialchars($gVal));
                        }
                    }
                } else {
                    $inv->addChild($key, htmlspecialchars($val));
                }
            }
        }

        // Output file XML
        return $this->response
            ->setHeader('Content-Type', 'application/xml')
            ->setHeader('Content-Disposition', 'attachment; filename="coretax_export.xml"')
            ->setBody($xml->asXML());
    }
    public function generateJurnalPiutang()
    {
        $receivables = $this->receivableModel->getReceivableJournal();

        $jurnalModel = new \App\Models\JournalModel();

        foreach ($receivables as $item) {
            $ref = $item['invoice_number'];

            // Cek apakah jurnal sudah ada
            $exists = $jurnalModel->where('reference', $ref)->first();
            if ($exists) {
                continue; // Lewati jika sudah dijurnal
            }

            $jurnalModel->insertBatch([
                [
                    'journal_date' => $item['invoice_date'],
                    'reference' => $ref,
                    'journal_account_id' => 2, // Piutang Usaha
                    'debit' => $item['total_amount'],
                    'credit' => 0,
                    'description' => 'Invoice ke ' . $item['nama_pelanggan']
                ],
                [
                    'journal_date' => $item['invoice_date'],
                    'reference' => $ref,
                    'journal_account_id' => 3, // Pendapatan Penjualan
                    'debit' => 0,
                    'credit' => $item['total_amount'],
                    'description' => 'Invoice ke ' . $item['nama_pelanggan']
                ]
            ]);

            $this->receivableModel->update($item['id'], ['is_journaled' => 1]);
        }
    }
    public function generateJurnalPembayaranPiutang()
    {
        $payments = $this->receivablePaymentModel->getReceivablePaymentJournal();

        $jurnalModel = new \App\Models\JournalModel();

        foreach ($payments as $item) {
            $ref = 'RECPAY/' . $item['invoice_number'];

            // Cek apakah jurnal sudah ada
            $exists = $jurnalModel->where('reference', $ref)->first();
            if ($exists) {
                continue; // Skip jika sudah dijurnal
            }

            /** 1 = Kas/Bank, 2 = Piutang Usaha */
            $jurnalModel->insertBatch([
                [
                    'journal_date' => $item['payment_date'],
                    'reference' => $ref,
                    'journal_account_id' => 1, // Kas/Bank
                    'debit' => $item['amount'],
                    'credit' => 0,
                    'description' => 'Pembayaran dari ' . $item['nama_pelanggan']
                ],
                [
                    'journal_date' => $item['payment_date'],
                    'reference' => $ref,
                    'journal_account_id' => 2, // Piutang Usaha
                    'debit' => 0,
                    'credit' => $item['amount'],
                    'description' => 'Pembayaran dari ' . $item['nama_pelanggan']
                ]
            ]);

            $this->receivablePaymentModel->update($item['id'], ['is_journaled' => 1]);
        }
    }
}
