<!DOCTYPE html>
<html lang="id">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Manifest Pengiriman Barang</title>
     <style>
          body {
               font-family: Arial, sans-serif;
               margin: 0;
               padding: 0;
               font-size: 12px;
          }

          /* .container {
               max-width: 800px;
               margin: 5px auto;
               padding: 5px;
               border: 1px solid #ccc;
               border-radius: 10px;
          } */

          .header {
               text-align: center;
               margin-bottom: 20px;
          }

          .header h1 {
               margin: 0;
               font-size: 24px;
          }

          .header p {
               margin: 5px 0;
               font-size: 14px;
          }

          .info {
               margin-bottom: 20px;
          }

          .info table {
               width: 100%;
               border-collapse: collapse;
          }

          .info th,
          .info td {
               padding: 0px;
               text-align: left;
          }

          .info th {
               width: 30%;
          }

          .table {
               width: 100%;
               border-collapse: collapse;
               margin-top: 20px;
          }

          .table th,
          .table td {
               border: 1px solid #000;
               padding: 5px;
               text-align: center;
          }

          .table th {
               background-color: #f4f4f4;
          }

          .footer {
               margin-top: 20px;
               text-align: right;
          }

          .footer .signature {
               margin-top: 40px;
          }

          .footer p {
               margin: 0;
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
     </style>
</head>

<body>
     <div class="container">
          <table class="noborder" style="width: 100%; margin-bottom: 5px;" cellspacing="0" cellpadding="0">
               <tr>
                    <td style="width: 20%;" border="0">
                         <img src="data:image/png;base64,<?= base64_encode(file_get_contents(FCPATH . 'assets/img/logo-rhl.png')); ?>" style="width: 150px; height: auto;" />
                    </td>
                    <td style="width: 80%; text-align: center;" border="0">
                         <h3 class="title">PT. Wahana Elangcargo Perkasa</h3>
                         <div class="subtitle"><strong>MANIFEST</strong></div>
                    </td>
               </tr>
          </table>

          <div style="width: 30%; float: left;">
               <table class="meta">
                    <tr>
                         <td><strong>NO:</strong> <?= $manifest[0]['manifest_number']; ?></td>
                    </tr>
                    <tr>
                         <td><strong>TO:</strong> <?= $manifest[0]['vendor_name']; ?></td>
                    </tr>
               </table>
          </div>
          <div style="width: 30%; float: right;">
               <table class="meta">
                    <tr>
                         <td><strong>DATE:</strong> <?= date('d-m-Y', strtotime($manifest[0]['date'])); ?></td>
                    </tr>
                    <tr>
                         <td>-</td>
                    </tr>
               </table>
          </div>
          <br>
          <br>

          <table class="table">
               <thead>
                    <tr>
                         <th>No</th>
                         <th>Surat Jalan</th>
                         <th>Pengirim</th>
                         <th>Penerima</th>
                         <th>To</th>
                         <th>Sub Vendor</th>
                         <th>Koli</th>
                         <th>Kg</th>
                    </tr>
               </thead>
               <?php $no = 1; ?>
               <?php foreach ($detail as $d) : ?>
                    <tbody>
                         <tr>
                              <td><?= $no++; ?></td>
                              <td><?= $d['no_surat_jalan']; ?></td>
                              <td><?= $d['nama_pelanggan']; ?></td>
                              <td><?= $d['nama_penerima']; ?></td>
                              <td><?= $d['kota']; ?></td>
                              <td><?= ($d['vendor_name']) ? $d['vendor_name'] : '-'; ?></td>
                              <td><?= $d['total_jumlah']; ?></td>
                              <td><?= ($d['total_berat'] > $d['total_volume'] ? round($d['total_berat']) : round($d['total_volume'])); ?> KG</td>
                         </tr>
                    </tbody>
               <?php endforeach; ?>
          </table>

          <div class="footer">
               <p>Hormat Kami,</p>
               <div class="signature">
                    <p>(_____________________)</p>
                    <p>Wahana Elangcargo Perkasa</p>
               </div>
          </div>
     </div>
</body>

</html>