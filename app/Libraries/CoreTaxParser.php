<?php
namespace App\Libraries;
class CoreTaxParser
{
    private $xml;
    private $data = [];

    /**
     * Primary key for receipt_export ID 
     * @var int
     */
    private $receipt_export_id = 0;

    /**
     * @var string
     */
    private $website_npwp_number = '';
    private $db;

    /**
     * Length of BuyerIDTKU
     * @var int
     */
    private $buyeridtku_length = 22;

    /**
     * Length of SellerIDTKU
     * @var int
     */
    private $selleridtku_length = 22;
    

    public function __construct($receipt_export_id = null)
    {
        if ($receipt_export_id) $this->receipt_export_id = $receipt_export_id;
        $this->db = \Config\Database::connect();
        // $db = \Config\Database::connect();
        // $builder = $db->table('tb_perusahaan');
        // $query = $builder->get();
        // $ph = $query->getResultArray();
        // $this->website_npwp_number = $ph[0]['npwp'];
    }

    public function loadFromString($xmlString)
    {
        libxml_use_internal_errors(true);
        $this->xml = simplexml_load_string($xmlString);
        if ($this->xml === false) {
            $errors = libxml_get_errors();
            $errorString = '';
            foreach ($errors as $error) {
                $errorString .= "\n" . $this->libxmlDisplayError($error);
            }
            libxml_clear_errors();
            throw new \Exception("Failed to parse XML: " . $errorString);
        }
    }

    public function loadFromArray($data)
    {
        $this->data = $data;
    }

    public function getKeyDescMap()
    {
        return [
            'TIN' => 'NPWP Ekspedisi',
            'TaxInvoiceDate' => 'Tanggal Invoice',
            'TaxInvoiceOpt' => 'Normal',
            'TrxCode' => '05',
            'RefDesc' => 'No Invoice',
            'SellerIDTKU' => 'NPWP Ekspedisi',
            'BuyerTin' => 'NPWP Relasi',
            'BuyerDocument' => 'TIN',
            'BuyerCountry' => 'IDN',
            'BuyerDocumentNumber' => '-',
            'BuyerName' => 'Nama NPWP Relasi',
            'BuyerAdress' => 'Alamat Faktur Pajak',
            'BuyerEmail'  => 'di kosongkan, belum ada inputan',
            'BuyerIDTKU' => 'NPWP Relasi + angka 0 sampai jumlah data 22 digit',
        ];
    }

