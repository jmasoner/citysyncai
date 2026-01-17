# WEEK 2 READINESS CHECKLIST

**Prepared By:** AI Agent  
**Date:** January 17, 2026  
**Status:** Ready for Execution (Jan 24, 2026)

---

## 📋 PRE-WEEK 2 ACTION ITEMS

### By Jan 23, 2026 (Before Week Starts)

#### 1. API Credentials Preparation
- [ ] **DeepSeek API Key**
  - Sign up: https://platform.deepseek.com
  - Generate API key
  - Add to `.env`: `DEEPSEEK_API_KEY=sk-xxxxx`
  - Test quota and rate limits
  - Estimated cost: $0.01 for 50 pages

- [ ] **OpenWeather API Key**
  - Sign up: https://openweathermap.org/api
  - Use free tier (1000 calls/day)
  - Add to `.env`: `OPENWEATHER_API_KEY=xxxxx`
  - Select weather endpoint version

- [ ] **EventBrite API Key** (Optional)
  - Sign up: https://www.eventbrite.com/platform/api
  - Add to `.env`: `EVENTBRITE_API_KEY=xxxxx`
  - Fallback: Skip if unavailable (not critical)

#### 2. n8n Configuration
- [ ] Access n8n at http://localhost:5678
- [ ] Create new workflow named "ComBrokers - Landing Page Generator"
- [ ] Set up PostgreSQL credentials:
  - Host: `citysyncai-postgres` (Docker DNS)
  - Port: `5432`
  - Database: `n8n`
  - User: `n8n`
  - Password: (from .env)

#### 3. Database Verification
- [ ] Verify 10 cities in PostgreSQL:
  ```bash
  docker exec citysyncai-postgres psql -U n8n -d n8n -c "SELECT COUNT(*) FROM cities;"
  ```
  Expected: `count = 10` ✅

- [ ] Verify 7 services:
  ```bash
  docker exec citysyncai-postgres psql -U n8n -d n8n -c "SELECT * FROM services;"
  ```
  Expected: All 7 services listed

- [ ] Check pages table is empty:
  ```bash
  docker exec citysyncai-postgres psql -U n8n -d n8n -c "SELECT COUNT(*) FROM pages;"
  ```
  Expected: `count = 0`

#### 4. Docker Services Health Check
- [ ] n8n running: http://localhost:5678 responds
- [ ] PostgreSQL running: Port 5432 accessible
- [ ] Redis running: Port 6379 accessible

---

## 🚀 WEEK 2 EXECUTION CHECKLIST

### Monday-Tuesday (Jan 24-25): Environment Setup

#### Day 1 Morning
- [ ] Verify all API keys configured in `.env`
- [ ] Start n8n workflow editor
- [ ] Add PostgreSQL connection to n8n
- [ ] Test query: SELECT COUNT(*) FROM cities
- [ ] Document connection details in notes

#### Day 1 Afternoon
- [ ] Test each API individually:
  - [ ] DeepSeek API (test prompt)
  - [ ] Weather API (test city lookup)
  - [ ] EventBrite API (test events search)
- [ ] Document response times
- [ ] Verify rate limits and quotas

#### Day 2
- [ ] Create n8n workflow from `N8N_WORKFLOW.json` specification
- [ ] Add all 12 nodes
- [ ] Connect workflow inputs/outputs
- [ ] Save workflow

---

### Monday-Wednesday (Jan 27-29): Workflow Development & Testing

#### Day 1: Database & API Nodes
- [ ] Database Query Node (Node 2)
  - [ ] Query returns 50 city+service combos
  - [ ] Data structure matches template
  - [ ] No SQL errors

- [ ] Weather Fetch Node (Node 3)
  - [ ] Returns temperature, conditions
  - [ ] Handles API errors gracefully
  - [ ] Response time < 2 seconds

- [ ] Events Fetch Node (Node 4)
  - [ ] Returns event listings (if available)
  - [ ] Graceful fallback if API unavailable
  - [ ] Response time < 2 seconds

#### Day 2: Content Generation
- [ ] DeepSeek Node (Node 6)
  - [ ] Takes city/service/weather as input
  - [ ] Returns valid JSON response
  - [ ] Headline < 60 chars
  - [ ] Subheading < 120 chars
  - [ ] CTA text < 20 chars

- [ ] Test with sample prompts:
  - [ ] "VoIP in New York"
  - [ ] "Internet in Los Angeles"
  - [ ] "Fiber in Chicago"

#### Day 3: Template Rendering & Validation
- [ ] Template Render Node (Node 7)
  - [ ] Loads base.html successfully
  - [ ] Injects all variables
  - [ ] No unreplaced {{ }} variables
  - [ ] Output is valid HTML

