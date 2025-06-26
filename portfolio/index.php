<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$currentPage = 'portfolio';
$pageTitle = 'Portfolio';
$pageDescription = 'Explore Ouma Tonny\'s projects and work in programming and data science';

// Get all projects
$projects = getProjects();

// Get categories for filter
$db = Database::getInstance();
$categories = $db->fetchAll("SELECT DISTINCT category FROM projects WHERE category != '' ORDER BY category");

include '../includes/header.php';
?>

<section class="hero-section mb-5">
    <div class="container">
        <div class="hero-content-wrapper" style="min-height: 30vh;">
            <div class="hero-text">
                <h1 class="animated-text">
                    <span class="name">Portfolio</span>
                </h1>
                <p class="dynamic-description" id="dynamicDescription" style="max-width: 600px;">Explore my projects, case studies, and contributions in the world of data and software.</p>
                <div class="hero-buttons animated-text-delay-3">
                    <a href="<?php echo BASE_PATH; ?>/contact.php" class="btn btn-primary">Hire Me</a>
                    <a href="#projects-grid" class="btn btn-outline">See Projects</a>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-background">
        <div class="animated-bg"></div>
    </div>
</section>

<section class="portfolio-section">
    <div class="container">
        <?php if ($categories): ?>
            <div class="portfolio-filter">
                <button class="filter-btn active" data-filter="all">All</button>
                <?php foreach ($categories as $category): ?>
                    <button class="filter-btn" data-filter="<?php echo strtolower(str_replace(' ', '-', $category['category'])); ?>">
                        <?php echo $category['category']; ?>
                    </button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <div class="projects-grid" id="projects-grid">
            <?php if (!empty($projects)): ?>
                <?php foreach ($projects as $project): ?>
                    <div class="project-card" data-category="<?php echo strtolower(str_replace(' ', '-', $project['category'])); ?>">
                        <div class="project-image">
                            <?php if (!empty($project['featured_image'])): ?>
                                <img src="<?php echo BASE_PATH; ?>/assets/uploads/projects/<?php echo $project['featured_image']; ?>" alt="<?php echo $project['title']; ?>">
                            <?php else: ?>
                                <img src="<?php echo BASE_PATH; ?>/assets/images/project-placeholder.jpg" alt="<?php echo $project['title']; ?>">
                            <?php endif; ?>
                        </div>
                        <div class="project-info">
                            <h3><?php echo $project['title']; ?></h3>
                            <p class="project-description"><?php echo substr($project['description'], 0, 100) . '...'; ?></p>
                            <div class="project-tags">
                                <?php 
                                $tags = explode(',', $project['tags']);
                                foreach ($tags as $tag) {
                                    if (trim($tag)) {
                                        echo '<span class="tag">' . trim($tag) . '</span>';
                                    }
                                }
                                ?>
                            </div>
                            <div class="project-footer">
                                <a href="<?php echo BASE_PATH; ?>/portfolio/view.php?slug=<?php echo $project['slug']; ?>" class="btn btn-small btn-secondary">View Project</a>
                                <div class="project-links">
                                    <?php if (!empty($project['github_url'])): ?>
                                        <a href="<?php echo $project['github_url']; ?>" target="_blank" class="btn-icon" title="GitHub">
                                            <i class="fab fa-github"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($project['dashboard_url'])): ?>
                                        <a href="<?php echo $project['dashboard_url']; ?>" target="_blank" class="btn-icon" title="Live Demo">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($project['video_url'])): ?>
                                        <a href="<?php echo $project['video_url']; ?>" target="_blank" class="btn-icon" title="Video">
                                            <i class="fas fa-play"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-projects">
                    <p>No projects found. Check back soon!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
    // Portfolio filter functionality
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        const projectCards = document.querySelectorAll('.project-card');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');
                
                // Update active button
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Filter projects
                projectCards.forEach(card => {
                    if (filter === 'all' || card.getAttribute('data-category') === filter) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    });
</script>

<?php include '../includes/footer.php'; ?>