<!DOCTYPE html>
<html>

<head>
    <title><?= $title; ?></title>
    <style>
        @page {
            margin: 10px; /* Atur margin halaman menjadi tipis */
        }

        body {
            margin: 10px; /* Atur margin badan halaman menjadi tipis */
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }

        th,
        td {
            padding: 5px 7px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
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
                <th>Total Cost</th>
                <th>Remark</th>
            </tr>
        </thead>
        <tbody>
            <?php $grandTotal = 0; ?>
            <?php foreach ($data as $index => $item) : ?>
                <?php $grandTotal += $item['total_cost']; ?>
                <?php $tanggal_order = date('d-m-y', strtotime($item['tanggal_order'])) ?>
                <?php $arrival_date = ($item['arrival_date']) ? date('d-m-y', strtotime($item['arrival_date'])) : '' ?>
                <?php $row_class = $arrival_date ? 'highlight' : ''; ?>
                <tr class="<?= $row_class; ?>">
                    <td><?= $index + 1; ?></td>
                    <td><?= $item['no_surat_jalan']; ?></td>
                    <td><?= $item['no_ref']; ?></td>
                    <td><?= $tanggal_order; ?></td>
                    <td><?= $item['origin']; ?></td>
                    <td><?= $item['dest']; ?></td>
                    <td><?= $item['leadtime']; ?></td>
                    <td><?= $item['nopol']; ?></td>
                    <td><?= $item['layanan']; ?></td>
                    <td><?= $item['total_qty'] . ' BOX'; ?></td>
                    <td><?= $item['total_hitung']; ?></td>
                    <td><?= $item['total_volume']; ?></td>
                    <td><?= $arrival_date; ?></td>
                    <td><?= $item['receipt_name']; ?></td>
                    <td><?= $item['leadtime_actual']; ?></td>
                    <td><?= $item['performance']; ?></td>
                    <td><?= formatRupiah($item['cost']); ?></td>
                    <td><?= formatRupiah($item['total_biaya_packing']); ?></td>
                    <td><?= formatRupiah($item['total_cost']); ?></td>
                    <td>-</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="18">Total</th>
                <th><?= formatRupiah($grandTotal); ?></th>
            </tr>
        </tfoot>
    </table>
</body>

</html>

<?php
function formatRupiah($angka)
{
    return 'Rp' . number_format($angka, 0, ',', '.');
}
?>
