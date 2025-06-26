<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$currentPage = 'about';
$pageTitle = 'About Me';
$pageDescription = 'Learn more about Ouma Tonny, programmer and data scientist';

// Get personal info
$personalInfo = getPersonalInfo();

include 'includes/header.php';
?>

<section class="page-header">
    <div class="container">
        <h1>About Me</h1>
        <p>Get to know me better</p>
    </div>
</section>

<section class="about-section">
    <div class="container">
        <div class="about-content">
            <div class="about-image">
                <div class="image-frame">
                    <img src="/assets/images/profile.jpg" alt="Ouma Tonny" onerror="this.src='/assets/images/profile-placeholder.jpg'">
                </div>
            </div>
            <div class="about-text">
                <h2>Hello, I'm <?php echo isset($personalInfo['full_name']) ? $personalInfo['full_name'] : 'Ouma Tonny'; ?></h2>
                <h3><?php echo isset($personalInfo['title']) ? $personalInfo['title'] : 'Programmer & Data Scientist'; ?></h3>
                
                <div class="about-bio">
                    <?php if (isset($personalInfo['bio']) && !empty($personalInfo['bio'])): ?>
                        <?php echo nl2br($personalInfo['bio']); ?>
                    <?php else: ?>
                        <p>I am a passionate programmer and data scientist with expertise in creating innovative solutions and deriving meaningful insights from data. With a strong background in both programming and data analysis, I bring a unique perspective to every project I work on.</p>
                        <p>My journey in the tech world has equipped me with a diverse skill set, allowing me to tackle complex problems and deliver high-quality results. I am constantly learning and exploring new technologies to stay at the forefront of the rapidly evolving tech landscape.</p>
                    <?php endif; ?>
                </div>
                
                <div class="skills-section">
                    <h3>My Skills</h3>
                    <div class="skills">
                        <div class="skill-item">
                            <span class="skill-name">Programming</span>
                            <div class="skill-bar">
                                <div class="skill-progress" style="width: 90%"></div>
                            </div>
                        </div>
                        <div class="skill-item">
                            <span class="skill-name">Data Science</span>
                            <div class="skill-bar">
                                <div class="skill-progress" style="width: 85%"></div>
                            </div>
                        </div>
                        <div class="skill-item">
                            <span class="skill-name">Web Development</span>
                            <div class="skill-bar">
                                <div class="skill-progress" style="width: 80%"></div>
                            </div>
                        </div>
                        <div class="skill-item">
                            <span class="skill-name">Machine Learning</span>
                            <div class="skill-bar">
                                <div class="skill-progress" style="width: 75%"></div>
                            </div>
                        </div>
                        <div class="skill-item">
                            <span class="skill-name">Database Management</span>
                            <div class="skill-bar">
                                <div class="skill-progress" style="width: 85%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="experience-section">
    <div class="container">
        <div class="section-header">
            <h2>Experience & Education</h2>
            <div class="section-line"></div>
        </div>
        
        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <h3>Senior Data Scientist</h3>
                    <h4>Tech Solutions Inc.</h4>
                    <p class="timeline-date">2020 - Present</p>
                    <p>Leading data science initiatives and developing machine learning models to solve complex business problems. Collaborating with cross-functional teams to implement data-driven solutions.</p>
                </div>
            </div>
            
            <div class="timeline-item">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <h3>Software Developer</h3>
                    <h4>Innovative Systems Ltd.</h4>
                    <p class="timeline-date">2018 - 2020</p>
                    <p>Developed and maintained web applications using modern frameworks. Implemented responsive designs and optimized application performance.</p>
                </div>
            </div>
            
            <div class="timeline-item">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <h3>Master's in Data Science</h3>
                    <h4>University of Technology</h4>
                    <p class="timeline-date">2016 - 2018</p>
                    <p>Specialized in machine learning algorithms and statistical analysis. Completed thesis on predictive modeling for financial markets.</p>
                </div>
            </div>
            
            <div class="timeline-item">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <h3>Bachelor's in Computer Science</h3>
                    <h4>National University</h4>
                    <p class="timeline-date">2012 - 2016</p>
                    <p>Focused on software engineering and database systems. Graduated with honors and received award for outstanding academic performance.</p>
                </div>
            </div>
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

<?php include 'includes/footer.php'; ?>
