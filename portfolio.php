<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// Ensure we're in the correct directory
chdir(__DIR__);

$currentPage = 'portfolio';
$pageTitle = 'Portfolio';
$pageDescription = 'View my projects and work';

// Get all projects
$projects = getProjects();

include 'includes/header.php';
?>

<section class="portfolio-section">
    <div class="container">
        <div class="section-header">
            <h2>My Portfolio</h2>
            <div class="section-line"></div>
        </div>

        <div class="portfolio-grid">
            <?php foreach ($projects as $project): ?>
                <div class="portfolio-item" data-project-id="<?php echo $project['id']; ?>">
                    <div class="portfolio-image">
                        <?php 
                        if ($project['featured_image']) {
                            // Construct image path
                            $imagePath = UPLOAD_DIR . 'projects/' . $project['featured_image'];
                            $fullPath = __DIR__ . '/../' . $imagePath;
                            
                            if (file_exists($fullPath)) {
                                // Use SITE_URL for absolute path
                                $imageSrc = SITE_URL . '/' . $imagePath;
                            } else {
                                // Log missing image error
                                error_log("Missing image file: " . $fullPath);
                                // Use default image if available
                                $imageSrc = SITE_URL . '/assets/images/default-project.jpg';
                            }
                            ?>
                            <img src="<?php echo $imageSrc; ?>" alt="<?php echo $project['title']; ?>" 
                                 class="portfolio-image" 
                                 style="width: 100%; height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="placeholder-image">
                                <i class="fas fa-project-diagram"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="portfolio-content">
                        <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                        <p><?php echo htmlspecialchars($project['description']); ?></p>
                        <div class="portfolio-tags">
                            <?php 
                            $tags = explode(',', $project['tags']);
                            foreach ($tags as $tag):
                                $cleanTag = trim($tag);
                            ?>
                                <span class="tag"><?php echo htmlspecialchars($cleanTag); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <button class="view-project-btn" data-project-id="<?php echo $project['id']; ?>" data-project-slug="<?php echo htmlspecialchars($project['slug']); ?>">
                            View Project
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Project Modal -->
<div id="projectModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="projectDetails"></div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all view project buttons
    const viewButtons = document.querySelectorAll('.view-project-btn');
    const modal = document.getElementById('projectModal');
    const modalContent = document.getElementById('projectDetails');
    const closeBtn = document.querySelector('.close');

    // Close modal when clicking the close button or outside
    closeBtn.onclick = function() {
        modal.style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }

    // Handle view project button clicks
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            fetchProjectDetails(this);
        });
    });

    // Fetch project details via AJAX
    async function fetchProjectDetails(button) {
        try {
            const projectId = button.dataset.projectId;
            const projectSlug = button.dataset.projectSlug;
            
            // Fetch project details
            const response = await fetch('<?php echo BASE_PATH; ?>/includes/ajax/get_project.php?id=' + projectId);
            const data = await response.json();
            
            if (data.success) {
                // Create the modal content
                const modalContent = `
                    <div class="project-header">
                        <h2>${data.project.title}</h2>
                        <div class="project-meta">
                            ${data.project.tags ? `<div class="tags">${data.project.tags}</div>` : ''}
                            ${data.project.category ? `<span class="category">${data.project.category}</span>` : ''}
                        </div>
                    </div>
                    ${data.project.featured_image ? 
                        `<div class="project-image">
                            <img src="${PROJECT_UPLOAD_PATH + data.project.featured_image}" alt="${data.project.title}">
                        </div>` : ''}
                    ${data.project.video_url ? 
                        `<div class="project-video">
                            <iframe src="${data.project.video_url}" allowfullscreen></iframe>
                        </div>` : ''}
                    <div class="project-content">
                        ${data.project.content}
                    </div>
                    <div class="project-links">
                        ${data.project.github_url ? 
                            `<a href="${data.project.github_url}" target="_blank" class="btn btn-secondary">
                                <i class="fab fa-github"></i> GitHub
                            </a>` : ''}
                        ${data.project.dashboard_url ? 
                            `<a href="${data.project.dashboard_url}" target="_blank" class="btn btn-primary">
                                <i class="fas fa-external-link-alt"></i> Live Demo
                            </a>` : ''}
                        <a href="<?php echo BASE_PATH; ?>/portfolio/view.php?slug=${projectSlug}" class="btn btn-primary">
                            <i class="fas fa-external-link-alt"></i> View Full Project
                        </a>
                    </div>
                `;
                
                // Update modal content and show it
                document.getElementById('projectDetails').innerHTML = modalContent;
                modal.style.display = 'block';
            } else {
                console.error('Error fetching project details');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }
});
</script>

<style>
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.7);
    z-index: 1000;
}

.modal-content {
    position: relative;
    background-color: #fff;
    margin: 2% auto;
    padding: 2rem;
    width: 90%;
    max-width: 1000px;
    max-height: 90vh;
    overflow-y: auto;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.close {
    position: absolute;
    right: 1rem;
    top: 1rem;
    font-size: 2rem;
    cursor: pointer;
    color: #666;
}

.close:hover {
    color: #333;
}

.project-header {
    text-align: center;
    margin-bottom: 2rem;
}

.project-meta {
    margin-top: 1rem;
    color: #666;
}

.project-image img {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
    margin: 1.5rem 0;
}

.project-video iframe {
    width: 100%;
    height: 360px;
    border: none;
    margin: 1.5rem 0;
}

.project-content {
    margin: 2rem 0;
}

.project-links {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 2rem;
}

.btn-secondary {
    background-color: #f0f0f0;
    color: #333;
}

.btn-primary {
    background-color: #007bff;
    color: #fff;
}

.btn {
    padding: 0.5rem 1.5rem;
    border-radius: 4px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: background-color 0.3s;
}

.btn:hover {
    opacity: 0.9;
}

.portfolio-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.portfolio-item {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}

.portfolio-item:hover {
    transform: translateY(-5px);
}

.portfolio-image {
    height: 200px;
    overflow: hidden;
}

.portfolio-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.placeholder-image {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f5f5f5;
    color: #666;
    font-size: 2rem;
}

.portfolio-content {
    padding: 1.5rem;
}

.portfolio-content h3 {
    margin: 0 0 1rem 0;
    color: #333;
}

.portfolio-content p {
    color: #666;
    margin-bottom: 1rem;
}

.portfolio-tags {
    margin-bottom: 1rem;
}

.tag {
    display: inline-block;
    background: #f0f0f0;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    margin-right: 0.5rem;
    font-size: 0.875rem;
    color: #666;
}

.view-project-btn {
    background: #007bff;
    color: #fff;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.view-project-btn:hover {
    background: #0056b3;
}
</style>
