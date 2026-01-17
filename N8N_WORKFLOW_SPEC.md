# CitySync AI - n8n Workflow Specification

**Purpose:** Define the complete n8n workflow for automated content generation, HTML rendering, quality checks, and CDN deployment.

---

## Workflow Overview

```
[Trigger: Schedule] 
    ↓
[Query Database: Pending Cities]
    ↓
[For Each City × Service] (Parallel)
    ├─ [Fetch Local Data]
    ├─ [Generate Content: DeepSeek]
    ├─ [Refine Content: Claude (optional)]
    ├─ [Render HTML]
    ├─ [Quality Checks]
    ├─ [Upload to S3/CDN]
    └─ [Update Database]
    ↓
[Generate Sitemap]
    ↓
[Submit to Google Search Console]
    ↓
[Send Completion Notification]
```

---

## Detailed Workflow Steps

### 1. Trigger (Daily Schedule)

**Node: Cron Job / Webhook**

```
Trigger Type: Cron
Schedule: 0 22 * * * (10 PM daily)
Timezone: America/Chicago
```

**Purpose:** Kick off generation at off-peak hours.

---

### 2. Query Pending Cities

**Node: PostgreSQL Query**

```sql
SELECT 
  c.id,
  c.city_name,
  c.state_code,
  c.population,
  c.coordinates,
  c.nearby_cities,
  s.service_name,
  s.service_slug,
  s.service_id
FROM cities c
CROSS JOIN services s
LEFT JOIN pages p ON c.id = p.city_id AND s.service_id = p.service_id
WHERE p.id IS NULL  -- Not yet generated
  AND c.status = 'active'
LIMIT {{ batch_size }}  -- Default: 5000
ORDER BY RANDOM()
```

**Output:** Array of city × service combinations to generate.

---

### 3. Fetch Local Data (In Parallel)

**Node: HTTP Requests (Parallel Execution)**

For each city, fetch:

#### A. Weather Data
```
Method: GET
URL: https://api.openweathermap.org/data/2.5/weather
Params:
  - q: {{ city_name }}, {{ state_code }}
  - appid: {{ OPENWEATHER_API_KEY }}
  - units: metric

Response: { temp, weather[], city, humidity, wind_speed }
```

#### B. Local Events
```
Method: GET
URL: https://www.eventbriteapi.com/v3/events/search/
Params:
  - location.address: {{ city_name }}, {{ state_code }}
  - token: {{ EVENTBRITE_API_KEY }}

Response: { events[] {title, start_time, category} }
```

#### C. Business Listings
```
Method: GET
URL: https://places.googleapis.com/v1/places:searchText
Body:
  - textQuery: "chamber of commerce in {{ city_name }}, {{ state_code }}"
  - maxResultCount: 5

Response: { places[] {name, formattedAddress, websiteUri} }
```

**Caching:** Store results in Redis for 1 week (avoid redundant API calls).

---

### 4. Generate Content: DeepSeek API

**Node: HTTP Request (Post)**

```
Method: POST
URL: https://api.deepseek.com/v1/chat/completions
Headers:
  Authorization: Bearer {{ DEEPSEEK_API_KEY }}
  Content-Type: application/json

Request Body:
{
  "model": "deepseek-chat",
  "messages": [
    {
      "role": "system",
      "content": "You are an expert telecom solutions consultant..."
    },
    {
      "role": "user",
      "content": "{{ generation_prompt }}"
    }
  ],
  "temperature": 0.7,
  "max_tokens": 2500
}
```

**Generation Prompt Template:**

```
You are creating a landing page for {{ service_name }} services in {{ city }}, {{ state }}.

LOCAL CONTEXT:
- Population: {{ population }}
- Weather: {{ weather }}
- Major Event: {{ event_name }}
- Chamber: {{ chamber_name }}

GENERATE THE FOLLOWING JSON:
{
  "hero_headline": "...",
  "hero_subheading": "...",
  "cta_button_text": "...",
  "problem_statement": "...",
  "service_overview": "...",
  "benefits": ["...", "...", "...", "..."],
  "case_study_title": "...",
  "case_study_quote": "...",
  "company_name": "{{ city }} based company",
  "faq": [
    { "question": "...", "answer": "..." },
    { "question": "...", "answer": "..." },
    { "question": "...", "answer": "..." }
  ],
  "combrokers_advantages": ["...", "...", "...", "..."]
}

REQUIREMENTS:
- All text must be unique (not generic)
- Include specific {{ city }} context
- Local service providers should be mentioned
- Maintain professional tone
- Focus on business problems and ROI
```

**Error Handling:** 
- Retry 3 times with exponential backoff
- Log error + cost to database
- Fall back to Claude or Ollama if DeepSeek fails

**Cost Tracking:** Log tokens used + cost to `api_usage` table.

---

