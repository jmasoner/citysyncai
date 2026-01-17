# PHASE 2B: DeepSeek API Integration Quick Start

**Objective:** Add AI-generated headlines to each page using DeepSeek API  
**Timeline:** 1-2 hours  
**Cost:** ~$0.005 for 50 test pages  

---

## Checklist Before Starting

- [ ] DeepSeek API key is in `.env` file
- [ ] n8n is running at `http://localhost:5678`
- [ ] PostgreSQL is running and has test data
- [ ] Output directory exists: `output/`

**Verify Status:**
```powershell
# Check services
curl http://localhost:5678/healthz  # Should return {"status":"ok"}

# Check database
psql -h localhost -U citysyncai -d citysyncai -c "SELECT COUNT(*) FROM cities;"  # Should show 10
```

---

## Step 1: Test DeepSeek API (5 minutes)

Create a test script to verify API access:

```python
#!/usr/bin/env python3
import requests
import os
from dotenv import load_dotenv

load_dotenv()

api_key = os.getenv("DEEPSEEK_API_KEY")
url = "https://api.deepseek.com/v1/chat/completions"

response = requests.post(
    url,
    headers={
        "Authorization": f"Bearer {api_key}",
        "Content-Type": "application/json"
    },
    json={
        "model": "deepseek-chat",
        "messages": [
            {
                "role": "system",
                "content": "You are a marketing copywriter."
            },
            {
                "role": "user",
                "content": "Write one compelling headline for VoIP services in Austin, TX."
            }
        ],
        "temperature": 0.7,
        "max_tokens": 50
    }
)

print(response.status_code)
print(response.json())
```

**Expected Output:**
```json
{
  "choices": [
    {
      "message": {
        "content": "Enterprise VoIP Solutions Built for Austin's Growing Tech Industry"
      }
    }
  ],
  "usage": {
    "prompt_tokens": 25,
    "completion_tokens": 14,
    "total_tokens": 39
  }
}
```

**Cost:** $0.00014 (39 tokens × $0.0000036 per token)

---

## Step 2: Create AI Headline Generator Script (10 minutes)

```python
#!/usr/bin/env python3
"""Generate AI-enhanced headlines using DeepSeek"""

import requests
import os
from dotenv import load_dotenv
import time

load_dotenv()

API_KEY = os.getenv("DEEPSEEK_API_KEY")
API_URL = "https://api.deepseek.com/v1/chat/completions"

def generate_headline(city, state, service, market_data):
    """Generate unique headline for a city/service combo"""
    
    prompt = f"""Generate a compelling, unique headline for a landing page offering {service} 
services in {city}, {state}. 

Market context:
- {market_data['total_businesses']} registered businesses
- {market_data['top_industries']} are major industries
- {market_data['market_gap']}

Requirements:
1. Unique and compelling (not generic)
2. Include city name
3. Appeal to business owners
4. Maximum 70 characters
5. Return ONLY the headline, no quotes or explanations

Examples of GOOD headlines:
- "VoIP for Austin's Tech Boom: Save 40% on Phone Costs"
- "Enterprise Internet for LA's Hollywood Studios"
- "Fiber-Ready Networks for NY Financial District"

Return just the headline:"""
    
    try:
        response = requests.post(
            API_URL,
            headers={
                "Authorization": f"Bearer {API_KEY}",
                "Content-Type": "application/json"
            },
            json={
                "model": "deepseek-chat",
                "messages": [
                    {
                        "role": "user",
                        "content": prompt
                    }
                ],
                "temperature": 0.8,  # Varies output
                "max_tokens": 60
            },
            timeout=10
        )
        
        if response.status_code == 200:
            headline = response.json()["choices"][0]["message"]["content"].strip()
            tokens = response.json()["usage"]["total_tokens"]
            cost = tokens * 0.0000036  # Rough estimate
            return headline, cost
        else:
            return f"Error: {response.status_code}", 0
    
    except Exception as e:
        return f"Error: {str(e)}", 0


if __name__ == "__main__":
    # Test with Austin VoIP
    market_data = {
        "total_businesses": "45,000+",
        "top_industries": "Technology, Healthcare, Finance",
        "market_gap": "66% lack fiber availability - we serve them"
    }
    
    headline, cost = generate_headline("Austin", "TX", "VoIP", market_data)
    print(f"Headline: {headline}")
    print(f"Cost: ${cost:.6f}")
```

---

## Step 3: Import n8n Workflow (10 minutes)

1. Open `http://localhost:5678` in browser
2. Click **"Workflows"** in left menu
3. Click **"Create Workflow"** or **"Import"**
4. Select **"From File"**
5. Choose `n8n_workflow_phase2.json`
6. Click **"Import"**
7. Review workflow (should show 13 nodes)
8. Click **"Save"**

**Workflow Nodes:**
1. Trigger (manual start)
2. Set batch parameters
3. Query cities from DB
4. Loop through cities/services
5. Fetch city business data
6. Generate AI headline (DeepSeek)
7. Extract headline
8. Render HTML template
9. Validate HTML
10. Store metadata in DB
11. Update counter
12. Log completion
13. Error handler

