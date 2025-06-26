<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// Ensure we're in the correct directory
chdir(__DIR__);

$currentPage = 'home';
$pageTitle = 'Home';
$pageDescription = 'Ouma Tonny - Programmer and Data Scientist Portfolio';

// Get featured projects
$featuredProjects = getProjects(3, true);

// Get latest blog posts
$latestPosts = getBlogPosts(3);

// Get personal info
$personalInfo = getPersonalInfo();

include 'includes/header.php';
?>

<section class="hero-section mb-5">
    <div class="container">
        <div class="hero-content-wrapper">
            <div class="hero-text mb-5">
                <h1 class="animated-text">
                    <span class="greeting mt-5">Hello, I'm</span>
                    <span class="name">Ouma Tonny</span>
                </h1>
                <h2 class="dynamic-title" id="dynamicTitle">Programmer & Data Scientist</h2>
                <p class="dynamic-description" id="dynamicDescription">Turning data into insights and code into solutions</p>
                <div class="hero-buttons animated-text-delay-3">
                    <a href="<?php echo BASE_PATH; ?>/portfolio" class="btn btn-primary">View My Work</a>
                    <a href="/contact.php" class="btn btn-outline">Get In Touch</a>
                </div>
                
                <!-- Skills showcase -->
                <div class="skills-showcase">
                    <h3>Technologies I Work With</h3>
                    <div class="tech-icons">
                        <div class="tech-item">
                            <i class="fab fa-python"></i>
                            <span>Python</span>
                        </div>
                        <div class="tech-item">
                            <i class="fas fa-database"></i>
                            <span>SQL</span>
                        </div>
                        <div class="tech-item">
                            <i class="fab fa-php"></i>
                            <span>php</span>
                        </div>
                        <div class="tech-item">
                            <i class="fas fa-chart-line"></i>
                            <span>Analytics</span>
                        </div>
                        <div class="tech-item">
                            <i class="fas fa-brain"></i>
                            <span>ML/AI</span>
                        </div>
                        <div class="tech-item">
                            <i class="fas fa-chart-pie"></i>
                            <span>Tableau</span>
                        </div>                                                                                                                                                                                 
                    </div>
                </div>
            </div>

            
            
            <div class="hero-image">
                <div class="image-container">
                    <img id="heroImage" src="https://oumatonny.github.io/Ouma.png" alt="Ouma Tonny" class="profile-image active">
                    <div class="image-overlay"></div>
                    <div class="floating-elements">
                        <div class="floating-icon" style="top: 10%; left: 10%;">
                            <i class="fas fa-code"></i>
                        </div>
                        <div class="floating-icon" style="top: 20%; right: 15%;">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div class="floating-icon" style="bottom: 30%; left: 5%;">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                        <div class="floating-icon" style="bottom: 15%; right: 10%;">
                            <i class="fas fa-database"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-background">
        <div class="animated-bg"></div>
        <div class="particles-container"></div>
    </div>
</section>

