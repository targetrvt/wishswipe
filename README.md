🚀 Installation
Step 1: Clone the Repository
bashgit clone https://github.com/yourusername/wishswipe.git
cd wishswipe
Step 2: Install PHP Dependencies
bashcomposer install
Step 3: Install Node Dependencies
bashnpm install
Step 4: Environment Setup
bash# Copy the example environment file
cp .env.example .env

# Generate application key
php artisan key:generate
Step 5: Configure Database
The project uses SQLite by default. Create the database file:
bash# For Unix/Linux/Mac
touch database/database.sqlite

# For Windows (PowerShell)
New-Item database/database.sqlite
Alternative: Using MySQL
If you prefer MySQL, update your .env file:
envDB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wishswipe
DB_USERNAME=root
DB_PASSWORD=your_password
Step 6: Run Migrations & Seeders
bash# Run migrations
php artisan migrate

# Seed the database with sample data
php artisan db:seed
Or use the custom fresh command:
bashphp artisan wishswipe:fresh
This will drop all tables, run migrations, and seed data.
Step 7: Create Storage Link
bashphp artisan storage:link
Step 8: Build Frontend Assets
bash# Development
npm run dev

# Production
npm run build
Step 9: Start the Development Server
bashphp artisan serve
Visit: http://localhost:8000
