-- Projekti + slike + komentari (SEO i javna stranica gotovih projekata)
CREATE TABLE IF NOT EXISTS projects (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    status ENUM('draft','published') NOT NULL DEFAULT 'draft',
    meta_title VARCHAR(255) DEFAULT NULL,
    meta_description VARCHAR(320) DEFAULT NULL,
    excerpt TEXT DEFAULT NULL,
    content MEDIUMTEXT DEFAULT NULL,
    model_path VARCHAR(500) DEFAULT NULL,
    blog_post_url VARCHAR(500) DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    published_at DATETIME DEFAULT NULL,
    INDEX idx_projects_status_created (status, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS project_images (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    project_id INT UNSIGNED NOT NULL,
    image_path VARCHAR(500) NOT NULL,
    alt_text VARCHAR(255) DEFAULT NULL,
    title_text VARCHAR(255) DEFAULT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    width INT DEFAULT NULL,
    height INT DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_project_images_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    INDEX idx_project_images_project (project_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS project_comments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    project_id INT UNSIGNED NOT NULL,
    author_name VARCHAR(120) NOT NULL,
    author_email VARCHAR(190) DEFAULT NULL,
    comment_text TEXT NOT NULL,
    status ENUM('approved','pending','spam') NOT NULL DEFAULT 'approved',
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent VARCHAR(255) DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_project_comments_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    INDEX idx_project_comments_project_status (project_id, status, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Privatni 3D preview linkovi za klijente (van javnog sajta)
CREATE TABLE IF NOT EXISTS client_3d_previews (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(190) NOT NULL,
    model_label VARCHAR(255) NOT NULL,
    preview_token VARCHAR(64) NOT NULL UNIQUE,
    model_path VARCHAR(500) NOT NULL,
    review_date DATE DEFAULT NULL,
    expires_at DATETIME DEFAULT NULL,
    notes TEXT DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_client_3d_expires (expires_at),
    INDEX idx_client_3d_review_date (review_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
