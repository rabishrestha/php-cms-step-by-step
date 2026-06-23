<?php
namespace HamroNews\Models;

require_once __DIR__ . '/postinterface.php';

class Post implements PostInterface {
    private int $id;
    private int $userId;
    private int $categoryId;
    private string $title;
    private string $slug;
    private string $summary;
    private string $content;
    private string $image;
    private string $publishedAt;
    
    // Joint Relational Properties
    private string $authorName;
    private string $categoryName;

    public function __construct(array $data) {
        $this->id           = (int)($data['id'] ?? 0);
        $this->userId       = (int)($data['user_id'] ?? 0);
        $this->categoryId   = (int)($data['category_id'] ?? 0);
        $this->title        = $data['title'] ?? '';
        $this->slug         = $data['slug'] ?? '';
        $this->summary      = $data['summary'] ?? '';
        $this->content      = $data['content'] ?? '';
        $this->image        = $data['image'] ?? 'default.jpg';
        $this->publishedAt  = $data['published_at'] ?? '';
        
        // Populate relational assignments if retrieved via SQL Joins
        $this->authorName   = $data['author_name'] ?? 'Unknown Author';
        $this->categoryName = $data['category_name'] ?? 'General';
    }

    public function getId(): int { return $this->id; }
    public function getUserId(): int { return $this->userId; }
    public function getCategoryId(): int { return $this->categoryId; }
    public function getTitle(): string { return $this->title; }
    public function getSlug(): string { return $this->slug; }
    public function getSummary(): string { return $this->summary; }
    public function getContent(): string { return $this->content; }
    public function getImage(): string { return $this->image; }
    public function getPublishedAt(): string { return $this->publishedAt; }
    
    // Advanced UI Getters
    public function getAuthor(): string { return $this->authorName; }
    public function getCategory(): string { return $this->categoryName; }
}