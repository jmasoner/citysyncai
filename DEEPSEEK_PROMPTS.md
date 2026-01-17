# DeepSeek & Claude Prompts - CitySync AI

**Purpose:** Optimized prompts for generating high-quality, conversion-focused landing pages.

**Key Principles:**
- Unique local context (not generic)
- Problem-solution-CTA flow
- Business ROI focus
- Local credibility signals
- JSON output for easy templating

---

## 1. Main Content Generation Prompt (DeepSeek)

**Use Case:** Generating full page content for a city × service combination

```
You are an expert telecom solutions consultant creating a landing page for business services.

CONTEXT:
- Service: {{ service_name }}
- City: {{ city_name }}, {{ state_code }}
- City Population: {{ population }}
- Local Major Employer: {{ major_employer }}
- Local Weather Challenge: {{ weather_challenge }}
- Major Annual Event: {{ event_name }}
- Chamber of Commerce: {{ chamber_name }}

TASK: Generate compelling, unique landing page content for ComBrokers offering {{ service_name }} in {{ city_name }}.

REQUIREMENTS:
1. All text must be unique (NOT generic or template-like)
2. Include specific {{ city_name }} context and challenges
3. Focus on ROI and business problems
4. Local credibility signals (weather, events, chamber, major employers)
5. Professional, conversion-focused tone
6. Avoid competitor brand names (use "competing providers" instead)
7. Emphasis on speed, reliability, cost-savings

OUTPUT: Return ONLY valid JSON (no markdown, no comments):
{
  "hero_headline": "Short, powerful headline with {{ city_name }} and value prop",
  "hero_subheading": "Supporting subheading with specific benefit",
  "cta_button_text": "Action-oriented CTA (e.g., 'Schedule Free Consultation')",
  
  "problem_statement": "Why {{ city_name }} businesses need {{ service_name }} - 2-3 sentences",
  "problem_detail": "Specific pain points (cost, reliability, slowness, etc.)",
  
  "service_overview": "What is {{ service_name }} and how it benefits {{ city_name }} businesses - 3-4 sentences",
  
  "benefits": [
    "Specific benefit #1 (with ROI angle)",
    "Specific benefit #2 (with risk reduction)",
    "Specific benefit #3 (with scalability angle)",
    "Specific benefit #4 (with reliability angle)"
  ],
  
  "case_study_title": "A {{ city_name }}-based or similar company story",
  "case_study_company": "{{ city_name }} Tech Company or similar (realistic but not real)",
  "case_study_challenge": "Their specific challenge with {{ service_name }}",
  "case_study_solution": "How ComBrokers solved it",
  "case_study_result": "Specific metric: saved $XXK, improved uptime to X%, scaled to X users, etc.",
  
  "faq": [
    {
      "question": "What's the average cost of {{ service_name }} in {{ city_name }}?",
      "answer": "Provide realistic pricing range based on industry, mention ComBrokers' competitive advantage"
    },
    {
      "question": "How long does {{ service_name }} setup take in {{ city_name }}?",
      "answer": "Specific timeline with mention of ComBrokers' speed advantage"
    },
    {
      "question": "Do you serve businesses in {{ city_name }}?",
      "answer": "Yes, we serve {{ city_name }}, {{ nearby_city_1 }}, {{ nearby_city_2 }}, and all of {{ state_name }}"
    },
    {
      "question": "What makes ComBrokers different from other {{ service_name }} providers?",
      "answer": "We have contracts with 400+ providers = unbeatable pricing, 24/7 local support, custom solutions"
    },
    {
      "question": "Can you help with migration from our current provider?",
      "answer": "Yes, we specialize in smooth migrations with zero downtime and dedicated support team"
    }
  ],
  
  "combrokers_advantages": [
    "Advantage #1: Best pricing (400+ provider contracts)",
    "Advantage #2: Local expertise ({{ city_name }} market knowledge)",
    "Advantage #3: 24/7 support (not 9-5)",
    "Advantage #4: Custom solutions (not one-size-fits-all)"
  ],
  
  "local_insight": "A one-sentence insight about {{ city_name }}'s specific business needs (economy, growth, industry mix)"
}

TONE: Professional, confident, conversational (avoid corporate jargon)
LENGTH: Concise but complete (avoid word-padding)
ACCURACY: All claims must be defensible and industry-standard
```

---

## 2. Headline & CTA Refinement (Claude)

