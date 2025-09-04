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
<?php
// Kelompokkan data berdasarkan penerima + kota
$grouped_data = [];
foreach ($combined_data as $item) {
     $group_key = $item['nama_penerima'] . '|' . $item['kota'] . '|' . $item['no_surat_jalan'];
     $grouped_data[$group_key][] = $item;
}
?>

<body>
     <div class="container">
          <table class="noborder" style="width: 100%; margin-bottom: 5px;" cellspacing="0" cellpadding="0">
               <tr>
                    <td style="width: 20%;" border="0">
                         <img src="data:image/png;base64,<?= base64_encode(file_get_contents(FCPATH . 'assets/img/logo-wep.png')); ?>" style="width: 150px; height: auto;" />
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
                         <th rowspan="2">No</th>
                         <th rowspan="2">Consignee</th>
                         <th rowspan="2">TO</th>
                         <th rowspan="2">Koli</th>
                         <th rowspan="2">KG</th>
                         <th colspan="3">Volume</th>
                         <th rowspan="2">Total</th>
                         <th rowspan="2">Keterangan</th>
                    </tr>
                    <tr>
                         <th>P</th>
                         <th>L</th>
                         <th>T</th>
                    </tr>
               </thead>
               <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($grouped_data as $group => $items): ?>
                         <?php
                         $rowspan = count($items);
                         $first = true;
                         $total = $total_koli = $total_volume = 0;

                         // Hitung total dulu di loop terpisah
                         foreach ($items as $item) {
                              $volume = round(($item['p'] * $item['l'] * $item['t'] / $item['divider']) * $item['koli']);
                              $v = (($item['p'] * $item['l'] * $item['t']) * $item['koli']) / 1000000;
                              $berat = round($item['kg'] * $item['koli']);
                              $total_hitung = ($berat > $volume) ? $berat : $volume;
                              $total += $total_hitung;
                              $total_koli += $item['koli'];
                              $total_volume += $v;
                         }

                         // Loop untuk menampilkan data
                         foreach ($items as $item):
                              $volume = round(($item['p'] * $item['l'] * $item['t'] / $item['divider']) * $item['koli']);
                              $berat = round($item['kg'] * $item['koli']);
                              $total_hitung = ($berat > $volume) ? $berat : $volume;
                         ?>
                              <tr>
                                   <?php if ($first): ?>
                                        <td><?= $no++; ?></td>
                                        <td><?= $item['sub_vendor_name']; ?></td>
                                        <td><?= $item['kota']; ?></td>
                                   <?php else: ?>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                   <?php endif; ?>

                                   <td><?= $item['koli']; ?></td>
                                   <td><?= $item['kg']; ?></td>
                                   <td><?= $item['p']; ?></td>
                                   <td><?= $item['l']; ?></td>
                                   <td><?= $item['t']; ?></td>
                                   <td><?= $total_hitung ?></td>
                                   <?php if ($first): ?>
                                        <td rowspan="<?= $rowspan; ?>">
                                             Total Koli: <?= $total_koli; ?><br>
                                             Berat Total: <?= $total; ?> Kg <br>
                                             <?php if($item['layanan'] !== 'UDARA REG' && $item['layanan'] !== 'UDARA EXP'){ ?>
                                             Volume Total: <?= round($total_volume, 3); ?> M<sup>3</sup> <br>
                                             <?php } ?>
                                        </td>
                                   <?php endif; ?>
                              </tr>
                         <?php
                              $first = false;
                         endforeach;
                         ?>

                         <!-- GAP ANTAR GRUP -->
                         <tr>
                              <td style="height: 15px;"></td>
                              <td style="height: 15px;"></td>
                              <td style="height: 15px;"></td>
                              <td style="height: 15px;"></td>
                              <td style="height: 15px;"></td>
                              <td style="height: 15px;"></td>
                              <td style="height: 15px;"></td>
                              <td style="height: 15px;"></td>
                              <td style="height: 15px;"></td>
                              <td style="height: 15px;"></td>
                         </tr>
                         <tr>
                              <td style="height: 15px;"></td>
                              <td style="height: 15px;"></td>
                              <td style="height: 15px;"></td>
                              <td style="height: 15px;"></td>
                              <td style="height: 15px;"></td>
                              <td style="height: 15px;"></td>
                              <td style="height: 15px;"></td>
                              <td style="height: 15px;"></td>
                              <td style="height: 15px;"></td>
                              <td style="height: 15px;"></td>
                         </tr>

                    <?php endforeach; ?>
               </tbody>

               <!-- <tbody>
                    <tr>
                         <td>1</td>
                         <td class="left">VKPP-KJP</td>
                         <td>BPN</td>
                         <td>3</td>
                         <td>47</td>
                         <td>27</td>
                         <td>27</td>
                         <td>20</td>
                         <td>7</td>
                         <td class="left" rowspan="10">TOTAL: 10 COLI<br>130 Kg, Sparepart</td>
                    </tr>
                    <tr>
                         <td>2</td>
                         <td class="left"></td>
                         <td></td>
                         <td>1</td>
                         <td>33</td>
                         <td>27</td>
                         <td>27</td>
                         <td>15</td>
                         <td>3</td>
                    </tr>
                    <tr>
                         <td>3</td>
                         <td class="left"></td>
                         <td></td>
                         <td>1</td>
                         <td>33</td>
                         <td>33</td>
                         <td>27</td>
                         <td>15</td>
                         <td>3</td>
                    </tr>
                    <tr>
                         <td>4</td>
                         <td class="left"></td>
                         <td></td>
                         <td>4</td>
                         <td>39</td>
                         <td>36</td>
                         <td>20</td>
                         <td>20</td>
                         <td>7</td>
                    </tr>
                    <tr>
                         <td>5</td>
                         <td class="left"></td>
                         <td></td>
                         <td>14</td>
                         <td>42</td>
                         <td>36</td>
                         <td>36</td>
                         <td>22</td>
                         <td>8</td>
                    </tr>
                    <tr>
                         <td>6</td>
                         <td class="left"></td>
                         <td></td>
                         <td>4</td>
                         <td>46</td>
                         <td>39</td>
                         <td>11</td>
                         <td>6</td>
                         <td>6</td>
                    </tr>
                    <tr>
                         <td>7</td>
                         <td class="left"></td>
                         <td></td>
                         <td>14</td>
                         <td>65</td>
                         <td>55</td>
                         <td>15</td>
                         <td>14</td>
                         <td>14</td>
                    </tr>
                    <tr>
                         <td>8</td>
                         <td class="left"></td>
                         <td></td>
                         <td>7</td>
                         <td>50</td>
                         <td>20</td>
                         <td>7</td>
                         <td>5</td>
                         <td>5</td>
                    </tr>
                    <tr>
                         <td>9</td>
                         <td class="left"></td>
                         <td></td>
                         <td>35</td>
                         <td>35</td>
                         <td>24</td>
                         <td>5</td>
                         <td>5</td>
                         <td>35</td>
                    </tr>
                    <tr>
                         <td>10</td>
                         <td class="left"></td>
                         <td></td>
                         <td>3</td>
                         <td>27</td>
                         <td>22</td>
                         <td>15</td>
                         <td>15</td>
                         <td>3</td>
                    </tr>

                    <tr>
                         <td>11</td>
                         <td class="left">BMT</td>
                         <td>MDC</td>
                         <td>6</td>
                         <td>36</td>
                         <td>27</td>
                         <td>28</td>
                         <td>6</td>
                         <td class="left" rowspan="6">TOTAL: 6 COLI<br>26 KG, Sparepart</td>
                    </tr>
                    <tr>
                         <td>12</td>
                         <td class="left"></td>
                         <td></td>
                         <td>11</td>
                         <td>45</td>
                         <td>43</td>
                         <td>16</td>
                         <td>11</td>
                         <td></td>
                    </tr>
                    <tr>
                         <td>13</td>
                         <td class="left"></td>
                         <td></td>
                         <td>3</td>
                         <td>33</td>
                         <td>22</td>
                         <td>15</td>
                         <td>3</td>
                         <td></td>
                    </tr>
                    <tr>
                         <td>14</td>
                         <td class="left"></td>
                         <td></td>
                         <td>1</td>
                         <td>33</td>
                         <td>22</td>
                         <td>15</td>
                         <td>2</td>
                         <td></td>
                    </tr>
                    <tr>
                         <td>15</td>
                         <td class="left"></td>
                         <td></td>
                         <td>1</td>
                         <td>33</td>
                         <td>22</td>
                         <td>15</td>
                         <td>2</td>
                         <td></td>
                    </tr>
                    <tr>
                         <td>16</td>
                         <td class="left"></td>
                         <td></td>
                         <td>1</td>
                         <td>58</td>
                         <td>20</td>
                         <td>10</td>
                         <td>2</td>
                         <td></td>
                    </tr>
               </tbody> -->
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