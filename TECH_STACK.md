# CitySync AI - Technology Stack & Setup Guide

**Purpose:** Define the complete tech stack, deployment architecture, and configuration for the AI-powered page generation system.

---

## Overview

**Architecture:** Containerized (Docker) with orchestrated workflows (n8n), AI APIs (DeepSeek/Claude), static HTML output, and CDN delivery.

**Key Principles:**
- Cost-efficient (DeepSeek primary, Claude secondary, Ollama fallback)
- Resilient (multi-provider failover)
- Scalable (parallel processing, caching)
- Simple (stateless, easy to deploy)

---

## Core Technologies

### 1. Content Generation (AI APIs)

#### Primary: DeepSeek API
```
Cost: ~$0.0001 per page
Speed: Fast (2-5 sec per page)
Quality: Good for bulk generation
Use Case: 50K pages × 7 services = main workhorse

Setup:
- Account: https://platform.deepseek.com
- API Endpoint: https://api.deepseek.com/v1/chat/completions
- Model: deepseek-chat
- Rate Limit: 100 req/min (adjust batch size)
```

#### Secondary: Claude Opus 4.5 API
```
Cost: ~$0.001 per page (expensive, selective use)
Speed: Slower but highest quality
Quality: Best for headlines, CTAs, conversions
Use Case: Top 20% of pages (5K pages), quality refinement

Setup:
- Account: https://console.anthropic.com
- API Endpoint: https://api.anthropic.com/v1/messages
- Model: claude-opus-4-1-20250805
- Rate Limit: 50 req/min
```

#### Tertiary: Local Ollama (Fallback)
```
Cost: Free
Speed: Slow (30+ sec per page)
Quality: Acceptable for fallback
Use Case: API downtime, cost control, offline mode

Setup:
- Download: https://ollama.ai
- Models: llama2:7b or mistral:7b
- Running: ollama serve (starts on localhost:11434)
- Integration: OpenAI-compatible endpoint
```

---

### 2. Orchestration & Workflow (n8n)

#### Purpose
Automate the entire generation pipeline: API calls → content generation → HTML templating → deployment.

#### Installation (Docker)
```yaml
version: '3.8'
services:
  n8n:
    image: n8n:latest
    ports:
      - "5678:5678"
    environment:
      - N8N_HOST=localhost
      - N8N_PORT=5678
      - N8N_PROTOCOL=http
      - DB_TYPE=postgres
      - DB_POSTGRE_HOST=postgres
      - DB_POSTGRE_PORT=5432
      - DB_POSTGRE_DATABASE=n8n
      - DB_POSTGRE_USER=n8n
      - DB_POSTGRE_PASSWORD=n8n_password
    volumes:
      - n8n_storage:/home/node/.n8n
    depends_on:
      - postgres
      - redis

  postgres:
    image: postgres:15-alpine
    ports:
      - "5432:5432"
    environment:
      - POSTGRES_USER=n8n
      - POSTGRES_PASSWORD=n8n_password
      - POSTGRES_DB=n8n
    volumes:
      - postgres_storage:/var/lib/postgresql/data

  redis:
    image: redis:7-alpine
    ports:
      - "6379:6379"
    volumes:
      - redis_storage:/data

volumes:
  n8n_storage:
  postgres_storage:
  redis_storage:
```

#### Access
- UI: http://localhost:5678
- API: http://localhost:5678/api

---

### 3. Database (PostgreSQL)

#### Schema Overview
```sql
-- Cities table
CREATE TABLE cities (
  id SERIAL PRIMARY KEY,
  city_name VARCHAR(100),
  state_code CHAR(2),
  population INT,
  coordinates POINT,
  zip_codes TEXT[],
  nearby_cities TEXT[],
  status VARCHAR(20) DEFAULT 'pending', -- pending, generating, generated, published
  created_at TIMESTAMP DEFAULT NOW(),
  updated_at TIMESTAMP DEFAULT NOW()
);

-- Generated pages table
CREATE TABLE pages (
  id SERIAL PRIMARY KEY,
  city_id INT REFERENCES cities(id),
  service_category VARCHAR(50), -- VoIP, Fiber, Internet, etc.
  title VARCHAR(255),
  slug VARCHAR(255) UNIQUE,
  content_hash VARCHAR(64), -- MD5 for duplicate detection
  html_content TEXT,
  status VARCHAR(20) DEFAULT 'pending', -- pending, generated, deployed, indexed
  seo_score INT,
  generated_at TIMESTAMP,
  deployed_at TIMESTAMP,
  indexed_at TIMESTAMP,
  created_at TIMESTAMP DEFAULT NOW()
);

-- Lead tracking table
CREATE TABLE leads (
  id SERIAL PRIMARY KEY,
  page_id INT REFERENCES pages(id),
  name VARCHAR(100),
  company VARCHAR(100),
  phone VARCHAR(20),
  email VARCHAR(100),
  service_type VARCHAR(50),
  city VARCHAR(100),
  state VARCHAR(2),
  quality_score INT DEFAULT 0, -- 1-10, calculated
  status VARCHAR(20) DEFAULT 'new', -- new, contacted, qualified, converted
  submitted_at TIMESTAMP DEFAULT NOW(),
  updated_at TIMESTAMP DEFAULT NOW()
);

-- API usage tracking
CREATE TABLE api_usage (
  id SERIAL PRIMARY KEY,
  provider VARCHAR(50), -- deepseek, claude, ollama
  endpoint VARCHAR(255),
  tokens_used INT,
  cost_cents DECIMAL(10, 2),
  status VARCHAR(20), -- success, error
  error_message TEXT,
  created_at TIMESTAMP DEFAULT NOW()
);
```

