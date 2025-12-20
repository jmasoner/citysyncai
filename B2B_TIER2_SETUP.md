# B2B Tier 2/3 City Pages Setup

## Overview

CitySyncAI now implements a **hybrid approach** for city pages:
- **Dynamic generation**: Pages auto-create when first accessed
- **Pre-generation**: Top 500 tier 2/3 cities can be pre-generated
- **B2B focused**: All content targets businesses only (no residential)

## Target Market

**Your Ideal Customer:**
- Businesses spending $1,500-$150,000/month on telecom services
- B2B enterprise communication solutions
- NO residential services

**City Strategy:**
- **Tier 1 EXCLUDED**: Major metros (NYC, LA, Chicago, etc.) - too competitive
- **Tier 2 INCLUDED**: Secondary metros (500K+ population)
- **Tier 3 INCLUDED**: Smaller metros/suburbs (100K-500K population)

This is the "Walmart approach" - go where competition is lower and needs are still high.

## How It Works

### 1. Hybrid Page Creation

**On-Demand (Dynamic):**
- When someone visits `/city/austin-tx/`
- If post doesn't exist, it's created automatically
- Content generated via AI (B2B focused)
- Future visits serve from cached post (fast!)

**Pre-Generation (Bulk):**
- Admin tool to generate top 500 tier 2/3 cities
- Creates WordPress posts in batches
- Respects API rate limits
- Progress tracking

### 2. City Tier Classification

**Tier 1** (Excluded):
- Major metros with highest competition
- NYC, LA, Chicago, Houston, Phoenix, etc.
- Population: Usually 1M+

**Tier 2** (Included):
- Secondary metros
- Examples: Austin, Nashville, Raleigh, Omaha
- Population: 500K - 1M

**Tier 3** (Included):
- Smaller metros and suburbs
- Examples: Madison WI, Boise ID, Fort Collins CO
- Population: 100K - 500K

## Setup Instructions

### Step 1: Activate the Plugin

1. Upload and activate CitySyncAI plugin
2. Go to **Settings → CitySyncAI**
3. Configure your Gemini API keys (both primary and secondary)
4. Select **Gemini 1.5 Flash** model (cost-efficient)
5. Enable multi-account rotation

### Step 2: Configure B2B Settings

1. In Settings, ensure:
   - Content type: "overview" (or your preferred type)
   - AI provider: Gemini
   - Schema enabled: Yes
   - All prompts are automatically B2B focused

### Step 3: Pre-Generate Top 500 Cities (Optional but Recommended)

1. Go to **City Pages → Bulk Generator**
2. Select:
   - Number of cities: 500
   - City tiers: Tier 2 and Tier 3 (both checked)
3. Click "Start Bulk Generation"
4. Monitor progress (generates in batches)

**Estimated time with 2 Gemini accounts:**
- 500 cities × ~1 second per city = ~8-10 minutes
- Cost: ~$0.30-0.60 (if using API directly)

### Step 4: Test Dynamic Generation

1. Visit a city page that wasn't pre-generated: `/city/test-city-tx/`
2. Page will auto-create on first visit
3. Future visits will be instant (cached)

## URL Structure

**City Page URLs:**
- Format: `https://yoursite.com/city/city-name-state/`
- Examples:
  - `/city/austin-tx/`
  - `/city/nashville-tn/`
  - `/city/omaha-ne/`

## B2B Content Focus

All generated content emphasizes:
- ✅ Enterprise telecom solutions
- ✅ Business phone systems
- ✅ Cloud services for businesses
- ✅ Unified communications
- ✅ Business internet connectivity
- ✅ Scalability and reliability
- ✅ Cost-effectiveness for businesses

All content explicitly excludes:
- ❌ Residential services
- ❌ Home internet
- ❌ Consumer-focused offerings

## Bulk Generation Best Practices

1. **Start with 100-200 cities** to test
2. **Use batch processing** (built-in, 10 cities at a time)
3. **Monitor API usage** in Settings dashboard
4. **Check generated pages** to ensure quality
5. **Scale up to 500** once satisfied

## Monitoring & Maintenance

### Check Generation Status

- Go to **City Pages → Bulk Generator**
- View progress, completed count, failures
- Continue generation if interrupted

### Review Generated Pages

- Go to **City Pages** in WordPress admin
- Review sample pages for quality
- Edit if needed (standard WordPress editor)

### Regenerate Content

- Edit any city page in WordPress
- Content can be manually updated
- Or delete and let it regenerate on next visit

## Cost Estimate

**Using Gemini 1.5 Flash with 2 accounts:**

**Pre-Generation (500 cities):**
- Input tokens: ~100K tokens
- Output tokens: ~500K tokens
- Cost: ~$0.30-0.60 total

**Ongoing (on-demand generation):**
- Per city: ~$0.001-0.002
- Only pay when page is first accessed
- Cached after first generation (no additional cost)

**Monthly (if generating 1000 new cities/month):**
- ~$1-2/month

## Next Steps

1. ✅ Plugin configured
2. ✅ API keys added
3. ✅ Test generation (1-2 cities)
4. ✅ Pre-generate top 500 tier 2/3 cities
5. ✅ Monitor and optimize

## Troubleshooting

**Pages not generating?**
- Check API keys are configured
- Check rate limits aren't exceeded
- Check error logs in Settings

**Wrong city tiers?**
- Verify city data includes tier classification
- Check bulk generator settings
- Tier 1 cities are automatically excluded

**Content too generic?**
- Edit prompts in Settings (advanced)
- Manually edit generated pages
- Adjust content type (overview/services/testimonials)

---

**Ready to go!** Start with a small batch test, then scale up to 500 cities.

