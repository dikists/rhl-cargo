<?php

namespace App\Controllers;

use FPDF;
use App\Models\OfferModel;
use App\Models\InvoiceModel;
use App\Models\JournalModel;
use App\Models\ManifestModel;
use App\Models\UangJalanModel;
use App\Models\PengirimanModel;
use App\Models\DetailOrderModel;
use App\Models\PengambilanModel;
use App\Models\OfferDetailsModel;
use App\Controllers\BaseController;
use App\Models\ManifestDetailModel;
use App\Models\ViewPengirimanSummaryModel;

class PdfController extends BaseController
{
    protected $pengirimanModel;
    protected $pengambilanModel;
    protected $invoiceModel;
    protected $jurnal;
    protected $ujModel;
    protected $detailOrderModel;
    protected $viewPengiriman;
    protected $manifestModel;
    protected $manifestDetailModel;
    protected $offerDetailsModel;
    protected $offerModel;
    public function __construct()
    {
        $this->pengirimanModel = new PengirimanModel();
        $this->pengambilanModel = new PengambilanModel();
        $this->invoiceModel = new InvoiceModel();
        $this->jurnal = new JournalModel();
        $this->ujModel = new UangJalanModel();
        $this->detailOrderModel = new DetailOrderModel();
        $this->viewPengiriman = new ViewPengirimanSummaryModel();
        $this->manifestModel = new ManifestModel();
        $this->manifestDetailModel = new ManifestDetailModel();
        $this->offerDetailsModel = new OfferDetailsModel();
        $this->offerModel = new OfferModel();
    }
    public function pengiriman_req_liugong()
    {
        ini_set('max_execution_time', 300);
        helper('pdf');

        $date_start = $this->request->getGet('date_start');
        $date_end = $this->request->getGet('date_end');
        $pengirim = $this->request->getGet('pengirim');
        $penerima = $this->request->getGet('penerima');
        $layanan = $this->request->getGet('layanan');
        $performance = $this->request->getGet('performance');

        $data = $this->pengirimanModel->getPengiriman($date_start, $date_end, $pengirim, $penerima, $layanan, $performance);

        $role = session()->get('role');
        if ($role == 'PIC RELASI') {
            if ($date_start < '2025-08-01' && $date_end < '2025-08-31') {
                $data = $this->pengirimanModel->getPengirimanLama($date_start, $date_end, $pengirim, $penerima, $layanan, $performance);
            }
        }

        $title = 'Pengiriman ' . date('d-m-Y', strtotime($date_start)) . ' - ' . date('d-m-Y', strtotime($date_end));

        $html = view('pdf/pengiriman_req_liugong', ['data' => $data, 'start' => $date_start, 'end' => $date_end, 'title' => $title]);
        create_pengiriman_pdf($html, 'laporan_pengiriman.pdf');
        exit;
    }
    public function invoice()
    {
        helper('pdf');
        $get = $this->request->getGet() ?? [];
        $data = $this->invoiceModel->getInvoices(null, $get);
        $start = $this->request->getGet('date_start');
        $end = $this->request->getGet('date_end');
        $title = 'Laporan Invoice <br>' . date('d-m-Y', strtotime($start)) . ' - ' . date('d-m-Y', strtotime($end));

        $html = view('pdf/invoice', ['data' => $data, 'start' => $start, 'end' => $end, 'title' => $title]);
        pdf_landscape($html, $title . '.pdf');
        exit;
    }

    public function laporan_jurnal()
    {
        helper('pdf');
        $get = $this->request->getGet() ?? [];
        $data = $this->jurnal->getJournal($get);

        $start = $this->request->getGet('date_start');
        $end = $this->request->getGet('date_end');
        $title = 'Laporan Jurnal <br>' . date('d-m-Y', strtotime($start)) . ' - ' . date('d-m-Y', strtotime($end));

        $html = view('pdf/jurnal', ['data' => $data, 'start' => $start, 'end' => $end, 'title' => $title]);
        pdf_portrait($html, $title . '.pdf');
        exit;
    }

