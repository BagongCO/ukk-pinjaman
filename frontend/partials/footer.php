<!-- FOOTER -->
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <div class="footer-logo">
                    <i class="fas fa-lightbulb"></i>
                    <span>Peminjaman Lampu</span>
                </div>
                <p class="footer-desc">Sewa lampu profesional untuk berbagai event. Terbaik, terpercaya, dan modern dengan pelayanan ramah dan harga bersahabat.</p>
                <div class="footer-social">
                    <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-whatsapp"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <div class="footer-section">
                <h6 class="footer-title">Menu Cepat</h6>
                <ul class="footer-links">
                    <li><a href=""><i class="fas fa-chevron-right"></i> Beranda</a></li>
                    <li><a href=""><i class="fas fa-chevron-right"></i> Daftar Lampu</a></li>
                    <li><a href=""><i class="fas fa-chevron-right"></i> Tentang Kami</a></li>
                    <li><a href=""><i class="fas fa-chevron-right"></i> Testimoni</a></li>
                    <li><a href=""><i class="fas fa-chevron-right"></i> Peminjaman Saya</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h6 class="footer-title">Layanan</h6>
                <ul class="footer-links">
                    <li><a href=""><i class="fas fa-chevron-right"></i> Sewa Lampu LED</a></li>
                    <li><a href=""><i class="fas fa-chevron-right"></i> Sewa Lampu Dekorasi</a></li>
                    <li><a href=""><i class="fas fa-chevron-right"></i> Sewa Lampu Panggung</a></li>
                    <li><a href=""><i class="fas fa-chevron-right"></i> Konsultasi Gratis</a></li>
                    <li><a href=""><i class="fas fa-chevron-right"></i> Event Organizer</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h6 class="footer-title">Kontak & Info</h6>
                <div class="footer-contact">
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Jl. Raya No. 123, Jakarta Selatan</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone-alt"></i>
                        <span>+62 812 3456 7890</span>
                    </div>
                    <div class="contact-item">
                        <i class="fab fa-whatsapp"></i>
                        <span>+62 812 3456 7890</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>info@sewalampu.com</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="footer-bottom-content">
                <div class="copyright">
                    <i class="far fa-copyright"></i> <?= date('Y') ?> <strong>Peminjaman Lampu</strong>. All rights reserved.
                </div>
                <div class="footer-bottom-links">
                    <a href="">Kebijakan Privasi</a>
                    <span>|</span>
                    <a href="">Syarat & Ketentuan</a>
                    <span>|</span>
                    <a href="">Bantuan</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top -->
<div class="back-to-top" id="backToTop">
    <i class="fas fa-arrow-up"></i>
</div>

<style>
    /* ========== FOOTER STYLES ========== */
    .footer {
        background: linear-gradient(135deg, #0f0c29, #1a1a3e, #24243e);
        position: relative;
        overflow: hidden;
    }

    .footer::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #4facfe);
    }

    .footer::after {
        content: '💡';
        position: absolute;
        bottom: 20px;
        right: 20px;
        font-size: 120px;
        opacity: 0.03;
        pointer-events: none;
    }

    .footer-content {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1.5fr;
        gap: 40px;
        padding: 60px 0 40px;
    }

    /* Footer Logo */
    .footer-logo {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .footer-logo i {
        font-size: 32px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .footer-logo span {
        font-size: 1.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #fff, #a78bfa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .footer-desc {
        color: #a0a0c0;
        line-height: 1.6;
        margin-bottom: 20px;
        font-size: 0.85rem;
    }

    /* Social Links */
    .footer-social {
        display: flex;
        gap: 12px;
    }

    .social-link {
        width: 38px;
        height: 38px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #a0a0c0;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .social-link:hover {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    /* Footer Title */
    .footer-title {
        color: white;
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 20px;
        position: relative;
        padding-bottom: 12px;
    }

    .footer-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 3px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        border-radius: 3px;
    }

    /* Footer Links */
    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links li {
        margin-bottom: 10px;
    }

    .footer-links a {
        color: #a0a0c0;
        text-decoration: none;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .footer-links a i {
        font-size: 10px;
        transition: all 0.3s ease;
    }

    .footer-links a:hover {
        color: #a78bfa;
        transform: translateX(5px);
    }

    .footer-links a:hover i {
        transform: translateX(3px);
        color: #a78bfa;
    }

    /* Contact Items */
    .footer-contact {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .contact-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        color: #a0a0c0;
        font-size: 0.85rem;
    }

    .contact-item i {
        width: 20px;
        color: #a78bfa;
        margin-top: 2px;
    }

    .contact-item span {
        flex: 1;
        line-height: 1.5;
    }

    /* Footer Bottom */
    .footer-bottom {
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        padding: 20px 0;
    }

    .footer-bottom-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .copyright {
        color: #a0a0c0;
        font-size: 0.8rem;
    }

    .copyright i {
        margin-right: 5px;
    }

    .copyright strong {
        color: #a78bfa;
    }

    .footer-bottom-links {
        display: flex;
        gap: 15px;
        align-items: center;
        flex-wrap: wrap;
    }

    .footer-bottom-links a {
        color: #a0a0c0;
        text-decoration: none;
        font-size: 0.8rem;
        transition: color 0.3s ease;
    }

    .footer-bottom-links a:hover {
        color: #a78bfa;
    }

    .footer-bottom-links span {
        color: rgba(255, 255, 255, 0.2);
    }

    /* Back to Top Button */
    .back-to-top {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 999;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    .back-to-top.show {
        opacity: 1;
        visibility: visible;
    }

    .back-to-top:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
    }

    /* Responsive Footer */
    @media (max-width: 992px) {
        .footer-content {
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
    }

    @media (max-width: 576px) {
        .footer-content {
            grid-template-columns: 1fr;
            text-align: center;
        }

        .footer-title::after {
            left: 50%;
            transform: translateX(-50%);
        }

        .footer-links a {
            justify-content: center;
        }

        .contact-item {
            justify-content: center;
        }

        .footer-social {
            justify-content: center;
        }

        .footer-bottom-content {
            flex-direction: column;
            text-align: center;
        }

        .footer-bottom-links {
            justify-content: center;
        }
    }
</style>

<script>
    // Back to Top Button
    const backToTop = document.getElementById('backToTop');
    
    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            backToTop.classList.add('show');
        } else {
            backToTop.classList.remove('show');
        }
    });
    
    backToTop.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
</script>