---

## Step 4: Test with Single Page (10 minutes)

**Manual Test:**
```bash
# Generate single page with AI headline capability
python scripts/render_template.py \
  --city "Austin" \
  --state "TX" \
  --service "VoIP" \
  --output test_output/

# Verify file created
ls -lh test_output/austin-voip-tx.html
```

**Then manually add AI headline:**
```python
from scripts.ai_headline_generator import generate_headline
from scripts.fetch_city_data import get_city_data

city_data = get_city_data("Austin", "TX")
headline, cost = generate_headline("Austin", "TX", "VoIP", city_data)
print(f"AI Headline: {headline}")
print(f"Cost: ${cost:.6f}")
```

---

## Step 5: Run 50-Page Batch with AI (15 minutes)

Create new batch script: `scripts/generate_batch_with_ai.py`

```python
#!/usr/bin/env python3
"""Generate 50 pages with AI headlines"""

import subprocess
import json
import time
from pathlib import Path
from concurrent.futures import ThreadPoolExecutor
from ai_headline_generator import generate_headline
from fetch_city_data import get_city_data

CITIES = [("Austin", "TX"), ("New York", "NY"), ("Los Angeles", "CA"), 
          ("Chicago", "IL"), ("Houston", "TX"), ("Phoenix", "AZ"),
          ("Philadelphia", "PA"), ("San Antonio", "TX"), ("San Diego", "CA"), ("Dallas", "TX")]
SERVICES = ["VoIP", "Internet", "Fiber", "Network", "Security"]

def generate_page_with_ai(city, state, service):
    """Generate single page with AI headline"""
    try:
        # Fetch city data
        city_data = get_city_data(city, state)
        
        # Generate AI headline
        ai_headline, ai_cost = generate_headline(city, state, service, city_data)
        
        # Render page
        cmd = ["python", "scripts/render_template.py",
               "--city", city, "--state", state, "--service", service,
               "--output", "output_ai/", "--template", "templates/base.html"]
        result = subprocess.run(cmd, capture_output=True, timeout=10)
        
        if result.returncode == 0:
            return {
                "status": "success",
                "city": city,
                "service": service,
                "ai_headline": ai_headline,
                "ai_cost": ai_cost
            }
    except Exception as e:
        return {"status": "error", "error": str(e)}

# Generate 50 pages
start = time.time()
with ThreadPoolExecutor(max_workers=5) as executor:
    futures = [
        executor.submit(generate_page_with_ai, city, state, service)
        for city, state in CITIES
        for service in SERVICES
    ]
    results = [f.result() for f in futures]

# Calculate costs
total_ai_cost = sum(r.get("ai_cost", 0) for r in results if r.get("status") == "success")
total_time = time.time() - start

print(f"\nGenerated {len(results)} pages in {total_time:.2f}s")
print(f"Total AI cost: ${total_ai_cost:.4f}")
print(f"Average cost per page: ${total_ai_cost/50:.6f}")
print(f"Total batch cost: ${total_ai_cost:.4f} (DeepSeek only)")
```

**Expected Output:**
```
Generated 50 pages in 45.32s
Total AI cost: $0.0073
Average cost per page: $0.000146
Total batch cost: $0.0073 (DeepSeek only)
```

---

## Step 6: Validate and Report (10 minutes)

Run validation suite:
```bash
python scripts/validation_report.py
python scripts/validate_seo.py
python scripts/validate_schema.py
```

Expected:
- ✅ 100% valid HTML
- ✅ 100% schema compliant
- ✅ 97%+ SEO score
- ✅ All AI headlines embedded

---

## Cost Breakdown

| Task | Tokens | Cost |
|------|--------|------|
| Single headline | ~50 | $0.00018 |
| 50 headlines | 2,500 | $0.009 |
| 1,000 headlines | 50,000 | $0.18 |
| 50,000 headlines | 2,500,000 | $9.00 |

---

## Troubleshooting

### "API key invalid" error
- Check `.env` file has correct DEEPSEEK_API_KEY
- Verify key doesn't have extra spaces/quotes
- Test with: `python -c "import os; print(os.getenv('DEEPSEEK_API_KEY'))"`

### "Connection timeout" error
- Check internet connection
- Verify DeepSeek API is not rate limited
- Wait 60 seconds and retry

### "Database connection refused"
- Ensure PostgreSQL is running: `docker ps | grep postgres`
- Verify credentials in `.env`
- Test: `psql -h localhost -U citysyncai`

---

## Success Criteria

✅ DeepSeek API responds successfully  
✅ AI headlines are unique and compelling  
✅ 50 pages generate in <60 seconds  
✅ Total cost is <$0.02  
✅ All pages validate successfully  
✅ Headlines appear in HTML output  

---

## Next Decision Point

**If successful:**
- Approve Phase 3 (scale to 50K pages)
- Deploy to staging CDN
- Begin monitoring lead quality

**If issues:**
- Adjust prompt for better headlines
- Optimize batch size
- Switch to different AI model

