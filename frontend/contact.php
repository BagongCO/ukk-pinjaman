<?php
include "config/connection.php";

// Proses pengiriman pesan kontak
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($connect, $_POST['name']);
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $phone = mysqli_real_escape_string($connect, $_POST['phone'] ?? '');
    $subject = mysqli_real_escape_string($connect, $_POST['subject']);
    $message = mysqli_real_escape_string($connect, $_POST['message']);
    
    $query = "INSERT INTO contacts (name, email, phone, subject, message, status, created_at) 
              VALUES ('$name', '$email', '$phone', '$subject', '$message', 'unread', NOW())";
    
    if (mysqli_query($connect, $query)) {
        $success_message = "Pesan Anda berhasil dikirim! Kami akan segera merespon.";
    } else {
        $error_message = "Gagal mengirim pesan: " . mysqli_error($connect);
    }
}
?>

<!-- Contact Info Section -->
<section class="py-5" id="contact">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100">
                    <div class="mb-3">
                        <div class="bg-purple-mist d-inline-flex p-3 rounded-circle">
                            <i class="fas fa-map-marker-alt fa-2x text-purple-primary"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold">Alamat Kami</h5>
                    <p class="text-muted mb-0">Jl. Lampu Raya No. 123<br>Jakarta Selatan, Indonesia</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100">
                    <div class="mb-3">
                        <div class="bg-purple-mist d-inline-flex p-3 rounded-circle">
                            <i class="fas fa-phone-alt fa-2x text-purple-primary"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold">Nomor Telepon</h5>
                    <p class="text-muted mb-0">+62 812 3456 7890<br>+62 811 2345 6789</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100">
                    <div class="mb-3">
                        <div class="bg-purple-mist d-inline-flex p-3 rounded-circle">
                            <i class="fas fa-envelope fa-2x text-purple-primary"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold">Email</h5>
                    <p class="text-muted mb-0">info@sewalampu.com<br>cs@sewalampu.com</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form & Map Section -->
<section class="py-4">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-5">
                        <h3 class="fw-bold mb-4">Kirim Pesan <span class="text-purple-primary">Kepada Kami</span></h3>
                        
                        <?php if ($success_message): ?>
                            <div class="alert alert-success rounded-3">
                                <i class="fas fa-check-circle me-2"></i> <?= $success_message ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger rounded-3">
                                <i class="fas fa-exclamation-circle me-2"></i> <?= $error_message ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control form-control-lg rounded-3" placeholder="Masukkan nama Anda" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control form-control-lg rounded-3" placeholder="email@example.com" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Nomor Telepon</label>
                                    <input type="tel" name="phone" class="form-control form-control-lg rounded-3" placeholder="081234567890">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Subjek <span class="text-danger">*</span></label>
                                    <input type="text" name="subject" class="form-control form-control-lg rounded-3" placeholder="Subjek pesan" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Pesan <span class="text-danger">*</span></label>
                                    <textarea name="message" class="form-control form-control-lg rounded-3" rows="5" placeholder="Tulis pesan Anda di sini..." required></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" name="submit" class="btn-gradient px-5 py-3 w-100">
                                        <i class="fas fa-paper-plane me-2"></i> Kirim Pesan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-0">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.5211773276665!2d106.828717!3d-6.200000!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f391c209f6f9%3A0x8c2c2b2b2b2b2b2b!2sJakarta!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" 
                            width="100%" 
                            height="450" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5 bg-purple-mist">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Pertanyaan <span class="text-purple-primary">Umum</span></h2>
            <p>Informasi yang sering ditanyakan oleh pelanggan kami</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="bg-white rounded-4 p-4 h-100 shadow-sm">
                    <div class="d-flex gap-3">
                        <i class="fas fa-question-circle fa-2x text-purple-primary"></i>
                        <div>
                            <h5 class="fw-bold">Berapa minimal hari sewa lampu?</h5>
                            <p class="text-muted mb-0">Minimal peminjaman lampu adalah 1 hari. Untuk acara besar bisa didiskusikan lebih lanjut.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="bg-white rounded-4 p-4 h-100 shadow-sm">
                    <div class="d-flex gap-3">
                        <i class="fas fa-truck fa-2x text-purple-primary"></i>
                        <div>
                            <h5 class="fw-bold">Apakah ada layanan antar jemput?</h5>
                            <p class="text-muted mb-0">Ya, kami menyediakan layanan antar jemput dengan biaya tambahan sesuai jarak.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="bg-white rounded-4 p-4 h-100 shadow-sm">
                    <div class="d-flex gap-3">
                        <i class="fas fa-money-bill-wave fa-2x text-purple-primary"></i>
                        <div>
                            <h5 class="fw-bold">Metode pembayaran apa saja?</h5>
                            <p class="text-muted mb-0">Kami menerima transfer bank (BCA, Mandiri, BRI), tunai, dan QRIS (Dana, OVO, GoPay).</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6" data-aos="fade-up" data-aos-delay="400">
                <div class="bg-white rounded-4 p-4 h-100 shadow-sm">
                    <div class="d-flex gap-3">
                        <i class="fas fa-headset fa-2x text-purple-primary"></i>
                        <div>
                            <h5 class="fw-bold">Jam operasional customer service?</h5>
                            <p class="text-muted mb-0">CS kami buka Senin-Sabtu pukul 09.00-21.00 WIB, dan layanan darurat 24 jam.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Social Media Section -->
<section class="py-5">
    <div class="container">
        <div class="cta-banner text-center" data-aos="zoom-in">
            <h3 class="mb-3">Ikuti Kami di Media Sosial</h3>
            <p class="mb-4 opacity-75">Dapatkan info promo dan update terbaru dari kami</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="#" class="btn btn-outline-light rounded-pill px-4 py-2">
                    <i class="fab fa-instagram me-2"></i> Instagram
                </a>
                <a href="#" class="btn btn-outline-light rounded-pill px-4 py-2">
                    <i class="fab fa-facebook-f me-2"></i> Facebook
                </a>
                <a href="#" class="btn btn-outline-light rounded-pill px-4 py-2">
                    <i class="fab fa-whatsapp me-2"></i> WhatsApp
                </a>
            </div>
        </div>
    </div>
</section>

<style>
    /* Tambahan style untuk contact page */
    .form-control:focus {
        border-color: var(--purple-primary);
        box-shadow: 0 0 0 3px rgba(90, 24, 154, 0.1);
    }
    
    .bg-purple-mist {
        background: #f3e8ff;
    }
    
    .alert-success {
        background: #d1fae5;
        border: none;
        color: #065f46;
        border-left: 4px solid #10b981;
    }
    
    .alert-danger {
        background: #fee2e2;
        border: none;
        color: #991b1b;
        border-left: 4px solid #ef4444;
    }
    
    .btn-outline-light {
        border: 2px solid white;
        color: white;
        transition: all 0.3s;
    }
    
    .btn-outline-light:hover {
        background: white;
        color: var(--purple-primary);
        transform: translateY(-2px);
    }
    
    @media (max-width: 768px) {
        .hero {
            min-height: auto;
            padding: 4rem 0;
        }
        .hero h1 {
            font-size: 2rem;
        }
    }
</style>