---

### 4. Caching (Redis)

#### Purpose
Reduce API calls by caching:
- Weather data (cache 1 week)
- Chamber info (cache 1 month)
- Generated sections (cache permanently)
- OpenAI responses (cache permanently)

#### Configuration
```yaml
redis:
  host: localhost
  port: 6379
  db: 0
  ttl:
    weather: 604800 # 1 week
    chamber: 2592000 # 1 month
    generated_content: 0 # Never expires
```

#### Usage Pattern (Node.js example)
```javascript
// Check cache first
const cachedContent = await redis.get(`content:voip:austin:tx`);
if (cachedContent) {
  return JSON.parse(cachedContent);
}

// Call API if not cached
const content = await callDeepSeekAPI(...);

// Store in cache
await redis.set(`content:voip:austin:tx`, JSON.stringify(content), 'EX', 604800);

return content;
```

---

### 5. Web Server & Static Hosting

#### Development (Local)
```bash
# Simple HTTP server for testing
npx http-server ./output --port 8080

# Or Node.js Express
node server.js (serves static files from ./public)
```

#### Production (Docker)
```dockerfile
# Dockerfile for Nginx (static file server)
FROM nginx:alpine
COPY nginx.conf /etc/nginx/nginx.conf
COPY ./output /usr/share/nginx/html
EXPOSE 80
```

#### Docker Compose
```yaml
web:
  image: nginx:alpine
  ports:
    - "80:80"
    - "443:443"
  volumes:
    - ./output:/usr/share/nginx/html
    - ./nginx.conf:/etc/nginx/nginx.conf
  depends_on:
    - api
```

---

### 6. CDN & Deployment

#### Option A: Bunny CDN (Recommended)
```
Cost: $0.01-0.02/GB
Setup:
- Create storage zone: citysyncai-storage
- Pull zone: citysyncai-cdn.b-cdn.net
- Sync files via FTP or API

Configuration:
- Geographic replication: Yes
- Cache: 1 year (static HTML)
- Compression: Gzip
```

#### Option B: Cloudflare
```
Cost: Free tier available, $20+/mo paid
Setup:
- Add domain to Cloudflare
- Set nameservers
- Enable Caching Rules

Configuration:
- Page Rule: Cache everything (for /pages/*)
- TTL: 1 year
```

#### Option C: AWS S3 + CloudFront
```
Cost: $0.023/GB data transfer
Setup:
- Create S3 bucket: citysyncai-pages
- Create CloudFront distribution
- Point domain via Route53

Configuration:
- Origin: S3 bucket
- Cache: 1 year
- Gzip compression: Yes
```

---

### 7. CI/CD & Deployment

#### GitHub Actions Pipeline
```yaml
# .github/workflows/deploy.yml
name: Deploy Pages

on:
  schedule:
    - cron: '0 22 * * *'  # 10 PM daily
  workflow_dispatch:

jobs:
  generate:
    runs-on: ubuntu-latest
    
    steps:
      - uses: actions/checkout@v3
      
      - name: Start n8n workflow
        run: |
          curl -X POST http://n8n-instance/webhook/generate \
            -H "Content-Type: application/json" \
            -d '{"batch_size": 5000}'
      
      - name: Wait for completion
        run: sleep 600  # 10 minutes
      
      - name: Sync to CDN
        run: |
          aws s3 sync ./output s3://citysyncai-pages \
            --cache-control "max-age=31536000"
      
      - name: Invalidate CDN cache
        run: |
          aws cloudfront create-invalidation \
            --distribution-id E123DIST \
            --paths "/*"
      
      - name: Submit to GSC
        run: node scripts/submit-sitemap.js
```

---

## Environment Variables

Create `.env` file:

