#!/bin/bash
# CitySync AI - Docker Setup Script
# Purpose: Initialize Docker containers for Phase 1 development
# Usage: bash DOCKER_SETUP.sh

set -e

echo "🐳 CitySync AI - Docker Setup"
echo "================================"

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "❌ Docker is not installed. Please install Docker Desktop first."
    exit 1
fi

# Check if Docker Compose is installed
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose is not installed. Please install it first."
    exit 1
fi

echo "✅ Docker and Docker Compose detected"
echo ""

# Create .env file if it doesn't exist
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    cat > .env << 'EOF'
# DeepSeek API
DEEPSEEK_API_KEY=your_key_here
DEEPSEEK_API_URL=https://api.deepseek.com/v1

# Claude API
CLAUDE_API_KEY=your_key_here
CLAUDE_MODEL=claude-opus-4-1-20250805

# Ollama (Local)
OLLAMA_HOST=http://localhost:11434

# Database
DB_HOST=postgres
DB_PORT=5432
DB_USER=n8n
DB_PASSWORD=n8n_password
DB_NAME=n8n

# Redis
REDIS_HOST=redis
REDIS_PORT=6379

# n8n
N8N_HOST=localhost
N8N_PORT=5678
N8N_PROTOCOL=http

# External APIs
OPENWEATHER_API_KEY=your_key_here
EVENTBRITE_API_KEY=your_key_here
GNEWS_API_KEY=your_key_here

# AWS (optional)
AWS_ACCESS_KEY_ID=your_key_here
AWS_SECRET_ACCESS_KEY=your_key_here
AWS_REGION=us-east-1

# Bunny CDN (optional)
BUNNY_API_KEY=your_key_here
BUNNY_STORAGE_ZONE=citysyncai-storage

# Google Search Console
GSC_KEY=your_service_account_json_here

# Analytics
PLAUSIBLE_API_TOKEN=your_token_here
EOF
    echo "✅ .env file created. Please fill in your API keys."
    echo ""
fi

# Create output directory if it doesn't exist
mkdir -p output
mkdir -p templates
mkdir -p css
mkdir -p scripts

echo "📁 Directories created"
echo ""

# Start Docker Compose
echo "🚀 Starting Docker containers..."
docker-compose up -d

echo ""
echo "⏳ Waiting for services to be ready (30 seconds)..."
sleep 30

# Check if services are running
echo ""
echo "📊 Service Status:"
echo "================================"

if docker-compose ps | grep -q "n8n.*Up"; then
    echo "✅ n8n is running (http://localhost:5678)"
else
    echo "❌ n8n failed to start"
fi

if docker-compose ps | grep -q "postgres.*Up"; then
    echo "✅ PostgreSQL is running (localhost:5432)"
else
    echo "❌ PostgreSQL failed to start"
fi

if docker-compose ps | grep -q "redis.*Up"; then
    echo "✅ Redis is running (localhost:6379)"
else
    echo "❌ Redis failed to start"
fi

if docker-compose ps | grep -q "nginx.*Up"; then
    echo "✅ Nginx is running (http://localhost:80)"
else
    echo "❌ Nginx failed to start"
fi

echo ""
echo "================================"
echo "✅ Docker setup complete!"
echo ""
echo "Next steps:"
echo "1. Edit .env file with your API keys"
echo "2. Access n8n at: http://localhost:5678"
echo "3. Access PostgreSQL at: localhost:5432"
echo "4. View logs: docker-compose logs -f"
echo ""
echo "To stop containers: docker-compose down"
echo "To view specific service logs: docker-compose logs n8n"
echo ""
