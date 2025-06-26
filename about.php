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
                    <img src="https://oumatonny.github.io/Ouma.png" alt="Ouma Tonny" onerror="this.src='/assets/images/tonny-seminar.png'">
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
            <h2>My Journey</h2>
            <div class="section-line"></div>
        </div>
        
        <h3 style="text-align: center; margin-bottom: 2rem;">Work Experience</h3>
        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <h3>Web Developer</h3>
                    <h4>Onest Developers, Nairobi</h4>
                    <p class="timeline-date">2023 - 2024</p>
                    <p>Provided web development, design, and SEO for various clients. Responsibilities included project design, version control, development, documentation, and customer support.</p>
                </div>
            </div>
            
            <div class="timeline-item">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <h3>IT Support Intern</h3>
                    <h4>KEPHIS, Nairobi</h4>
                    <p class="timeline-date">2022 - 2022</p>
                    <p>Configured and troubleshooted LANs, updated software, and provided ICT support at the Kenya Plant Health Inspectorate Service.</p>
                </div>
            </div>
        </div>

        <div class="section-header" style="margin-top: 4rem;">
            <h2>Education & Certifications</h2>
            <div class="section-line"></div>
        </div>

        <div class="education-grid">
            <div class="education-card">
                <div class="education-icon"><i class="fas fa-university"></i></div>
                <h3>Bachelor of Science in Computer Science</h3>
                <p class="institution">University of Eldoret | Graduated Nov 2023</p>
                <ul>
                    <li>Second Class Honors (Upper Division)</li>
                    <li>Final Project: Image Recognition System for Class Attendance</li>
                </ul>
            </div>
            
            <div class="education-card">
                <div class="education-icon"><i class="fas fa-certificate"></i></div>
                <h3>Introduction to Cyber Security</h3>
                <p class="institution">CISCO | June 2022</p>
            </div>
            
            <div class="education-card">
                <div class="education-icon"><i class="fas fa-certificate"></i></div>
                <h3>Web Development Stack</h3>
                <p class="institution">Udemy | 2020</p>
                <p>Completed courses covering JavaScript, PHP, Python, Bootstrap, and more.</p>
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
