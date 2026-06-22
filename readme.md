  Project Architecture & Security Fundamentals :root { --bg-color: #f8f9fa; --text-color: #212529; --card-bg: #ffffff; --border-color: #dee2e6; --primary: #0066cc; --accent: #d9534f; --code-bg: #f1f3f5; } body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; line-height: 1.6; background-color: var(--bg-color); color: var(--text-color); margin: 0; padding: 40px 20px; } .container { max-width: 850px; margin: 0 auto; background: var(--card-bg); padding: 40px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid var(--border-color); } h1 { font-size: 2.2rem; color: #111; border-bottom: 2px solid var(--primary); padding-bottom: 10px; margin-top: 0; } h2 { font-size: 1.5rem; color: #222; margin-top: 30px; border-bottom: 1px solid var(--border-color); padding-bottom: 5px; } h3 { font-size: 1.1rem; color: #333; } hr { border: 0; border-top: 1px solid var(--border-color); margin: 30px 0; } pre { background-color: #1e1e24; color: #f8f9fa; padding: 18px; border-radius: 6px; overflow-x: auto; font-family: "Fira Code", Consolas, Monaco, monospace; font-size: 0.9rem; } code { font-family: monospace; background-color: var(--code-bg); padding: 2px 6px; border-radius: 4px; font-size: 0.95rem; } pre code { background-color: transparent; padding: 0; color: inherit; } table { width: 100%; border-collapse: collapse; margin: 20px 0; } th, td { padding: 12px; border: 1px solid var(--border-color); text-align: left; } th { background-color: var(--code-bg); font-weight: 600; } .tip-box { background-color: #e7f5ff; border-left: 4px solid var(--primary); padding: 20px; border-radius: 0 6px 6px 0; margin: 20px 0; } .tip-box strong { color: #004085; } ul { padding-left: 20px; } li { margin-bottom: 8px; }

📂 Project Architecture & Security Fundamentals
===============================================

When building web applications with pure PHP, maintaining a clean project structure is not just about keeping files neat—it directly dictates your application's **security profile** and **cross-platform reliability**.

🛠️ The Suggested Project Directory
-----------------------------------

Below is the blueprint for our standard, framework-free architecture. It utilizes a **Public/Private Split** and strict lowercase naming rules.

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

* * *

🛡️ 1. How the `public/` Folder Maintains Security
--------------------------------------------------

In an unorganized PHP arrangement, developers often scatter administrative backend scripts directly alongside public layout views within the root directory. This makes critical configuration engines highly vulnerable.

If your web server suffers a temporary processing bottleneck or a minor configuration glitch, it might fail to execute PHP files correctly. Instead, it could serve files like `config/db.php` as raw text files directly inside the user's browser—**instantly revealing your plaintext database passwords to the world.**

By splitting files using a dedicated `public/` boundary, you manually instruct your web server (Apache or Nginx) to lock its operations exclusively into that subdirectory as its **Document Root**.

### The Architectural Isolation Layer

*   **The Public Face (`public/`):** This is the only folder visible to web clients. Browsers can only download or interact with assets housed right inside this container (e.g., loading `style.css` or viewing `index.php`).
*   **The Invisible Vault (`config/`, `src/`, `templates/`):** These parent directories sit securely outside the server's visual scope. They possess no valid external URL. Attempting to force access via a browser path like `example.com/config/db.php` returns an immediate `404 Not Found` or `403 Forbidden` restriction.
*   **The Private Bridge:** Your frontend entry files inside `public/` dynamically request backend support behind the safety of the server's firewall via fast, internal filesystem paths:

    <?php
    require_once __DIR__ . '/../config/db.php';
    ?>

* * *

🔤 2. Why Lowercase File & Folder Names are Crucial
---------------------------------------------------

You should explicitly commit to **all-lowercase syntax** across your entire project workspace (e.g., referencing `dashboard.php` rather than `Dashboard.php`).

This uniform standard prevents runtime structural failures caused by **Operating System Case Sensitivity Constraints**:

Operating System Context

Filesystem Rule

Real-World Operational Consequence

**Windows & macOS**

Case-**Insensitive**

Requesting `/login.php` or `/Login.php` targets the exact same file without issue.

**Linux / Unix**

**Strictly Case-Sensitive**

Requesting `/login.php` while your file is saved as `Login.php` creates a critical **404 Not Found** link failure.

**💡 Core Lesson for Learners:** Because more than 90% of global web servers deploy exclusively on Linux infrastructure, sticking to strict lowercase styling across your workspace ensures your framework-free application functions flawlessly on your local computer and your production server alike.