- [ ] Quality Check Node (Node 8)
  - [ ] Validates HTML syntax
  - [ ] Checks schema markup
  - [ ] Verifies form presence
  - [ ] Returns pass/fail status

---

### Wednesday-Thursday (Jan 29-30): Testing & Optimization

#### Single Page Test
- [ ] Run workflow with 1 city + 1 service
- [ ] Verify output HTML quality
- [ ] Check all dynamic content correct
- [ ] Test form submission handler
- [ ] Measure execution time

#### Batch Generation (50 Pages)
- [ ] Set database limit to 50
- [ ] Run complete workflow
- [ ] Monitor execution in n8n UI
- [ ] Total time: Target < 5 minutes

#### Cost Verification
- [ ] Track API calls:
  - [ ] DeepSeek calls: 50
  - [ ] Weather calls: 50
  - [ ] EventBrite calls: 50
- [ ] Calculate total cost
- [ ] Verify < $0.02 total

#### Output Validation
- [ ] Run validation scripts:
  ```bash
  python scripts/validation_report.py
  python scripts/validate_schema.py
  python scripts/validate_seo.py
  ```
- [ ] Verify 100% valid HTML
- [ ] Schema markup all valid
- [ ] SEO score > 90%

---

### Thursday-Friday (Jan 30-31): Documentation & Decision

#### Workflow Documentation
- [ ] Export n8n workflow as JSON
- [ ] Save to Git repository
- [ ] Document each node configuration
- [ ] Create troubleshooting guide
- [ ] Document common errors + solutions

#### Analysis & Reporting
- [ ] Cost analysis report
- [ ] Performance metrics
- [ ] Quality metrics
- [ ] Comparison to competitors

#### Go/No-Go Decision
- [ ] All success criteria met?
- [ ] Cost < $0.02/page? ✅
- [ ] Pages valid? (100%)
- [ ] Conversion ready?
- [ ] Decision: PROCEED to Phase 2

---

## 📊 SUCCESS CRITERIA

| Criterion | Target | Threshold | Status |
|-----------|--------|-----------|--------|
| Pages Generated | 50+ | 50+ | ⏳ |
| Valid HTML | 100% | 95%+ | ⏳ |
| Schema Valid | 100% | 95%+ | ⏳ |
| Forms Working | 100% | 100% | ⏳ |
| Cost/Page | <$0.02 | <$0.05 | ⏳ |
| Execution Time | <5 min | <10 min | ⏳ |
| Error Rate | <2% | <5% | ⏳ |
| SEO Score | >90% | >85% | ⏳ |

---

## 🛠️ TROUBLESHOOTING REFERENCE

### Common Issues & Solutions

#### DeepSeek API Timeout
```
Error: Request timeout after 30s
Solution: Increase timeout in node config to 60s
         Or reduce max_tokens from 500 to 300
```

#### Database Connection Failed
```
Error: Connection refused at 5432
Solution: 
1. Check: docker ps (verify citysyncai-postgres running)
2. Verify credentials match .env file
3. Test: psql -h localhost -U n8n -d n8n
```

#### Template Rendering Errors
```
Error: Unreplaced variables {{ city }}
Solution:
1. Verify variable names match exactly
2. Check for extra spaces: {{ city }} vs {{city}}
3. Use regex test: /\{\{[^}]*\}\}/ to find unreplaced
```

#### Weather API No Data
```
Error: Empty weather response
Solution:
1. Verify city name format (use 2-letter state code)
2. Check API key is valid
3. Test directly: curl "https://api.openweathermap.org/data/2.5/weather?q=NewYork,NY&appid=XXXXX"
```

---

## 📞 ESCALATION CONTACTS

If issues arise:
1. **API Issues:** Contact support for respective API provider
2. **n8n Issues:** Check n8n documentation or GitHub issues
3. **Docker Issues:** Verify Docker daemon running
4. **Database Issues:** Run PostgreSQL diagnostics

---

## 📅 TIMELINE AT A GLANCE

```
Fri Jan 17  ✅ Week 1 complete (framework ready)
            📋 Week 2 planning done

Wed Jan 22  ⏳ Prepare API keys + credentials
            ⏳ Verify database + Docker services

Fri Jan 24  🚀 Week 2 Day 1: n8n setup
Mon Jan 27  🚀 Workflow development begins
Wed Jan 29  🚀 Single page test
Fri Jan 31  🎯 Batch generation + validation
            📊 Go/No-Go decision

Mon Feb 03  🚀 Phase 2 execution: Scale to 1K pages
```

---

## ✅ PHASE 2 READINESS

Upon successful Week 2 completion:
- Infrastructure validated ✅
- Cost model proven ✅
- Quality standards met ✅
- **Ready to scale to 50K pages**

---

**Next Session:** Monday, January 24, 2026 - Begin Week 2 execution.  
**Status:** All systems ready. Awaiting go signal.
