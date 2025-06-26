</main>
        
        <footer class="site-footer">
            <div class="container">
                <div class="footer-inner">
                    <div class="footer-info">
                        <h3>Ouma Tonny</h3>
                        <p>Programmer & Data Scientist</p>
                    </div>
                    
                    <div class="footer-links">
                        <h4>Quick Links</h4>
                        <ul>
                            <li><a href="<?php echo SITE_URL; ?>">Home</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/portfolio/">Portfolio</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/blog/">Blog</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/about.php">About</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/contact.php">Contact</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-social">
                        <h4>Connect</h4>
                        <div class="social-icons">
                            <?php 
                            $personalInfo = getPersonalInfo();
                            if ($personalInfo && !empty($personalInfo['github_url'])) {
                                echo '<a href="' . escapeHTML($personalInfo['github_url']) . '" target="_blank" title="GitHub"><i class="fab fa-github"></i></a>';
                            }
                            ?>
                            <a href="https://www.linkedin.com/in/tonny-ouma/" target="_blank" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
                            <a href="https://www.facebook.com/ouma.tonny.92/" target="_blank" title="Facebook"><i class="fab fa-facebook"></i></a>
                            <a href="#" target="_blank" title="Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="https://wa.me/254742942435" target="_blank" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                            <a href="mailto:ouatonny8@gmail.com" title="Email"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="footer-bottom">
                    <p>&copy; <?php echo date('Y'); ?> Ouma Tonny. All Rights Reserved.</p>
                    <p>
                        <a href="https://oumatonny.github.io" target="_blank">More Info</a>
                    </p>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- JavaScript -->
    <script src="<?php echo ASSETS_URL; ?>/js/main.js"></script>
    <script src="<?php echo ASSETS_URL; ?>/js/animations.js"></script>
    <script src="<?php echo ASSETS_URL; ?>/js/dynamic-hero.js"></script>
</body>
</html>
