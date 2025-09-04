<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Air_Waybill_<?= $label['no_surat_jalan']; ?></title>
     <style>
          @import url('https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap');

          * {
               margin: 0;
               padding: 0;
               box-sizing: border-box;
               font-family: "Roboto Mono", monospace;
               font-optical-sizing: auto;
               font-weight: 700;
               font-style: normal;
          }

          .container {
               width: auto;
               height: 200px;
               margin: 10px 10px auto;
               /* Kontainer penuh */
               /* overflow: hidden; */
               /* Hindari elemen overflow */
               /* border: 1px solid black; */
          }

          .item {
               float: left;
               /* Membuat elemen berjejer horizontal */
               width: auto;
               /* Lebar sesuai konten */
               padding: 10px;
               /* background: #f0f0f0; */
               margin-right: 10px;
               /* Jarak antar elemen */
               /* border: 1px solid #ddd; */
               text-align: center;
          }

          .h3 {
               font-size: 15px;
          }

          .h4 {
               font-size: 13px;
          }

          .container-two {
               width: 100%;
               display: flex;
               justify-content: space-between;
               flex-wrap: nowrap;
               /* Elemen tetap berjejer meski kecil */
               gap: 5px;
               /* Jarak antar elemen */
          }

          .office {
               font-size: 10px;
               flex: 1;
               padding: 10px;
               box-sizing: border-box;
               /* width: 50%; */
          }

          table {
               border-collapse: collapse;
               width: 100%;
               margin-top: 20px;
               border: 2px;
               border-style: solid;
               border-color: black;
          }

          tr,
          th,
          td {
               text-align: center;
               padding: 2px;
               border: 1px;
          }

          .kotak-utama {
               width: 100px;
               /* Lebar kotak */
               height: auto;
               /* Tinggi kotak */
               padding: 10px;
               margin-left: 5px;
          }

          .teks-dengan-kotak {
               text-align: left;
               position: relative;
               /* Posisi relatif untuk mengatur pseudo-elemen */
               padding-right: 40px;
               margin-left: -10px;
               /* Menambahkan ruang untuk kotak */
               font-weight: bold;
               /* Mempertebal teks */
               font-size: 10px;
          }

          /* Pseudo-elemen ::after */
          .teks-dengan-kotak::after {
               content: '';
               /* Konten pseudo-elemen kosong */
               position: absolute;
               /* Posisi absolut untuk mengatur kotak di sebelah kanan teks */
               right: -25px;
               /* Kotak berada di sebelah kanan teks */
               top: -5px;
               /* Kotak berada di atas teks */
               width: 20px;
               /* Lebar kotak */
               height: 20px;
               /* Tinggi kotak */
               background-color: rgb(255, 255, 255);
               /* Warna latar belakang kotak */
               border: 2px solid black;
               /* Garis tepi kotak */
          }

          .container-bawah-head {
               top: 338px;
               position: absolute;
               margin-top: 10px;
               border-left: 2px solid black;
               border-bottom: 1.5px solid black;
               border-top: 1.5px solid black;
               border-right: 2px solid black;
               height: 30px;
               width: 1098px;
               background-color: #6495ED;
               font-family: "Roboto Mono", monospace;
               font-size: 12px;
               color: white;
          }

          .container-bawah {
               top: 370px;
               position: absolute;
               display: flex;
               justify-content: space-between;
               margin-top: 10px;
               border-left: 2px solid black;
               border-bottom: 2px solid black;
               border-right: 2px solid black;
               height: 150px;
               width: 1098px;
          }

          .desc-label {
               position: absolute;
               border-right: 1.3px solid black;
               padding-left: 50px;
               padding-right: 50px;
               margin-right: 30px;
               height: 30px;
          }

          .shipper-label {
               position: absolute;
               left: 424px;
               border-right: 1.3px solid black;
               padding-left: 50px;
               padding-right: 50px;
               height: 30px;
          }

          .collected-label {
               position: absolute;
               left: 661px;
               border-right: 1.3px solid black;
               padding-left: 50px;
               padding-right: 50px;
               height: 30px;
          }

          .received-label {
               position: absolute;
               left: 877px;
               padding-left: 30px;
               padding-right: 30px;
               height: 30px;
          }

          .desc-bawah {
               position: absolute;
               border-right: 1.3px solid black;
               width: 404px;
               height: 130px;
               padding: 10px;
               box-sizing: border-box;
          }

          .shipper-bawah {
               position: absolute;
               border-right: 1.3px solid black;
               width: 217px;
               height: 130px;
               padding: 10px;
               left: 424px;
               box-sizing: border-box;
          }

          .collected-bawah {
               position: absolute;
               border-right: 1.3px solid black;
               width: 195px;
               height: 130px;
               padding: 10px;
               left: 661px;
               box-sizing: border-box;
          }

          .page-break {
               page-break-before: always;
          }

          .colected {
               position: absolute;
               top: 50%;
               left: 68%;
               transform: translate(-50%, -50%) rotate(-12deg);
               font-size: 20px;
               font-weight: bold;
               color: rgba(0, 0, 139);
               /* Warna merah dengan transparansi */
               text-transform: uppercase;
               letter-spacing: 3px;
               border: 3px solid rgba(0, 0, 139);
               /* padding: 15px; */
               padding-left: 25px;
               padding-right: 25px;
               border-radius: 10%;
               text-align: center;
               width: 150px;
          }

          .dto {
               position: absolute;
               top: 50%;
               left: 88%;
               transform: translate(-50%, -50%) rotate(-12deg);
               font-size: 20px;
               font-weight: bold;
               color: rgba(0, 0, 139);
               /* Warna merah dengan transparansi */
               text-transform: uppercase;
               letter-spacing: 3px;
               border: 3px solid rgba(0, 0, 139);
               /* padding: 15px; */
               padding-left: 25px;
               padding-right: 25px;
               border-radius: 10%;
               text-align: center;
               width: 150px;
          }
     </style>
