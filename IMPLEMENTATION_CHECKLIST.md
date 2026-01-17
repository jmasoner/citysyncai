# Phase 1 Implementation Checklist

**Owner:** You + GitHub Copilot  
**Timeline:** Weeks 1-2 (Jan 24 - Feb 7, 2026)  
**Goal:** Generate 500-1,000 validated pages, ready to scale

---

## Pre-Phase 1: Setup & Planning (Jan 17-23)

### API Account Setup
- [ ] **DeepSeek** - Create account, generate API key
  - URL: https://platform.deepseek.com
  - Test endpoint: Make 1 test call ($0.0001)
  - Documentation: Save API docs locally

- [ ] **Claude API** - Create account, generate API key
  - URL: https://console.anthropic.com
  - Request Opus model access if needed
  - Set up billing ($0 trial or $10 credit)

- [ ] **OpenWeather** - Create account, generate API key
  - URL: https://openweathermap.org/api
  - Free tier: 1,000 calls/day (enough for testing)
  - Test API call for Austin, TX

- [ ] **Google Search Console** - Set up project
  - Add domain: combrokers.com
  - Download service account JSON
  - Store safely in `.env`

- [ ] **GitHub Secrets** - Add all API keys to repo
  - Settings → Secrets and variables → Actions
  - Add: DEEPSEEK_API_KEY, CLAUDE_API_KEY, OPENWEATHER_API_KEY, AWS credentials

### Local Development Setup
- [ ] **Clone repository** to local machine
- [ ] **Install Docker Desktop** (if not already installed)
- [ ] **Edit .env file** with all API keys
- [ ] **Run DOCKER_SETUP.sh** - Start all containers
- [ ] **Verify services**:
  - [ ] n8n at http://localhost:5678 (can access)
  - [ ] PostgreSQL at localhost:5432 (can connect)
  - [ ] Redis at localhost:6379 (can ping)
  - [ ] Nginx at http://localhost:80 (can access)

### Database Setup
- [ ] **Connect to PostgreSQL**
- [ ] **Run CITY_DATABASE.sql** - Create all tables
- [ ] **Verify schema**:
  - [ ] `cities` table exists (10+ rows)
  - [ ] `services` table exists (7 rows)
  - [ ] `pages` table empty (ready for generation)
  - [ ] `leads` table empty (ready for form submissions)

