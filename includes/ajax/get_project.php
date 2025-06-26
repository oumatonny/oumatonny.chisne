<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../functions.php';

// Ensure we're in the correct directory
chdir(__DIR__ . '/../../');

header('Content-Type: application/json');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: /oumatonny/portfolio');
    exit;
}

$projectId = intval($_GET['id']);
$project = getProject($projectId);

if (!$project) {
    header('Location: /oumatonny/portfolio');
    exit;
}

// Format the tags for display
$tags = $project['tags'] ? explode(',', $project['tags']) : [];
$formattedTags = array_map('trim', $tags);
$tagElements = '';
foreach ($formattedTags as $tag) {
    $tagElements .= sprintf('<span class="tag">%s</span>', htmlspecialchars($tag));
}

// Format content for display
$content = $project['content'] ?? '';
$content = str_replace(['<p>', '</p>'], ['<p class="project-paragraph">', '</p>'], $content);

$response = [
    'success' => true,
    'project' => [
        'title' => htmlspecialchars($project['title']),
        'description' => htmlspecialchars($project['description']),
        'content' => $content,
        'featured_image' => $project['featured_image'],
        'video_url' => $project['video_url'],
        'github_url' => $project['github_url'],
        'dashboard_url' => $project['dashboard_url'],
        'tags' => $tagElements,
        'category' => htmlspecialchars($project['category'])
    ]
];

echo json_encode($response);
