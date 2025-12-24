<?php
session_start();
include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container my-5 pt-5">
    <div class="text-center mb-5" data-aos="fade-down">
        <h2 class="display-5 fw-bold mb-2">Join the Nextoware Network</h2>
        <p class="text-secondary fs-5">Choose your path to get started</p>
    </div>

    <div class="row g-4 justify-content-center">
        <!-- Client Option -->
        <div class="col-md-5" data-aos="fade-right" data-aos-delay="100">
            <div class="premium-card h-100 text-center p-5 border-0 shadow-lg">
                <div class="icon-circle icon-blue mx-auto mb-4" style="width: 80px; height: 80px; font-size: 2rem;">
                    <i class="bi bi-person-badge"></i>
                </div>
                <h3 class="fw-bold mb-3">I am a Client</h3>
                <p class="text-secondary mb-4">Register as an Individual or Company to hire elite hardware engineering talent.</p>
                <ul class="list-unstyled text-start mb-5 px-3 small">
                    <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i> Post hardware projects</li>
                    <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i> Access verified engineers</li>
                    <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i> Secure payments & tracking</li>
                </ul>
                <a href="register_user.php" class="btn btn-premium w-100 py-3 rounded-4 fw-bold">Register as Client</a>
            </div>
        </div>

        <!-- Engineer Option -->
        <div class="col-md-5" data-aos="fade-left" data-aos-delay="200">
            <div class="premium-card h-100 text-center p-5 border-0 shadow-lg" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="icon-circle icon-cyan mx-auto mb-4" style="width: 80px; height: 80px; font-size: 2rem;">
                    <i class="bi bi-cpu"></i>
                </div>
                <h3 class="fw-bold mb-3">I am an Engineer</h3>
                <p class="text-secondary mb-4">Join our global network of experts and find high-quality hardware projects.</p>
                <ul class="list-unstyled text-start mb-5 px-3 small">
                    <li class="mb-2"><i class="bi bi-check2-circle text-info me-2"></i> Showcase your expertise</li>
                    <li class="mb-2"><i class="bi bi-check2-circle text-info me-2"></i> Get paid for your skills</li>
                    <li class="mb-2"><i class="bi bi-check2-circle text-info me-2"></i> Remote & on-site work</li>
                </ul>
                <a href="register_engineer.php" class="btn btn-outline-dark w-100 py-3 rounded-4 fw-bold border-2">Join as Expert</a>
            </div>
        </div>
    </div>

    <div class="text-center mt-5" data-aos="fade-up">
        <p class="text-secondary">Already have an account? <a href="login.php" class="text-primary fw-bold text-decoration-none">Sign In</a></p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
