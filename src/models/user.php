<?php
namespace HamroNews\Models;

require_once __DIR__ . '/userinterface.php';

class User implements UserInterface {
    private int $id;
    private string $username;
    private string $full_name;
    private string $email;
    private ?string $contact_no;
    private string $role;
    private string $status;

    public function __construct(array $data) {
        $this->id         = (int)($data['id'] ?? 0);
        $this->username   = $data['username'] ?? '';
        $this->full_name  = $data['full_name'] ?? '';
        $this->email      = $data['email'] ?? '';
        $this->contact_no = $data['contact_no'] ?? null;
        $this->role       = $data['role'] ?? 'Reporter';
        $this->status     = $data['status'] ?? 'Pending';
    }

    public function getId(): int { return $this->id; }
    public function getUsername(): string { return $this->username; }
    public function getFullName(): string { return $this->full_name; }
    public function getEmail(): string { return $this->email; }
    public function getContactNo(): ?string { return $this->contact_no; }
    public function getRole(): string { return $this->role; }
    public function getStatus(): string { return $this->status; }
}