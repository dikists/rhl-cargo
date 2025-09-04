<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        @page {
            margin: 10px;
            /* Atur margin halaman menjadi tipis */
        }

        body {
            margin: 2px;
            /* Atur margin badan halaman menjadi tipis */
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            table-layout: auto;
            word-wrap: break-word;
        }

        th,
        td {
            /* border: 1px solid #000; */
            border: 1px solid #ddd;
            padding: 3px;
            text-align: center;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
        }

        .h3 {
            text-transform: uppercase;
            font-size: 14px;
            font-weight: 800;
        }

        .highlight {
            background-color: #dff0d8;
        }
    </style>
</head>

<body>
    <h2 style="text-align: center;">
        <div class="h3">PT. Wahana Elangcargo Perkasa</div>
        <?= $title; ?>
    </h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Resi Number</th>
                <th>No Ref</th>
                <th>Pickup Date</th>
                <th>Origin</th>
                <th>Dest</th>
                <th>SLA</th>
                <th>Truck Number</th>
                <th>Layanan</th>
                <th>Total Qty</th>
                <th>Total Berat</th>
                <th>Total Volume</th>
                <th>Arrival Date</th>
                <th>Receipt Name</th>
                <th>Leadtime Actual</th>
                <th>Performance</th>
                <th>Cost</th>
                <th>Add Cost</th>
                <th>Asuransi</th>
                <th>Surcharge</th>
                <th>Total Cost</th>
                <th>Remark</th>
            </tr>
        </thead>
        <tbody>
            <?php $grandTotal = 0; ?>
            <?php foreach ($data as $index => $item) : ?>
                <?php $totalCost = (int)$item['biaya_paket'] * (int)$item['berat'] + (int)$item['biaya_packing'] + (int)$item['insurance'] + (int)$item['surcharge']; ?>
                <?php $grandTotal += $totalCost; ?>
                <?php $tanggal_order = date('d-m-y', strtotime($item['tanggal_order'])) ?>
                <?php $arrival_date = ($item['tanggal_terima']) ? date('d-m-y', strtotime($item['tanggal_terima'])) : '' ?>
                <?php $row_class = $arrival_date ? 'highlight' : ''; ?>
                <tr class="<?= $row_class; ?>">
                    <td><?= $index + 1; ?></td>
                    <td><?= $item['no_surat_jalan']; ?></td>
                    <td><?= $item['no_ref']; ?></td>
                    <td><?= $tanggal_order; ?></td>
                    <td><?= $item['origin']; ?></td>
                    <td><?= $item['nama_penerima']; ?></td>
                    <td><?= $item['leadtime']; ?></td>
                    <td><?= $item['plate_number']; ?></td>
                    <td><?= $item['layanan']; ?></td>
                    <td><?= $item['koli'] . ' BOX'; ?></td>
                    <td><?= $item['berat']; ?></td>
                    <td><?= $item['volume']; ?></td>
                    <td><?= $arrival_date; ?></td>
                    <td><?= $item['dto']; ?></td>
                    <td><?= $item['lt_actual']; ?></td>
                    <td><?= $item['performance']; ?></td>
                    <td><?= formatRupiah($item['biaya_paket']); ?></td>
                    <td><?= formatRupiah($item['biaya_packing']); ?></td>
                    <td><?= formatRupiah((int)$item['insurance']); ?></td>
                    <td><?= formatRupiah((int)$item['surcharge']); ?></td>
                    <td><?= formatRupiah($totalCost); ?></td>
                    <td><?= $item['remark']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="20">Total</th>
                <th><?= formatRupiah($grandTotal); ?></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</body>

</html>