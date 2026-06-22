<?php
namespace HamroNews\Database;

// This file contains ONLY the structural definition (Contract)
interface PostInterface {
    public function getId(): int;
    public function getTitle(): string;
    public function getSlug(): string;
    public function getSummary(): string;
    public function getContent(): string;
    public function getCategory(): string;
    public function getAuthor(): string;
    public function getPublishedAt(): string;
}