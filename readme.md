my-php-project/
├── config/                  # Database credentials and global settings
│   ├── db.php               # PDO Database connection logic
│   └── config.php           # App constants, error reporting settings
│
├── src/                     # Private Backend Logic (The "Engine")
│   ├── auth/                # Login, registration, session checks
│   ├── database/            # Functions/Classes that talk to the DB
│   └── helpers/             # Utility functions (validation, sanitization)
│
├── templates/               # Reusable HTML layout pieces
│   ├── header.php           # Top navigation, meta tags, CSS links
│   └── footer.php           # Bottom footer, JS script tags
│
├── public/                  # The ONLY folder exposed to the web
│   ├── assets/              # Static frontend files
│   │   ├── css/
│   │   │   └── style.css
│   │   ├── js/
│   │   │   └── app.js
│   │   └── images/
│   │
│   ├── index.php            # Homepage / Landing page
│   ├── login.php            # Login page
│   └── dashboard.php        # Protected user dashboard
│
├── .htaccess                # Apache configuration (optional, for clean URLs)
└── readme.md


# Core Architecture & Security Fundamentals

When building web applications with pure PHP, maintaining a clean project structure is not just about organization—it directly impacts your application's security and cross-platform compatibility.

---

## 1. How the `public/` Folder Maintains Security

In a poorly structured PHP application, all files live in the main root directory. This allows anyone to attempt to access files like `config/db.php` directly through their browser. If the web server ever misconfigures or undergoes a temporary crash, it might serve that file as plain text instead of executing it, **exposing your database credentials to the open internet.**

By using a **Public/Private Split**, you configure your web server (such as Apache or Nginx) to look directly into the `public/` folder as its **Document Root**.

### The Security Boundary
* **The Exposed Zone (`public/`):** The internet can *only* see and touch assets inside this directory (e.g., `index.php`, `login.php`, images, CSS, and JavaScript files).
* **The Protected Vault (`config/`, `src/`, `templates/`):** These files live completely outside the web root. They are physically inaccessible via a browser URL. If a visitor types `example.com/config/db.php`, the server returns a `404 Not Found` or `403 Forbidden` error.
* **The Bridge:** Your frontend scripts inside `public/` safely pull in backend code using filesystem-level functions:
```php
  require_once __DIR__ . '/../config/db.php';