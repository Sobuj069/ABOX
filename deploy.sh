#!/bin/bash
# ABox Live Server Deployment Script for voicechanger.smcloudit.top

echo "🚀 1. Pulling latest updates from GitHub..."
git pull origin main

echo "📦 2. Ensuring composer dependencies are installed..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "🗄️ 3. Running database migrations..."
php artisan migrate --force

echo "🔗 4. Linking public storage..."
php artisan storage:link

echo "⚡ 5. Clearing and caching configuration & views..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ ABox successfully updated on voicechanger.smcloudit.top!"
