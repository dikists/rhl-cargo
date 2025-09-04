<!DOCTYPE html>
<html>

<head>
    <title>Bukti Pengeluaran Kas - Uang Jalan</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
        }

        .container {
            width: 700px;
            margin: 0 auto;
        }

        table {
            width: 100%;
        }

        .title {
            text-align: center;
            margin-bottom: 20px;
        }

        .footer {
            margin-top: 40px;
        }

        .signature {
            width: 50%;
            float: left;
            text-align: center;
        }

        .line {
            margin-top: 60px;
            border-top: 1px solid #000;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        @media print {
            .no-print {
                display: none;
            }
        }

        .noborder td,
        .noborder th {
            border: none !important;
        }
    </style>
</head>

<body>
    <?php if ($isReprint): ?>
        <div style="
                position: fixed;
                top: 20%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(-20deg);
                font-size: 90px;
                color: rgba(255, 0, 0, 0.2);
                font-weight: bold;
                white-space: nowrap;
                z-index: 999;
            ">
            RE-PRINT
        </div>
    <?php endif; ?>


    <div class="container">
        <table class="noborder" style="width: 100%; margin-bottom: 5px;" cellspacing="0" cellpadding="0">
            <tr>
                <td style="width: 20%;" border="0">
                    <img src="data:image/png;base64,<?= base64_encode(file_get_contents(FCPATH . 'assets/img/logo-wep.png')); ?>" style="width: 230px; height: auto;" />
                </td>
                <td style="width: 80%; text-align: center;" border="0">
                    <h1>PT. Wahana Elangcargo Perkasa</h1>
                    <h2 style="margin: 0;">Bukti Pengeluaran Kas</h2>
                </td>
            </tr>
        </table>
        <hr>
        <br>
        <table>
            <tr>
                <td><strong>Tanggal</strong></td>
                <td>: <?= date('d F Y', strtotime($uang_jalan['tanggal'])) ?></td>
            </tr>
            <tr>
                <td><strong>No. Referensi</strong></td>
                <td>: <?= esc($uang_jalan['reference']) ?></td>
            </tr>
            <tr>
                <td><strong>Kurir</strong></td>
                <td>: <?= ucwords($uang_jalan['driver_name']) ?></td>
            </tr>
            <tr>
                <td><strong>Tujuan</strong></td>
                <td>: <?= esc($uang_jalan['tujuan']) ?></td>
            </tr>
            <tr>
                <td><strong>Jumlah</strong></td>
                <td>: <?= formatRupiah($uang_jalan['jumlah']) ?></td>
            </tr>
            <tr>
                <td><strong>Metode</strong></td>
                <td>: <?= ucfirst($uang_jalan['metode_pembayaran']) ?></td>
            </tr>
            <tr>
                <td><strong>Status</strong></td>
                <td>: <?= ucfirst($uang_jalan['status']) ?></td>
            </tr>
            <tr>
                <td><strong>Keterangan</strong></td>
                <td>: <?= esc($uang_jalan['keterangan']) ?></td>
            </tr>
        </table>

        <div class="footer">
            <div class="signature">
                Disetujui Oleh,<br><br><br><br>
                <div class="line"></div>
            </div>
            <div class="signature">
                Diterima Oleh,<br><br><br><br>
                <div class="line"></div>
            </div>
        </div>
    </div>
</body>

</html>