<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>RESI</title>
    <style>
        @page {
            size: 8.5in 5.5in;
            /* Half Letter landscape */
            margin: 0.25in;
        }

        body {
            font-family: "DejaVu Sans", Arial, Helvetica, sans-serif;
            font-size: 10px;
            color: #000;
        }

        /* ============ VARIASI WARNA ============ */

        .theme-red .border .red-head,
        .theme-red .stamp-head {
            background: #c40010;
            color: #fff;
        }

        .theme-green .border .red-head,
        .theme-green .stamp-head {
            background: #137836;
            color: #fff;
        }

        .theme-blue .border .red-head,
        .theme-blue .stamp-head {
            background: #004c99;
            color: #fff;
        }

        /* .theme-red .logo-box, */
        .theme-red .right-stamp td,
        .theme-red table.border th,
        .theme-red table.border td {
            border-color: #c40010;
            border: 2px solid #c40010;
        }
        /* .theme-green .logo-box, */
        .theme-green .right-stamp td,
        .theme-green table.border th,
        .theme-green table.border td {
            border-color: #137836;
            border: 2px solid #137836;
        }
        /* .theme-blue .logo-box, */
        .theme-blue .right-stamp td,
        .theme-blue table.border th,
        .theme-blue table.border td {
            border-color: #004c99;
            border: 2px solid #004c99;
        }

        .theme-red .border,
        .theme-red .header-wrap,
        .theme-red .red-head,
        .theme-red .section-title {
            border-color: #c40010;
            color: #000;
        }

        .theme-green .border,
        .theme-green .header-wrap,
        .theme-green .stamp-head,
        .theme-green .red-head,
        .theme-green .section-title {
            border-color: #137836;
            color: #000;
        }

        .theme-blue .border,
        .theme-blue .header-wrap,
        .theme-blue .stamp-head,
        .theme-blue .red-head,
        .theme-blue .section-title {
            border-color: #004c99;
            color: #000;
        }

        /* ============ STYLING UTAMA ============ */
        .border {
            border: 2px solid;
            border-collapse: collapse;
            width: 100%;
        }

        .border td,
        .border th {
            border: 1.5px solid;
            padding: 6px 8px;
            vertical-align: top;
        }

        .red-head {
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .3px;
            padding: 4px 8px;
            font-size: 9.5px;
        }

        .header-wrap {
            border: 2px solid;
            padding: 8px;
            margin-bottom: 6px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo-box {
            width: 115px;
            height: 100px;
            border: 2px solid;
            text-align: center;
            vertical-align: middle;
        }

        .logo-box img {
            max-width: 100%;
            max-height: 100%;
        }

        .company {
            text-align: center;
            padding: 0 6px;
            line-height: 1.25;
        }

        .company .title {
            font-size: 18px;
            font-weight: 800;
            letter-spacing: .5px;
        }

        .company .subtitle {
            font-size: 10px;
            font-weight: 700;
            margin-top: 2px;
        }

        .company .address {
            margin-top: 3px;
            font-size: 9px;
        }

        .right-stamp {
            width: 150px;
            vertical-align: top;
        }

        .stamp-head {
            font-weight: bold;
            text-align: center;
            padding: 4px 0;
            margin: 0;
            line-height: 1.2;
        }

        .stamp-body {
            padding: 6px;
            line-height: 1.4;
            font-size: 11px;
            font-weight: bold;
            text-align: center;
        }

        .section-title {
            font-weight: bold;
            padding: 4px 8px;
            text-transform: uppercase;
            font-size: 9.5px;
        }

        .small {
            font-size: 9px;
        }

        .muted {
            color: #333;
        }

        .desc-row td {
            height: 90px;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        /* pisahkan halaman */
        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>

    <?php
    $themes = ['theme-blue', 'theme-red', 'theme-green'];
    foreach ($themes as $idx => $theme) {
    ?>
        <div class="<?= $theme ?>">
            <!-- HEADER -->
            <div class="header-wrap">
                <table class="header-table">
                    <tr>
                        <td class="logo-box" style="border: none;">
                            <img src="data:image/png;base64,<?= base64_encode(file_get_contents(FCPATH . getenv('COMPANY_LOGO'))); ?>" style="width: 150px; height: auto;" />
                        </td>
                        <td class="company">
                            <div class="title"><?= $company['nama']; ?></div>
                            <div class="subtitle">Domestic And International Cargo Services</div>
                            <div class="subtitle">OFFICE / KANTOR</div>
                            <div class="address">
                                <?= $company['alamat']; ?><br>
                                Phone : <?= $company['telepon']; ?> &nbsp;&nbsp; Email : <?= $company['email']; ?>
                            </div>
                        </td>
                        <td class="right-stamp" style="border: none;">
                            <table style="width:100%; border-collapse: collapse;">
                                <tr>
                                    <td style="width:50%; vertical-align: top; padding:0; margin:0;">
                                        <div class="stamp-head" style="color: #fff;">Date</div>
                                        <div class="stamp-body" style="margin-top: 20px;">
                                            <?= date('d/m/Y', strtotime($label['tanggal'])); ?>
                                        </div>
                                    </td>
                                    <td style="width:50%; vertical-align: top; padding:0; margin:0;">
                                        <div class="stamp-head" style="color: #fff;">No. Resi</div>
                                        <div class="stamp-body">
                                            <?= $label['no_surat_jalan']; ?><br>
                                            <img src="https://bwipjs-api.metafloor.com/?bcid=code128&text=<?= $label['no_surat_jalan']; ?>&scale=2&height=5&width=16" alt="Barcode">
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- BLOK PENGIRIM/PENERIMA -->
            <table class="border">
                <tr>
                    <th class="red-head" style="width: 45%;">Consignor / Pengirim</th>
                    <th class="red-head" style="width: 45%;">Consignee / Penerima</th>
                    <th class="red-head" style="width: 10%;">Pieces</th>
                </tr>
                <tr>
                    <td>
                        <div class="bold"><?= $label['nama_pelanggan']; ?></div>
                        <?= $label['alamat_pelanggan']; ?>
                    </td>
                    <td>
                        <div class="bold"><?= $label['nama_penerima']; ?></div>
                        <?= $label['alamat_penerima']; ?>
                    </td>
                    <td class="center">
                        <?= $label['koli']; ?><br>
                        <span class="small muted">Koli</span>
                    </td>
                </tr>
            </table>

            <!-- BLOK SERVICE -->
            <table class="border" style="margin-top: 5px;">
                <tr>
                    <th class="red-head" style="width: 20%;">Service</th>
                    <th class="red-head" style="width: 20%;">Weight (Kg)</th>
                    <th class="red-head" style="width: 20%;">Volume (M³)</th>
                    <th class="red-head" style="width: 20%;">Special Instruction</th>
                    <th class="red-head" style="width: 20%;">Type Of Shipment</th>
                </tr>
                <tr>
                    <td><?= $label['layanan']; ?></td>
                    <td class="center"><?= round($label['berat'], 2); ?></td>
                    <td class="center"><?= round($label['volume'], 2); ?></td>
                    <td>☐ Packing / Packing Kayu <br> ☐ Insurance / Asuransi</td>
                    <td>☐ Document/Dokumen <br> ☐ Parcel/Paket</td>
                </tr>
            </table>

            <!-- BLOK DESKRIPSI -->
            <table class="border" style="margin-top: 5px;">
                <tr>
                    <th class="red-head">Description of Contents</th>
                    <th class="red-head" style="width: 25%;">Shipper's Reference</th>
                    <th class="red-head" style="width: 20%;">Collected By</th>
                    <th class="red-head" style="width: 20%;">Received By</th>
                </tr>
                <tr class="desc-row">
                    <td>
                        <ul style="list-style-type: none;">
                            <?php foreach ($barang as $b) {
                                echo '<li>- ' . $b['barang'] . ' : ' . $b['jumlah'] . ' ' . $b['satuan'] . '</li>';
                            } ?>
                        </ul>
                    </td>
                    <td>
                        <?php if (!empty($pengiriman['tanggal_kirim'])) { ?>
                            Tanggal: <?= date('d/m/Y', strtotime($pengiriman['tanggal_kirim'])); ?> <br><br>
                        <?php } else { ?>
                            Tanggal: ___________ <br><br>
                        <?php } ?>
                        Tanda Tangan: ______
                    </td>
                    <td>
                        <?php if (!empty($pengiriman['tanggal_ambil'])) { ?>
                            Tanggal: <?= date('d/m/Y', strtotime($pengiriman['tanggal_ambil'])); ?> <br><br>
                        <?php } else { ?>
                            Tanggal: ___________ <br><br>
                        <?php } ?>
                        Tanda Tangan: ______
                    </td>
                    <td>
                        <?php if (!empty($pengiriman['tanggal_terima'])) { ?>
                            Tanggal: <?= date('d/m/Y', strtotime($pengiriman['tanggal_terima'])); ?> <br>
                            DTO: <?= $pengiriman['dto']; ?><br><br>
                        <?php } else { ?>
                            Tanggal: ___________ <br><br>
                        <?php } ?>
                        Tanda Tangan: ______
                    </td>
                </tr>
            </table>
        </div>

        <?php if ($idx < 2) { ?>
            <div class="page-break"></div>
        <?php } ?>

    <?php } // end foreach 
    ?>
</body>

</html>