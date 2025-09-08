<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Invoice <?= $invoice['invoice_number']; ?></title>
    <link rel="shortcut icon" href="<?= getenv('COMPANY_LOGO'); ?>" type="image/x-icon">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
        }

        .invoice-box {
            margin-top: -20px;
            padding: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table td {
            padding: 5px;
            vertical-align: top;
        }

        table tr.heading td {
            border: #9d9d9d 1px solid;
            font-weight: bold;
        }

        table tr.item td {
            border: #9d9d9d 1px solid;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .mt-5 {
            margin-top: 5px;
        }

        .mt-20 {
            margin-top: 20px;
        }

        .logo {
            width: 80px;
            height: 60px;
            position: relative;
            top: 40px;
        }

        .small {
            font-size: 13px;
            font-weight: normal;
            line-height: 14px;
            margin-left: 83px;
            display: inline-block;
            margin-top: -20px;
            padding: 2px 4px;
        }

        h2 {
            margin-top: -10px;
        }

        .sub_head_left {
            width: 40%;
            border: 1px solid #9d9d9d;
            border-radius: 5px;
            float: left;
            height: 100px;
            margin-bottom: 100px;
            overflow: hidden;
            padding: 5px;
        }

        .sub_head_right {
            width: 40%;
            border: 1px solid #9d9d9d;
            border-radius: 5px;
            float: right;
            height: 100px;
            margin-bottom: 100px;
            overflow: hidden;
            padding: 5px;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <!-- Header Perusahaan -->
        <table>
            <tr>
                <td>
                    <img src="data:image/png;base64,<?= base64_encode(file_get_contents(FCPATH . getenv('COMPANY_LOGO_TEXT'))); ?>" style="width: 280px; height: auto;" />
                </td>
                <td class="text-right">
                    <?php if ($title == 'invoice') { ?>
                        <h3>INVOICE</h3>
                        <b>No:</b> <?= $invoice['invoice_number']; ?><br>
                        <b>Tanggal:</b> <?= date('d/m/Y', strtotime($invoice['issue_date'])); ?><br>
                        <b>Jatuh Tempo:</b> <?= date('d/m/Y', strtotime($invoice['due_date'])); ?><br>
                    <?php } else { ?>
                        <h1><u>DEBIT NOTE</u></h1>
                    <?php } ?>
                </td>
            </tr>
        </table>

        <div class="sub_head_left">
            Bill From : <br>
            <?= $perusahaan['nama']; ?><br>
            <?= $perusahaan['alamat']; ?>
        </div>
        <div class="sub_head_right">
            Bill To : <br>
            <?= $invoice['nama_pelanggan']; ?><br>
            <?= $invoice['alamat_pelanggan']; ?>
        </div>

        <!-- Detail Barang/Jasa -->
        <table style="margin-top: 120px;" border="1" cellspacing="0" cellpadding="5">
            <tr class="heading">
                <td>Deskripsi</td>
                <td class="text-center">Qty</td>
                <td class="text-right">Harga</td>
                <td class="text-right">Total</td>
            </tr>
            <?php
            $grandTotal = 0;
            $jumlahData = count($offer_details);
            $jumlahBaris = max($jumlahData, 5); // minimal 10, kalau lebih ikuti jumlah data

            for ($i = 0; $i < $jumlahBaris; $i++):
                if ($i < $jumlahData):
                    $detail = $offer_details[$i];
                    $grandTotal += $detail['total'];
            ?>
                    <tr class="item">
                        <td><?= $detail['item_name']; ?></td>
                        <td class="text-center"><?= $detail['quantity']; ?></td>
                        <td class="text-right"><?= formatRupiah($detail['price']); ?></td>
                        <td class="text-right"><?= formatRupiah($detail['total']); ?></td>
                    </tr>
                <?php else: ?>
                    <tr class="item">
                        <td>&nbsp;</td>
                        <td class="text-center">&nbsp;</td>
                        <td class="text-right">&nbsp;</td>
                        <td class="text-right">&nbsp;</td>
                    </tr>
                <?php endif; ?>
            <?php endfor; ?>

        </table>

        <!-- Ringkasan -->
        <table class="mt-5" style="line-height: 10px; 
              font-size: 12px;
              border-collapse: separate; 
              border-spacing: 0; 
              width: 30%; 
              float: right; 
              border-radius: 10px; 
              overflow: hidden;" border="1">
            <tr style="border-radius: 5px;">
                <td class="text-right" width="50%">
                    Subtotal:
                </td>
                <td class="text-right">
                    <?= formatRupiah($invoice['total_amount']); ?>
                </td>
            </tr>
            <?php if ($invoice['ppn'] != '') { ?>
                <tr>
                    <td class="text-right">
                        PPN (<?= $invoice['ppn']; ?>%):
                    </td>
                    <td class="text-right">
                        <?= formatRupiah(($invoice['total_amount'] * $invoice['ppn'] / 100)); ?>
                    </td>
                </tr>
            <?php } ?>
            <?php
            if ($invoice['pph'] != '') {
            ?>
                <tr>
                    <td class="text-right">
                        PPH (<?= $invoice['pph']; ?>%):
                    </td>
                    <td class="text-right">
                        -<?= formatRupiah(($invoice['total_amount'] * $invoice['pph'] / 100)); ?>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td class="text-right"><b>Grand Total:</b></td>
                <td class="text-right">
                    <b><?= formatRupiah($invoice['total_amount'] + ($invoice['total_amount'] * $invoice['ppn'] / 100) - ($invoice['total_amount'] * $invoice['pph'] / 100)); ?></b>
                </td>
            </tr>
        </table>

        <!-- Informasi Pembayaran -->
        <table class="mt-20" style="width: 70%; float: left; font-size: 12px;">
            <tr>
                <td width="50%">
                    <b>Pembayaran ke:</b><br>
                    Bank: <?= $invoice['bank_name']; ?><br>
                    No Rekening: <?= $invoice['bank_number']; ?><br>
                    Atas Nama: <?= $invoice['holder_name']; ?>
                </td>
                <td width="50%" style="text-align: center;">
                    <br>
                    <br>
                    <br>
                    <br>
                    <!-- Tanda Tangan -->
                    <?= $invoice['signatory']; ?>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>