<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>RESI</title>
<style>
  @page {
    size: 8.5in 5.5in;      /* Half Letter landscape */
    margin: 0.25in;         /* margin aman untuk printer */
  }
  body {
    font-family: "DejaVu Sans", Arial, Helvetica, sans-serif;
    font-size: 10px;
    color: #000;
  }

  .border {
    border: 2px solid #c40010;
    border-collapse: collapse;
    width: 100%;
  }
  .border td, .border th {
    border: 1.5px solid #c40010;
    padding: 6px 8px;
    vertical-align: top;
  }

  .red-head {
    background: #c40010;
    color: #fff;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: .3px;
    padding: 4px 8px;
    font-size: 9.5px;
  }

  .header-wrap {
    border: 2px solid #c40010;
    padding: 8px;
    margin-bottom: 6px;
  }
  .header-table { width: 100%; border-collapse: collapse; }
  .logo-box {
    width: 75px; height: 60px; border: 2px solid #c40010; text-align: center; vertical-align: middle;
  }
  .logo-box img { max-width: 100%; max-height: 100%; }
  .company {
    text-align: center;
    padding: 0 6px;
    line-height: 1.25;
  }
  .company .title {
    font-size: 18px;
    font-weight: 800;
    letter-spacing: .5px;
  }
  .company .subtitle {
    font-size: 10px;
    font-weight: 700;
    margin-top: 2px;
  }
  .company .address {
    margin-top: 3px;
    font-size: 9px;
  }

  .right-stamp {
    width: 150px;
    border: 2px solid #c40010;
    vertical-align: top;
  }
  .stamp-head {
    background: #c40010;
    /* background: #137836; */
    color: #fff;
    font-weight: bold;
    text-align: center;
    padding: 4px 0;       /* kecilkan padding vertikal */
    margin: 0;            /* pastikan tidak ada margin */
    line-height: 1.2;     /* rapat */
  }
  .stamp-body {
    padding: 6px;
    line-height: 1.4;
    font-size: 11px;
    font-weight: bold;
    text-align: center;
  }

  .section-title {
    background: #c40010;
    color: #fff;
    font-weight: bold;
    padding: 4px 8px;
    text-transform: uppercase;
    font-size: 9.5px;
  }

  .small { font-size: 9px; }
  .muted { color: #333; }

  /* grid bawah */
  .desc-row td { height: 90px; } /* tinggi area deskripsi */
  .center { text-align: center; }
  .right { text-align: right; }
  .bold { font-weight: bold; }
</style>
</head>
<body>

  <!-- HEADER -->
  <div class="header-wrap">
    <table class="header-table">
      <tr>
        <td class="logo-box">
           <img src="data:image/png;base64,<?= base64_encode(file_get_contents(FCPATH . 'assets/img/logo_rhl.png')); ?>" style="width: 150px; height: auto;" />
        </td>
        <td class="company">
          <div class="title"><?= $company['nama']; ?></div>
          <div class="subtitle">Domestic And International Cargo Services</div>
          <div class="subtitle">OFFICE / KANTOR</div>
          <div class="address">
            <?= $company['alamat']; ?><br>
            Phone : <?= $company['telepon']; ?> &nbsp;&nbsp; Email : <?= $company['email']; ?>
          </div>
        </td>
        <td class="right-stamp" style="border: none;">
        <table style="width:100%; border-collapse: collapse;">
          <tr>
            <!-- Kotak Date -->
            <td style="border:2px solid #c40010; width:50%; vertical-align: top; padding: 0; margin: 0;">
              <div class="stamp-head">Date</div>
              <div class="stamp-body" style="margin-top: 20px;">
                <?= date('d/m/Y', strtotime($label['tanggal'])); ?>
              </div>
            </td>

            <!-- Kotak No. Resi + Barcode -->
            <td style="border:2px solid #c40010; width:50%; vertical-align: top; padding: 0; margin: 0;">
              <div class="stamp-head">No. Resi</div>
              <div class="stamp-body">
                RESI-<?= $label['no_surat_jalan']; ?><br>
                <img src="https://bwipjs-api.metafloor.com/?bcid=code128&text=<?= $label['no_surat_jalan']; ?>&scale=2&height=5&width=16" alt="Barcode">
              </div>
            </td>
          </tr>
        </table>
      </td>
      </tr>
    </table>
  </div>

  <!-- BLOK PENGIRIM / PENERIMA / INFO KOLom KANAN -->
  <table class="border">
    <tr>
      <th class="red-head" style="width: 45%;">Consignor / Pengirim</th>
      <th class="red-head" style="width: 45%;">Consignee / Penerima</th>
      <th class="red-head" style="width: 10%;">Pieces</th>
    </tr>
    <tr>
      <td>
        <div class="bold"><?= $label['nama_pelanggan']; ?></div>
        <?= $label['alamat_pelanggan']; ?>
      </td>
      <td>
        <div class="bold"><?= $label['nama_penerima']; ?></div>
        <?= $label['alamat_penerima']; ?>
      </td>
      <td class="center">
        <?= $label['koli']; ?><br>
        <span class="small muted">Koli</span>
      </td>
    </tr>
  </table>

  <!-- BLOK BARIS 2: URGENT/INSURANCE/WEIGHT/MEASURE (opsional) -->
  <table class="border" style="margin-top: 6px;">
    <tr>
      <th class="red-head" style="width: 20%;">Service</th>
      <th class="red-head" style="width: 20%;">Weight (Kg)</th>
      <th class="red-head" style="width: 20%;">Volume (M³)</th>
      <th class="red-head" style="width: 20%;">Special Instruction</th>
      <th class="red-head" style="width: 20%;">Type Of Shipment</th>
    </tr>
    <tr>
      <td><?= $label['layanan']; ?></td>
      <td class="center"><?= round($label['berat'], 2); ?></td>
      <td class="center"><?= round($label['volume'], 2); ?></td>
      <td>☐ Packing / Packing Kayu <br> ☐ Insurance / Asuransi</td>
      <td>☐ Document/Dokumen <br> ☐ Parcel/Paket</td>
    </tr>
  </table>

  <!-- BLOK DESKRIPSI / REFERENSI / COLLECTED -->
  <table class="border" style="margin-top: 6px;">
    <tr>
      <th class="red-head">Description of Contents</th>
      <th class="red-head" style="width: 25%;">Shipper's Reference</th>
      <th class="red-head" style="width: 20%;">Collected By</th>
      <th class="red-head" style="width: 20%;">Received By</th> <!-- kolom baru -->
    </tr>
    <tr class="desc-row">
      <td>
        <ul style="list-style-type: none;">

            <?php foreach ($barang as $b) {
                echo '<li>- ' . $b['barang'] . ' : ' . $b['jumlah'] . ' ' . $b['satuan'] . '</li>';
            }
            ?>
        </ul>
      </td>
      <td>
        <?php  if (!empty($pengiriman['tanggal_kirim'])) { ?>
          Tanggal: <?= date('d/m/Y', strtotime($pengiriman['tanggal_kirim'])); ?> <br><br>
          <?php }else{?>
          Tanggal: ___________ <br><br>
        <?php } ?>
        Tanda Tangan: ______
      </td>
      <td>
        <?php  if (!empty($pengiriman['tanggal_ambil'])) { ?>
          Tanggal: <?= date('d/m/Y', strtotime($pengiriman['tanggal_ambil'])); ?> <br><br>
          <?php }else{?>
          Tanggal: ___________ <br><br>
        <?php } ?>
        Tanda Tangan: ______
      </td>
      <td>
        <?php  if (!empty($pengiriman['tanggal_terima'])) { ?>
          Tanggal: <?= date('d/m/Y', strtotime($pengiriman['tanggal_terima'])); ?> <br>
          DTO: <?= $pengiriman['dto']; ?></div><br><br>
          <?php }else{?>
          Tanggal: ___________ <br><br>
        <?php } ?>
        Tanda Tangan: ______
      </td>

    </tr>
  </table>

</body>
</html>