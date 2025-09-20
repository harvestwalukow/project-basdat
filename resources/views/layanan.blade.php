<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>PetHotel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-family: Arial, sans-serif; }
    .hero {
      position: relative;
      background: linear-gradient(to right, #2563eb, #7c3aed);
      color: white;
    }
    .hero::after {
      content: "";
      position: absolute;
      inset: 0;
      background: rgba(0,0,0,0.2);
    }
    .hero-content {
      position: relative;
      z-index: 1;
    }
    .feature-card {
      transition: box-shadow 0.2s ease;
    }
    .feature-card:hover {
      box-shadow: 0px 4px 16px rgba(0,0,0,0.1);
    }
    .cta {
      background: #2563eb;
      color: white;
    }
    .star {
      color: #facc15;
    }
  </style>
</head>
<body>

  <!-- Hero Section -->
  <section class="hero py-5">
    <div class="container hero-content">
      <div class="row align-items-center">
        <div class="col-lg-6 mb-4">
          <h1 class="display-4 fw-bold mb-3">Tempat Nyaman untuk Anjing & Kucing Anda</h1>
          <p class="lead mb-4">
            Hotel hewan terpercaya dengan fasilitas lengkap, perawatan profesional, 
            dan kasih sayang seperti di rumah sendiri.
          </p>
          <div class="d-flex flex-wrap gap-3">
            <a href="#register" class="btn btn-light text-primary btn-lg">Daftar Sekarang</a>
            <a href="#services" class="btn btn-outline-light btn-lg">Lihat Paket</a>
          </div>
        </div>
        <div class="col-lg-6">
          <img src="https://images.unsplash.com/photo-1668522907255-62950845ff46?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1080" 
               alt="Happy pets playing" 
               class="img-fluid rounded shadow-lg">
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section class="py-5 bg-light">
    <div class="container text-center">
      <h2 class="mb-3">Mengapa Memilih PetHotel?</h2>
      <p class="text-muted mb-5">
        Kami menyediakan perawatan terbaik dengan fasilitas modern dan tim profesional 
        yang berpengalaman dalam merawat hewan peliharaan.
      </p>
      <div class="row g-4">
        <div class="col-md-6 col-lg-3">
          <div class="card p-4 feature-card h-100 text-center">
            <div class="fs-1 mb-3">🏨</div>
            <h5>Fasilitas Mewah</h5>
            <p class="text-muted">Kandang ber-AC dengan tempat tidur empuk dan mainan untuk kenyamanan hewan peliharaan Anda</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="card p-4 feature-card h-100 text-center">
            <div class="fs-1 mb-3">👨‍⚕</div>
            <h5>Dokter Hewan Standby</h5>
            <p class="text-muted">Tim dokter hewan profesional siap 24/7 untuk memastikan kesehatan hewan kesayangan</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="card p-4 feature-card h-100 text-center">
            <div class="fs-1 mb-3">✨</div>
            <h5>Grooming & Spa</h5>
            <p class="text-muted">Layanan grooming lengkap dengan peralatan modern untuk perawatan bulu dan kuku</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="card p-4 feature-card h-100 text-center">
            <div class="fs-1 mb-3">🎾</div>
            <h5>Area Bermain</h5>
            <p class="text-muted">Ruang bermain luas untuk aktivitas fisik dan sosialisasi antar hewan</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="cta py-5 text-center">
    <div class="container">
      <h2 class="mb-3">Siap Memberikan Liburan untuk Hewan Kesayangan?</h2>
      <p class="lead mb-4 text-light">Reservasi sekarang dan berikan pengalaman menginap terbaik untuk hewan peliharaan Anda.</p>
      <a href="#reservation" class="btn btn-light text-primary btn-lg">Mulai Reservasi</a>
    </div>
  </section>

  <!-- Testimonials -->
  <section class="py-5">
    <div class="container text-center">
      <h2 class="mb-5">Apa Kata Pelanggan Kami</h2>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card p-4 h-100">
            <div class="mb-3">
              <span class="star">⭐</span><span class="star">⭐</span><span class="star">⭐</span><span class="star">⭐</span><span class="star">⭐</span>
            </div>
            <p class="text-muted">"PetHotel sangat memuaskan! Kucing saya Mimi terlihat bahagia dan sehat setelah menginap 1 minggu."</p>
            <p class="fw-bold">Sarah Wijaya</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card p-4 h-100">
            <div class="mb-3">
              <span class="star">⭐</span><span class="star">⭐</span><span class="star">⭐</span><span class="star">⭐</span><span class="star">⭐</span>
            </div>
            <p class="text-muted">"Fasilitas lengkap dan perawatan profesional. Anjing saya Max suka sekali dengan area bermainnya."</p>
            <p class="fw-bold">Budi Santoso</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card p-4 h-100">
            <div class="mb-3">
              <span class="star">⭐</span><span class="star">⭐</span><span class="star">⭐</span><span class="star">⭐</span><span class="star">⭐</span>
            </div>
            <p class="text-muted">"Staff ramah dan berpengalaman. Sangat recommended untuk yang butuh penitipan hewan terpercaya."</p>
            <p class="fw-bold">Diana Putri</p>
          </div>
        </div>
      </div>
    </div>
  </section>

</body>
</html>