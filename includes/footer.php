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
                            if ($personalInfo) {
                                if (!empty($personalInfo['github_url'])) {
                                    echo '<a href="' . escapeHTML($personalInfo['github_url']) . '" target="_blank"><i class="fab fa-github"></i></a>';
                                }
                                if (!empty($personalInfo['linkedin_url'])) {
                                    echo '<a href="' . escapeHTML($personalInfo['linkedin_url']) . '" target="_blank"><i class="fab fa-linkedin"></i></a>';
                                }
                                if (!empty($personalInfo['twitter_url'])) {
                                    echo '<a href="' . escapeHTML($personalInfo['twitter_url']) . '" target="_blank"><i class="fab fa-twitter"></i></a>';
                                }
                            }
                            ?>
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