### 5. Refine Content: Claude Opus (Optional, Top 20%)

**Node: Conditional → HTTP Request**

```
Condition: IF {{ is_high_intent_keyword }} OR {{ is_tier1_city }}
  Then: Call Claude API
  Else: Skip
```

```
Method: POST
URL: https://api.anthropic.com/v1/messages
Headers:
  x-api-key: {{ CLAUDE_API_KEY }}
  anthropic-version: 2023-06-01

Request Body:
{
  "model": "claude-opus-4-1-20250805",
  "max_tokens": 1024,
  "messages": [
    {
      "role": "user",
      "content": "Refine these headlines/CTAs for maximum conversion:
        - Headline: {{ hero_headline }}
        - Subheading: {{ hero_subheading }}
        - CTA: {{ cta_button_text }}
        
        Respond with JSON: { 'headline': '...', 'subheading': '...', 'cta': '...' }"
    }
  ]
}
```

**Merge Refined Content:** Update hero section with Claude's output.

---

### 6. Render HTML

**Node: Function / Script Execution**

```javascript
// Render HTML from template + variables
const Handlebars = require('handlebars');
const fs = require('fs');

// Load base template
const template = fs.readFileSync('./templates/base.html', 'utf8');
const compiled = Handlebars.compile(template);

// Merge content + local data
const pageData = {
  page_title: `${service_name} in ${city}, ${state} | ComBrokers`,
  meta_description: `Find ${service_name.toLowerCase()} providers in ${city}, ${state}...`,
  
  // Hero
  hero_headline: content.hero_headline,
  hero_subheading: content.hero_subheading,
  cta_button_text: content.cta_button_text,
  
  // Local context
  city: city_name,
  state: state_code,
  population: population,
  weather_insight: weather.description,
  event_name: events[0]?.title || 'local events',
  chamber_name: chamber?.name || 'Chamber of Commerce',
  
  // Service
  service_name: service_name,
  service_overview: content.service_overview,
  benefit_1: content.benefits[0],
  benefit_2: content.benefits[1],
  benefit_3: content.benefits[2],
  benefit_4: content.benefits[3],
  
  // Case study
  case_study_title: content.case_study_title,
  case_study_quote: content.case_study_quote,
  company_name: content.company_name,
  
  // FAQ
  faq_1_question: content.faq[0].question,
  faq_1_answer: content.faq[0].answer,
  faq_2_question: content.faq[1].question,
  faq_2_answer: content.faq[1].answer,
  faq_3_question: content.faq[2].question,
  faq_3_answer: content.faq[2].answer,
  
  // Nearby cities
  nearby_city_1: nearby_cities[0],
  nearby_city_2: nearby_cities[1],
  nearby_city_3: nearby_cities[2],
  
  // Form tracking
  page_id: page_id,
  service_type: service_type,
  
  // URLs
  page_url: `https://combrokers.com/${service_slug}/${city_slug}-${state_code}/`,
  state_name: state_name,
  state_code: state_code,
  service_slug: service_slug
};

// Render
const html = compiled(pageData);

// Validate
if (!html.includes('<h1>') || html.length < 2000) {
  throw new Error('Invalid HTML generated');
}

return { html, page_id, city_name, state_code, service_slug };
```

**Output:** Valid HTML string.

---

### 7. Quality Checks

**Node: Function**

```javascript
// Validate HTML output
const validateHTML = (html) => {
  const issues = [];
  
  // Check for required elements
  if (!html.includes('<h1>')) issues.push('Missing H1 tag');
  if (!html.includes('id="lead-form"')) issues.push('Missing form');
  if (!html.includes('LocalBusiness')) issues.push('Missing schema markup');
  if (html.length < 3000) issues.push('Content too short');
  
  // Check for duplicates
  const hash = require('crypto')
    .createHash('md5')
    .update(html)
    .digest('hex');
  
  // Flag if similar page exists
  const similar = db.query(
    'SELECT * FROM pages WHERE content_hash = ?', [hash]
  );
  
  if (similar.length > 0) {
    issues.push('Duplicate content detected');
  }
  
  return {
    valid: issues.length === 0,
    issues: issues,
    hash: hash,
    length: html.length
  };
};
```

**Error Handling:**
- If invalid: Log error, retry with Claude
- If duplicate: Flag and skip
- If passes: Continue to deployment

---

### 8. Upload to CDN/Storage

**Node: Amazon S3 / HTTP Upload**

```
Method: PUT
URL: https://{{ BUCKET }}.s3.amazonaws.com/pages/{{ service_slug }}/{{ city_slug }}-{{ state_code }}.html

Headers:
  - Content-Type: text/html; charset=utf-8
  - Cache-Control: max-age=31536000, public
  - x-amz-acl: public-read

