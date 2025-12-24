<footer class="footer-premium">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4">
                <a class="navbar-brand text-white d-block mb-4" href="index.php">Nextoware</a>
                <p class="text-secondary small">The global marketplace for professional hardware engineering and industrial design services. Verified talent, precision results.</p>
                <div class="mt-4 mb-4">
                    <h6 class="text-white fw-bold mb-2">Support & Inquiries</h6>
                    <p class="text-secondary small mb-1"><i class="bi bi-telephone-fill me-2 text-primary"></i> 9834633557</p>
                    <p class="text-secondary small mb-0"><i class="bi bi-telephone-fill me-2 text-primary"></i> 9130798346</p>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <a href="#" class="text-white opacity-50 hover-opacity-100 fs-5"><i class="bi bi-linkedin"></i></a>
                    <a href="#" class="text-white opacity-50 hover-opacity-100 fs-5"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="text-white opacity-50 hover-opacity-100 fs-5"><i class="bi bi-github"></i></a>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="text-white fw-bold mb-4">Platform</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="#" class="text-secondary text-decoration-none">How it works</a></li>
                    <li class="mb-2"><a href="#" class="text-secondary text-decoration-none">For Engineers</a></li>
                    <li class="mb-2"><a href="#" class="text-secondary text-decoration-none">For Companies</a></li>
                    <li class="mb-2"><a href="#" class="text-secondary text-decoration-none">Pricing</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="text-white fw-bold mb-4">Domain Areas</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="#" class="text-secondary text-decoration-none">PCB Design</a></li>
                    <li class="mb-2"><a href="#" class="text-secondary text-decoration-none">IoT Systems</a></li>
                    <li class="mb-2"><a href="#" class="text-secondary text-decoration-none">Robotics</a></li>
                    <li class="mb-2"><a href="#" class="text-secondary text-decoration-none">Firmware</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h6 class="text-white fw-bold mb-4">Newsletter</h6>
                <p class="text-secondary small mb-4">Monthly hardware insights and project opportunities delivered to your inbox.</p>
                <div class="input-group mb-3">
                    <input type="text" class="form-control bg-dark border-secondary border-opacity-25 text-white small p-3" placeholder="email@address.com">
                    <button class="btn btn-premium border-0" type="button">Join</button>
                </div>
            </div>
        </div>
        <hr class="my-5 border-secondary border-opacity-10">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <span class="small">&copy; <?php echo date('Y'); ?> Nextoware. All rights reserved.</span>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <a href="#" class="text-secondary text-decoration-none small me-4">Privacy Policy</a>
                <a href="#" class="text-secondary text-decoration-none small">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            easing: 'ease-in-out'
        });
    </script>
    <!-- Custom JS -->
    <script src="<?php echo BASE_URL; ?>assets/js/main.js"></script>
</body>
</html>
