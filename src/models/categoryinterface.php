<?php
namespace HamroNews\Models;

interface CategoryInterface {
    public function getId(): int;
    public function getName(): string;
    public function getSlug(): string;
}