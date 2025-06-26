<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$currentPage = 'blog';
$pageTitle = 'Blog';
$pageDescription = 'Read Ouma Tonny\'s insights and experiences in programming and data science';

// Get all blog posts
$posts = getBlogPosts();

// Get all tags for filter
$db = Database::getInstance();
$tags = $db->fetchAll("SELECT t.* FROM blog_tags t 
                      JOIN blog_post_tags pt ON t.id = pt.tag_id 
                      GROUP BY t.id 
                      ORDER BY t.name");

include '../includes/header.php';
?>

<section class="hero-section mb-5">
    <div class="container">
        <div class="hero-content-wrapper" style="min-height: 30vh;">
            <div class="hero-text">
                <h1 class="animated-text">
                    <span class="name">My Blog</span>
                </h1>
                <p class="dynamic-description" id="dynamicDescription" style="max-width: 600px;">Insights, tutorials, and reflections on data science, programming, and technology.</p>
                <div class="hero-buttons animated-text-delay-3">
                    <a href="<?php echo BASE_PATH; ?>/contact.php" class="btn btn-primary">Get In Touch</a>
                    <a href="#blog-main" class="btn btn-outline">Read Posts</a>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-background">
        <div class="animated-bg"></div>
    </div>
</section>

<section class="blog-section">
    <div class="container">
        <div class="blog-layout">
            <div class="blog-main" id="blog-main">
                <?php if ($posts): ?>
                    <div class="posts-grid">
                        <?php foreach ($posts as $post): ?>
                            <div class="post-card">
                                <div class="post-image">
                                    <?php if (!empty($post['featured_image'])): ?>
                                        <a href="/blog/post.php?slug=<?php echo $post['slug']; ?>">
                                            <img src="/assets/uploads/blog/<?php echo $post['featured_image']; ?>" alt="<?php echo $post['title']; ?>">
                                        </a>
                                    <?php else: ?>
                                        <a href="/blog/post.php?slug=<?php echo $post['slug']; ?>">
                                            <img src="/assets/images/blog-placeholder.jpg" alt="<?php echo $post['title']; ?>">
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <div class="post-info">
                                    <div class="post-meta">
                                        <span class="post-date"><?php echo formatDate($post['published_at']); ?></span>
                                        <span class="post-author">By <?php echo $post['author_name']; ?></span>
                                    </div>
                                    <h3>
                                        <a href="/blog/post.php?slug=<?php echo $post['slug']; ?>">
                                            <?php echo $post['title']; ?>
                                        </a>
                                    </h3>
                                    <p>
                                        <?php echo $post['excerpt'] ? $post['excerpt'] : substr(strip_tags($post['content']), 0, 150) . '...'; ?>
                                    </p>
                                    <a href="/blog/post.php?slug=<?php echo $post['slug']; ?>" class="read-more">Read More</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-posts">
                        <p>No blog posts found. Check back soon!</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="blog-sidebar">
                <?php if ($tags): ?>
                    <div class="sidebar-widget">
                        <h3>Tags</h3>
                        <div class="tag-cloud">
                            <?php foreach ($tags as $tag): ?>
                                <a href="/blog/index.php?tag=<?php echo $tag['slug']; ?>" class="tag">
                                    <?php echo $tag['name']; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="sidebar-widget">
                    <h3>Recent Posts</h3>
                    <?php
                    $recentPosts = getBlogPosts(5);
                    if ($recentPosts):
                    ?>
                        <ul class="recent-posts">
                            <?php foreach ($recentPosts as $recentPost): ?>
                                <li>
                                    <a href="/blog/post.php?slug=<?php echo $recentPost['slug']; ?>">
                                        <?php echo $recentPost['title']; ?>
                                    </a>
                                    <span class="post-date"><?php echo formatDate($recentPost['published_at']); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