**Use Case:** Optimizing headlines and CTAs for maximum conversion

```
I'm optimizing a landing page for {{ service_name }} in {{ city_name }}, {{ state_code }}.

Current content:
- Headline: "{{ original_headline }}"
- Subheading: "{{ original_subheading }}"
- CTA Button: "{{ original_cta }}"

Target audience: {{ target_audience_description }}
(e.g., "Small business owners, IT managers, operations directors")

TASK: Refine these elements for maximum conversion (form submissions).

REQUIREMENTS:
1. Headline must be specific to {{ city_name }} and {{ service_name }}
2. Include a power word (Best, Most, Only, Proven, etc.)
3. CTA should use action verbs (Schedule, Get, Discover, Save, etc.)
4. Avoid generic language
5. Focus on benefit or urgency

PROVIDE:
{
  "headline": "New, optimized headline (8-12 words)",
  "subheading": "Supporting copy (1 sentence, 10-15 words)",
  "cta_button": "CTA text (3-5 words, action-oriented)",
  "cta_rationale": "Why this works better - 1 sentence"
}

Examples:
WEAK: "Get VoIP Service" 
STRONG: "Cut Phone Costs 40% with Austin's #1 VoIP Provider"

WEAK: "Learn More"
STRONG: "Get Free Quote" or "Schedule 15-Min Consultation"
```

---

## 3. Local Context Generator (Optional)

**Use Case:** When AI doesn't have specific {{ city_name }} context

```
Generate 3-4 local context insights for {{ city_name }}, {{ state_code }} that would be relevant for {{ service_name }} marketing.

Consider:
- Weather patterns (storms, outages, reliability needs)
- Major industries in {{ city_name }}
- Seasonal business challenges
- Local economy facts (population growth, tech hubs, etc.)
- Events that impact business operations

OUTPUT: 
[
  "Weather insight: {{ city_name }} experiences {{ weather_pattern }}, making {{ service_name }} with {{ X% uptime }} reliability critical",
  "Industry insight: {{ city_name }} has {{ X }} tech companies, all requiring {{ service_name }} for growth",
  "Event insight: During {{ event_name }} ({{ month }}), {{ city_name }} sees {{ impact }} in demand for {{ service_name }}",
  "Economy insight: {{ city_name }}'s {{ X }}% business growth requires {{ service_name }} infrastructure scaling"
]
```

---

## 4. FAQ Expansion Prompt

**Use Case:** Creating 10+ FAQ items for deeper SEO

```
Create 10 SEO-optimized FAQ questions and answers for {{ service_name }} in {{ city_name }}.

Requirements:
1. Questions should target common search queries
2. Answers must be 2-3 sentences with specific benefits
3. Include ComBrokers' unique angle in answers
4. Mix transactional (pricing, setup) and informational (why) questions
5. Some Q&A should mention {{ city_name }} specifically

Search Intent Mix:
- 3 Transactional ("How much...", "How long...", "Where...")
- 4 Informational ("What is...", "Why do...", "Benefits of...")
- 3 Comparison ("Why not...", "Difference between...")

RETURN as JSON array:
{
  "faqs": [
    {
      "question": "Q1",
      "answer": "A1"
    },
    ...
  ]
}
```

---

## 5. Case Study Generator

**Use Case:** Creating realistic but non-real case studies

```
Create a realistic but fictional case study for a {{ city_name }}-based business using {{ service_name }}.

IMPORTANT: Company name should NOT be a real company. Create a realistic fictional name.

CONTEXT:
- City: {{ city_name }}
- Service: {{ service_name }}
- Company Size: {{ company_size }} (e.g., 50-100 employees)
- Industry: {{ industry }} (e.g., Tech, Finance, Manufacturing)

REQUIREMENTS:
1. Company name sounds realistic but is fictional
2. Challenge must be specific to {{ city_name }} or {{ service_name }}
3. Solution timeline should be realistic (2-4 weeks)
4. Results must be quantifiable and believable
5. Avoid exaggerated claims

OUTPUT:
{
  "company_name": "{{ City }} {{ Industry }} Co.",
  "company_size": "X employees",
  "industry": "{{ industry }}",
  "location": "{{ city_name }}, {{ state_code }}",
  
  "challenge": "Specific business problem (2-3 sentences)",
  "challenge_impact": "Cost or risk (e.g., '$50K/month in downtime, 40% slower than competitors')",
  
  "solution": "How ComBrokers fixed it (2-3 sentences)",
  "implementation": "Timeline and process (e.g., '2-week deployment with zero downtime')",
  
  "results": {
    "metric_1": "Specific result with number (e.g., '60% cost reduction')",
    "metric_2": "Specific result with number (e.g., '99.99% uptime')",
    "metric_3": "Specific result with number (e.g., 'Support reduced IT team by 30%')"
  },
  
  "quote": "1-2 sentence testimonial-style quote from fictional owner/manager"
}
```

