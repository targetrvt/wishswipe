🛒 WishSwipe
<p align="center"> <img src="public/images/wishSwipe_logo.png" alt="WishSwipe Logo" width="300"> </p> <p align="center"> <strong>Swipe Your Way to Great Deals!</strong><br> An innovative marketplace platform combining the swiping experience of dating apps with online shopping. </p> <p align="center"> <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel"> <img src="https://img.shields.io/badge/Filament-3.x-FFAA00?style=for-the-badge" alt="Filament"> <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php" alt="PHP"> <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License"> </p>
📖 About

WishSwipe reimagines online shopping through a swipe-based interface. Swipe right on products you love and connect instantly with sellers.

✨ Key Features

🎯 Swipe Interface – Intuitive browsing of products

💬 Real-time Messaging – Chat with sellers instantly

📍 Location Discovery – Find nearby products

🔐 Secure Authentication – Email/password + optional 2FA

👥 Role-Based Access Control – Admin & user panels

📱 Responsive Design – Desktop & mobile ready

🌍 Multi-language Support – English & Latvian

🤖 AI Support Assistant – ChatGPT-powered

📊 Analytics Dashboard – Track listings & sales

🗂️ Category Management – Organized categories with icons

🛠️ Tech Stack
Backend

Laravel 11.x, Filament 3.x

SQLite/MySQL/PostgreSQL

Spatie Permission (roles & permissions)

Frontend

Tailwind CSS 4.x, Alpine.js, Livewire, Vite

Integrations

Google Maps API, OpenAI GPT, Filament Shield

📋 Prerequisites

PHP ≥ 8.2, Composer ≥ 2.x

Node.js ≥ 18.x & npm

SQLite (default) or MySQL/PostgreSQL

Git

Optional:

Google Maps API Key

OpenAI API Key

🚀 Installation (Linux)
# 1. Clone repository
git clone https://github.com/yourusername/wishswipe.git
cd wishswipe

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Setup environment
cp .env.example .env
php artisan key:generate

# 5. Configure database
# SQLite
touch database/database.sqlite
php artisan migrate --seed
# Or use fresh command
php artisan wishswipe:fresh

# 6. Storage link
php artisan storage:link

# 7. Build frontend assets
npm run dev   # Development
npm run build # Production

# 8. Start server
php artisan serve


Visit: http://localhost:8000

👤 Default Accounts
Role	Email	Password	Access
Admin	admin@admin.com
	87654321	Full admin panel access
User	user@user.com
	12345678	User dashboard & listings
⚙️ Configuration
Environment (.env)

Database: DB_CONNECTION=sqlite (default) or MySQL/PostgreSQL

Optional: GOOGLE_MAPS_API_KEY and OPENAI_API_KEY

Storage
php artisan storage:link
chmod -R 775 storage bootstrap/cache

Email (production)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@wishswipe.com"
MAIL_FROM_NAME="${APP_NAME}"

🧪 Testing
php artisan test             # Run all tests
php artisan test --filter=ExampleTest
php artisan test --coverage   # With coverage

📦 Deployment (Production)
# Cache & optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
npm run build

# Set permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache


Configure web server & queue workers as needed.

📚 Resources

Laravel Docs

Filament Docs

Tailwind CSS Docs

Alpine.js Docs

🤝 Contributing

Fork the repo

Create a feature branch

Commit changes

Push branch & open Pull Request

Follow PSR-12 standards, add tests, and update documentation.

📄 License

MIT License – see LICENSE

<p align="center">Made with ❤️ by the WishSwipe Team</p>