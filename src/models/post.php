<?php
namespace HamroNews\Models; // Updated namespace to reflect models folder

use Exception;

// Since both interface and class live in src/models/, use a direct local include
require_once __DIR__ . '/postinterface.php';

class Post implements PostInterface {
    private int $id;
    private string $title;
    private string $slug;
    private string $summary;
    private string $content;
    private string $category;
    private string $author;
    private string $publishedAt;

    public function __construct(array $data) {
        if (!isset($data['title']) || !isset($data['slug'])) {
            throw new Exception("Missing critical post parameters.");
        }

        $this->id          = $data['id'] ?? 0;
        $this->title       = $data['title'];
        $this->slug        = $data['slug'];
        $this->summary     = $data['summary'] ?? '';
        $this->content     = $data['content'] ?? '';
        $this->category    = $data['category'] ?? 'General';
        $this->author      = $data['author'] ?? 'Staff Writer';
        $this->publishedAt = $data['published_at'] ?? date('F j, Y');
    }

    public function getId(): int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function getSlug(): string { return $this->slug; }
    public function getSummary(): string { return $this->summary; }
    public function getContent(): string { return $this->content; }
    public function getCategory(): string { return $this->category; }
    public function getAuthor(): string { return $this->author; }
    public function getPublishedAt(): string { return $this->publishedAt; }
}