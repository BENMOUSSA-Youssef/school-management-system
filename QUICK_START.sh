#!/bin/bash

# 🚀 Quick Start Script for Laravel Project
# This script will help you run your project quickly

echo "🚀 Starting Laravel Project Setup..."
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
    php artisan key:generate
    echo "✅ .env file created!"
else
    echo "✅ .env file already exists"
fi

# Check if database exists
if [ ! -f database/database.sqlite ]; then
    echo "📦 Creating database..."
    touch database/database.sqlite
    echo "✅ Database created!"
else
    echo "✅ Database already exists"
fi

# Check if migrations have been run
echo ""
echo "🔄 Checking migrations..."
if php artisan migrate:status 2>&1 | grep -q "No migrations found"; then
    echo "📊 Running migrations..."
    php artisan migrate
    echo "✅ Migrations completed!"
else
    echo "✅ Migrations already run"
fi

echo ""
echo "🎉 Setup Complete!"
echo ""
echo "👉 Starting development server..."
echo "👉 Open http://localhost:8000 in your browser"
echo ""
echo "Press Ctrl+C to stop the server"
echo ""

# Start the server
php artisan serve