    private function libxmlDisplayError($error)
    {
        $return = "\n";
        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $return .= "Warning $error->code: ";
                break;
            case LIBXML_ERR_ERROR:
                $return .= "Error $error->code: ";
                break;
            case LIBXML_ERR_FATAL:
                $return .= "Fatal Error $error->code: ";
                break;
        }
        $return .= trim($error->message);
        if ($error->file) {
            $return .= " in $error->file";
        }
        $return .= " on line $error->line\n";
        return $return;
    }

    public function get_receipt_tax($receipt_export_id)
    {
        $sql = "SELECT invoices.*, 
                    (select rt.rtax_value from relation_tax rt where rt.rtax_id = invoices.rtax_id) AS ppn,
                    (select percent from relation_pph where id = invoices.rtaxpph_id) AS pph,
                    tp.nama_pelanggan, 
                    tp.alamat_pelanggan, 
                    tp.kota, 
                    tp.telepon_pelanggan, 
                    tp.npwp
                FROM invoices
                left join tb_pelanggan tp on tp.id_pelanggan = invoices.id_pelanggan
                WHERE id in ('" . implode("','", $receipt_export_id) . "')";
        $query = $this->db->query($sql);
        $db_query = $query->getResultArray();
        $result = [];
        foreach ($db_query as $key => $value) {
            $result[] = $value;
        }
        return $result;
    }

    public function parseToArray()
    {
        $this->data['TIN'] = (string) $this->xml->TIN;
        $this->data['ListOfTaxInvoice'] = [];

        foreach ($this->xml->ListOfTaxInvoice->TaxInvoice as $taxInvoice) {
            $invoice = [
                'TaxInvoiceDate' => (string) $taxInvoice->TaxInvoiceDate,
                'TaxInvoiceOpt' => (string) $taxInvoice->TaxInvoiceOpt,
                'TrxCode' => (string) $taxInvoice->TrxCode,
                'AddInfo' => (string) $taxInvoice->AddInfo,
                'CustomDoc' => (string) $taxInvoice->CustomDoc,
                'CustomDocMonthYear' => (string) $taxInvoice->CustomDocMonthYear,
                'RefDesc' => (string) $taxInvoice->RefDesc,
                'FacilityStamp' => (string) $taxInvoice->FacilityStamp,
                'SellerIDTKU' => (string) $taxInvoice->SellerIDTKU,
                'BuyerTin' => (string) $taxInvoice->BuyerTin,
                'BuyerDocument' => (string) $taxInvoice->BuyerDocument,
                'BuyerCountry' => (string) $taxInvoice->BuyerCountry,
                'BuyerDocumentNumber' => (string) $taxInvoice->BuyerDocumentNumber,
                'BuyerName' => (string) $taxInvoice->BuyerName,
                'BuyerAdress' => (string) $taxInvoice->BuyerAdress,
                'BuyerEmail' => (string) $taxInvoice->BuyerEmail,
                'BuyerIDTKU' => (string) $taxInvoice->BuyerIDTKU,
                'ListOfGoodService' => [],
            ];

            foreach ($taxInvoice->ListOfGoodService->GoodService as $goodService) {
                $service = [
                    'Opt' => (string) $goodService->Opt,
                    'Code' => (string) $goodService->Code,
                    'Name' => (string) $goodService->Name,
                    'Unit' => (string) $goodService->Unit,
                    'Price' => (float) $goodService->Price,
                    'Qty' => (int) $goodService->Qty,
                    'TotalDiscount' => (float) $goodService->TotalDiscount,
                    'TaxBase' => (float) $goodService->TaxBase,
                    'OtherTaxBase' => (float) $goodService->OtherTaxBase,
                    'VATRate' => (int) $goodService->VATRate,
                    'VAT' => (float) $goodService->VAT,
                    'STLGRate' => (int) $goodService->STLGRate,
                    'STLG' => (float) $goodService->STLG,
                ];
                $invoice['ListOfGoodService'][] = $service;
            }
            $this->data['ListOfTaxInvoice'][] = $invoice;
        }


        return $this->data;
    }
    /* untuk kebutuhan get data */
    public function buildArray()
    {

        $get_receipt_data = $this->get_receipt_tax($this->receipt_export_id);
        /* d($get_receipt_data);
        die; */

        /* build receipt detail data */

        $build_data = [];
        $build_data['TIN'] = $this->website_npwp_number;
        foreach ($get_receipt_data as $kr => $kval) {

            $BuyerIDTKU = str_pad($this->cleanNPWPNumber($kval['npwp']), 22, '0', STR_PAD_RIGHT);
            // jika NITKU pada relasi sudah diisi maka replace dengan NITKU yang diisi
            if (!empty($kval['relation_nitku'])) {
                $BuyerIDTKU = $kval['relation_nitku'];
            }

            $build_data['ListOfTaxInvoice'][$kr] = [
                'TaxInvoiceDate' => $kval['issue_date'],
                'TaxInvoiceOpt' => 'Normal',
                'TrxCode' => '05',
                'AddInfo' => null,
                'CustomDoc' => null,
                'CustomDocMonthYear' => null,
                'RefDesc' => $kval['invoice_number'],
                'FacilityStamp' => null,
                'SellerIDTKU' => str_pad($this->website_npwp_number, 22, '0', STR_PAD_RIGHT),
                'BuyerTin' => $this->cleanNPWPNumber($kval['npwp']),
                'BuyerDocument' => 'TIN',
                'BuyerCountry' => 'IDN',
                'BuyerDocumentNumber' => '-',
                'BuyerName' => $kval['nama_pelanggan'],
                'BuyerAdress' => $kval['alamat_pelanggan'],
                'BuyerEmail' => null,
                'BuyerIDTKU' => $BuyerIDTKU,
            ];


            $tax_base   = $kval['total_amount'];
            $vrate      = $kval['ppn']; // 1.2
            $vat        = floor($tax_base * $vrate / 100); // vrate: 

            // $description = (!empty($jval['custom_keterangan'])) ? $jval['custom_keterangan'] : $this->buildTextDescrtiption($jval);
            $build_data['ListOfTaxInvoice'][$kr]['ListOfGoodService'] = [
                'Opt' => 'B',
                'Code' => '060000',
                'Name' => 'Jasa Pengiriman Barang',
                'Unit' => 'UM.0033',
                'Price' => $kval['total_amount'],
                'Qty' => 1,
                'TotalDiscount' => 0,
                'TaxBase' => $tax_base,
                'OtherTaxBase' => $tax_base,
                'VATRate' => 11,
                'VAT' => $vat,
                'STLGRate' => 0,
                'STLG' => 0,
            ];
            
        }

        return $build_data;
    }

    private function cleanNPWPNumber($npwpNumber)
    {
        $str = str_replace('.', '', $npwpNumber);
        $str = str_replace('-', '', $str);
        return $str;
    }

    public function setNPWPNumber($npwpNumber)
    {
        $this->website_npwp_number = $npwpNumber;
        return $this;
    }

    public function getFormattedNPWPNumber()
    {
        $npwpNumber = $this->cleanNPWPNumber($this->website_npwp_number);
        return str_pad($npwpNumber, 22, '0', STR_PAD_RIGHT);
    }

    public function buildTextDescrtiption($receipt_detail = [])
    {

        $required_map = ['rdetail_cat_id', 'city_sender', 'city_receiver', 'loc_load'];
        foreach ($required_map as $key => $value) {
            if (!isset($receipt_detail[$value])) {
                throw new \Exception('Kolom ' . $value . ' harus diisi');
            }
        }

        if ($receipt_detail['rdetail_cat_id'] == 27) { // freight OK
            $text_desc = 'Biaya freight forwarding, expedisi barang kirim dan lainnya dari ' . $receipt_detail['city_sender'] . ' - ' . $receipt_detail['city_receiver'];
        } elseif ($receipt_detail['rdetail_cat_id'] == 92) { // Muat Inap OK
            $text_desc = 'Biaya freight forwarding, Tambahan Inap expedisi barang kirim di ' . $receipt_detail['loc_load'];
        } elseif ($receipt_detail['rdetail_cat_id'] == 68) { // LSS OK
            $text_desc = 'Biaya freight forwarding, Tambahan LSS expedisi barang kirim dari ' . $receipt_detail['city_sender'] . ' - ' . $receipt_detail['city_receiver'];
        } elseif ($receipt_detail['rdetail_cat_id'] == 28) { // Storage OK
            $text_desc = 'Biaya freight forwarding, Tambahan Storage expedisi barang kirim di ' . $receipt_detail['city_receiver'];
        } elseif ($receipt_detail['rdetail_cat_id'] == 31) { // Bongkar OK
            $text_desc = 'Biaya Ongkos Bongkar';
        } elseif ($receipt_detail['rdetail_cat_id'] == 33) { // Batal Muat
            $text_desc = 'Biaya freight forwarding, Batal Muat expedisi barang kirim di ' . $receipt_detail['loc_load'];
        } elseif ($receipt_detail['rdetail_cat_id'] == 30) { // Asuransi
            $text_desc = 'Biaya Premi Asuransi';
        } elseif ($receipt_detail['rdetail_cat_id'] == 29) { //Demmurage
            $text_desc = 'Biaya freight forwarding, Tambahan demurrage expedisi barang kirim di ' . $receipt_detail['city_receiver'];
        } else {
            $text_desc = 'Biaya freight forwarding, expedisi barang kirim dan lainnya dari ' . $receipt_detail['city_sender'] . ' - ' . $receipt_detail['city_receiver'];
        }

        return $text_desc;
    }
}
