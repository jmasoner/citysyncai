# Phase 1: MVP - Weeks 1-2 Quick Start

**Goal:** Generate 500-1,000 high-quality pages, validate ranking + conversion.

**Timeline:** 2 weeks  
**Target Completion:** February 1, 2026

---

## Week 1: Setup & Template Building

### Monday-Tuesday: Environment Setup

**Tasks:**
- [ ] Clone repo and navigate to project
- [ ] Install Docker Desktop (if not already installed)
- [ ] Run `DOCKER_SETUP.sh` to start containers
- [ ] Access n8n UI (http://localhost:5678)
- [ ] Access PostgreSQL (localhost:5432)
- [ ] Create `.env` file with API keys

**Commands:**
```bash
cd c:\Users\john\OneDrive\MyProjects\citysyncai
docker-compose up -d
docker-compose logs -f  # Verify all services started
```

**Checkpoint:** All 4 services running (n8n, PostgreSQL, Redis, Nginx)

---

### Wednesday-Thursday: Database & City Data

**Tasks:**
- [ ] Connect to PostgreSQL
- [ ] Run `CITY_DATABASE.sql` to create schema
- [ ] Import US cities data (50 states × 150-300 cities)
- [ ] Verify 10K+ cities in database

**Commands:**
```bash
# Connect to PostgreSQL
psql -h localhost -U n8n -d n8n

# Run schema
\i CITY_DATABASE.sql

# Verify
SELECT COUNT(*) FROM cities;  -- Should show 10,000+
```

**Sample Data to Import:**
- City name, state code, population, coordinates, nearby cities, ZIP codes

**Checkpoint:** Cities table populated, ready for generation

---

### Friday: HTML Template Building

**Tasks:**
- [ ] Review `PAGE_TEMPLATE_SPEC.md` 
- [ ] Build HTML template file (`templates/base.html`)
- [ ] Build CSS stylesheet (`css/main.css`)
- [ ] Test template locally with sample data
- [ ] Verify all schema markup included

**Template Structure:**
- Header/Navigation
- Hero section (headline, CTA)
- Local context (weather, events, chamber)
- Service overview + case study
- Provider comparison table
- Lead capture form
- FAQ with schema
- Related cities links
- Footer

**Checkpoint:** HTML template renders correctly, forms functional, schema valid

---

## Week 2: n8n Workflow & Page Generation

### Monday-Tuesday: n8n Workflow Setup

**Tasks:**
- [ ] Access n8n UI
- [ ] Create new workflow
- [ ] Set up nodes (see `N8N_WORKFLOW_SPEC.md`):
  - Trigger (manual start)
  - Query cities database
  - Fetch weather data
  - Call DeepSeek API
  - Render HTML template
  - Quality checks
  - Upload to staging S3/CDN

**Key Nodes:**
1. **Trigger** - Manual (for testing)
2. **Database** - Query 10 pending cities
3. **HTTP** - Weather API call
4. **HTTP** - DeepSeek API call
5. **Function** - Render HTML template
6. **Function** - Validate HTML
7. **HTTP** - Upload to S3/CDN
8. **Database** - Update page status

**Checkpoint:** Workflow executes end-to-end without errors

---

### Wednesday: Generate Test Batch (50 Pages)

**Tasks:**
- [ ] Select 5 high-intent cities (Austin, Seattle, NYC, LA, Denver)
- [ ] Generate 50 pages (5 services × 5 cities)
- [ ] Monitor workflow execution
- [ ] Check generated HTML in output folder
- [ ] Verify form endpoints work

**Expected Results:**
- 50 HTML files generated
- ~2-5 sec per page
- < 2% error rate
- All forms submitting correctly

**Checkpoint:** 50 valid pages generated with < 2% errors

---

### Thursday: Scale to 500 Pages

**Tasks:**
- [ ] Expand to 20 cities
- [ ] Generate 500 pages (5 services × 20 cities)
- [ ] Monitor API costs (should be ~$0.05)
- [ ] Check for duplicate content
- [ ] Verify all files uploaded to staging

**Expected Results:**
- 500 HTML files
- Total time: ~2 hours
- Total cost: ~$0.05
- 0% duplicate content

**Checkpoint:** 500 pages live on staging CDN

---

### Friday: QA & Validation

**Tasks:**
- [ ] Spot check 20 random pages
- [ ] Verify each page has:
  - [ ] Unique title + meta description
  - [ ] H1 tag present
  - [ ] Local context (city-specific data)
  - [ ] Working lead capture form
  - [ ] Schema markup valid
  - [ ] Links to nearby cities
  - [ ] Load time < 2 sec
- [ ] Test form submission with real data
- [ ] Check SEO metrics (use browser tools)
- [ ] Document any issues

**Validation Checklist:**
```
Page: Austin - VoIP
- Title: ✓ "VoIP Service Provider in Austin, Texas"
- Meta: ✓ "Find reliable VoIP phone systems in Austin, TX"
- H1: ✓ "Best VoIP Service Providers in Austin, Texas"
- Local: ✓ Weather, events, chamber info present
- Form: ✓ Submitting successfully
- Schema: ✓ Valid LocalBusiness + Service
- Speed: ✓ 1.2 sec load time
```

**Checkpoint:** 500 pages validated, ready for Phase 2

---

## Phase 1 Success Criteria (Go/No-Go Decision)

### MUST HAVE (Go to Phase 2):
- ✅ 500 pages generated
- ✅ 100% valid HTML
- ✅ 0% duplicate content
- ✅ All forms functional
- ✅ Schema markup valid on all pages
- ✅ Page generation < 5 sec each

### NICE TO HAVE:
- ✅ CDN cache working
- ✅ Sitemap generated
- ✅ Analytics tracking working
- ✅ Load times < 2 sec

### FAILURE CRITERIA (Restart Phase 1):
- ❌ > 5% error rate
- ❌ Duplicate content found
- ❌ Forms not submitting
- ❌ Invalid HTML/schema
- ❌ API costs > $1 (indicates inefficiency)

---

## Daily Task Checklist (Use This!)

### Week 1

**Monday:**
- [ ] Docker containers started
- [ ] n8n accessible at localhost:5678
- [ ] PostgreSQL accessible at localhost:5432
- [ ] `.env` file created with API keys

**Tuesday:**
- [ ] Cities table created (10K+ rows)
- [ ] Database verified
- [ ] Ready for template building

**Wednesday:**
- [ ] HTML template created
- [ ] CSS stylesheet complete
- [ ] Template renders with sample data

**Thursday:**
- [ ] Form validation working
- [ ] Schema markup verified
- [ ] Local testing complete

**Friday:**
- [ ] Template finalized
- [ ] Ready for n8n integration

### Week 2

**Monday:**
- [ ] n8n workflow created
- [ ] All nodes configured
- [ ] Trigger tested manually

**Tuesday:**
- [ ] Workflow executes end-to-end
- [ ] 10 test pages generated successfully
- [ ] No errors in logs

**Wednesday:**
- [ ] 50 pages generated
- [ ] API cost tracked (~$0.05)
- [ ] Files uploaded to staging

**Thursday:**
- [ ] 500 pages generated
- [ ] Total cost verified (~$0.05)
- [ ] All files accessible

**Friday:**
- [ ] 500 pages validated
- [ ] Phase 1 complete
- [ ] Ready for Phase 2 go/no-go decision

---

## Files You'll Need

| File | Purpose | Status |
|------|---------|--------|
| `TECH_STACK.md` | Infrastructure reference | ✅ Ready |
| `PAGE_TEMPLATE_SPEC.md` | HTML structure | ✅ Ready |
| `N8N_WORKFLOW_SPEC.md` | Workflow blueprint | ✅ Ready |
| `DOCKER_SETUP.sh` | Docker commands | ✅ Ready |
| `CITY_DATABASE.sql` | Database schema | ✅ Ready |
| `DEEPSEEK_PROMPTS.md` | API prompts | ✅ Ready |
| `.env` | API keys (create yourself) | Create now |
| `templates/base.html` | HTML template | Create Week 1 |
| `css/main.css` | Stylesheet | Create Week 1 |

---

## Key Contacts & Resources

### APIs Needed (Sign up now):
- DeepSeek: https://platform.deepseek.com
- Claude: https://console.anthropic.com
- OpenWeather: https://openweathermap.org/api
- Google Search Console: https://search.google.com/search-console

### Tools:
- Docker: https://www.docker.com/products/docker-desktop
- n8n: http://localhost:5678 (runs locally)
- PostgreSQL: Included in docker-compose
- Redis: Included in docker-compose

### Support:
- n8n Docs: https://docs.n8n.io
- DeepSeek API: https://platform.deepseek.com/api-docs
- Claude API: https://docs.anthropic.com

---

## Quick Reference Commands

```bash
# Start all services
docker-compose up -d

# Check status
docker-compose ps

# View logs
docker-compose logs -f n8n
docker-compose logs -f postgres

# Connect to DB
psql -h localhost -U n8n -d n8n

# Stop all services
docker-compose down

# Delete & restart (clean slate)
docker-compose down -v
docker-compose up -d
```

---

## Troubleshooting

### PostgreSQL won't start
```bash
docker-compose down
docker-compose up postgres -d
docker-compose logs postgres
```

### n8n UI not accessible
```bash
# Check if port 5678 is in use
netstat -ano | findstr :5678

# If in use, kill process or use different port in docker-compose.yml
```

### DeepSeek API errors
- Verify API key in `.env`
- Check rate limits (100 req/min)
- Use fallback prompt if timeout

### Form not submitting
- Check browser console for errors
- Verify form endpoint in HTML
- Test with curl: `curl -X POST http://localhost/api/leads -d '{...}'`

---

## Phase 1 → Phase 2 Transition

**Once Phase 1 is complete:**
1. Review success criteria
2. Document any issues/learnings
3. Update CHANGELOG.md
4. Plan Phase 2 (scale to 50K pages)
5. Schedule Phase 2 kickoff meeting

**Expected Phase 2 Start Date:** February 3, 2026

---

**Status:** Ready to start immediately  
**Last Updated:** January 17, 2026  
**Owner:** You (with GitHub Copilot support)
