# How to Use CitySyncAI - Complete Guide

## 🎯 Overview

CitySyncAI generates SEO-optimized city pages for your WordPress site. Each page is designed to be a lead generation machine with multiple forms, CTAs, and conversion-optimized content.

---

## 🚀 Quick Start: Creating Your First City Page

### Method 1: Create Single City Page (Recommended for Testing)

1. **Go to WordPress Admin**
2. **Navigate to:** City Pages → Create Cities
3. **You'll see:** Three cities pre-filled (Walla Walla WA, Andalusia AL, Destin FL)
4. **Click:** "Create: Walla Walla WA, Andalusia AL, Destin FL" button
5. **Wait:** Pages generate automatically (30-60 seconds each)
6. **Result:** You'll see success messages with links to view each page

### Method 2: Create Custom City

1. **Go to:** City Pages → Create Cities
2. **In the textarea**, enter cities in format: `City Name, State` (one per line)
   ```
   Austin, TX
   Nashville, TN
   Omaha, NE
   ```
3. **Click:** "Create City Pages" button
4. **Wait for generation**
5. **View:** Click "View Page" links to see your new pages

### Method 3: Bulk Generate Top 500 Tier 2/3 Cities

1. **Go to:** City Pages → Bulk Generator
2. **Set:**
   - Number of cities: 500 (or any number)
   - City tiers: Check "Tier 2" and "Tier 3" (both)
3. **Click:** "Start Bulk Generation"
4. **Monitor:** Progress updates in real-time
5. **Result:** 500 city pages created over time (batches of 10)

---

## 📄 What Gets Created

When you create a city page, the plugin:

1. **Generates AI content** (2000+ words, B2B-focused, fiber-focused)
2. **Creates a WordPress post** (stored in database)
3. **Adds metadata** (city name, state, population, coordinates)
4. **Generates FAQs** (AI-powered, with schema markup)
5. **Creates testimonials** (AI-generated social proof)
6. **Inserts schema markup** (for SEO)
7. **Creates URL:** `/city/city-name-state/` (e.g., `/city/austin-tx/`)

---

## 🎨 Page Structure

Each city page includes:

### Hero Section (Above the Fold)
- City name and "Business Fiber Internet in [City]" headline
- Address checker form
- Value propositions (same-day, free, all carriers)
- Trust signals (25 years, 2,740 clients)
- Phone number: 850-359-8004

### Main Content
- **Fiber-focused introduction** (AI-generated)
- **Services grid** (6 telecom services with icons)
- **Why Choose Us section** (local benefits)
- **Testimonials** (AI-generated)
- **FAQ section** (8-10 questions with schema)

### Sidebar
- Quick quote form (sticky - follows scroll)
- Quick facts about the city
- Contact information
- Trust badges

### Final CTA
- Large call-to-action section
- Address checker form again
- Phone number

---

## ⚙️ Configuration Settings

### Before Creating Pages, Configure:

1. **Settings → CitySyncAI → API Keys**
   - Add your AI provider API key (Gemini, DeepSeek, or Grok)
   - If using 2 Gemini accounts, add both and enable rotation

2. **Settings → CitySyncAI → AI Provider**
   - Select: Gemini, DeepSeek, or Grok

3. **Settings → CitySyncAI → Gemini Model** (if using Gemini)
   - Start with: "Gemini Pro" (most compatible)
   - Later try: "Gemini 1.5 Flash" (cheaper)

4. **Settings → CitySyncAI → Enable AI Content**
   - Must be checked ✓

5. **Settings → CitySyncAI → Enable Schema Injection**
   - Should be checked ✓

---

## 🔄 Managing Created Pages

### View All City Pages

1. **Go to:** City Pages → All City Pages
2. **See:** List of all generated city pages
3. **Edit:** Click any page to edit (standard WordPress editor)
4. **Delete:** Trash pages you don't need

### Edit a City Page

1. **Go to:** City Pages → All City Pages
2. **Click:** The city page you want to edit
3. **Edit content** in WordPress editor
4. **Update:** Click "Update" to save

### Regenerate Content

1. **Edit the city page**
2. **Delete the content**
3. **Save** (this triggers regeneration on next view)
4. **OR:** Delete the page and recreate it

---

## 🌐 Viewing Pages on Frontend

### URL Format

Pages are accessible at:
```
https://yoursite.com/city/city-name-state/
```

