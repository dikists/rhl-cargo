<!DOCTYPE html>
<html lang="id">

<head>
     <meta charset="UTF-8">
     <title>Label_<?= $label['no_surat_jalan']; ?></title>
     <style>
          body {
               font-family: Arial, sans-serif;
               text-align: center;
          }

          .container {
               width: 100%;
               border: 2px solid black;
               padding: 5px;
               margin-bottom: 5px;
          }

          .logo {
               margin-bottom: 5px;
          }

          .barcode {
               border-top: 2px solid #000;
          }

          .barcode img {
               width: 100%;
               max-width: 150px;
          }

          .info {
               display: flex;
               justify-content: space-between;
               font-size: 14px;
               font-weight: bold;
               margin: 10px 0;
               border-top: 2px solid #000;
               border-bottom: 2px solid #000;
          }

          .qr {
               border-bottom: 2px solid #000;
          }

          .info>div {
               display: flex;
               flex-direction: row;
               gap: 10px;
               /* Jarak antar teks */
          }

          table {
               width: 100%;
               border-collapse: collapse;
               margin-top: -2px;
               text-align: center;
          }

          /* .destination {
               display: flex;
               justify-content: space-between;
               font-size: 18px;
               font-weight: bold;
               margin: 10px 0;
          } */

          .qr img {
               width: 80px;
               height: 80px;
          }

          .footer {
               font-weight: bold;
               margin-top: 10px;
          }

          .page-break {
               page-break-before: always;
          }
     </style>
</head>

<body>
     <?php $berat = empty($berat) ? '-' : $berat . ' KG'; ?>
     <?php $count = 1; ?>
     <?php for ($i = 0; $i < $qty; $i++) { ?>
          <div class="container">
               <div class="logo">
                    <img src="data:image/png;base64,<?= base64_encode(file_get_contents(FCPATH . 'assets/img/logo-wep.png')); ?>" style="width: 200px; height: auto;" />
               </div>

               <div class="barcode">
                    <h2 style="margin-bottom: -1px; margin-top: -3px;">Nomor Air Waybill</h2>
                    <img src="https://bwipjs-api.metafloor.com/?bcid=code128&text=<?= $label['no_surat_jalan']; ?>&scale=3&height=8" alt="Barcode">
                    <h1 style="margin-bottom: -10px; margin-top: -3px;"><?= $label['no_surat_jalan']; ?></h1>
               </div>

               <div class="info">
                    <table>
                         <tr class="item-detail">
                              <th width="50%">Berat Total</th>
                              <th width="50%">Jumlah Koli</th>
                         </tr>
                         <tr class="item-detail" style="font-weight: bold; font-size: 25px;">.
                              <td><?= $berat; ?></td>
                              <td><?= $count++; ?>/<?= $qty; ?></td>
                         </tr>
                         <tr class="item-detail">
                              <th>Asal</th>
                              <th>Tujuan</th>
                         </tr>
                         <tr class="item-detail" style="font-weight: bold; font-size: 25px;">
                              <td><?= $label['asal']; ?></td>
                              <td><?= $label['tujuan']; ?></td>
                         </tr>
                    </table>
               </div>

               <div class="qr">
                    <table>
                         <tr class="item-detail">
                              <th width="100%">
                                   <h2 style="margin: -5px;"><?= $label['nama_penerima']; ?></h2>
                              </th>
                         </tr>
                         <tr class="item-detail">
                              <th width="100%">
                                   <p style="font-weight: bold; font-size: 28px; margin: 5px"><?= $label['alamat_penerima']; ?></p>
                              </th>
                         </tr>
                    </table>
               </div>

               <div class="footer">
                    <p>WAHANA ELANGCARGO PERKASA</p>
               </div>
          </div>

          <!-- Tambahkan page break untuk halaman baru -->
          <?php if ($i < $qty - 1): ?>
               <div class="page-break"></div>
          <?php endif; ?>

     <?php } ?>

</body>

</html>