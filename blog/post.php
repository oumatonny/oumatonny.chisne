<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$currentPage = 'blog';

// Get post slug from URL
$slug = isset($_GET['slug']) ? clean($_GET['slug']) : '';

if (empty($slug)) {
    // Redirect to blog page if no slug provided
    redirect('/oumatonny/blog');
}

// Get post details
$post = getBlogPost($slug);

if (!$post) {
    // Redirect to blog page if post not found
    redirect('/oumatonny/blog');
}

// Get post tags
$tags = getPostTags($post['id']);

$pageTitle = $post['title'];
$pageDescription = $post['excerpt'] ? $post['excerpt'] : substr(strip_tags($post['content']), 0, 160);

include '../includes/header.php';
?>

<section class="post-header">
    <div class="container">
        <div class="breadcrumbs">
            <a href="/oumatonny/blog">Blog</a> / <span><?php echo $post['title']; ?></span>
        </div>
        <h1><?php echo $post['title']; ?></h1>
        <div class="post-meta">
            <span class="post-date"><?php echo formatDate($post['published_at']); ?></span>
            <span class="post-author">By <?php echo $post['author_name']; ?></span>
        </div>
    </div>
</section>

<section class="post-content">
    <div class="container">
        <div class="post-layout">
            <div class="post-main">
                <?php if (!empty($post['featured_image'])): ?>
                    <div class="post-featured-image">
                        <img src="/assets/uploads/blog/<?php echo $post['featured_image']; ?>" alt="<?php echo $post['title']; ?>">
                    </div>
                <?php endif; ?>
                
                <div class="post-body">
                    <?php echo $post['content']; ?>
                </div>
                
                <?php if ($tags): ?>
                    <div class="post-tags">
                        <h3>Tags:</h3>
                        <div class="tags-list">
                            <?php foreach ($tags as $tag): ?>
                                <a href="/oumatonny/blog/index.php?tag=<?php echo $tag['slug']; ?>" class="tag">
                                    <?php echo $tag['name']; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="post-navigation">
                    <?php
                    // Get previous and next posts
                    $db = Database::getInstance();
                    $prevPost = $db->fetch("SELECT title, slug FROM blog_posts 
                                          WHERE published_at < :published_at AND status = 'published' 
                                          ORDER BY published_at DESC LIMIT 1", 
                                          [':published_at' => $post['published_at']]);
                    
                    $nextPost = $db->fetch("SELECT title, slug FROM blog_posts 
                                          WHERE published_at > :published_at AND status = 'published' 
                                          ORDER BY published_at ASC LIMIT 1", 
                                          [':published_at' => $post['published_at']]);
                    ?>
                    
                    <div class="nav-links">
                        <?php if ($prevPost): ?>
                            <a href="/oumatonny/blog/post.php?slug=<?php echo $prevPost['slug']; ?>" class="nav-previous">
                                <i class="fas fa-arrow-left"></i>
                                <span><?php echo $prevPost['title']; ?></span>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($nextPost): ?>
                            <a href="/oumatonny/blog/post.php?slug=<?php echo $nextPost['slug']; ?>" class="nav-next">
                                <span><?php echo $nextPost['title']; ?></span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="post-sidebar">
                <div class="sidebar-widget">
                    <h3>Recent Posts</h3>
                    <?php
                    $recentPosts = getBlogPosts(5);
                    if ($recentPosts):
                    ?>
                        <ul class="recent-posts">
                            <?php foreach ($recentPosts as $recentPost): ?>
                                <?php if ($recentPost['id'] != $post['id']): ?>
                                    <li>
                                        <a href="/oumatonny/blog/post.php?slug=<?php echo $recentPost['slug']; ?>">
                                            <?php echo $recentPost['title']; ?>
                                        </a>
                                        <span class="post-date"><?php echo formatDate($recentPost['published_at']); ?></span>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
                
                <?php if ($tags): ?>
                    <div class="sidebar-widget">
                        <h3>Tags</h3>
                        <div class="tag-cloud">
                            <?php foreach ($tags as $tag): ?>
                                <a href="/oumatonny/blog/index.php?tag=<?php echo $tag['slug']; ?>" class="tag">
                                    <?php echo $tag['name']; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
