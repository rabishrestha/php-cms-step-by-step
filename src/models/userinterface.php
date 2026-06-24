<?php
namespace HamroNews\Models;

interface UserInterface {
    public function getId(): int;
    public function getUsername(): string;
    public function getFullName(): string;
    public function getEmail(): string;
    public function getContactNo(): ?string;
    public function getRole(): string;
    public function getStatus(): string;
}