---

## 6. Provider Comparison Generator

**Use Case:** Creating realistic but non-branded competitor comparison

```
Create a provider comparison table for {{ service_name }} in {{ city_name }}.

REQUIREMENTS:
1. Compare 4-5 providers (use generic names like "Provider A", "Provider B")
2. ComBrokers always has competitive advantage in key columns
3. Include realistic pricing ranges
4. Support quality should be ComBrokers' strong point
5. Avoid naming real competitors (use "National Provider", "Local Provider", etc.)

OUTPUT:
{
  "providers": [
    {
      "name": "National Provider A",
      "speed": "Low",
      "pricing": "$$$$ (High)",
      "support": "Business hours only",
      "setup_time": "4-6 weeks",
      "contract": "3-year minimum"
    },
    {
      "name": "Local Provider B",
      "speed": "Medium",
      "pricing": "$$$ (Moderate)",
      "support": "Limited support",
      "setup_time": "2-3 weeks",
      "contract": "2-year minimum"
    },
    {
      "name": "ComBrokers",
      "speed": "Fast",
      "pricing": "$$ (Best value)",
      "support": "24/7 expert support",
      "setup_time": "1 week",
      "contract": "Flexible terms"
    }
  ],
  "summary": "Why ComBrokers wins: Best pricing + fastest service + dedicated support"
}
```

---

## 7. Local Content Injection Variables

**Use Case:** Dynamic local context based on city data

```
When generating for {{ city_name }}, {{ state_code }}:

INJECT:
- {{ weather_insight }}: "[{{ city_name }}'s {{ seasonal_weather }}] makes reliable {{ service_name }} critical"
- {{ industry_insight }}: "{{ city_name }}'s {{ major_industry }} sector requires..."
- {{ growth_insight }}: "{{ city_name }} is growing {{ growth_rate }}% annually, requiring..."
- {{ event_insight }}: "During {{ event_name }} in {{ month }}, {{ city_name }} sees {{ peak }} demand"
- {{ chamber_insight }}: "Recommended by {{ city_name }} Chamber of Commerce"
- {{ competitor_insight }}: "Unlike competitors, ComBrokers serves {{ nearby_cities }} with local expertise"
- {{ pricing_insight }}: "{{ city_name }} average cost: ${{ low }}-{{ high }}/month, ComBrokers: ${{ competitive }}"
```

---

## 8. A/B Testing Prompt Variants

**Use Case:** Creating variants for testing

**Variant A (Risk Reduction):**
```
Focus the headline on security, reliability, and uptime guarantees.
Emphasize "99.99% uptime", "zero downtime migration", "24/7 support"
```

**Variant B (Cost Savings):**
```
Focus the headline on ROI and cost reduction.
Emphasize "Save $XXK annually", "Best pricing guaranteed", "No hidden fees"
```

**Variant C (Speed/Productivity):**
```
Focus the headline on speed, efficiency, and competitive advantage.
Emphasize "Fastest setup", "Immediate results", "Scale at any time"
```

---

## Testing These Prompts

1. **Start with Prompt #1** (Main Content Generator) to generate 50+ pages
2. **Use Prompt #2** (Headlines) to refine top 20% of pages
3. **Monitor conversion rates** for each variant
4. **Identify winning patterns** (cost vs. speed vs. reliability focus)
5. **Update prompts** based on what converts best

---

## Example API Call (DeepSeek)

```bash
curl -X POST https://api.deepseek.com/v1/chat/completions \
  -H "Authorization: Bearer $DEEPSEEK_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "deepseek-chat",
    "messages": [
      {
        "role": "system",
        "content": "[System prompt from above]"
      },
      {
        "role": "user",
        "content": "[Filled-in prompt with actual city/service data]"
      }
    ],
    "temperature": 0.7,
    "max_tokens": 2500
  }'
```

---

**Last Updated:** January 17, 2026  
**Status:** Ready for Phase 1 Use
