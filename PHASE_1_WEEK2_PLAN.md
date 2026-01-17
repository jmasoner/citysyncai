# Phase 1 Week 2: API Integration & Workflow Orchestration

**Dates:** January 24-31, 2026  
**Goal:** Build and test complete n8n workflow generating 50 pages with real AI content  
**Success Criteria:** 50+ pages with AI-generated headlines, FAQ, local context + valid forms

---

## 📋 WEEK 2 EXECUTION PLAN

### Day 1-2: n8n Workflow Setup (Jan 24-25)

#### A. PostgreSQL Data Verification
- [ ] Verify all 10 cities in database with correct service mappings
- [ ] Create test query for city+service combinations
- [ ] Check database indexes on (city_id, service_id, status)
- **Task:** `docker exec citysyncai-postgres psql -U n8n -d n8n -c "SELECT * FROM cities JOIN services ORDER BY cities.id;"`

#### B. n8n Environment Configuration
- [ ] Access n8n UI at http://localhost:5678
- [ ] Create "ComBrokers" project workspace
- [ ] Set up credentials for all API providers:
  - [ ] DeepSeek API key (primary content generation)
  - [ ] Claude API key (optional refinement)
  - [ ] OpenWeather API key (local weather context)
  - [ ] EventBrite API key (local events)
  - [ ] AWS/S3 credentials (for page storage - Phase 2)

#### C. PostgreSQL Connection Node
- [ ] Create n8n PostgreSQL connection (localhost:5432)
- [ ] Test credentials: user=n8n, password=[from .env]
- [ ] Verify table access from n8n

#### D. API Credential Testing
- [ ] Test DeepSeek API call (simple prompt test)
- [ ] Verify response format and token counting
- [ ] Measure API response time
- [ ] Document rate limits and cost per request

### Day 3: Workflow Node Development (Jan 27)

#### A. Trigger Configuration
- [ ] Manual trigger (for testing) - Node 1
- [ ] Database pagination logic (for batch processing)

#### B. Database Query Node (Node 2)
```sql
SELECT 
  c.id, c.name as city, c.state, c.population,
  s.id, s.name as service_name, s.id as service_id
FROM cities c
CROSS JOIN services s
WHERE c.status = 'active' AND s.status = 'active'
LIMIT 50
```
- [ ] Returns 50 city+service combinations
- [ ] Maps to template variables

#### C. Parallel Data Fetching (Nodes 3-5)
- [ ] Node 3: OpenWeather API - Current conditions + forecast
  - Input: `{{ $item(1).city }}, {{ $item(1).state }}`
  - Output: `temperature, conditions, forecast`
  
- [ ] Node 4: EventBrite API - Upcoming local events
  - Input: City name
  - Output: Array of upcoming events
  
- [ ] Node 5: Chamber of Commerce lookup (fallback HTTP node)
  - Input: City + State
  - Output: Local business info (cached/static for MVP)

#### D. DeepSeek Content Generation (Node 6)
```
Prompt Template:
"Generate a compelling headline for a [SERVICE_NAME] landing page in [CITY], [STATE].
Consider: local population [POPULATION], current weather [WEATHER], upcoming events [EVENTS].
Return JSON: { headline: string, subheading: string, cta_text: string }"
```
- [ ] System role: "You are an expert copywriter for telecom services landing pages"
- [ ] Temperature: 0.7 (balanced creativity + consistency)
- [ ] Max tokens: 500
- [ ] Error handling: Timeout/retry logic

#### E. HTML Template Rendering (Node 7)
- [ ] Load `templates/base.html`
- [ ] Inject all context variables:
  - City/state/population from DB
  - Service info from DB
  - Weather/events from API calls
  - Headlines/copy from DeepSeek
- [ ] Output: Full HTML file content
- [ ] Validate: Check for unreplaced variables

#### F. Quality Validation (Node 8)
- [ ] HTML syntax check (no unclosed tags)
- [ ] Schema markup validation (JSON-LD parse)
- [ ] Form field presence check
- [ ] Minimum content length (avoid thin pages)

#### G. File Storage (Node 9)
- [ ] Save to local `output/` directory with slug filename
  - Pattern: `{city}-{service}-{state}.html`
- [ ] Update database `pages` table with metadata:
  - `page_id`, `city_id`, `service_id`, `title`, `slug`, `url`, `status`

#### H. Database Updates (Node 10)
```sql
INSERT INTO pages (page_id, city_id, service_id, title, slug, status, created_at)
VALUES ({{ $item().page_id }}, {{ $item().city_id }}, ...)
```
- [ ] Track generation timestamp
- [ ] Log any API costs
- [ ] Update generation_logs table

#### I. Error Handling & Logging (Node 11)
- [ ] Catch node: If any node fails, log error to PostgreSQL
- [ ] Retry logic: DeepSeek failures retry up to 3x
- [ ] Notifications: Slack/email if batch fails
- [ ] Database: Track errors in generation_logs

### Day 4: Testing & Optimization (Jan 28)

#### A. Single Page Test Run
- [ ] Run workflow manually with 1 city + 1 service
- [ ] Verify output HTML quality
- [ ] Check all dynamic content inserted correctly
- [ ] Validate form submission handler

