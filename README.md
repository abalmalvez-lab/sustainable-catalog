# EcoShelf - Sustainable Product Catalog

A full PHP + MySQL MVC web application that promotes eco-friendly consumer habits through a curated, searchable, and filterable database of sustainable products with sustainability ratings.

---

## Features

- **User Registration & Login** - Secure account system with password hashing (bcrypt), CSRF protection, and session management
- **Product Catalog** - 25 seed products across 8 categories, each with sustainability leaf ratings (1-5)
- **Search & Filter** - Full-text search, category filter, sustainability rating filter, price range, certification filter, stock status, and multiple sort options
- **Product Detail Pages** - Full product info with carbon footprint, materials, certifications, and related products
- **User Reviews** - Authenticated users can rate and review products
- **Responsive Design** - Mobile-friendly layout using Font Awesome 6 icons and Google Fonts
- **Security** - CSRF tokens, prepared SQL statements, password hashing, XSS protection via output escaping

---

## Tech Stack

| Layer       | Technology                              |
|-------------|-----------------------------------------|
| Backend     | PHP 7.4+ (MVC architecture)             |
| Database    | MySQL 5.7+ / MariaDB                    |
| Frontend    | HTML5, CSS3 (custom), JavaScript        |
| Icons       | Font Awesome 6.5 (CDN)                  |
| Fonts       | Google Fonts (DM Serif Display, Nunito) |

---

## Directory Structure

```
sustainable-catalog/
├── index.php                  # Front controller / router
├── .htaccess                  # Apache security & caching rules
│
├── config/
│   ├── app.php                # App constants & settings
│   ├── database.php           # PDO database connection (singleton)
│   └── schema.sql             # Full database schema + seed data
│
├── controllers/
│   ├── AuthController.php     # Registration, login, logout
│   └── ProductController.php  # Catalog, detail, search, reviews
│
├── models/
│   ├── User.php               # User CRUD, authentication
│   └── Product.php            # Product queries, filters, reviews
│
├── views/
│   ├── layouts/
│   │   ├── header.php         # HTML head, navbar, flash messages
│   │   └── footer.php         # Footer, JS includes
│   ├── auth/
│   │   ├── login.php          # Login form
│   │   └── register.php       # Registration form
│   └── products/
│       ├── home.php           # Landing page with featured products
│       ├── catalog.php        # Full catalog with sidebar filters
│       └── detail.php         # Single product + reviews
│
├── helpers/
│   └── functions.php          # Utility functions (CSRF, flash, rendering)
│
└── public/
    ├── css/
    │   └── style.css          # Complete stylesheet
    └── js/
        └── app.js             # Nav toggle, password meter, star ratings
```

---

## Setup Instructions for Preparing a Hosting Server and Basic Database Schema

### NOTES: **<u>You can request for the 'schema.sql'</u>** if you need to use/deploy the application.

### Step 1: Preparing for Web Hosting Online Server
1. Go to Any Web Hosting that offer hosting service for PHP/MySQL
2. Sign up and create a new hosting account

### Step 2: Create the MySQL Database
1. In the control panel, go to **MySQL Databases**
2. Create a new database - note the:
   - **Database name** (e.g., `CMSC207ProductCatalogDB`)
   - **Username** (e.g., `DBUserId`)
   - **Password**
   - **Host** (e.g., `MyDB.ExampleDomainHosting.com`)

### Step 3: Import the Database Schema
1. Open **phpMyAdmin** from the control panel
2. Select your database
3. Go to the **Import** tab
4. Upload the file `config/schema.sql`
5. Click **Go** - this creates all tables and loads 25 sample products

### Step 4: Configure the Application
1. Edit `config/database.php` and update:
   ```php
   define('DB_HOST', 'MyDB.ExampleDomainHosting.com');              // MySqlHostConnection
   define('DB_NAME', 'CMSC207ProductCatalogDB');                    // Database Name
   define('DB_USER', 'DBUserId');                                   // DB Username
   define('DB_PASS', 'actual_password_here');                       // DB Password
   ```

2. Edit `config/app.php` if needed:
   ```php
   define('BASE_URL', '/');  // Usually '/' for root deployment
   ```

### Step 5: Upload Files
1. Open the **Online File Manager** or connect via **FTP**
   - FTP Host: `ftpupload.net`
   - Use the FTP credentials from the control panel
2. Upload ALL files to the `htdocs/` directory
3. Make sure `index.php` is at the root of `htdocs/`

### Step 6: Visit the published site for this project.
Open `https://aalmalvez.ct.ws` in your browser.

---

## Application Pages

| URL                                          | Description              |
|----------------------------------------------|--------------------------|
| `index.php`                                  | Home / landing page      |
| `index.php?page=register`                    | User registration        |
| `index.php?page=login`                       | User login               |
| `index.php?page=logout`                      | Logout                   |
| `index.php?page=catalog`                     | Full product catalog     |
| `index.php?page=catalog&search=bamboo`       | Search products          |
| `index.php?page=catalog&category=personal-care` | Filter by category    |
| `index.php?page=catalog&min_rating=4`        | Filter by rating         |
| `index.php?page=detail&slug=bamboo-cutlery-travel-set` | Product detail |

---

## Security Features

- **Password Hashing** - bcrypt with cost factor 12
- **CSRF Protection** - Unique token per session, validated on every POST
- **Prepared Statements** - All SQL queries use PDO prepared statements
- **XSS Prevention** - All output escaped with `htmlspecialchars()`
- **Session Security** - HttpOnly cookies, strict mode, ID regeneration on login
- **Directory Protection** - `.htaccess` blocks direct access to PHP source files
- **Input Validation** - Server-side validation on all user inputs

---

## Customization

### Adding New Products
Insert rows into the `products` table via phpMyAdmin, or build an admin panel.

### Changing Colors
Edit the CSS variables at the top of `public/css/style.css`:
```css
:root {
    --primary: #15803d;     /* Main green */
    --accent: #d97706;      /* Warm accent */
    /* ... */
}
```

### Adding Product Images
1. Update the `image_url` column in the `products` table
2. Modify the product card template in the views to display `<img>` tags

---

## License

Final Project created by Anthony Almalvez for CMSC207