    public function uang_jalan($id)
    {
        helper('pdf');
        $data = $this->ujModel->getUangJalan($id);

        // Cek apakah sudah pernah dicetak
        $isReprint = ($data['is_printed'] == 1);

        // Update status is_printed jika pertama kali print
        if (!$isReprint) {
            $this->ujModel->update($id, ['is_printed' => 1]);
        }

        $html = view('pdf/uang_jalan', ['uang_jalan' => $data, 'isReprint' => $isReprint]);
        create_uang_jalan_pdf($html, 'uang_jalan.pdf');
        exit;
    }
    public function manifest_sub($no_surat_jalan)
    {
        helper('pdf');
        $data['tracking'] = $this->pengirimanModel->getTrackingData($no_surat_jalan);
        $data['detail'] = $this->viewPengiriman->cari($no_surat_jalan);

        $id_order = $data['tracking']->id_order;
        $id_layanan = $data['tracking']->id_layanan;

        $data['barang'] = $this->pengirimanModel->getDetailOrder(id_order: $id_order, id_layanan: $id_layanan);
        $data['title'] = 'Manifest';
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // die;

        $html = view('pdf/manifest_sub', $data);
        create_uang_jalan_pdf($html, $data['title'] . '.pdf');
        exit;
    }
    public function manifest_rincian($id)
    {
        ini_set('max_execution_time', 300);
        helper('pdf');
        $data['manifest'] = $this->manifestModel->getManifest($id);

        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // die;

        $detail_surat_jalan = $this->manifestDetailModel->getPDFDetailManifest($id);
        $detail_surat_jalan_indexed = [];
        foreach ($detail_surat_jalan as $row) {
            $detail_surat_jalan_indexed[$row['no_surat_jalan']] = $row;
        }

        // echo "<pre>";
        // print_r($detail_surat_jalan);
        // echo "</pre>";
        // die;

        $detail_list = json_decode($data['manifest'][0]['detail_list'], true);
        $combined_data = [];

        foreach ($detail_list as $detail) {
            $no_sj = $detail['no_surat_jalan'];
            $info_sj = $detail_surat_jalan_indexed[$no_sj] ?? [];
            $sub_vendor_name = $detail['no_surat_jalan'] . ' ( ' . $detail['sub_vendor_name'] . ' )';
            if (empty($detail['sub_vendor_name'])) {
                $sub_vendor_name = $detail['no_surat_jalan'] . ' ( ' . $info_sj['nama_pelanggan'] . ' )';
            }
            $items = $this->detailOrderModel->getDetailOrders($detail['id_order']);

            // echo "<pre>";
            // print_r($detail);
            // echo "</pre>";
            // die;

            // Ambil info tambahan jika ada

            foreach ($items as $item) {
                $combined_data[] = [
                    'no_surat_jalan' => $no_sj,
                    'sub_vendor_name' => $sub_vendor_name,
                    'koli'           => $item['jumlah'],
                    'kg'             => $item['berat'],
                    'p'              => $item['panjang'],
                    'l'              => $item['lebar'],
                    't'              => $item['tinggi'],
                    'total'          => $item['jumlah'],
                    // data tambahan dari $detail_surat_jalan
                    'nama_pelanggan' => $info_sj['nama_pelanggan'] ?? '',
                    'nama_penerima'  => $info_sj['nama_penerima'] ?? '',
                    'kota'           => $info_sj['kota'] ?? '',
                    'divider'        => $info_sj['divider'] ?? '',
                    'layanan'        => $info_sj['layanan'] ?? '',
                ];
            }
        }

        // echo "<pre>";
        // print_r($combined_data);
        // echo "</pre>";
        // die;

        $data['combined_data'] = $combined_data;



        // echo "<pre>";
        // print_r($data['manifest']);
        // echo "</pre>";
        // die;

        $html = view('pdf/manifest_rincian', $data);
        create_pdf($html, 'Manifest_' . $id . '.pdf');
        exit;
    }

    public function printInvoiceOffer($id, $title)
    {
        helper('pdf');
        $db = \Config\Database::connect();

        // Mengambil data perusahaan
        $query = $db->table('tb_perusahaan')->get();
        $from = $query->getRowArray();

        // Mengambil data invoice
        $head = $this->invoiceModel->getInvoices($id);
        $offer = $this->offerModel->where('offer_number', $head['invoice_number'])->get()->getRowArray();
        $detail = $this->offerDetailsModel->getOfferDetails($offer['id']);
        $data['title'] = $title;
        $data['perusahaan'] = $from;
        $data['invoice'] = $head;
        $data['offer_details'] = $detail;

        $html = view('pdf/invoice_offer', $data);
        create_pdf($html, $title.'_'. $head['invoice_number'] . '.pdf');
        exit;
    }
}
