    </div>
</div>

<style>
/* Modern Premium Footer Styles */
.footer-premium {
    background-color: #0f172a;
    color: #94a3b8;
    font-family: 'Outfit', sans-serif;
    padding: 3rem 0 1.5rem 0;
    margin-top: auto;
    border-top: 1px solid #1e293b;
    position: relative;
    overflow: hidden;
}

/* Subtle glow effect behind footer */
.footer-premium::before {
    content: '';
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 300px;
    height: 1px;
    background: linear-gradient(90deg, transparent, #3b82f6, transparent);
    box-shadow: 0 0 15px 2px rgba(59, 130, 246, 0.4);
}

.footer-premium .footer-brand {
    font-weight: 700;
    font-size: 1.4rem;
    color: #ffffff;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    letter-spacing: 0.5px;
}

.footer-premium .footer-tagline {
    font-size: 0.95rem;
    font-weight: 300;
    margin-bottom: 1.5rem;
    color: #64748b;
}

.footer-divider {
    height: 1px;
    background: linear-gradient(to right, transparent, rgba(148, 163, 184, 0.15), transparent);
    margin: 1.5rem 0;
}

.footer-premium .copyright {
    font-size: 0.85rem;
    font-weight: 400;
}

.footer-premium .footer-links {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 20px;
}

.footer-premium .footer-links a {
    color: #cbd5e1;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: color 0.2s ease, transform 0.2s ease;
}

.footer-premium .footer-links a:hover {
    color: #60a5fa;
    transform: translateY(-1px);
}
</style>

<!-- ✅ Premium Footer -->
<footer class="footer-premium mt-auto">
    <div class="container text-center">
        <div class="footer-brand">
            <i class="bi bi-mortarboard-fill text-primary"></i>
            <?= defined('APP_NAME') ? APP_NAME : 'SmartPrep' ?>
        </div>
        <div class="footer-tagline">
            Next-Generation University Management System
        </div>
        
        <div class="footer-links">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
            <a href="#">Support Center</a>
        </div>

        <div class="footer-divider"></div>

        <div class="copyright">
            &copy; <?= date('Y') ?> <?= defined('APP_NAME') ? APP_NAME : 'SmartPrep' ?>. All rights reserved.
        </div>
    </div>
</footer>

<!-- ✅ Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- ✅ Custom JS -->
<script src="<?= defined('BASE_URL') ? base_url('assets/js/main.js') : 'assets/js/main.js' ?>"></script>
<script src="<?= defined('BASE_URL') ? base_url('assets/js/ajax.js') : 'assets/js/ajax.js' ?>"></script>

</body>
</html>