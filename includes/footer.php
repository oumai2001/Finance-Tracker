</div> <!-- Fin content-wrapper -->
    </main>
    
    <!-- Footer Principal -->
    <footer class="main-footer">
        <div class="footer-container">
            <!-- Section À propos -->
            <div class="footer-section">
                <h3>
                    <i class="fas fa-chart-line"></i> Finance Tracker
                </h3>
                <p class="footer-description">
                    Solution professionnelle de gestion financière pour suivre, analyser et optimiser vos revenus et dépenses en temps réel.
                </p>
                <div class="social-links">
                    <a href="#" title="Facebook"><i class="fab fa-facebook"></i></a>
                    <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
                    <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            
            <!-- Liens Rapides -->
            <div class="footer-section">
                <h4>Liens Rapides</h4>
                <ul class="footer-links">
                    <li><a href="index.php"><i class="fas fa-angle-right"></i> Dashboard</a></li>
                    <li><a href="incomes.php"><i class="fas fa-angle-right"></i> Mes Revenus</a></li>
                    <li><a href="expenses.php"><i class="fas fa-angle-right"></i> Mes Dépenses</a></li>
                </ul>
            </div>
            
            <!-- Support -->
            <div class="footer-section">
                <h4>Support</h4>
                <ul class="footer-links">
                    <li><a href="#"><i class="fas fa-angle-right"></i> Centre d'aide</a></li>
                    <li><a href="#"><i class="fas fa-angle-right"></i> FAQ</a></li>
                    <li><a href="#"><i class="fas fa-angle-right"></i> Tutoriels</a></li>
                    <li><a href="#"><i class="fas fa-angle-right"></i> Contact</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Barre de copyright -->
        <div class="footer-bottom">
            <div class="footer-container">
                <p class="copyright">
                    &copy; <?= date('Y') ?> Finance Tracker. Tous droits réservés.
                </p>
                <div class="footer-bottom-links">
                    <a href="#">Politique de confidentialité</a>
                    <span>•</span>
                    <a href="#">Conditions d'utilisation</a>
                    <span>•</span>
                    <a href="#">Mentions légales</a>
                </div>
                <p class="developer-credit">
                    Développé avec <i class="fas fa-heart"></i> par <strong>Votre Équipe</strong>
                </p>
            </div>
        </div>
    </footer>
    
    <!-- Bouton retour en haut -->
    <button id="scrollToTop" class="scroll-to-top" title="Retour en haut">
        <i class="fas fa-arrow-up"></i>
    </button>
    
    <!-- Scripts -->
    <script src="assets/js/script.js"></script>
    <script src="assets/js/pwa-install.js"></script>
    
    <script>
        // Menu Mobile
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const closeMobileNav = document.getElementById('closeMobileNav');
        const mobileNav = document.getElementById('mobileNav');
        const mobileOverlay = document.getElementById('mobileOverlay');
        
        function toggleMobileMenu() {
            mobileNav.classList.toggle('active');
            mobileOverlay.classList.toggle('active');
            document.body.style.overflow = mobileNav.classList.contains('active') ? 'hidden' : '';
        }
        
        mobileMenuBtn?.addEventListener('click', toggleMobileMenu);
        closeMobileNav?.addEventListener('click', toggleMobileMenu);
        mobileOverlay?.addEventListener('click', toggleMobileMenu);
        
        // Toggle Theme
        const themeToggle = document.getElementById('themeToggle');
        themeToggle?.addEventListener('click', () => {
            document.body.classList.toggle('dark-theme');
            const icon = themeToggle.querySelector('i');
            icon.classList.toggle('fa-moon');
            icon.classList.toggle('fa-sun');
            localStorage.setItem('theme', document.body.classList.contains('dark-theme') ? 'dark' : 'light');
        });
        
        // Charger le thème sauvegardé
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-theme');
            const icon = themeToggle?.querySelector('i');
            if (icon) {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            }
        }
        
        // Scroll to Top
        const scrollBtn = document.getElementById('scrollToTop');
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollBtn?.classList.add('visible');
            } else {
                scrollBtn?.classList.remove('visible');
            }
        });
        
        scrollBtn?.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        
        // Dropdown profil
        const userProfile = document.querySelector('.user-profile');
        userProfile?.addEventListener('click', (e) => {
            e.stopPropagation();
            userProfile.classList.toggle('active');
        });
        
        document.addEventListener('click', () => {
            userProfile?.classList.remove('active');
        });
    </script>
</body>
</html>