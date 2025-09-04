<!DOCTYPE html>
<html lang="id">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Surat Penawaran</title>
     <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
     <style>
          body {
               font-family: Arial, sans-serif;
               margin: 0;
               padding: 20px;
               font-size: 11px;
          }

          .kop-surat {
               text-align: center;
               border-bottom: 3px solid black;
               padding-bottom: 10px;
               margin-bottom: 20px;
          }

          .kop-surat img {
               height: 80px;
               position: absolute;
               left: 20px;
               top: 20px;
          }

          .kop-surat h1 {
               margin: 0;
               font-size: 20px;
               text-transform: uppercase;
          }

          .kop-surat p {
               margin: 5px 0;
               font-size: 12px;
          }

          .kop-surat .alamat {
               font-size: 11px;
               margin-top: 2px;
          }

          .table-bordered {
               border: 1px solid black;
               border-collapse: collapse;
               width: 100%;
          }

          .table-bordered th,
          .table-bordered td {
               border: 1px solid black;
               padding: 8px;
               text-align: left;
          }

          .data tr,
          .data td {
               font-size: 11px;
          }

          .signature-container {
               text-align: left;
               margin-top: 50px;
               margin-left: 50px;
               /* Sesuaikan dengan kebutuhan */
          }

          .signature-title {
               font-weight: bold;
               margin-left: 50px;
          }

          .signature-name {
               font-weight: bold;
               text-decoration: underline;
               margin-top: 60px;
               margin-left: -10px;
               /* Atur jarak antara "Hormat Kami" dan nama */
          }
     </style>
</head>

<body>

     <div class="kop-surat">
          <!-- <img src="logo.png" alt="Logo Perusahaan"> Ganti dengan logo perusahaan -->
          <img src="data:image/png;base64,<?= base64_encode(file_get_contents(FCPATH . 'assets/img/logo_rhl.png')); ?>" style="width: 150px; height: 100px;" />
          <h1>PT. Wahana Elangcargo Perkasa</h1>
          <p class="alamat">Wisma Ciliwung Blok A 108 Jl. Bukit Duri Tanjakan No 54 <br> Tebet Jakarta-Selatan</p>
          <p>Telepon: 085175295353 | Email: rhl@rajawalihandallogistik.com</p>
          <p>Website: rajawalihandallogistik.com</p>
     </div>

     <table width="100%" class="data">
          <tr>
               <td width="10%">
                    Nomor
               </td>
               <td width="2%">
                    :
               </td>
               <td width="63%">
                    <?= $offer[0]['offer_number'] ?>
               </td>
               <td rowspan="2" align="right">
                    Jakarta, <?= date_format(date_create($offer[0]['created_at']), "d F Y") ?>
               </td>
          </tr>
          <tr>
               <td width="10%">
                    Perihal
               </td>
               <td width="2%">
                    :
               </td>
               <td>
                    <?= $offer[0]['offer_text_title'] ?>
               </td>
          </tr>
     </table>

     <p>Kepada Yth, <br> <?= $offer[0]['nama_pelanggan']; ?></p>
     <p><?= $offer[0]['alamat_pelanggan']; ?></p>

     <p>Dengan hormat, <br> <?= $offer[0]['offer_text_opening']; ?></p>

     <table class="table table-bordered mt-4 data">
          <thead style="background-color: #f4f4f4;">
               <tr>
                    <th>Description</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
               </tr>
          </thead>
          <tbody>
               <?php $grandTotal = 0; ?>
               <?php foreach ($offer_details as $detail): ?>
                    <?php $grandTotal += $detail['total']; ?>
                    <tr>
                         <td><?= $detail['item_name']; ?></td>
                         <td><?= $detail['quantity']; ?></td>
                         <td><?= number_format($detail['price'], 0); ?></td>
                         <td><?= number_format($detail['total'], 0); ?></td>
                    </tr>
               <?php endforeach; ?>
          </tbody>
          <tfoot>
               <tr>
                    <th colspan="3" class="text-right">Total Harga</th>
                    <th><?= number_format($grandTotal, 0); ?></th>
               </tr>
          </tfoot>
     </table>
     <br><br>
     Klausal : <p><?= $offer[0]['offer_clause_desc']; ?></p>
     Catatan :<br />QUOTATION DI KIRIM SECARA KOMPUTERISASI, DAN DIANGGAP SAH WALAU TANPA TANDA TANGAN.
     <p>Demikian penawaran yang kami ajukan. Kami harap dapat memenuhi kebutuhan jasa pengiriman barang Anda.</p>

     <div class="signature-container">
          <div class="signature-title">Hormat Kami</div>
          <div class="signature-name">WAHANA ELANGCARGO PERKASA</div>
     </div>
</body>

</html>