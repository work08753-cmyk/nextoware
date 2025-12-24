<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="glass-badge mb-3 d-inline-block">The Hardware Standard</div>
                <h1 class="hero-title">Engineering <br>at the <span class="text-primary">Next Level.</span></h1>
                <p class="lead mb-5 text-secondary pe-lg-5">Connect with the world's most elite hardware engineers. From PCB design to complex firmware systems, we bring your vision to life with precision.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="auth/register_user.php" class="btn btn-premium btn-lg px-5">Start Building</a>
                    <a href="auth/register_engineer.php" class="btn btn-outline-dark btn-lg px-5 rounded-4 border-2">Join as Expert</a>
                </div>
                <div class="mt-5 d-flex align-items-center gap-5 text-secondary">
                    <div>
                        <div class="h4 fw-bold text-dark mb-0">500+</div>
                        <div class="small">Verified Experts</div>
                    </div>
                    <div class="vr opacity-10"></div>
                    <div>
                        <div class="h4 fw-bold text-dark mb-0">1.2k</div>
                        <div class="small">Projects Delivered</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="zoom-in" data-aos-delay="200">
                <div class="position-relative p-4">
                    <div class="bg-primary opacity-10 position-absolute rounded-circle blur-3xl" style="width: 500px; height: 500px; top: -50px; right: -50px; z-index: -1;"></div>
                    <img src="assets/img/hero.png" alt="Hardware Engineering" class="img-fluid rounded-5 shadow-2xl border border-white border-5 floating">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Trust Section -->
<section class="py-5 border-bottom border-top bg-light">
    <div class="container py-3">
        <div class="row align-items-center text-center g-4">
            <div class="col-6 col-md-3"><h5 class="fw-bold text-secondary m-0"><i class="bi bi-layers-fill me-2"></i>ALTIUM <span class="fw-normal small">PLATINUM</span></h5></div>
            <div class="col-6 col-md-3"><h5 class="fw-bold text-secondary m-0"><i class="bi bi-motherboard-fill me-2"></i>KICAD <span class="fw-normal small">CERTIFIED</span></h5></div>
            <div class="col-6 col-md-3"><h5 class="fw-bold text-secondary m-0"><i class="bi bi-cpu-fill me-2"></i>FIRMWARE <span class="fw-normal small">ALLIANCE</span></h5></div>
            <div class="col-6 col-md-3"><h5 class="fw-bold text-secondary m-0"><i class="bi bi-wifi me-2"></i>IoT <span class="fw-normal small">GLOBAL HUB</span></h5></div>
        </div>
    </div>
</section>

<!-- Features Grid -->
<section class="py-5 my-5">
    <div class="container py-5">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-4 fw-bold mb-3">Engineered for Quality</h2>
            <p class="text-secondary mx-auto fs-5" style="max-width: 700px;">A specialized ecosystem for hardware development where technical excellence is the priority.</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="premium-card h-100">
                    <div class="icon-circle icon-blue"><i class="bi bi-cpu-fill"></i></div>
                    <h4 class="mb-3">Expert Assessment</h4>
                    <p class="text-secondary mb-0">Rigorous technical testing for every engineer. We verify knowledge in analog, digital, and embedded systems.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="premium-card h-100">
                    <div class="icon-circle icon-cyan"><i class="bi bi-shield-check"></i></div>
                    <h4 class="mb-3">Quality Guaranteed</h4>
                    <p class="text-secondary mb-0">Every project undergoes milestone tracking to ensure your hardware meets industrial standards.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="premium-card h-100">
                    <div class="icon-circle icon-emerald"><i class="bi bi-graph-up-arrow"></i></div>
                    <h4 class="mb-3">Scalable Talent</h4>
                    <p class="text-secondary mb-0">From single prototypes to full-scale production management, find the right scale for your needs.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="container py-5 mb-5" data-aos="flip-up">
    <div class="bg-dark rounded-5 p-5 text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
        <div class="bg-primary opacity-10 position-absolute rounded-circle blur-3xl" style="width: 300px; height: 300px; bottom: -100px; left: -100px;"></div>
        <div class="row align-items-center g-4 position-relative z-1">
            <div class="col-lg-8">
                <h2 class="display-5 fw-bold mb-3 text-white">Ready to transform <br>your hardware concept?</h2>
                <p class="lead text-white-50 mb-0">Hire the worlds most specialized hardware talent today.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="auth/register_user.php" class="btn btn-premium btn-lg px-5 shadow-lg">Get Started Now</a>
            </div>
        </div>
    </div>
</section>



<?php include 'includes/footer.php'; ?>