### Documentation Review
- [ ] **Read** PROJECT_SCOPE.md (strategic overview)
- [ ] **Read** TECH_STACK.md (infrastructure understanding)
- [ ] **Read** PAGE_TEMPLATE_SPEC.md (HTML structure)
- [ ] **Read** N8N_WORKFLOW_SPEC.md (automation blueprint)
- [ ] **Read** DEEPSEEK_PROMPTS.md (content generation)
- [ ] **Skim** PHASE_1_QUICKSTART.md (this week's tasks)

### Project Kickoff
- [ ] **Create GitHub project board** for Phase 1 tracking
- [ ] **Create GitHub issues** for each week's deliverables
- [ ] **Invite team members** (if applicable)
- [ ] **Schedule weekly sync** (optional, if team-based)

---

## Week 1: Template & Database (Jan 24-30)

### Day 1-2: Docker & Database Verification

**Monday AM:**
- [ ] Confirm all Docker containers running
- [ ] Confirm PostgreSQL has data:
  ```sql
  SELECT COUNT(*) FROM cities;  -- Should be 10+
  SELECT COUNT(*) FROM services;  -- Should be 7
  ```
- [ ] Create .env file with API keys
- [ ] Test DeepSeek API call (1 call)
- [ ] Check DeepSeek cost (should be < $0.0001)

**Monday PM - Tuesday:**
- [ ] Import full US cities dataset into `cities` table
  - [ ] 50 states
  - [ ] 150-300 cities per state
  - [ ] Total: 10,000+ cities
- [ ] Verify cities data:
  ```sql
  SELECT COUNT(*) FROM cities;  -- Should be ~10,000
  SELECT COUNT(DISTINCT state_code) FROM cities;  -- Should be 50
  ```
- [ ] Confirm nearby_cities populated
- [ ] Spot-check 5 random cities

**Daily Task:** Log Docker container status
- ```bash
  docker-compose ps  # All showing "Up"
  docker-compose logs n8n | tail -20  # No errors
  ```

---

### Day 3-5: HTML Template Development

**Wednesday AM:**
- [ ] Create `templates/base.html` - Base HTML structure
  - [ ] Header with navigation
  - [ ] Hero section (H1, subheading, CTA)
  - [ ] Local context section (4 cards)
  - [ ] Service overview section
  - [ ] Provider comparison table
  - [ ] Lead capture form (name, email, phone, company, service, city)
  - [ ] FAQ section (accordion)
  - [ ] Related cities links
  - [ ] Footer

- [ ] Create `css/main.css` - Stylesheet
  - [ ] Mobile responsive (breakpoints: 768px, 1024px)
  - [ ] Accessible color contrast
  - [ ] Form styling
  - [ ] Button hover effects
  - [ ] Accordion interactions

**Wednesday PM - Thursday:**
- [ ] Test template locally with sample data
  - [ ] Create `test/sample-data.json` with mock page variables
  - [ ] Render template with sample data
  - [ ] Verify all sections display correctly
  - [ ] Check mobile responsiveness (use DevTools)

- [ ] Validate HTML output
  - [ ] No broken tags
  - [ ] All form fields present
  - [ ] All links functional
  - [ ] CSS loads properly

**Thursday PM - Friday:**
- [ ] Validate schema markup
  - [ ] LocalBusiness schema present
  - [ ] Service schema present
  - [ ] FAQ schema present
  - [ ] Use Google's Rich Results Tester

- [ ] Test form submission
  - [ ] Create test form endpoint
  - [ ] Test form validation (email format, required fields)
  - [ ] Verify submission receives data

**Friday:**
- [ ] Create `templates/error-page.html` for fallback
- [ ] Create `templates/success-page.html` for form submission
- [ ] Final template review
- [ ] Update `PAGE_TEMPLATE_SPEC.md` if changes made

**Daily Task:** Review generated samples
```bash
node scripts/render-template.js --city Austin --service voip --output test-output.html
# Manually review generated HTML
```

**End of Week 1 Checkpoint:**
- [ ] HTML template complete and tested
- [ ] CSS responsive and styled
- [ ] All schema markup valid
- [ ] Form fully functional
- [ ] Sample pages look professional
- [ ] Ready for n8n integration

---

## Week 2: n8n Workflow & Page Generation (Jan 31 - Feb 7)

### Day 1-2: n8n Workflow Setup

**Monday AM:**
- [ ] Access n8n UI (http://localhost:5678)
- [ ] Create new workflow: "CitySync - Page Generation v1"
- [ ] Set up trigger node:
  - [ ] Type: Manual trigger (for testing)
  - [ ] Add input fields: `batch_size` (default 10), `service` (default "all")

**Monday PM:**
- [ ] Add database query node:
  ```sql
  SELECT c.id, c.city_name, c.state_code, c.population, 
         c.nearby_cities, s.service_id, s.service_name, s.service_slug
  FROM cities c
  CROSS JOIN services s
  LEFT JOIN pages p ON c.id = p.city_id AND s.service_id = p.service_id
  WHERE p.id IS NULL AND c.status = 'active'
  LIMIT $1
  ```
  - [ ] Limit: `{{ $input.body.batch_size }}`
  - [ ] Test query: Should return city × service combinations

- [ ] Add parallel HTTP nodes for data fetching:
  - [ ] Weather API (OpenWeather)
  - [ ] Events API (Eventbrite)
  - [ ] Chamber info (Google Places)

**Tuesday:**
- [ ] Add DeepSeek API call node
  - [ ] HTTP POST to https://api.deepseek.com/v1/chat/completions
  - [ ] Model: deepseek-chat
  - [ ] Max tokens: 2500
  - [ ] Temperature: 0.7
  - [ ] Prompt: Use template from `DEEPSEEK_PROMPTS.md`
  - [ ] Parse JSON response

- [ ] Add error handling:
  - [ ] Retry on rate limit (3x with exponential backoff)
  - [ ] Fallback to Claude on error
  - [ ] Log all errors to database

**Daily Task:** Test each node individually
```
- Test database query: Run, verify output
- Test weather API: Austin, TX should return data
- Test DeepSeek: 1 call should succeed
```

---

### Day 3-5: Template Rendering & Deployment

**Wednesday AM:**
- [ ] Add template rendering node (Function node in n8n)
  - [ ] Load `templates/base.html`
  - [ ] Substitute all `{{ variable }}` with data
  - [ ] Validate HTML output
  - [ ] Return rendered HTML

- [ ] Add quality check node
  - [ ] Validate HTML (no broken tags)
  - [ ] Check content length (> 3000 chars)
  - [ ] Verify schema markup
  - [ ] Flag duplicates (hash comparison)

**Wednesday PM:**
- [ ] Add S3/CDN upload node
  - [ ] Upload HTML to S3 bucket
  - [ ] File path: `/pages/{{ service_slug }}/{{ city_slug }}-{{ state_code }}.html`
  - [ ] Cache-Control: max-age=31536000
  - [ ] Verify upload succeeded (GET file back)

- [ ] Add database update node
  - [ ] Update `pages` table:
    - [ ] status = 'generated'
    - [ ] html_content = rendered HTML
    - [ ] generated_at = NOW()
  - [ ] Log to `api_usage` table (tokens, cost)

**Thursday:**
- [ ] Activate loop: Process all cities × services in batch
  - [ ] Add "Loop" node to iterate through database results
  - [ ] Map all sub-nodes to loop

- [ ] Test workflow with 10 pages
  - [ ] Trigger workflow manually with batch_size=10
  - [ ] Monitor execution
  - [ ] Check generated files in S3
  - [ ] Verify database updates
  - [ ] Check total cost (should be ~$0.001)

**Thursday PM - Friday:**
- [ ] Scale to 50 pages
  - [ ] Set batch_size=50
  - [ ] Monitor workflow
  - [ ] Check for errors
  - [ ] Verify all 50 files uploaded

- [ ] Test with all 5 services × 5 cities = 50 pages
  - [ ] Monitor completion time (~30-45 minutes)
  - [ ] Verify cost (~$0.01)
  - [ ] Check success rate (should be > 98%)

**Friday:**
- [ ] Create n8n workflow JSON export
  - [ ] File: `n8n-workflow-export.json`
  - [ ] Commit to GitHub for version control

- [ ] Document any adjustments made
  - [ ] Update `N8N_WORKFLOW_SPEC.md` if deviations
  - [ ] Add troubleshooting notes

**End of Week 2 Checkpoint:**
- [ ] n8n workflow complete
- [ ] 50 pages generated successfully
- [ ] All pages uploaded to CDN
- [ ] Database tracking pages
- [ ] Cost: ~$0.01 (on budget)
- [ ] Ready for Phase 2 scale (500 pages)

---

## Phase 1 Validation & Go/No-Go Decision (Feb 6-7)

### Friday, Feb 6: Quality Assurance

**Spot Check 10 Random Pages:**
- [ ] Page loads (< 2 sec)
- [ ] Title unique and SEO-optimized
- [ ] H1 tag present and relevant
- [ ] Local context section populated
- [ ] Case study realistic and unique
- [ ] FAQ items present (3+)
- [ ] Form fields all present
- [ ] Schema markup valid
- [ ] Links to nearby cities present
- [ ] Mobile responsive

**Form Testing:**
- [ ] Submit test lead on 3 random pages
- [ ] Verify data received in database
- [ ] Check email confirmation (if configured)

**Performance Testing:**
- [ ] Run PageSpeed Insights on 5 pages (target: 80+)
- [ ] Check Core Web Vitals
- [ ] Verify load time < 2 sec

**Content Validation:**
- [ ] Search for duplicates (content_hash check)
- [ ] Verify no generic/template text
- [ ] Check local context is city-specific
- [ ] Confirm pricing mentioned realistically

### Friday, Feb 7: Phase 1 Go/No-Go

**Checklist for Phase 2 approval:**
- [ ] 50+ pages generated
- [ ] 100% valid HTML
- [ ] 0% duplicate content
- [ ] All forms functional
- [ ] Schema markup valid on all
- [ ] Cost < $0.02 (on budget)
- [ ] Error rate < 2%
- [ ] Page speed > 80 (PageSpeed Insights)
- [ ] Database tracking all pages
- [ ] CDN serving files correctly

**GO Decision Criteria (All must pass):**
- ✅ Quality score ≥ 90/100
- ✅ Error rate ≤ 2%
- ✅ Cost ≤ $0.02
- ✅ Form conversion testing positive

**If GO:**
- [ ] Update CHANGELOG.md - "Phase 1 Complete"
- [ ] Commit to GitHub
- [ ] Schedule Phase 2 kickoff
- [ ] Plan scaling to 50K pages

**If NO-GO:**
- [ ] Document blockers
- [ ] Create GitHub issues for fixes
- [ ] Schedule 1-week iteration
- [ ] Re-validate next Friday

---

## Weekly Sync Points

Every Friday 3 PM:
- [ ] Review completion checklist
- [ ] Note blockers/issues
- [ ] Update timeline if needed
- [ ] Plan next week
- [ ] Commit progress to GitHub

---

## Files Created During Phase 1

| File | Status | Notes |
|------|--------|-------|
| `templates/base.html` | To Create | HTML template |
| `css/main.css` | To Create | Stylesheet |
| `test/sample-data.json` | To Create | Test data |
| `n8n-workflow-export.json` | To Create | Workflow backup |
| `.env` | To Create | API keys (private) |
| `output/` | To Create | Generated HTML files |
| `reports/` | To Create | Generation reports |

---

## Success Metrics (Track Weekly)

| Metric | Target | Week 1 | Week 2 |
|--------|--------|--------|--------|
| Pages Generated | 50+ | 0 | 50+ |
| Error Rate | < 2% | N/A | < 2% |
| Cost | < $0.02 | $0 | < $0.02 |
| Avg Page Speed | < 2 sec | N/A | < 2 sec |
| Form Submissions | 5+ | 0 | 5+ |
| Content Uniqueness | 100% | N/A | 100% |

---

## Emergency Contacts

**If stuck:**
- n8n Support: https://community.n8n.io
- DeepSeek Issues: support@deepseek.com
- Docker Issues: https://docker.com/support
- Database Issues: PostgreSQL docs

---

**Owner:** You  
**Support:** GitHub Copilot  
**Status:** Ready to start Jan 24, 2026