```env
# DeepSeek API
DEEPSEEK_API_KEY=sk-xxx
DEEPSEEK_API_URL=https://api.deepseek.com/v1

# Claude API
CLAUDE_API_KEY=sk-ant-xxx
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

# External APIs
OPENWEATHER_API_KEY=xxx
EVENTBRITE_API_KEY=xxx
GNEWS_API_KEY=xxx

# AWS (if using S3/CloudFront)
AWS_ACCESS_KEY_ID=xxx
AWS_SECRET_ACCESS_KEY=xxx
AWS_REGION=us-east-1

# Bunny CDN (if using)
BUNNY_API_KEY=xxx
BUNNY_STORAGE_ZONE=citysyncai-storage

# Google Search Console
GSC_KEY=xxx (service account JSON)

# Analytics
PLAUSIBLE_API_TOKEN=xxx
```

---

## Development Environment Setup

### Prerequisites
```bash
# Install Docker
# Install Docker Compose
# Install Node.js 18+
# Install Git

# Clone repo
git clone <repo> citysyncai
cd citysyncai
```

### Quick Start
```bash
# 1. Create .env file
cp .env.example .env
# Edit .env with your API keys

# 2. Start Docker containers
docker-compose up -d

# 3. Wait for services to be ready
docker-compose logs -f

# 4. Access n8n
open http://localhost:5678

# 5. Access database
psql -h localhost -U n8n -d n8n
```

---

## API Configuration & Testing

### DeepSeek Test Request
```bash
curl -X POST https://api.deepseek.com/v1/chat/completions \
  -H "Authorization: Bearer $DEEPSEEK_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "deepseek-chat",
    "messages": [
      {
        "role": "user",
        "content": "Generate a VoIP landing page for Austin, Texas"
      }
    ],
    "temperature": 0.7
  }'
```

### Claude Test Request
```bash
curl -X POST https://api.anthropic.com/v1/messages \
  -H "x-api-key: $CLAUDE_API_KEY" \
  -H "anthropic-version: 2023-06-01" \
  -H "content-type: application/json" \
  -d '{
    "model": "claude-opus-4-1-20250805",
    "max_tokens": 1024,
    "messages": [
      {
        "role": "user",
        "content": "Refine this headline for conversion: Best VoIP Services in Austin"
      }
    ]
  }'
```

---

## Monitoring & Observability

### Logs
```bash
# View Docker logs
docker-compose logs -f n8n
docker-compose logs -f postgres
docker-compose logs -f redis

# Query database logs
SELECT * FROM api_usage WHERE created_at > NOW() - INTERVAL 1 DAY;
```

### Metrics to Track
- API cost per page
- Generation time per page
- Error rate
- Cache hit rate
- CDN bandwidth usage
- Lead form conversion rate

---

## Cost Breakdown (Monthly)

| Component | Cost | Notes |
|-----------|------|-------|
| **VPS (Infrastructure)** | $15-30 | DigitalOcean, Linode, Vultr |
| **CDN (Bunny)** | $10-50 | Based on GB transferred |
| **DeepSeek API** | $1-10 | 50K pages × $0.0001 (one-time per batch) |
| **Claude API** | $1-5 | 5K refinements × $0.001 (optional) |
| **External APIs** | $5-20 | Weather, Events, News, etc. |
| **Domain + DNS** | $1-2 | Domain registration |
| **Analytics** | $9-20 | Plausible or Fathom |
| **TOTAL** | **$42-137/mo** | Very cost-effective |

---

## Security Considerations

### Secrets Management
```bash
# Never commit .env file
echo ".env" >> .gitignore

# Use GitHub Secrets for CI/CD
# Add each API key as a repository secret
```

### Rate Limiting
```javascript
// Implement exponential backoff
const delay = (ms) => new Promise(resolve => setTimeout(resolve, ms));
let retries = 0;
const maxRetries = 5;

while (retries < maxRetries) {
  try {
    return await callAPI();
  } catch (error) {
    if (error.status === 429) {
      retries++;
      await delay(Math.pow(2, retries) * 1000);
    } else {
      throw error;
    }
  }
}
```

### API Key Rotation
- Rotate keys monthly
- Use separate keys for dev/prod
- Monitor for unusual activity

---

## Scaling Considerations

### Horizontal Scaling
- Run multiple n8n workers
- Distribute API calls across regions
- Use queue system (Bull.js) for job management

### Vertical Scaling
- Increase VPS resources (CPU, RAM)
- Optimize database queries with indexes
- Increase cache capacity (Redis)

---

## Related Documentation
- `PAGE_TEMPLATE_SPEC.md` - HTML structure for generated pages
- `N8N_WORKFLOW_SPEC.md` - Detailed workflow automation
- `PROJECT_SCOPE.md` - Overall roadmap and goals

---

**Last Updated:** [Current Date]  
**Status:** Ready for Implementation
