-- Sample data for Ouma Tonny Portfolio
-- Run this script to populate the database with sample content

USE oumatonny_portfolio;

-- Insert admin user
INSERT INTO users (username, password_hash, email, full_name) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@oumatonny.com', 'Ouma Tonny');

-- Insert personal information
INSERT INTO personal_info (full_name, title, bio, email, github_url, linkedin_url, twitter_url) VALUES
('Ouma Tonny', 'Programmer & Data Scientist',
'Passionate about creating innovative solutions through programming and data science. Specializing in developing efficient algorithms and building robust systems.',
'contact@oumatonny.com',
'https://github.com/oumatonny',
'https://linkedin.com/in/oumatonny',
'https://twitter.com/oumatonny');

-- Insert blog tags
INSERT INTO blog_tags (name, slug) VALUES
('Data Science', 'data-science'),
('Programming', 'programming'),
('Machine Learning', 'machine-learning'),
('Web Development', 'web-development'),
('Career', 'career');

-- Insert blog posts
INSERT INTO blog_posts (title, slug, excerpt, content, author_id, status, published_at) VALUES
('Introduction to Data Science', 'introduction-to-data-science',
'An overview of data science concepts and tools',
'<h2>Introduction to Data Science</h2>
<p>Data science is an interdisciplinary field that uses scientific and statistical approaches, algorithms, and computer systems to extract knowledge and insights from data.</p>
<h3>Key Concepts</h3>
<ul>
<li>Data Analysis</li>
<li>Machine Learning</li>
<li>Statistical Analysis</li>
<li>Data Visualization</li>
</ul>
<h3>Tools and Technologies</h3>
<ul>
<li>Python</li>
<li>R</li>
<li>SQL</li>
<li>TensorFlow</li>
<li>Scikit-learn</li>
</ul>',
1, 'published', NOW()),

('My Journey into Data Science', 'my-journey-into-data-science',
'Reflections on my transition into data science',
'<h2>My Journey into Data Science</h2>
<p>My journey into data science began during my computer science studies when I first encountered the power of data analysis and machine learning algorithms.</p>
<h3>Early Challenges</h3>
<p>Initially, I struggled with understanding complex statistical concepts and implementing machine learning algorithms. However, through consistent practice and learning, I gradually built a solid foundation in data science.</p>
<h3>Key Learnings</h3>
<ul>
<li>Importance of clean data</li>
<li>Feature engineering techniques</li>
<li>Model evaluation metrics</li>
<li>Deployment strategies</li>
</ul>',
1, 'published', NOW());

-- Associate blog posts with tags
INSERT INTO blog_post_tags (post_id, tag_id) VALUES
(1, 1),  -- Introduction to Data Science -> Data Science
(1, 2),  -- Introduction to Data Science -> Programming
(2, 1),  -- My Journey -> Data Science
(2, 2),  -- My Journey -> Programming
(2, 3);  -- My Journey -> Machine Learning

-- Insert projects
INSERT INTO projects (title, slug, description, content, featured_image, github_url, tags, category, is_featured) VALUES
('Weather Prediction System', 'weather-prediction-system',
'AI-powered weather prediction system using historical data and machine learning algorithms.',
'<h2>Project Overview</h2>
<p>This project implements a sophisticated weather prediction system that uses machine learning algorithms to forecast weather conditions based on historical meteorological data.</p>
<h3>Key Features</h3>
<ul>
<li>Historical weather data analysis</li>
<li>Multiple ML models for prediction</li>
<li>Real-time weather API integration</li>
<li>Interactive web interface</li>
<li>Accuracy metrics and model comparison</li>
</ul>
<h3>Technical Implementation</h3>
<p>The system uses ensemble methods combining Random Forest, LSTM neural networks, and XGBoost to achieve high prediction accuracy. Data preprocessing includes feature engineering for temporal patterns and weather correlations.</p>
<h3>Results</h3>
<p>Achieved 85% accuracy in 7-day weather forecasts and 92% accuracy for next-day predictions, outperforming traditional meteorological models in specific regional conditions.</p>',
'weather-system.jpg',
'https://github.com/oumatonny/weather-prediction',
'Python, TensorFlow, Scikit-learn, Flask, API Integration',
'Data Science', TRUE),

('Task Management App', 'task-management-app',
'Full-stack task management application with team collaboration features.',
'<h2>Project Overview</h2>
<p>A comprehensive task management application designed for teams and individuals to organize, track, and collaborate on projects efficiently.</p>
<h3>Features</h3>
<ul>
<li>Project and task organization</li>
<li>Team collaboration tools</li>
<li>Real-time notifications</li>
<li>File sharing and comments</li>
<li>Progress tracking and reporting</li>
<li>Mobile-responsive design</li>
</ul>
<h3>Architecture</h3>
<p>Built with a modern tech stack including React for the frontend, Node.js and Express for the backend, and MongoDB for data storage. Real-time features implemented using Socket.io.</p>',
'task-manager.jpg',
'https://github.com/oumatonny/task-manager',
'React, Node.js, Express, MongoDB, Socket.io',
'Web Development', FALSE);

-- Insert contact message (example)
INSERT INTO contact_messages (name, email, subject, message, created_at) VALUES
('John Doe', 'john@example.com', 'General Inquiry', 'I have some questions about your services.', NOW());