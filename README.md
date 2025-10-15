WishSwipe 🛒
<p align="center">
  <img src="public/images/wishSwipe_logo.png" alt="WishSwipe Logo" width="300">
</p>
<p align="center">
  <strong>Swipe Your Way to Great Deals!</strong><br>
  A revolutionary marketplace platform that combines the intuitive swiping experience of dating apps with online shopping.
</p>
<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/Filament-3.x-FFAA00?style=for-the-badge" alt="Filament">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License">
</p>

📖 About
WishSwipe is an innovative marketplace application that reimagines online shopping through an engaging, swipe-based interface. Browse products by swiping right on items you love and left on ones you don't. When you match with a product, instantly connect with sellers through our built-in messaging system.
✨ Key Features

🎯 Intuitive Swiping Interface - Browse products with familiar swipe gestures
💬 Real-time Messaging - Instant chat with sellers when you match
📍 Location-Based Discovery - Find products near you with Google Maps integration
🔐 Secure Authentication - Email/password login with optional 2FA
👥 Role-Based Access Control - Admin and user panels with granular permissions
📱 Responsive Design - Works seamlessly on desktop and mobile devices
🌍 Multi-language Support - Available in English and Latvian
🤖 AI Support Assistant - ChatGPT-powered customer support
📊 Analytics Dashboard - Track your listings, views, and sales
🗂️ Category Management - Organized product categories with icons


🛠️ Tech Stack
Backend

Laravel 11.x - Modern PHP framework
Filament 3.x - Elegant admin panel
SQLite/MySQL/PostgreSQL - Flexible database options
Spatie Permission - Role and permission management

Frontend

Tailwind CSS 4.x - Utility-first CSS framework
Alpine.js - Minimal JavaScript framework
Livewire - Dynamic interfaces without JavaScript complexity
Vite - Lightning-fast build tool

Integrations

Google Maps API - Location services and geocoding
OpenAI GPT - AI-powered support chatbot
Filament Shield - Authorization management


📋 Prerequisites
Before you begin, ensure you have the following installed:

PHP >= 8.2
Composer >= 2.x
Node.js >= 18.x and npm
SQLite (default) or MySQL/PostgreSQL
Git

Optional Requirements

Google Maps API Key (for location features)
OpenAI API Key (for AI support assistant)


🚀 Installation
1. Clone the Repository
bashgit clone https://github.com/yourusername/wishswipe.git
cd wishswipe
2. Install PHP Dependencies
bashcomposer install
3. Install Node Dependencies
bashnpm install
4. Environment Configuration
bash# Copy the example environment file
cp .env.example .env

# Generate application key
php artisan key:generate
5. Configure Environment Variables
Edit .env file with your settings:
envAPP_NAME=WishSwipe
APP_ENV=local
APP_KEY=base64:your-generated-key
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database (SQLite default)
DB_CONNECTION=sqlite

# For MySQL/PostgreSQL, uncomment and configure:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=wishswipe
# DB_USERNAME=root
# DB_PASSWORD=

# Optional: Google Maps API
GOOGLE_MAPS_API_KEY=your_google_maps_api_key

# Optional: OpenAI for AI Support
OPENAI_API_KEY=your_openai_api_key
6. Database Setup
bash# Create SQLite database file (if using SQLite)
touch database/database.sqlite

# Run migrations and seeders
php artisan migrate --seed

# OR use the custom command to fresh install
php artisan wishswipe:fresh
7. Storage Link
bashphp artisan storage:link
8. Build Frontend Assets
bash# Development
npm run dev

# Production
npm run build
9. Start the Application
bash# Start Laravel development server
php artisan serve

# In a separate terminal, start the queue worker
php artisan queue:work

# In another terminal, run Vite dev server (if not using build)
npm run dev
Visit: http://localhost:8000

👤 Default Accounts
After seeding, you can log in with:
Admin Account

Email: admin@admin.com
Password: 87654321
Role: Super Admin
Access: Full system access including admin panel

Regular User Account

Email: user@user.com
Password: 12345678
Role: Panel User
Access: User dashboard, create listings, swipe products


