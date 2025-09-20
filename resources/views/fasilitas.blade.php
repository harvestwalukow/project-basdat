<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PawsHotel - Fasilitas</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background-color: #fffaf5;
      color: #333;
    }

    .section {
      padding: 60px 20px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .section h2 {
      text-align: center;
      color: #ff6a28;
      font-size: 32px;
      margin-bottom: 10px;
    }

    .section p.subtitle {
      text-align: center;
      color: #555;
      font-size: 18px;
      margin-bottom: 40px;
    }

    .content {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 30px;
      align-items: start;
    }

    .features {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
    }

    .card {
      background: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
      transition: transform 0.2s ease;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card-icon {
      font-size: 28px;
      color: #ff6a28;
      margin-bottom: 10px;
    }

    .card h3 {
      font-size: 18px;
      margin-bottom: 8px;
      color: #333;
    }

    .card p {
      font-size: 14px;
      color: #666;
    }

    .image-box img {
      width: 100%;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .why {
      margin-top: 20px;
      background: #fff7ef;
      padding: 20px;
      border-radius: 12px;
    }

    .why h3 {
      color: #ff6a28;
      margin-bottom: 15px;
    }

    .why ul {
      list-style: none;
      padding: 0;
    }

    .why ul li {
      margin-bottom: 10px;
      padding-left: 20px;
      position: relative;
    }

    .why ul li::before {
      content: "•";
      color: #ff6a28;
      font-size: 20px;
      position: absolute;
      left: 0;
      top: -2px;
    }

    /* Responsive */
    @media(max-width: 900px) {
      .content {
        grid-template-columns: 1fr;
      }
    }

  </style>
</head>
<body>

  <section class="section">
    <h2>Fasilitas Modern & Lengkap</h2>
    <p class="subtitle">Dilengkapi dengan fasilitas terdepan untuk memberikan pengalaman terbaik bagi hewan peliharaan Anda</p>
    
    <div class="content">
      <!-- Fitur -->
      <div class="features">
        <div class="card">
          <div class="card-icon">❄️</div>
          <h3>Ruangan Ber-AC</h3>
          <p>Semua kamar dilengkapi AC untuk kenyamanan optimal</p>
        </div>
        <div class="card">
          <div class="card-icon">📹</div>
          <h3>CCTV 24/7</h3>
          <p>Pengawasan keamanan dan live streaming untuk pemilik</p>
        </div>
        <div class="card">
          <div class="card-icon">🎮</div>
          <h3>Area Bermain</h3>
          <p>Taman luas dengan berbagai permainan dan obstacle</p>
        </div>
        <div class="card">
          <div class="card-icon">⚕️</div>
          <h3>Klinik In-House</h3>
          <p>Dokter hewan siaga 24 jam untuk emergency</p>
        </div>
        <div class="card">
          <div class="card-icon">🍴</div>
          <h3>Kitchen Premium</h3>
          <p>Menu khusus bergizi untuk hewan kesayangan</p>
        </div>
        <div class="card">
          <div class="card-icon">📶</div>
          <h3>WiFi & Apps</h3>
          <p>Koneksi internet dan aplikasi monitoring untuk pemilik</p>
        </div>
      </div>

      <!-- Gambar + Why -->
      <div>
        <div class="image-box">
          <img src="https://images.unsplash.com/photo-1619983081634-3e16f3a83692?ixlib=rb-4.0.3&q=80&w=1080" alt="Cat on sofa">
        </div>
        <div class="why">
          <h3>Mengapa Memilih PawsHotel?</h3>
          <ul>
            <li>Staff terlatih dan bersertifikat</li>
            <li>Lokasi strategis dan mudah dijangkau</li>
            <li>Fasilitas modern dan nyaman</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

</body>
</html>
