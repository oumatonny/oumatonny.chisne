<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$currentPage = 'portfolio';

// Get project slug from URL
$slug = isset($_GET['slug']) ? clean($_GET['slug']) : '';

if (empty($slug)) {
    // Redirect to portfolio page if no slug provided
    redirect('/oumatonny/portfolio');
}

// Get project details
$project = getProject($slug);

if (!$project) {
    // Redirect to portfolio page if project not found
    redirect('/oumatonny/portfolio');
}

$pageTitle = $project['title'];
$pageDescription = substr($project['description'], 0, 160);

include '../includes/header.php';
?>

<section class="project-header">
    <div class="container">
        <div class="breadcrumbs">
            <a href="/portfolio">Portfolio</a> / <span><?php echo $project['title']; ?></span>
        </div>
        <h1><?php echo $project['title']; ?></h1>
        <?php if (!empty($project['category'])): ?>
            <div class="project-category">
                <span><?php echo $project['category']; ?></span>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="project-details">
    <div class="container">
        <div class="project-content">
            <div class="project-main">
                <?php if (!empty($project['featured_image'])): ?>
                    <div class="project-featured-image">   
                        <img src="<?php echo BASE_PATH; ?>/assets/uploads/projects/<?php echo $project['featured_image']; ?>" alt="<?php echo $project['title']; ?>">
                    </div>
                <?php endif; ?>
                
                <div class="project-description">
                    <h2>Project Overview</h2>
                    <p><?php echo $project['description']; ?></p>
                </div>
                
                <?php if (!empty($project['content'])): ?>
                    <div class="project-full-content">
                        <?php echo $project['content']; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($project['video_url'])): ?>
                    <div class="project-video">
                        <h2>Project Video</h2>
                        <div class="video-container">
                            <?php
                            // Extract video ID from YouTube or Vimeo URL
                            $videoId = '';
                            $videoType = '';
                            
                            if (strpos($project['video_url'], 'youtube.com') !== false || strpos($project['video_url'], 'youtu.be') !== false) {
                                $videoType = 'youtube';
                                if (strpos($project['video_url'], 'youtube.com/watch?v=') !== false) {
                                    $videoId = substr($project['video_url'], strpos($project['video_url'], 'v=') + 2);
                                    $videoId = strtok($videoId, '&');
                                } elseif (strpos($project['video_url'], 'youtu.be/') !== false) {
                                    $videoId = substr($project['video_url'], strpos($project['video_url'], 'youtu.be/') + 9);
                                }
                            } elseif (strpos($project['video_url'], 'vimeo.com') !== false) {
                                $videoType = 'vimeo';
                                $videoId = substr($project['video_url'], strrpos($project['video_url'], '/') + 1);
                            }
                            
                            if ($videoType === 'youtube' && !empty($videoId)) {
                                echo '<iframe width="100%" height="450" src="https://www.youtube.com/embed/' . $videoId . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                            } elseif ($videoType === 'vimeo' && !empty($videoId)) {
                                echo '<iframe src="https://player.vimeo.com/video/' . $videoId . '" width="100%" height="450" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
                            } else {
                                echo '<a href="' . $project['video_url'] . '" target="_blank" class="btn btn-primary">Watch Video</a>';
                            }
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($project['dashboard_url'])): ?>
                    <div class="project-dashboard">
                        <h2>Interactive Dashboard</h2>
                        <div class="dashboard-container">
                            <iframe src="<?php echo $project['dashboard_url']; ?>" width="100%" height="600" frameborder="0"></iframe>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="project-sidebar">
                <div class="project-info-card">
                    <h3>Project Details</h3>
                    
                    <div class="info-item">
                        <span class="info-label">Date</span>
                        <span class="info-value"><?php echo formatDate($project['created_at']); ?></span>
                    </div>
                    
                    <?php if (!empty($project['category'])): ?>
                        <div class="info-item">
                            <span class="info-label">Category</span>
                            <span class="info-value"><?php echo $project['category']; ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($project['tags'])): ?>
                        <div class="info-item">
                            <span class="info-label">Technologies</span>
                            <div class="info-tags">
                                <?php 
                                $tags = explode(',', $project['tags']);
                                foreach ($tags as $tag) {
                                    if (trim($tag)) {
                                        echo '<span class="tag">' . trim($tag) . '</span>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($project['github_url'])): ?>
                        <div class="info-item">
                            <span class="info-label">GitHub</span>
                            <a href="<?php echo $project['github_url']; ?>" target="_blank" class="info-link">
                                <i class="fab fa-github"></i> View Repository
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="project-nav">
                    <h3>More Projects</h3>
                    <?php
                    // Get other projects
                    $db = Database::getInstance();
                    $otherProjects = $db->fetchAll("SELECT id, title, slug FROM projects WHERE id != :id ORDER BY RAND() LIMIT 3", [':id' => $project['id']]);
                    
                    if ($otherProjects):
                    ?>
                        <ul class="project-nav-list">
                            <?php foreach ($otherProjects as $otherProject): ?>
                                <li>
                                    <a href="/portfolio/view.php?slug=<?php echo $otherProject['slug']; ?>">
                                        <?php echo $otherProject['title']; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    
                    <a href="/portfolio" class="btn btn-outline btn-block">View All Projects</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>

<div class="project-links">
    <?php if (!empty($project['github_url'])): ?>
        <a href="<?php echo $project['github_url']; ?>" target="_blank" class="btn btn-github">
            <i class="fab fa-github"></i> View on GitHub
        </a>
    <?php endif; ?>
    
    <?php if (!empty($project['dashboard_url'])): ?>
        <a href="<?php echo $project['dashboard_url']; ?>" target="_blank" class="btn btn-primary">
            Live Demo
        </a>
    <?php endif; ?>
</div>
