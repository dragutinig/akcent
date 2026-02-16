<?php

require_once 'Database.php';

class ProjectRepository
{
    private $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function ensureSchema(): void
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS projects (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $this->db->query("CREATE TABLE IF NOT EXISTS project_images (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $this->db->query("CREATE TABLE IF NOT EXISTS project_models (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            project_id INT UNSIGNED NOT NULL,
            model_label VARCHAR(255) DEFAULT NULL,
            model_path VARCHAR(500) NOT NULL,
            sort_order INT NOT NULL DEFAULT 0,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_project_models_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
            INDEX idx_project_models_project (project_id, sort_order, id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        $this->db->query("CREATE TABLE IF NOT EXISTS project_comments (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");


        $this->db->query("CREATE TABLE IF NOT EXISTS post_comments_public (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            post_id INT UNSIGNED NOT NULL,
            author_name VARCHAR(120) NOT NULL,
            author_email VARCHAR(190) DEFAULT NULL,
            comment_text TEXT NOT NULL,
            status ENUM('approved','pending','spam') NOT NULL DEFAULT 'approved',
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_post_comments_public (post_id, status, created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        $this->db->query("CREATE TABLE IF NOT EXISTS client_3d_previews (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }



    private function normalizeStoredPath(?string $path): string
    {
        $path = trim((string) $path);
        if ($path === '') {
            return '';
        }

        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        $path = str_replace('\\', '/', $path);

        // legacy absolute filesystem path -> convert to relative from /blog or /img or /gallery
        foreach (array('/blog/', '/img/', '/gallery/') as $needle) {
            $pos = stripos($path, $needle);
            if ($pos !== false) {
                return ltrim(substr($path, $pos + 1), '/');
            }
        }

        while (strpos($path, '../') === 0) {
            $path = substr($path, 3);
        }

        return ltrim($path, '/');
    }
    public function createProject(array $data): int
    {
        $sql = "INSERT INTO projects (title, slug, status, meta_title, meta_description, excerpt, content, model_path, blog_post_url, published_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ssssssssss', $data['title'], $data['slug'], $data['status'], $data['meta_title'], $data['meta_description'], $data['excerpt'], $data['content'], $data['model_path'], $data['blog_post_url'], $data['published_at']);
        $stmt->execute();
        return (int) $stmt->insert_id;
    }

    public function addProjectImage(int $projectId, array $img): void
    {
        $sql = "INSERT INTO project_images (project_id, image_path, alt_text, title_text, sort_order, width, height)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('isssiii', $projectId, $img['image_path'], $img['alt_text'], $img['title_text'], $img['sort_order'], $img['width'], $img['height']);
        $stmt->execute();
    }

    public function listProjects(?string $status = null): array
    {
        if ($status !== null) {
            $stmt = $this->db->prepare('SELECT * FROM projects WHERE status = ? ORDER BY created_at DESC');
            $stmt->bind_param('s', $status);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $this->db->query('SELECT * FROM projects ORDER BY created_at DESC');
        }

        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $row['model_path'] = $this->normalizeStoredPath($row['model_path'] ?? '');
            $row['blog_post_url'] = $this->normalizeStoredPath($row['blog_post_url'] ?? '');
            $row['images'] = $this->listProjectImages((int) $row['id']);
            $row['models'] = $this->listProjectModels((int) $row['id']);
            $rows[] = $row;
        }
        return $rows;
    }

    public function getProjectBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM projects WHERE slug = ? LIMIT 1');
        $stmt->bind_param('s', $slug);
        $stmt->execute();
        $project = $stmt->get_result()->fetch_assoc();
        if (!$project) {
            return null;
        }
        $project['model_path'] = $this->normalizeStoredPath($project['model_path'] ?? '');
        $project['blog_post_url'] = $this->normalizeStoredPath($project['blog_post_url'] ?? '');
        $project['images'] = $this->listProjectImages((int) $project['id']);
        $project['models'] = $this->listProjectModels((int) $project['id']);
        return $project;
    }

    public function addProjectModel(int $projectId, array $model): void
    {
        $sql = "INSERT INTO project_models (project_id, model_label, model_path, sort_order)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('issi', $projectId, $model['model_label'], $model['model_path'], $model['sort_order']);
        $stmt->execute();
    }

    public function listProjectModels(int $projectId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM project_models WHERE project_id = ? ORDER BY sort_order ASC, id ASC');
        $stmt->bind_param('i', $projectId);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = [];
        while ($r = $res->fetch_assoc()) {
            $r['model_path'] = $this->normalizeStoredPath($r['model_path'] ?? '');
            $rows[] = $r;
        }
        return $rows;
    }

    public function listProjectImages(int $projectId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM project_images WHERE project_id = ? ORDER BY sort_order ASC, id ASC');
        $stmt->bind_param('i', $projectId);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = [];
        while ($r = $res->fetch_assoc()) {
            $r['image_path'] = $this->normalizeStoredPath($r['image_path'] ?? '');
            $rows[] = $r;
        }
        return $rows;
    }

    public function deleteProject(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM projects WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }

    public function addComment(array $data): void
    {
        $stmt = $this->db->prepare('INSERT INTO project_comments (project_id, author_name, author_email, comment_text, status, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('issssss', $data['project_id'], $data['author_name'], $data['author_email'], $data['comment_text'], $data['status'], $data['ip_address'], $data['user_agent']);
        $stmt->execute();
    }

    public function listApprovedComments(int $projectId, int $limit = 20): array
    {
        $stmt = $this->db->prepare('SELECT author_name, comment_text, created_at FROM project_comments WHERE project_id = ? AND status = "approved" ORDER BY created_at DESC LIMIT ?');
        $stmt->bind_param('ii', $projectId, $limit);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = [];
        while ($r = $res->fetch_assoc()) {
            $rows[] = $r;
        }
        return $rows;
    }

    public function createClientPreview(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO client_3d_previews (client_name, model_label, preview_token, model_path, review_date, expires_at, notes) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('sssssss', $data['client_name'], $data['model_label'], $data['preview_token'], $data['model_path'], $data['review_date'], $data['expires_at'], $data['notes']);
        $stmt->execute();
        return (int) $stmt->insert_id;
    }

    public function updateClientPreview(int $id, array $data): void
    {
        $stmt = $this->db->prepare('UPDATE client_3d_previews SET client_name = ?, model_label = ?, model_path = ?, review_date = ?, expires_at = ?, notes = ? WHERE id = ?');
        $stmt->bind_param('ssssssi', $data['client_name'], $data['model_label'], $data['model_path'], $data['review_date'], $data['expires_at'], $data['notes'], $id);
        $stmt->execute();
    }

    public function listClientPreviews(): array
    {
        $res = $this->db->query('SELECT * FROM client_3d_previews ORDER BY created_at DESC');
        $rows = [];
        while ($r = $res->fetch_assoc()) {
            $rows[] = $r;
        }
        return $rows;
    }

    public function getClientPreview(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM client_3d_previews WHERE id = ? LIMIT 1');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if ($row) {
            $row['model_path'] = $this->normalizeStoredPath($row['model_path'] ?? '');
        }
        return $row ?: null;
    }

    public function getClientPreviewByToken(string $token): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM client_3d_previews WHERE preview_token = ? LIMIT 1');
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if ($row) {
            $row['model_path'] = $this->normalizeStoredPath($row['model_path'] ?? '');
        }
        return $row ?: null;
    }

    public function deleteClientPreview(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM client_3d_previews WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
    }
}
