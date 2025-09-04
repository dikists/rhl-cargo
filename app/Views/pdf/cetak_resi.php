<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Airwaybill</title>
     <link rel="stylesheet" href="styles.css">
     <style>
          body {
               font-family: Arial, sans-serif;
               margin: 0;
               padding: 0;
               font-size: 12px;
               color: #000;
          }

          .airwaybill {
               width: 800px;
               margin: auto;
               border: 1px solid #000;
               padding: 10px;
          }

          .header {
               display: flex;
               justify-content: space-between;
               align-items: center;
               border-bottom: 2px solid #000;
               padding-bottom: 10px;
          }

          .logo-section {
               width: 15%;
          }

          .logo {
               max-width: 100%;
               height: auto;
          }

          .company-info {
               width: 55%;
               text-align: left;
          }

          .note-info {
               width: 25%;
               text-align: right;
          }

          .note-info h2 {
               margin: 0;
               font-size: 24px;
          }

          .section {
               display: flex;
               justify-content: space-between;
               margin: 20px 0;
          }

          .sender,
          .receiver {
               width: 45%;
          }

          .details {
               width: 100%;
               border-collapse: collapse;
               margin-bottom: 10px;
          }

          .details th,
          .details td {
               border: 1px solid #000;
               padding: 5px;
               text-align: center;
          }

          .description {
               margin: 10px 0;
          }

          .footer {
               display: flex;
               justify-content: space-between;
               text-align: center;
          }

          .footer div {
               width: 23%;
               border-top: 1px solid #000;
               padding-top: 10px;
          }
     </style>
</head>

<body>
     <div class="airwaybill">
          <header class="header">
               <div class="logo-section">
                    <img src="logo-placeholder.png" alt="Logo" class="logo">
               </div>
               <div class="company-info">
                    <h1>PT. WAHANA ELANGCARGO PERKASA</h1>
                    <p>Domestic And International Cargo Services</p>
                    <p><b>OFFICE / KANTOR:</b> Jl. Jatinegara Barat No. 187 CDE RW 001<br>
                         Kel. Kampung Melayu, Kec. Jatinegara - 13320<br>
                         Phone: 021 - 8571498<br>
                         Email: rhl@rajawalihandallogistik.com</p>
                    <p><b>WAREHOUSE / GUDANG:</b> Wisma Cilincing Blok A 108<br>
                         Bukit Duri Tanjakan No. 54 - 12540<br>
                         Phone: 021 - 8593694<br>
                         Email: rhl@rajawalihandallogistik.com</p>
               </div>
               <div class="note-info">
                    <p><b>Date/Tanggal:</b> 2024-11-21</p>
                    <p><b>CONSIGNEE NOTE NUMBER:</b></p>
                    <h2>022634</h2>
               </div>
          </header>
          <main>
               <div class="section">
                    <div class="sender">
                         <h3>CONSIGNOR / PENGIRIM</h3>
                         <p>PT LiuGong Machinery Indonesia<br>
                              The Kensington Office Tower 12th Floor,<br>
                              Jl. Boulevard Raya No.1 Kelapa Gading, Jakarta Utara, 14240<br>
                              Tel: 0822-1822-5097</p>
                    </div>
                    <div class="receiver">
                         <h3>CONSIGNEE / PENERIMA</h3>
                         <p>PDC MANADO<br>
                              Jl. Raya Manado-Bitung, Pergudangan Angropolisi, Blok G8,<br>
                              Woloan II Kec. Kalawat, Kabupaten Minahasa Utara<br>
                              Block G4 (UP: TAILRU)<br>
                              Tel: 08532901826</p>
                    </div>
               </div>
               <table class="details">
                    <tr>
                         <th>PIECES/SATUAN</th>
                         <th>WEIGHT/BERAT</th>
                         <th>DOCUMENT</th>
                         <th>FREIGHT</th>
                    </tr>
                    <tr>
                         <td>3 Koli</td>
                         <td>32 KG</td>
                         <td>☐</td>
                         <td>☐</td>
                    </tr>
               </table>
               <p class="description"><b>DESCRIPTION OF CONTENTS / SPECIAL INSTRUCTION:</b> Sparepart Dll</p>
          </main>
          <footer class="footer">
               <div>
                    <p><b>SHIPPER'S REFERENCE:</b></p>
                    <p>Date/Tanggal:</p>
               </div>
               <div>
                    <p><b>COLLECTED BY RHL CARGO:</b></p>
                    <p>Date/Tanggal:</p>
               </div>
               <div>
                    <p><b>RECEIVED BY CONSIGNEE:</b></p>
                    <p>Date/Tanggal:</p>
               </div>
               <div>
                    <p><b>CONSIGNEE'S SIGNATURE:</b></p>
                    <p>Date/Tanggal:</p>
               </div>
          </footer>
     </div>
</body>

</html>