Body: {{ html }}
```

**Or Bunny CDN:**

```
Method: PUT
URL: https://{{ BUNNY_STORAGE_ZONE }}.storage.bunnycdn.com/pages/{{ service_slug }}/{{ city_slug }}-{{ state_code }}.html

Headers:
  - AccessKey: {{ BUNNY_API_KEY }}
  - Content-Type: text/html

Body: {{ html }}
```

**Verification:**
- GET the file back
- Verify content matches
- Store public URL in database

---

### 9. Update Database

**Node: PostgreSQL Query**

```sql
INSERT INTO pages (
  city_id, service_id, title, slug, content_hash, html_content, status, generated_at
) VALUES (
  $1, $2, $3, $4, $5, $6, 'generated', NOW()
)
ON CONFLICT (slug) DO UPDATE SET
  html_content = $6,
  content_hash = $5,
  updated_at = NOW(),
  status = 'generated'
RETURNING id;
```

**Also log to API usage:**

```sql
INSERT INTO api_usage (
  provider, tokens_used, cost_cents, status
) VALUES (
  'deepseek', $1, $2, 'success'
);
```

---

### 10. Generate Sitemap (After All Pages)

**Node: Function**

```javascript
// After all pages generated, create sitemap.xml
const db = require('pg');

const pages = await db.query(
  `SELECT slug, updated_at FROM pages 
   WHERE status = 'generated' 
   ORDER BY updated_at DESC`
);

const sitemap = `<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
${pages.map(page => `  <url>
    <loc>https://combrokers.com/${page.slug}</loc>
    <lastmod>${page.updated_at.toISOString()}</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>`).join('\n')}
</urlset>`;

// Upload to CDN
await uploadToS3('sitemap.xml', sitemap);

return { pages: pages.length, generated_at: new Date() };
```

---

### 11. Submit to Google Search Console

**Node: HTTP Request**

```
Method: POST
URL: https://www.google.com/ping?sitemap=https://combrokers.com/sitemap.xml

Or via GSC API:
Method: POST
URL: https://www.google.com/webmasters/tools/submit-url

Headers:
  Authorization: Bearer {{ GSC_ACCESS_TOKEN }}
  Content-Type: application/json

Body:
{
  "url": "https://combrokers.com/sitemap.xml"
}
```

---

### 12. Monitoring & Notifications

**Node: Send Email / Slack**

```
Email Template:
Subject: CitySync Generation Complete ✓

{{ total_pages_generated }} pages generated
{{ error_count }} errors
Total cost: ${{ total_cost }}
Generation time: {{ duration_minutes }} minutes

High performers:
- {{ city_1 }}: {{ page_count }} pages
- {{ city_2 }}: {{ page_count }} pages

Next steps:
- Check Google Search Console for indexing
- Monitor conversion rate
- Review top keywords in Analytics
```

**Slack Webhook:**

```
{
  "text": "🚀 CitySync Generation Complete",
  "blocks": [
    { "type": "section", "text": { "type": "mrkdwn", "text": "*Status:* ✓ Success\n*Pages:* 5,000\n*Cost:* $0.50\n*Time:* 45 min" } }
  ]
}
```

---

## Parallel Execution Strategy

**Goal:** Generate 5,000+ pages/day efficiently.

```
n8n Configuration:
- Max concurrent workflows: 20
- API rate limit buffer: 80% (don't max out)
- Queue system: Bull.js (prioritize high-intent keywords)
- Batch size: 250 cities per batch
```

**Pseudocode:**

```javascript
for (let i = 0; i < cities.length; i += 250) {
  const batch = cities.slice(i, i + 250);
  
  // Generate in parallel (20 concurrent)
  const promises = batch.map(city => 
    generatePageForCity(city)
      .then(html => uploadToCDN(html))
      .catch(error => logError(error))
  );
  
  await Promise.allSettled(promises);
  
  // Update database after batch
  await updateDatabase(batch);
}
```

---

## Error Handling & Fallback

```
Flow:
1. Try DeepSeek API
   ├─ If success: Continue
   ├─ If rate-limited: Queue and retry after 60 sec
   ├─ If error: Try Claude
   
2. Try Claude API
   ├─ If success: Continue
   ├─ If error: Use cached/template content

3. Fallback to Ollama (local)
   ├─ If success: Continue
   └─ If error: Mark page as "retry_later"

All errors logged to database with:
- Service name
- Error message
- Timestamp
- Retry count
```

---

## Performance Targets

| Metric | Target | Current |
|--------|--------|---------|
| Pages/day | 5,000 | TBD |
| Cost/page | < $0.0002 | TBD |
| Generation time | 2-5 sec | TBD |
| Success rate | > 98% | TBD |
| CDN upload speed | < 1 sec | TBD |

---

## n8n Workflow Export (JSON)

[To be created after Phase 1 MVP testing]

---

**Last Updated:** [Current Date]  
**Status:** Ready for Phase 1 Implementation
