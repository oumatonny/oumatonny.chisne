-- Database setup script for Ouma Tonny Portfolio
-- Run this script to create the database and tables

-- Create database
CREATE DATABASE IF NOT EXISTS oumatonny_portfolio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE oumatonny_portfolio;

-- Users table (for admin authentication)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    full_name VARCHAR(100),
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Projects table
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    content LONGTEXT,
    featured_image VARCHAR(255),
    video_url VARCHAR(255),
    dashboard_url VARCHAR(255),
    github_url VARCHAR(255),
    tags VARCHAR(255),
    category VARCHAR(50),
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_slug (slug),
    INDEX idx_category (category),
    INDEX idx_featured (is_featured),
    INDEX idx_created (created_at)
);

-- Blog posts table
CREATE TABLE IF NOT EXISTS blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    excerpt TEXT,
    content LONGTEXT,
    featured_image VARCHAR(255),
    author_id INT,
    status ENUM('draft', 'published') DEFAULT 'published',
    published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_status (status),
    INDEX idx_published (published_at),
    INDEX idx_author (author_id)
);

-- Blog tags table
CREATE TABLE IF NOT EXISTS blog_tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    slug VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_slug (slug)
);

-- Blog post to tag relationship
CREATE TABLE IF NOT EXISTS blog_post_tags (
    post_id INT,
    tag_id INT,
    PRIMARY KEY (post_id, tag_id),
    FOREIGN KEY (post_id) REFERENCES blog_posts(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES blog_tags(id) ON DELETE CASCADE
);

-- Personal info table
CREATE TABLE IF NOT EXISTS personal_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    title VARCHAR(100),
    bio TEXT,
    email VARCHAR(100),
    phone VARCHAR(20),
    location VARCHAR(100),
    github_url VARCHAR(255),
    linkedin_url VARCHAR(255),
    twitter_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Contact messages table (optional - for storing contact form submissions)
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200),
    message TEXT NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_created (created_at),
    INDEX idx_read (is_read)
);

-- Page views table (optional - for analytics)
CREATE TABLE IF NOT EXISTS page_views (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page_url VARCHAR(255) NOT NULL,
    page_title VARCHAR(255),
    ip_address VARCHAR(45),
    user_agent TEXT,
    referer VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_page (page_url),
    INDEX idx_created (created_at),
    INDEX idx_ip (ip_address)
);

-- Insert default admin user (password: admin123 - CHANGE THIS!)
INSERT INTO users (username, password_hash, email, full_name) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@oumatonny.chisne.co.ke', 'Ouma Tonny')
ON DUPLICATE KEY UPDATE username = username;

-- Insert default personal info
INSERT INTO personal_info (id, full_name, title, bio, email, github_url) VALUES 
(1, 'Ouma Tonny', 'Programmer & Data Scientist', 
'I am a passionate programmer and data scientist with expertise in creating innovative solutions and deriving meaningful insights from data. With a strong background in both programming and data analysis, I bring a unique perspective to every project I work on.

My journey in the tech world has equipped me with a diverse skill set, allowing me to tackle complex problems and deliver high-quality results. I am constantly learning and exploring new technologies to stay at the forefront of the rapidly evolving tech landscape.',
'contact@oumatonny.chisne.co.ke', 'https://github.com/oumatonny')
ON DUPLICATE KEY UPDATE 
full_name = VALUES(full_name),
title = VALUES(title),
bio = VALUES(bio),
email = VALUES(email),
github_url = VALUES(github_url);

-- Insert sample projects
INSERT INTO projects (title, slug, description, content, category, tags, is_featured) VALUES 
('Data Analysis Dashboard', 'data-analysis-dashboard', 
'Interactive dashboard for analyzing sales data with real-time visualizations and insights.',
'<h2>Project Overview</h2>
<p>This project involved creating a comprehensive data analysis dashboard that provides real-time insights into sales performance, customer behavior, and market trends.</p>

<h3>Key Features</h3>
<ul>
<li>Real-time data visualization</li>
<li>Interactive charts and graphs</li>
<li>Automated report generation</li>
<li>Mobile-responsive design</li>
</ul>

<h3>Technologies Used</h3>
<p>The dashboard was built using Python, Pandas, Plotly, and Dash for the backend, with a modern React frontend for enhanced user experience.</p>',
'Data Science', 'Python, Pandas, Plotly, Dash, React', TRUE),