</head>

<body>
     <div class="container">
          <table border="1">
               <tr>
                    <th rowspan="3" style="text-align: left; padding-top: 10px;">
                         <img src="data:image/png;base64,<?= base64_encode(file_get_contents(FCPATH . 'assets/img/logo_wep.png')); ?>" style="width: 150px; height: auto;" />
                         <div style="position: absolute; top: 18px; left: 200px; font-weight: bold; font-size: 28px;">
                              PT. Wahana Elangcargo Perkasa
                         </div>
                    </th>
                    <th style="text-align: center;"></th>
                    <!-- <th>PT. Wahana Elangcargo Perkasa</th> -->
                    <th style="border: 1px solid black; background-color: #6495ED; color: white;">Date/Tanggal</th>
                    <th style="border: 1px solid black; background-color: #6495ED; color: white;">CONSIGNEE NOTE NUMBER</th>
               </tr>
               <tr>
                    <th>
                         <div style="position: absolute; top: 60px; left: 250px; font-weight: bold; font-size: 16px;">
                              Domestic And International Cargo Services
                         </div>
                    </th>
                    <th rowspan="2" style="border: 1px solid black; font-size: 20px"><?= date('d-m-Y', strtotime($label['tanggal'])); ?></th>
                    <th rowspan="2" style="border: 1px solid black; font-size: 20px">
                         <img src="https://bwipjs-api.metafloor.com/?bcid=code128&text=<?= $label['no_surat_jalan']; ?>&scale=3&height=6&width=18" alt="Barcode"> <br>
                         <?= $label['no_surat_jalan']; ?>
                    </th>
               </tr>
               <tr>
                    <th>
                         <div style="position: absolute; top: 80px; left: 250px; font-weight: bold; font-size: 12px;">
                              OFFICE / KANTOR <br>
                              Jl. Bukit duri tanjakan no. 54 | Wisma Ciliwung Blok A 108 <br>
                              Phone : 021 - 83794186 <br>
                              Email : wepcargo@gmail.com
                         </div>
                    </th>
               </tr>
               <tr style="background-color: #6495ED; font-size: 12px; color: white;">
                    <th style="border: 1px solid black">CONSIGNOR/PENGIRIM</th>
                    <th style="border: 1px solid black">CONSIGNEE/PENERIMA</th>
                    <th style="border: 1px solid black">PIECIES/SATUAN</th>
                    <th style="border: 1px solid black">WEIGHT/BERAT</th>
               </tr>
               <tr>
                    <th style="border: 1px solid black; text-align: left; width: 350px">
                         <?= $label['nama_pelanggan']; ?>
                         <br>
                         <div style="font-size: 11px;">
                              <?= $label['alamat_pelanggan']; ?>
                         </div>
                    </th>
                    <th style="border: 1px solid black; text-align: left; width: 400px">
                         <?= $label['nama_penerima']; ?>
                         <br>
                         <div style="font-size: 11px;">
                              <?= $label['alamat_penerima']; ?>
                         </div>
                    </th>
                    <th style="border: 1px solid black; width: 150px">
                         <?= $label['koli']; ?> Koli
                         <div class="kotak-utama">
                              <div class="teks-dengan-kotak">PARCEL/PAKET</div>
                         </div>
                         <div class="kotak-utama">
                              <div class="teks-dengan-kotak">URGENT</div>
                         </div>
                         <div class="kotak-utama">
                              <div class="teks-dengan-kotak">INSURANCE/ASURANSI
                              </div>
                         </div>
                    </th>
                    <th style="border: 1px solid black">
                         <?= round($label['berat'], 2); ?> Kg
                         <div class="kotak-utama">
                              <div class="teks-dengan-kotak">DOCUMENT/DOKUMEN
                              </div>
                         </div>
                         <div class="kotak-utama">
                              <div class="teks-dengan-kotak">NORMAL
                              </div>
                         </div>
                         <div class="kotak-utama">
                              <div class="teks-dengan-kotak">COLLECT
                              </div>
                         </div>
                    </th>
               </tr>
               <!-- <tr style="background-color: #6495ED; font-size: 12px; color: white;">
                    <th style="border: 1px solid black">DESCRIPTION OF CONTENTS / SPECIAL INSTRUCTION</th>
                    <th style="border: 1px solid black">SHIPPER'S REFERENCE</th>
                    <th style="border: 1px solid black">COLLECTED BY WEP</th>
                    <th style="border: 1px solid black">RECEIVED BY CONSIGNEE</th>
               </tr> -->
               <!-- <tr>
                    <th>
                         <div style="height: 150px; font-weight: bold; font-size: 12px;">

                         </div>
                    </th>
                    <th style="border: 1px solid black; width: 50px;">

                    </th>
                    <th style="border: 1px solid black">

                    </th>
                    <th style="border: 1px solid black">

                    </th>
               </tr> -->
          </table>
          <div class="container-bawah-head">
               <div class="desc-label">
                    DESCRIPTION OF CONTENTS / SPECIAL INSTRUCTION
               </div>
               <div class="shipper-label">
                    SHIPPER'S REFERENCE
               </div>
               <div class="collected-label">
                    COLLECTED BY WEP
               </div>
               <div class="received-label">
                    RECEIVED BY CONSIGNEE
               </div>
          </div>
          <div class="container-bawah">
               <div class="desc-bawah">
                    <ul style="list-style-type: none;">

                         <?php foreach ($barang as $b) {
                              echo '<li>- ' . $b['barang'] . ' : ' . $b['jumlah'] . ' ' . $b['satuan'] . '</li>';
                         }
                         ?>
                    </ul>
               </div>
               <div class="shipper-bawah">

               </div>
               <div class="collected-bawah">

               </div>
               <?php
               if (!empty($pengiriman['tanggal_kirim'])) {
               ?>
                    <div class="colected"><?= date('d-m-Y', strtotime($pengiriman['tanggal_kirim'])); ?> <?= $pengiriman['driver']; ?></div>
               <?php
               }
               ?>
               <?php
               if (!empty($pengiriman['tanggal_terima'])) {
               ?>
                    <div class="dto"><?= date('d-m-Y', strtotime($pengiriman['tanggal_terima'])); ?> <?= $pengiriman['dto']; ?></div>
               <?php
               }
               ?>
          </div>
     </div>
     <div class="page-break"></div>
     <div class="container">
          <table border="1">
               <tr>
                    <th rowspan="3" style="text-align: left; padding-top: 10px;">
                         <img src="data:image/png;base64,<?= base64_encode(file_get_contents(FCPATH . 'assets/img/logo_wep.png')); ?>" style="width: 150px; height: auto;" />
                         <div style="position: absolute; top: 18px; left: 200px; font-weight: bold; font-size: 28px;">
                              PT. Wahana Elangcargo Perkasa
                         </div>
                    </th>
                    <th style="text-align: center;"></th>
                    <!-- <th>PT. Wahana Elangcargo Perkasa</th> -->
                    <th style="border: 1px solid black; background-color: #D84040; color: white;">Date/Tanggal</th>
                    <th style="border: 1px solid black; background-color: #D84040; color: white;">CONSIGNEE NOTE NUMBER</th>
               </tr>
               <tr>
                    <th>
                         <div style="position: absolute; top: 60px; left: 250px; font-weight: bold; font-size: 16px;">
                              Domestic And International Cargo Services
                         </div>
                    </th>
                    <th rowspan="2" style="border: 1px solid black; font-size: 20px"><?= date('d-m-Y', strtotime($label['tanggal'])); ?></th>
                    <th rowspan="2" style="border: 1px solid black; font-size: 20px">
                         <img src="https://bwipjs-api.metafloor.com/?bcid=code128&text=<?= $label['no_surat_jalan']; ?>&scale=3&height=6&width=18" alt="Barcode"> <br>
                         <?= $label['no_surat_jalan']; ?>
                    </th>
               </tr>
               <tr>
                    <th>
                         <div style="position: absolute; top: 80px; left: 250px; font-weight: bold; font-size: 12px;">
                              OFFICE / KANTOR <br>
                              Jl. Bukit duri tanjakan no. 54 | Wisma Ciliwung Blok A 108 <br>
                              Phone : 021 - 83794186 <br>
                              Email : wepcargo@gmail.com
                         </div>
                    </th>
               </tr>
               <tr style="background-color: #D84040; font-size: 12px; color: white;">
                    <th style="border: 1px solid black">CONSIGNOR/PENGIRIM</th>
                    <th style="border: 1px solid black">CONSIGNEE/PENERIMA</th>
                    <th style="border: 1px solid black">PIECIES/SATUAN</th>
                    <th style="border: 1px solid black">WEIGHT/BERAT</th>
               </tr>
               <tr>
                    <th style="border: 1px solid black; text-align: left; width: 350px">
                         <?= $label['nama_pelanggan']; ?>
                         <br>
                         <div style="font-size: 11px;">
                              <?= $label['alamat_pelanggan']; ?>
                         </div>
                    </th>
                    <th style="border: 1px solid black; text-align: left; width: 400px">
                         <?= $label['nama_penerima']; ?>
                         <br>
                         <div style="font-size: 11px;">
                              <?= $label['alamat_penerima']; ?>
                         </div>
                    </th>
                    <th style="border: 1px solid black; width: 150px">
                         <?= $label['koli']; ?> Koli
                         <div class="kotak-utama">
                              <div class="teks-dengan-kotak">PARCEL/PAKET</div>
                         </div>
                         <div class="kotak-utama">
                              <div class="teks-dengan-kotak">URGENT</div>
                         </div>
                         <div class="kotak-utama">
                              <div class="teks-dengan-kotak">INSURANCE/ASURANSI
                              </div>
                         </div>
                    </th>
                    <th style="border: 1px solid black">
                         <?= round($label['berat']); ?> Kg
                         <div class="kotak-utama">
                              <div class="teks-dengan-kotak">DOCUMENT/DOKUMEN
                              </div>
                         </div>
                         <div class="kotak-utama">
                              <div class="teks-dengan-kotak">NORMAL
                              </div>
                         </div>
                         <div class="kotak-utama">
                              <div class="teks-dengan-kotak">COLLECT
                              </div>
                         </div>
                    </th>
               </tr>
               <!-- <tr style="background-color: #6495ED; font-size: 12px; color: white;">
                    <th style="border: 1px solid black">DESCRIPTION OF CONTENTS / SPECIAL INSTRUCTION</th>
                    <th style="border: 1px solid black">SHIPPER'S REFERENCE</th>
                    <th style="border: 1px solid black">COLLECTED BY WEP</th>
                    <th style="border: 1px solid black">RECEIVED BY CONSIGNEE</th>
               </tr> -->
               <!-- <tr>
                    <th>
                         <div style="height: 150px; font-weight: bold; font-size: 12px;">

                         </div>
                    </th>
                    <th style="border: 1px solid black; width: 50px;">

                    </th>
                    <th style="border: 1px solid black">

                    </th>
                    <th style="border: 1px solid black">

                    </th>
               </tr> -->
          </table>
          <div class="container-bawah-head" style="margin-top: 20px; background-color: #D84040;">
               <div class="desc-label">
                    DESCRIPTION OF CONTENTS / SPECIAL INSTRUCTION
               </div>
               <div class="shipper-label">
                    SHIPPER'S REFERENCE
               </div>
               <div class="collected-label">
                    COLLECTED BY WEP
               </div>
               <div class="received-label">
                    RECEIVED BY CONSIGNEE
               </div>
          </div>
          <div class="container-bawah">
               <div class="desc-bawah">
                    <ul style="list-style-type: none;">

                         <?php foreach ($barang as $b) {
                              echo '<li>- ' . $b['barang'] . ' : ' . $b['jumlah'] . ' ' . $b['satuan'] . '</li>';
                         }
                         ?>
                    </ul>
               </div>
               <div class="shipper-bawah">

               </div>
               <div class="collected-bawah">

               </div>
               <?php
               if (!empty($pengiriman['tanggal_kirim'])) {
               ?>
                    <div class="colected"><?= date('d-m-Y', strtotime($pengiriman['tanggal_kirim'])); ?> <?= $pengiriman['driver']; ?></div>
               <?php
               }
               ?>
               <?php
               if (!empty($pengiriman['tanggal_terima'])) {
               ?>
                    <div class="dto"><?= date('d-m-Y', strtotime($pengiriman['tanggal_terima'])); ?> <?= $pengiriman['dto']; ?></div>
               <?php
               }
               ?>
          </div>
     </div>
     <div class="page-break"></div>
     <div class="container">
          <table border="1">
               <tr>
                    <th rowspan="3" style="text-align: left; padding-top: 10px;">
                         <img src="data:image/png;base64,<?= base64_encode(file_get_contents(FCPATH . 'assets/img/logo_wep.png')); ?>" style="width: 150px; height: auto;" />
                         <div style="position: absolute; top: 18px; left: 200px; font-weight: bold; font-size: 28px;">
                              PT. Wahana Elangcargo Perkasa
                         </div>
                    </th>
                    <th style="text-align: center;"></th>
                    <!-- <th>PT. Wahana Elangcargo Perkasa</th> -->
                    <th style="border: 1px solid black; background-color: #009990; color: white;">Date/Tanggal</th>
                    <th style="border: 1px solid black; background-color: #009990; color: white;">CONSIGNEE NOTE NUMBER</th>
               </tr>
               <tr>
                    <th>
                         <div style="position: absolute; top: 60px; left: 250px; font-weight: bold; font-size: 16px;">
                              Domestic And International Cargo Services
                         </div>
                    </th>
                    <th rowspan="2" style="border: 1px solid black; font-size: 20px"><?= date('d-m-Y', strtotime($label['tanggal'])); ?></th>
                    <th rowspan="2" style="border: 1px solid black; font-size: 20px">
                         <img src="https://bwipjs-api.metafloor.com/?bcid=code128&text=<?= $label['no_surat_jalan']; ?>&scale=3&height=6&width=18" alt="Barcode"> <br>
                         <?= $label['no_surat_jalan']; ?>
                    </th>
               </tr>
               <tr>
                    <th>
                         <div style="position: absolute; top: 80px; left: 250px; font-weight: bold; font-size: 12px;">
                              OFFICE / KANTOR <br>
                              Jl. Bukit duri tanjakan no. 54 | Wisma Ciliwung Blok A 108 <br>
                              Phone : 021 - 83794186 <br>
                              Email : wepcargo@gmail.com
                         </div>
                    </th>
               </tr>
               <tr style="background-color: #009990; font-size: 12px; color: white;">
                    <th style="border: 1px solid black">CONSIGNOR/PENGIRIM</th>
                    <th style="border: 1px solid black">CONSIGNEE/PENERIMA</th>
                    <th style="border: 1px solid black">PIECIES/SATUAN</th>
                    <th style="border: 1px solid black">WEIGHT/BERAT</th>
               </tr>
               <tr>
                    <th style="border: 1px solid black; text-align: left; width: 350px">
                         <?= $label['nama_pelanggan']; ?>
                         <br>
                         <div style="font-size: 11px;">
                              <?= $label['alamat_pelanggan']; ?>
                         </div>
                    </th>
                    <th style="border: 1px solid black; text-align: left; width: 400px">
                         <?= $label['nama_penerima']; ?>
                         <br>
                         <div style="font-size: 11px;">
                              <?= $label['alamat_penerima']; ?>
                         </div>
                    </th>
                    <th style="border: 1px solid black; width: 150px">
                         <?= $label['koli']; ?> Koli
                         <div class="kotak-utama">
                              <div class="teks-dengan-kotak">PARCEL/PAKET</div>
                         </div>
                         <div class="kotak-utama">
                              <div class="teks-dengan-kotak">URGENT</div>
                         </div>
                         <div class="kotak-utama">
                              <div class="teks-dengan-kotak">INSURANCE/ASURANSI
                              </div>
                         </div>
                    </th>
                    <th style="border: 1px solid black">
                         <?= round($label['berat']); ?> Kg
                         <div class="kotak-utama">
                              <div class="teks-dengan-kotak">DOCUMENT/DOKUMEN
                              </div>
                         </div>
                         <div class="kotak-utama">
                              <div class="teks-dengan-kotak">NORMAL
                              </div>
                         </div>
                         <div class="kotak-utama">
                              <div class="teks-dengan-kotak">COLLECT
                              </div>
                         </div>
                    </th>
               </tr>
               <!-- <tr style="background-color: #6495ED; font-size: 12px; color: white;">
                    <th style="border: 1px solid black">DESCRIPTION OF CONTENTS / SPECIAL INSTRUCTION</th>
                    <th style="border: 1px solid black">SHIPPER'S REFERENCE</th>
                    <th style="border: 1px solid black">COLLECTED BY WEP</th>
                    <th style="border: 1px solid black">RECEIVED BY CONSIGNEE</th>
               </tr> -->
               <!-- <tr>
                    <th>
                         <div style="height: 150px; font-weight: bold; font-size: 12px;">

                         </div>
                    </th>
                    <th style="border: 1px solid black; width: 50px;">

                    </th>
                    <th style="border: 1px solid black">

                    </th>
                    <th style="border: 1px solid black">

                    </th>
               </tr> -->
          </table>
          <div class="container-bawah-head" style="margin-top: 20px; background-color: #009990;">
               <div class="desc-label">
                    DESCRIPTION OF CONTENTS / SPECIAL INSTRUCTION
               </div>
               <div class="shipper-label">
                    SHIPPER'S REFERENCE
               </div>
               <div class="collected-label">
                    COLLECTED BY WEP
               </div>
               <div class="received-label">
                    RECEIVED BY CONSIGNEE
               </div>
          </div>
          <div class="container-bawah">
               <div class="desc-bawah">
                    <ul style="list-style-type: none;">

                         <?php foreach ($barang as $b) {
                              echo '<li>- ' . $b['barang'] . ' : ' . $b['jumlah'] . ' ' . $b['satuan'] . '</li>';
                         }
                         ?>
                    </ul>
               </div>
               <div class="shipper-bawah">

               </div>
               <div class="collected-bawah">

               </div>
               <?php
               if (!empty($pengiriman['tanggal_kirim'])) {
               ?>
                    <div class="colected"><?= date('d-m-Y', strtotime($pengiriman['tanggal_kirim'])); ?> <?= $pengiriman['driver']; ?></div>
               <?php
               }
               ?>
               <?php
               if (!empty($pengiriman['tanggal_terima'])) {
               ?>
                    <div class="dto"><?= date('d-m-Y', strtotime($pengiriman['tanggal_terima'])); ?> <?= $pengiriman['dto']; ?></div>
               <?php
               }
               ?>
          </div>
     </div>
</body>

</html>