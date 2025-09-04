<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Invoice</title>
    <style>
        @page {
            margin: 10px;
            /* Atur margin halaman menjadi tipis */
        }

        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background-color: #f0f0f0;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .noborder td,
        .noborder th {
            border: none !important;
        }
    </style>
</head>

<body>

    <!-- HEADER DENGAN LOGO -->
    <table class="noborder" style="width: 100%; margin-bottom: 5px;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="width: 20%;" border="0">
                <img src="data:image/png;base64,<?= base64_encode(file_get_contents(FCPATH . 'assets/img/logo-wep.png')); ?>" style="width: 250px; height: auto;" />
            </td>
            <td style="width: 80%; text-align: center;" border="0">
                <h1>Wahana Elangcargo Perkasa</h1>
                <h2 style="margin: 0;"><?= $title; ?></h2>
            </td>
        </tr>
    </table>
    <hr>
    <br>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Akun</th>
                <th>Referensi</th>
                <th>Keterangan</th>
                <th>Debit</th>
                <th>Kredit</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($data as $item) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $item['journal_date']; ?></td>
                    <td>[<?= $item['account_code']; ?>] <?= $item['account_name']; ?> (<?= $item['account_type']; ?>)</td>
                    <td><?= $item['reference']; ?></td>
                    <td><?= $item['description']; ?></td>
                    <td><?= formatRupiah($item['debit']); ?></td>
                    <td><?= formatRupiah($item['credit']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>

</html>