Examples:
- `/city/austin-tx/`
- `/city/walla-walla-wa/`
- `/city/destin-fl/`

### How to View

1. **After creation**, click "View Page" link
2. **OR** go to City Pages → All City Pages → Click "View" link
3. **OR** visit the URL directly

---

## 📊 Bulk Generation Strategy

### Tier 2/3 Cities Only (Your Strategy)

The bulk generator automatically:
- ✅ Excludes Tier 1 cities (NYC, LA, Chicago, etc.)
- ✅ Includes Tier 2 cities (500K+ population - secondary metros)
- ✅ Includes Tier 3 cities (100K-500K - smaller metros)

This matches your "Walmart approach" - targeting less competitive markets.

### Recommended Process

1. **Start Small:** Generate 10-20 cities first to test
2. **Review:** Check quality, design, forms working
3. **Adjust:** Make any needed changes
4. **Scale Up:** Generate 100-200 cities
5. **Monitor:** Check analytics, leads, conversions
6. **Full Scale:** Generate all 500 tier 2/3 cities

---

## 🔧 Forms Setup (Critical for Lead Generation)

### Before Pages Work Fully, You Need:

1. **Gravity Forms Installed** (you own it)
2. **Create Address Checker Form** (Form ID: 1)
   - Fields: Business Name, Address, Email, Phone
   - Enable AJAX
   - Set email notifications

3. **Create Quick Quote Form** (Form ID: 2)
   - Fields: Name, Email, Phone, Company, Monthly Spend, Services
   - Enable AJAX
   - Set email notifications

4. **Configure Form IDs** (when we add this to admin, or via code)

---

## 💰 Cost Management

### DeepSeek (Recommended for Bulk - Very Affordable)
- **Cost:** ~$0.14 per 1M input tokens, $0.28 per 1M output tokens
- **Per city page:** ~$0.001-0.002 (very cheap!)
- **500 cities:** ~$0.50-1.00 total
- **Get API key:** https://platform.deepseek.com/

### Grok (Alternative)
- **Cost:** Check x.ai pricing
- **Get API key:** https://x.ai/api

### Gemini (When you have tokens)
- **Free tier:** 1,500 requests/day
- **Paid accounts:** Varies by plan
- **Best for:** Testing and small batches

---

## ✅ Best Practices

### Before Bulk Generation

1. ✅ Test with 1-2 cities first
2. ✅ Verify content quality
3. ✅ Check mobile responsiveness
4. ✅ Test forms work
5. ✅ Verify API keys work
6. ✅ Check SEO/schema markup

### During Generation

1. ✅ Monitor API usage/quota
2. ✅ Check for errors in logs
3. ✅ Verify pages are created correctly
4. ✅ Test a few random pages

### After Generation

1. ✅ Review analytics
2. ✅ Check form submissions
3. ✅ Monitor lead quality
4. ✅ Optimize based on data
5. ✅ A/B test different approaches

---

## 🐛 Troubleshooting

### Pages Not Generating?

- Check API key is correct
- Check API provider is selected correctly
- Check "Enable AI Content" is checked
- Check error logs (WordPress → Tools → Site Health)

### Content Looks Generic?

- Edit prompts in code (advanced)
- Manually edit pages after generation
- Regenerate with different content type

### Forms Not Working?

- Gravity Forms must be installed/active
- Forms must be created (Address Checker + Quick Quote)
- Form IDs must match (will be configurable)

### Mobile Issues?

- Test on actual devices
- Check CSS is loading
- Verify responsive design

---

## 📈 Expected Results

With proper setup and 500 tier 2/3 city pages:

- **Traffic:** 1000-2000 visitors/month per page (with good SEO)
- **Leads:** 30-50 quote requests/month per page (3-5% conversion)
- **Total:** 500 pages = 15,000-25,000 leads/month potential

**Your goal:** 30-50 quotes/day = 900-1,500/month per page
- Requires higher traffic (10K-20K/month) OR
- Higher conversion rate (5-10%)

---

## 🎯 Next Steps After Setup

1. ✅ Configure API provider (DeepSeek or Grok)
2. ✅ Test with 1-2 cities
3. ✅ Set up Gravity Forms
4. ✅ Create test city pages
5. ✅ Verify everything works
6. ✅ Generate 100-200 cities (test batch)
7. ✅ Monitor and optimize
8. ✅ Scale to 500 cities

---

**You're ready to start generating!** 🚀

