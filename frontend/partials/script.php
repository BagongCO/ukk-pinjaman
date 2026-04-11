<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
    AOS.init({
        duration: 800,
        once: true,
        offset: 100
    });

    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        const backTop = document.getElementById('backToTop');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
            backTop.classList.add('show');
        } else {
            navbar.classList.remove('scrolled');
            backTop.classList.remove('show');
        }
    });

    // Back to top
    document.getElementById('backToTop').addEventListener('click', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
</script>
</body>
</html>