('E-commerce Website', 'ecommerce-website',
'Full-stack e-commerce platform with payment integration and admin panel.',
'<h2>Project Overview</h2>
<p>A complete e-commerce solution built from scratch with modern web technologies, featuring a user-friendly interface, secure payment processing, and comprehensive admin management.</p>

<h3>Features</h3>
<ul>
<li>Product catalog with search and filtering</li>
<li>Shopping cart and checkout process</li>
<li>Payment gateway integration</li>
<li>Order management system</li>
<li>Admin dashboard</li>
</ul>',
'Web Development', 'PHP, MySQL, JavaScript, Bootstrap, PayPal API', TRUE),

('Machine Learning Model', 'machine-learning-model',
'Predictive model for customer churn analysis using advanced ML algorithms.',
'<h2>Project Overview</h2>
<p>Developed a machine learning model to predict customer churn for a telecommunications company, helping them identify at-risk customers and implement retention strategies.</p>

<h3>Methodology</h3>
<ul>
<li>Data preprocessing and feature engineering</li>
<li>Model selection and hyperparameter tuning</li>
<li>Cross-validation and performance evaluation</li>
<li>Model deployment and monitoring</li>
</ul>',
'Data Science', 'Python, Scikit-learn, TensorFlow, Jupyter', FALSE)

ON DUPLICATE KEY UPDATE title = VALUES(title);

-- Insert sample blog posts
INSERT INTO blog_posts (title, slug, excerpt, content, author_id, status) VALUES 
('Getting Started with Data Science', 'getting-started-data-science',
'A comprehensive guide for beginners looking to enter the field of data science.',
'<h2>Introduction</h2>
<p>Data science is one of the most exciting and rapidly growing fields in technology today. This guide will help you understand what data science is and how to get started on your journey.</p>

<h3>What is Data Science?</h3>
<p>Data science is an interdisciplinary field that uses scientific methods, processes, algorithms, and systems to extract knowledge and insights from structured and unstructured data.</p>

<h3>Essential Skills</h3>
<ul>
<li>Programming (Python, R, SQL)</li>
<li>Statistics and Mathematics</li>
<li>Data Visualization</li>
<li>Machine Learning</li>
<li>Domain Knowledge</li>
</ul>

<h3>Learning Path</h3>
<p>Start with the basics of programming and statistics, then move on to data manipulation and visualization before diving into machine learning algorithms.</p>',
1, 'published'),

('Building Responsive Web Applications', 'building-responsive-web-applications',
'Learn the best practices for creating web applications that work seamlessly across all devices.',
'<h2>The Importance of Responsive Design</h2>
<p>In today\'s mobile-first world, creating responsive web applications is not just a nice-to-have feature—it\'s essential for success.</p>

<h3>Key Principles</h3>
<ul>
<li>Mobile-first approach</li>
<li>Flexible grid systems</li>
<li>Scalable images and media</li>
<li>Touch-friendly interfaces</li>
</ul>

<h3>Tools and Frameworks</h3>
<p>Modern CSS frameworks like Bootstrap and Tailwind CSS make it easier to create responsive designs, while tools like Flexbox and CSS Grid provide powerful layout options.</p>',
1, 'published')

ON DUPLICATE KEY UPDATE title = VALUES(title);

-- Insert sample tags
INSERT INTO blog_tags (name, slug) VALUES 
('Data Science', 'data-science'),
('Web Development', 'web-development'),
('Programming', 'programming'),
('Machine Learning', 'machine-learning'),
('Python', 'python'),
('JavaScript', 'javascript'),
('Career', 'career'),
('Tutorial', 'tutorial')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Associate tags with blog posts
INSERT INTO blog_post_tags (post_id, tag_id) 
SELECT p.id, t.id 
FROM blog_posts p, blog_tags t 
WHERE (p.slug = 'getting-started-data-science' AND t.slug IN ('data-science', 'career', 'tutorial'))
   OR (p.slug = 'building-responsive-web-applications' AND t.slug IN ('web-development', 'programming', 'tutorial'))
ON DUPLICATE KEY UPDATE post_id = VALUES(post_id);

-- Create indexes for better performance
CREATE INDEX idx_projects_featured_created ON projects(is_featured, created_at);
CREATE INDEX idx_blog_posts_status_published ON blog_posts(status, published_at);
CREATE INDEX idx_contact_messages_read_created ON contact_messages(is_read, created_at);

-- Show completion message
SELECT 'Database setup completed successfully!' as message;
