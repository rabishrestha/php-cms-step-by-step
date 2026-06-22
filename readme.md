::: container
# 📂 Project Architecture & Security Fundamentals

When building web applications with pure PHP, maintaining a clean
project structure is not just about keeping files neat---it directly
dictates your application\'s **security profile** and **cross-platform
reliability**.

## 🛠️ The Suggested Project Directory

Below is the blueprint for our standard, framework-free architecture. It
utilizes a **Public/Private Split** and strict lowercase naming rules.

    my-php-project/
    ├── config/                  # Server configuration & credentials
    │   ├── db.php               # Database connection logic (PDO wrapper)
    │   └── config.php           # Global configurations, site constants & error toggles
    │
    ├── src/                     # Private Backend Core (The Engine)
    │   ├── auth/                # Login modules, registration processing & session logic
    │   ├── database/            # Database queries (CRUD operations for posts/users)
    │   └── helpers/             # Utility toolkits (data sanitization & validation formulas)
    │
    ├── templates/               # Modular Frontend Layouts
    │   ├── header.php           # Global top navigation, meta tags & stylesheet attachments
    │   └── footer.php           # Global bottom footer & JavaScript script injection
    │
    ├── public/                  # ⚠️ THE ONLY FOLDER EXPOSED TO THE WEB INTERNET
    │   ├── assets/              # Browser-facing static files
    │   │   ├── css/
    │   │   │   └── style.css    # Global template styling
    │   │   ├── js/
    │   │   │   └── app.js       # Global dynamic script actions
    │   │   └── images/          # Uploads, icons, and dynamic banners
    │   │
    │   ├── index.php            # Public Homepage / Guest landing feed
    │   ├── login.php            # Administration entry screen
    │   └── dashboard.php        # Secured user management terminal
    │
    ├── .htaccess                # Apache routing instructions (For clean SEO URLs)
    └── readme.html              # Project documentation manual

------------------------------------------------------------------------

## 🛡️ 1. How the `public/` Folder Maintains Security

In an unorganized PHP arrangement, developers often scatter
administrative backend scripts directly alongside public layout views
within the root directory. This makes critical configuration engines
highly vulnerable.

If your web server suffers a temporary processing bottleneck or a minor
configuration glitch, it might fail to execute PHP files correctly.
Instead, it could serve files like `config/db.php` as raw text files
directly inside the user\'s browser---**instantly revealing your
plaintext database passwords to the world.**

By splitting files using a dedicated `public/` boundary, you manually
instruct your web server (Apache or Nginx) to lock its operations
exclusively into that subdirectory as its **Document Root**.

### The Architectural Isolation Layer

-   **The Public Face (`public/`):** This is the only folder visible to
    web clients. Browsers can only download or interact with assets
    housed right inside this container (e.g., loading `style.css` or
    viewing `index.php`).
-   **The Invisible Vault (`config/`, `src/`, `templates/`):** These
    parent directories sit securely outside the server\'s visual scope.
    They possess no valid external URL. Attempting to force access via a
    browser path like `example.com/config/db.php` returns an immediate
    `404 Not Found` or `403 Forbidden` restriction.
-   **The Private Bridge:** Your frontend entry files inside `public/`
    dynamically request backend support behind the safety of the
    server\'s firewall via fast, internal filesystem paths:

```{=html}
<!-- -->
```
    <?php
    require_once __DIR__ . '/../config/db.php';
    ?>

------------------------------------------------------------------------

## 🔤 2. Why Lowercase File & Folder Names are Crucial

You should explicitly commit to **all-lowercase syntax** across your
entire project workspace (e.g., referencing `dashboard.php` rather than
`Dashboard.php`).

This uniform standard prevents runtime structural failures caused by
**Operating System Case Sensitivity Constraints**:

  Operating System Context   Filesystem Rule               Real-World Operational Consequence
  -------------------------- ----------------------------- --------------------------------------------------------------------------------------------------------------------
  **Windows & macOS**        Case-**Insensitive**          Requesting `/login.php` or `/Login.php` targets the exact same file without issue.
  **Linux / Unix**           **Strictly Case-Sensitive**   Requesting `/login.php` while your file is saved as `Login.php` creates a critical **404 Not Found** link failure.

::: tip-box
**💡 Core Lesson for Learners:** Because more than 90% of global web
servers deploy exclusively on Linux infrastructure, sticking to strict
lowercase styling across your workspace ensures your framework-free
application functions flawlessly on your local computer and your
production server alike.
:::
:::