📱 Application Structure
User Panel (/app)

Dashboard - View statistics and quick actions
Discover - Swipe through products
My Listings - Manage your products
Messages - Chat with matched buyers/sellers
Profile - Update personal information and settings

Admin Panel (/admin)

Users Management - Manage user accounts and roles
Categories - Create and organize product categories
Products - Oversee all marketplace listings
Conversations - Monitor messaging activity
Permissions - Configure role-based access control


⚙️ Configuration
Google Maps Setup

Get API key from Google Cloud Console
Enable these APIs:

Maps JavaScript API
Places API
Geocoding API


Add key to .env:

envGOOGLE_MAPS_API_KEY=your_api_key_here
OpenAI ChatGPT Setup

Get API key from OpenAI Platform
Add to .env:

envOPENAI_API_KEY=your_openai_key_here
Email Configuration
For production, configure mail settings in .env:
envMAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@wishswipe.com"
MAIL_FROM_NAME="${APP_NAME}"

🎨 Customization
Changing App Colors
Edit app/Providers/Filament/AppPanelProvider.php:
php->colors([
    'primary' => Color::Blue,    // Change primary color
    'success' => Color::Green,
    'warning' => Color::Orange,
    // ...
])
Adding New Product Categories

Log in as admin
Navigate to Categories in admin panel
Click Create and fill in:

Name
Slug (auto-generated)
Description
Icon (emoji or Font Awesome class)



Customizing Landing Page
Edit resources/views/landing.blade.php to modify:

Hero section
Features
Categories showcase
Call-to-action sections


🧪 Testing
bash# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test
php artisan test --filter=ExampleTest

📦 Production Deployment
1. Optimize Application
bash# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Build assets for production
npm run build
2. Environment Settings
Update .env for production:
envAPP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Use strong database
DB_CONNECTION=mysql

# Configure proper mail service
MAIL_MAILER=smtp

# Use queue driver
QUEUE_CONNECTION=redis
3. Set Permissions
bashchmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
4. Configure Web Server
Nginx Example:
nginxserver {
    listen 80;
    server_name yourdomain.com;
    root /path/to/wishswipe/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
5. Setup Supervisor for Queue
ini[program:wishswipe-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/wishswipe/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/path/to/wishswipe/storage/logs/worker.log

🛟 Troubleshooting
Common Issues
1. Permission Denied Errors
bashsudo chmod -R 775 storage bootstrap/cache
sudo chown -R $USER:www-data storage bootstrap/cache
2. SQLite Database Locked
bash# Increase timeout in database config
'timeout' => 30,
3. Google Maps Not Loading

Verify API key is correct
Check API is enabled in Google Cloud Console
Ensure billing is enabled for Google Cloud project

4. Images Not Displaying
bash# Recreate storage link
php artisan storage:link
5. Queue Jobs Not Processing
bash# Restart queue worker
php artisan queue:restart

📚 Documentation

Laravel Documentation
Filament Documentation
Tailwind CSS Documentation
Alpine.js Documentation


🤝 Contributing
Contributions are welcome! Please follow these steps:

Fork the repository
Create a feature branch (git checkout -b feature/AmazingFeature)
Commit your changes (git commit -m 'Add some AmazingFeature')
Push to the branch (git push origin feature/AmazingFeature)
Open a Pull Request

Coding Standards

Follow PSR-12 coding standards
Write descriptive commit messages
Add tests for new features
Update documentation as needed


📄 License
This project is open-sourced software licensed under the MIT license.

🙏 Acknowledgments

Laravel - The PHP framework
Filament - Admin panel framework
Tailwind CSS - Utility-first CSS
Google Maps - Location services
Font Awesome - Icon library
Unsplash - Stock photos


📧 Support
If you encounter any issues or have questions:

📫 Email: WishSwipe.support@gmail.com
🐛 Report a Bug
💡 Request a Feature


🌟 Star History
If you find this project useful, please consider giving it a ⭐!

<p align="center">Made with ❤️ by the WishSwipe Team</p>