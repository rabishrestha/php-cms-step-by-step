<?php
namespace HamroNews\Models;

// GUARANTEED SAFE: Only load the local contract interface file
require_once __DIR__ . '/categoryinterface.php';

class Category implements CategoryInterface {
    private int $id;
    private string $name;
    private string $slug;

    public function __construct(array $data) {
        $this->id   = (int)($data['id'] ?? 0);
        $this->name = $data['name'] ?? '';
        $this->slug = $data['slug'] ?? '';
    }

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getSlug(): string { return $this->slug; }
}