#### B. Batch Generation (50 pages)
- [ ] Set database limit to 50 (10 cities × 5 services)
- [ ] Run complete workflow
- [ ] Measure total execution time
- [ ] Track API costs:
  - DeepSeek: 50 calls × $0.0001 = ~$0.01
  - Weather API: 50 calls (free tier)
  - EventBrite: Free for basic events search
- [ ] Total expected cost: **<$0.02** ✅

#### C. Output Validation
- [ ] Generate validation report (same scripts as Week 1)
- [ ] Check all 50 pages for:
  - Valid HTML ✅
  - All variables replaced ✅
  - Schema markup valid ✅
  - Forms functional ✅

#### D. Performance Tuning
- [ ] Profile workflow execution time
- [ ] Identify bottlenecks (API calls vs rendering)
- [ ] Optimize DeepSeek prompts if needed
- [ ] Consider parallel batching for scale

### Day 5: Documentation & Preparation (Jan 29-31)

#### A. Workflow Documentation
- [ ] Export n8n workflow as JSON
- [ ] Create `N8N_WORKFLOW_DEPLOYMENT.json`
- [ ] Document each node configuration
- [ ] Create troubleshooting guide

#### B. Cost Analysis
- [ ] Calculate cost per page generation
- [ ] Project costs for 50K pages (Phase 2)
- [ ] Compare vs. competitors' infrastructure
- [ ] ROI analysis: Leads per dollar spent

#### C. Quality Report
- [ ] Run all validation scripts
- [ ] Generate comprehensive report
- [ ] Document any issues found
- [ ] Create recommendations for Phase 2

#### D. Go/No-Go Decision Preparation
- [ ] Create decision matrix
- [ ] Prepare metrics for stakeholder review
- [ ] Document lessons learned
- [ ] Plan Phase 2 scaling strategy

---

## 🎯 DELIVERABLES (End of Week 2)

1. **✅ n8n Workflow** - Complete, tested, documented
2. **✅ 50 Generated Pages** - AI-assisted content with all features
3. **✅ Validation Report** - All checks passing
4. **✅ Cost Analysis** - Confirmed <$0.02 per page
5. **✅ Deployment Guide** - Ready to scale to 50K pages
6. **✅ Go/No-Go Decision** - Framework to approve Phase 2

---

## 📊 SUCCESS CRITERIA

| Criteria | Target | Threshold |
|----------|--------|-----------|
| Pages Generated | 50+ | 50+ |
| HTML Validity | 100% | 95%+ |
| Schema Compliance | 100% | 95%+ |
| Form Functionality | 100% | 100% |
| AI Content Quality | "Ready" | Publishable |
| Cost per Page | <$0.02 | <$0.05 |
| Workflow Execution Time | <1 min | <5 min |
| Error Rate | 0% | <2% |

---

## 🔄 N8N WORKFLOW ARCHITECTURE

```
┌─ Trigger (Manual for testing)
│
├─ Database Query (50 city+service combos)
│
├─ Parallel Data Fetch ─┬─ Weather API
│                       ├─ Events API
│                       └─ Chamber lookup
│
├─ DeepSeek Content Generation
│  (Headlines, subheading, CTA copy)
│
├─ Template Rendering
│  (Inject all variables into base.html)
│
├─ Quality Validation
│  (HTML/Schema/Form checks)
│
├─ File Storage
│  (Save to output/ directory)
│
├─ Database Update
│  (Insert metadata to pages table)
│
└─ Error Handling
   (Log failures, retry logic, notifications)
```

---

## 💰 BUDGET TRACKING

### Projected Week 2 Costs
- **DeepSeek API:** 50 pages × $0.0001 = **$0.01**
- **Weather API:** Free tier (50 calls/day)
- **EventBrite:** Free (basic search)
- **n8n:** Self-hosted (free on local Docker)
- **Total:** **<$0.05**

### Phase 2 Projection (50K pages)
- **DeepSeek API:** 50,000 × $0.0001 = **$5.00**
- **Infrastructure:** $50-100/month (Docker + minimal hosting)
- **Monthly Total:** **~$60-110** (vs $500K+ competitors)

---

## 🚀 PHASE 2 READINESS

By end of Week 2, we will have:
- ✅ Proven infrastructure (n8n + APIs + PostgreSQL)
- ✅ Validated page generation at scale (50+ pages)
- ✅ Cost model confirmed (<$0.02/page)
- ✅ Quality standards met (100% valid HTML + schema)
- ✅ Ready to deploy to 50K+ pages

**Next:** Week of Feb 3-7, 2026 - Scale to 1K pages, verify traffic, measure lead quality before full 50K rollout.

---

## 📅 Timeline

```
Wed Jan 24 ── Fri Jan 25: n8n Setup & API Config
Mon Jan 27 ── Tue Jan 28: Workflow Development & Testing
Wed Jan 29 ── Fri Jan 31: Optimization & Documentation
Mon Feb 03: Go/No-Go Decision → Phase 2 Execution
```

---

**Status:** Ready to begin. Standing by for Week 2 start (Jan 24).
