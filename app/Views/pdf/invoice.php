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
    <table class="noborder" style="width: 100%; margin-bottom: 20px;" cellspacing="0" cellpadding="0">
        <tr>
            <td style="width: 20%;" border="0">
                <img src="data:image/png;base64,<?= base64_encode(file_get_contents(FCPATH . getenv('COMPANY_LOGO_TEXT'))); ?>" style="width: 250px; height: auto;" />
            </td>
            <td style="width: 80%; text-align: center;" border="0">
                <h1 style="margin: 0;"><?= $title; ?></h1>
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Invoice</th>
                <th>No Faktur</th>
                <th>Bill To</th>
                <th>Total</th>
                <th>PPN</th>
                <th>PPH</th>
                <th>Grand Total</th>
                <th>Status</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_paid = 0;
            $total_unpaid = 0;
            $no = 1;
            foreach ($data as $index => $row) {
                $total_amount = $row['total_amount'];
                $ppn = $row['ppn'];
                $pph = $row['pph'];
                $totalppn = $total_amount * $ppn / 100;
                $totalpph = $total_amount * $pph / 100;
                $grand_total = $total_amount + $totalppn - $totalpph;

                if (strtolower($row['status']) == 'paid') {
                    $total_paid += $grand_total;
                } else {
                    $total_unpaid += $grand_total;
                }

                echo "<tr>";
                echo "<td>" . $no++ . "</td>";
                echo "<td>" . $row['issue_date'] . "</td>";
                echo "<td>" . $row['invoice_number'] . "</td>";
                echo "<td>" . $row['tax_invoice_number'] . "</td>";
                echo "<td>" . $row['nama_pelanggan'] . "</td>";
                echo "<td>" . formatRupiah($row['total_amount']) . "</td>";
                echo "<td>" . formatRupiah($totalppn) . "</td>";
                echo "<td>" . formatRupiah($totalpph) . "</td>";
                echo "<td>" . formatRupiah($grand_total) . "</td>";
                echo "<td>" . $row['status'] . "</td>";
                echo "<td>" . $row['notes'] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <br><br>

    <table style="width: 50%; font-size: 12px;">
        <tr>
            <td style="text-align: left; font-weight: bold;">Total Invoice Paid:</td>
            <td style="text-align: right;"><?= formatRupiah($total_paid) ?></td>
        </tr>
        <tr>
            <td style="text-align: left; font-weight: bold;">Total Invoice Unpaid:</td>
            <td style="text-align: right;"><?= formatRupiah($total_unpaid) ?></td>
        </tr>
    </table>

</body>

</html>