<!-- Enhanced About Preview Section -->
<section class="about-preview enhanced">
    <div class="container">
        <div class="section-header">
            <h2>About Me</h2>
            <div class="section-line"></div>
        </div>
        <div class="about-content-enhanced">
            <div class="about-stats">
                <div class="stat-item">
                    <div class="stat-number" data-target="50">0</div>
                    <div class="stat-label">Projects Completed</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-target="3">0</div>
                    <div class="stat-label">Years Experience</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-target="25">0</div>
                    <div class="stat-label">Happy Clients</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" data-target="100">0</div>
                    <div class="stat-label">Success Rate %</div>
                </div>
            </div>
            
            <div class="about-main-content">
                <div class="about-text-enhanced">
                    <h3>Passionate About Technology & Innovation</h3>
                    <p><?php echo isset($personalInfo['bio']) ? substr($personalInfo['bio'], 0, 300) . '...' : 'I am a passionate programmer and data scientist with expertise in creating innovative solutions and deriving meaningful insights from data. My journey combines technical excellence with creative problem-solving to deliver impactful results.'; ?></p>
                    
                    <div class="expertise-areas">
                        <div class="expertise-item">
                            <div class="expertise-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="expertise-content">
                                <h4>Data Science</h4>
                                <p>Machine Learning, Statistical Analysis, Data Visualization</p>
                            </div>
                        </div>
                        <div class="expertise-item">
                            <div class="expertise-icon">
                                <i class="fas fa-code"></i>
                            </div>
                            <div class="expertise-content">
                                <h4>Programming</h4>
                                <p>Python, JavaScript, PHP, SQL, React</p>
                            </div>
                        </div>
                        <div class="expertise-item">
                            <div class="expertise-icon">
                                <i class="fas fa-globe"></i>
                            </div>
                            <div class="expertise-content">
                                <h4>Web Development</h4>
                                <p>Full-Stack Development, Responsive Design, API Integration</p>
                            </div>
                        </div>
                    </div>
                    
                    <a href="/about.php" class="btn btn-secondary">Learn More About Me</a>
                </div>
                
                <div class="skills-visual">
                    <div class="skill-circle" data-percentage="90">
                        <div class="skill-circle-inner">
                            <span class="skill-percentage">90%</span>
                            <span class="skill-name">Programming</span>
                        </div>
                    </div>
                    <div class="skill-circle" data-percentage="85">
                        <div class="skill-circle-inner">
                            <span class="skill-percentage">85%</span>
                            <span class="skill-name">Data Science</span>
                        </div>
                    </div>
                    <div class="skill-circle" data-percentage="80">
                        <div class="skill-circle-inner">
                            <span class="skill-percentage">80%</span>
                            <span class="skill-name">Web Dev</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="services-section">
    <div class="container">
        <div class="section-header">
            <h2>What I Do</h2>
            <div class="section-line"></div>
            <p>Comprehensive solutions for your data and development needs</p>
        </div>
        
        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h3>Data Analysis</h3>
                <p>Transform raw data into actionable insights using advanced statistical methods and visualization techniques.</p>
                <ul>
                    <li>Statistical Analysis</li>
                    <li>Data Visualization</li>
                    <li>Business Intelligence</li>
                    <li>Reporting & Dashboards</li>
                </ul>
            </div>
            
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-brain"></i>
                </div>
                <h3>Machine Learning</h3>
                <p>Build predictive models and AI solutions to automate processes and enhance decision-making.</p>
                <ul>
                    <li>Predictive Modeling</li>
                    <li>Classification & Regression</li>
                    <li>Deep Learning</li>
                    <li>Model Deployment</li>
                </ul>
            </div>
            
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-laptop-code"></i>
                </div>
                <h3>Web Development</h3>
                <p>Create modern, responsive web applications with clean code and intuitive user experiences.</p>
                <ul>
                    <li>Frontend Development</li>
                    <li>Backend Systems</li>
                    <li>Database Design</li>
                    <li>API Integration</li>
                </ul>
            </div>
            
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3>Mobile Solutions</h3>
                <p>Develop cross-platform mobile applications that deliver seamless user experiences.</p>
                <ul>
                    <li>React Native</li>
                    <li>Progressive Web Apps</li>
                    <li>Mobile UI/UX</li>
                    <li>App Store Deployment</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="featured-projects">
    <div class="container">
        <div class="section-header">
            <h2>Featured Projects</h2>
            <div class="section-line"></div>
        </div>
        <div class="projects-grid">
            <?php if ($featuredProjects): ?>
                <?php foreach ($featuredProjects as $project): ?>
                    <div class="project-card">
                        <div class="project-image">
                            <?php if (!empty($project['featured_image'])): ?>
                                <img src="<?php echo SITE_URL; ?>/assets/uploads/projects/<?php echo $project['featured_image']; ?>" alt="<?php echo $project['title']; ?>" 
                                     style="width: 100%; height: 200px; object-fit: cover;" 
                                     class="project-image">
                            <?php else: ?>
                                <img src="<?php echo SITE_URL; ?>/assets/images/project-placeholder.jpg" alt="<?php echo $project['title']; ?>" 
                                     style="width: 100%; height: 200px; object-fit: cover;" 
                                     class="project-image">
                            <?php endif; ?>
                        </div>
                        <div class="project-info">
                            <h3><?php echo $project['title']; ?></h3>
                            <p><?php echo substr($project['description'], 0, 100) . '...'; ?></p>
                            <div class="project-tags">
                                <?php 
                                $tags = explode(',', $project['tags']);
                                foreach ($tags as $tag) {
                                    echo '<span class="tag">' . trim($tag) . '</span>';
                                }
                                ?>
                            </div>
                            <a href="<?php echo BASE_PATH; ?>/portfolio/view.php?slug=<?php echo $project['slug']; ?>" class="btn btn-small">View Project</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-projects">
                    <p>No featured projects yet. Check back soon!</p>
                </div>
            <?php endif; ?>
        </div>
        <div class="view-all">
            <a href="/portfolio" class="btn btn-primary">View All Projects</a>
        </div>
    </div>
</section>

<section class="latest-posts">
    <div class="container">
        <div class="section-header">
            <h2>Latest Blog Posts</h2>
            <div class="section-line"></div>
        </div>
        <div class="posts-grid">
            <?php if ($latestPosts): ?>
                <?php foreach ($latestPosts as $post): ?>
                    <div class="post-card">
                        <div class="post-image">
                            <?php if (!empty($post['featured_image'])): ?>
                                <img src="<?php echo SITE_URL; ?>/assets/uploads/blog/<?php echo $post['featured_image']; ?>" alt="<?php echo $post['title']; ?>" 
                                     style="width: 100%; height: 200px; object-fit: cover;" 
                                     class="post-image">
                            <?php else: ?>
                                <img src="<?php echo SITE_URL; ?>/assets/images/blog-placeholder.jpg" alt="<?php echo $post['title']; ?>" 
                                     style="width: 100%; height: 200px; object-fit: cover;" 
                                     class="post-image">
                            <?php endif; ?>
                        </div>
                        <div class="post-info">
                            <div class="post-meta">
                                <span class="post-date"><?php echo formatDate($post['published_at']); ?></span>
                                <span class="post-author">By <?php echo $post['author_name']; ?></span>
                            </div>
                            <h3><?php echo $post['title']; ?></h3>
                            <p><?php echo $post['excerpt'] ? $post['excerpt'] : substr(strip_tags($post['content']), 0, 150) . '...'; ?></p>
                            <a href="/blog/post.php?slug=<?php echo $post['slug']; ?>" class="read-more">Read More</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-posts">
                    <p>No blog posts yet. Check back soon!</p>
                </div>
            <?php endif; ?>
        </div>
        <div class="view-all">
            <a href="/blog" class="btn btn-primary">View All Posts</a>
        </div>
    </div>
</section>

<section class="contact-cta">
    <div class="container">
        <div class="cta-content">
            <h2>Let's Work Together</h2>
            <p>Have a project in mind or want to collaborate? Get in touch!</p>
            <a href="/contact.php" class="btn btn-primary">Contact Me</a>
        </div>
    </div>
</section>

// Add similar code where projects are displayed
<div class="project-links">
// Same link structure as above 
</div>
<?php include 'includes/footer.php'; ?>
