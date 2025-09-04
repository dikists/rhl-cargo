<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Manifest</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 8pt;
        }

        .title {
            text-align: center;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            margin-bottom: 10px;
        }

        table.meta,
        table.detail {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table.meta td {
            padding: 3px;
            vertical-align: top;
            font-size: 8pt;
            border: 1px solid #000;
        }

        table.detail th,
        table.detail td {
            border: 1px solid #000;
            padding: 2px;
            font-size: 8pt;
            vertical-align: top;
            text-align: center;
        }

        table.detail td.left {
            text-align: left;
        }

        .signature {
            margin-top: 40px;
            text-align: left;
        }

        .signature-line {
            margin-top: 40px;
            border-top: 1px solid black;
            width: 200px;
        }
    </style>
</head>

<body>

    <table class="noborder" style="width: 100%; margin-bottom: 5px;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="width: 20%;" border="0">
                <img src="data:image/png;base64,<?= base64_encode(file_get_contents(FCPATH . 'assets/img/logo-rhl.png')); ?>" style="width: 150px; height: auto;" />
            </td>
            <td style="width: 80%; text-align: center;" border="0">
                <div class="title">PT. Wahana Elangcargo Perkasa</div>
                <div class="subtitle"><strong>MANIFEST</strong></div>
            </td>
        </tr>
    </table>

    <div style="width: 30%; float: left;">
        <table class="meta">
            <tr>
                <td><strong>TO:</strong> <?= ($detail[0]['sub_vendor_name']) ? $detail[0]['sub_vendor_name'] : $detail[0]['vendor_name']; ?></td>
            </tr>
            <tr>
                <td><strong>-</strong></td>
            </tr>
        </table>
    </div>
    <div style="width: 30%; float: right;">
        <table class="meta">
            <tr>
                <td><strong>DATE:</strong> <?= date('d-m-Y', strtotime($detail[0]['tanggal_kirim'])); ?></td>
            </tr>
            <tr>
                <td><strong>VIA:</strong> <?= ucfirst(explode(' ', $detail[0]['layanan'])[0]); ?></td>
            </tr>
        </table>
    </div>
    <br>
    <br>
    <br>
    <br>

    <table class="detail">
        <thead>
            <tr>
                <th>No.</th>
                <th>AWB</th>
                <th>Shipper</th>
                <th>Consignee</th>
                <th>TO</th>
                <th>Koli</th>
                <th>Kg</th>
                <th>SPX Doc</th>
                <th>Remark</th>
            </tr>
        </thead>
        <tbody>
            <?php
            for ($i = 0; $i < 10; $i++) {
                $data = $detail[$i] ?? null; // jika data tidak ada, maka null
            ?>
                <tr>
                    <td><?= $i + 1; ?></td>
                    <td><?= $data['no_surat_jalan'] ?? ''; ?></td>
                    <td><?= $data['nama_pelanggan'] ?? ''; ?></td>
                    <td><?= $data['nama_penerima'] ?? ''; ?></td>
                    <td><?= $data['dest'] ?? ''; ?></td>
                    <td><?= $data['koli'] ?? ''; ?></td>
                    <td><?= isset($data['berat']) ? round($data['berat']) : ''; ?></td>
                    <td></td>
                    <td></td>
                </tr>
            <?php } ?>
        </tbody>

    </table>

    <div class="signature">
        <div class="signature-line"></div>
    </div>

</body>

</html>