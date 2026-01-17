# Gemini Account Setup Guide

## Your Current Setup

You have **two paid Gemini accounts**:
- **Account 1**: $19.95/month
- **Account 2**: $24.95/month

## Capacity Analysis

### Option A: Using Subscription Daily Limits (If Applicable)

If your accounts use daily prompt limits:
- **Account 1**: ~100 prompts/day
- **Account 2**: ~100 prompts/day
- **Combined**: ~200 prompts/day
- **19,000 cities**: ~95 days (3+ months)
- **Cost**: $0 additional (already paid)

### Option B: Using Gemini API Directly (RECOMMENDED)

If you have API access (not just subscription prompts):
- **Much faster**: Can generate all cities in hours
- **Much cheaper**: ~$6-12 total one-time cost
- **Use Gemini 1.5 Flash**: 40x cheaper than Pro

**Recommended Model**: Gemini 1.5 Flash
- Fast and cost-effective
- Perfect for bulk content generation
- ~$0.075 per 1M input tokens, $0.30 per 1M output tokens

**Cost Estimate**:
- 19,000 cities × ~1,200 tokens = 22.8M tokens
- Total cost: **~$6-12** (one-time)

## Setup Instructions

### Step 1: Get Your API Keys

1. Go to [Google AI Studio](https://makersuite.google.com/app/apikey)
2. Sign in with each account
3. Generate API keys for both accounts
4. Copy both keys

### Step 2: Configure in Plugin

1. Go to **Settings → CitySyncAI**
2. Under "API Keys" section:
   - **Primary Key**: Paste first API key
   - **Secondary Key**: Paste second API key
   - **Enable Multi-Account Rotation**: Check this box
3. Under "Gemini Model": Select **Gemini 1.5 Flash** (recommended for cost efficiency)
4. Click "Save Changes"

### Step 3: How It Works

With multi-account enabled:
- Plugin automatically rotates between both keys
- Doubles your throughput capacity
- Balances load between accounts
- If one key fails, automatically tries the other

## Capacity with Your Accounts

### Using Both Accounts Together:

**With API Access**:
- Rate limit: 60 requests/minute per key = 120 requests/minute combined
- 19,000 cities ÷ 120 = ~158 minutes = **~2.5 hours** to complete all
- Cost: ~$6-12 total

**With Subscription Limits**:
- 200 prompts/day combined
- 19,000 cities ÷ 200 = **95 days**
- Cost: $0 additional (already paid monthly fees)

## Recommendations

1. **Check if you have API access** (not just subscription prompts)
   - API access = pay per use, no daily limits
   - Subscription prompts = fixed daily limits

2. **If you have API access**: 
   - Use Gemini 1.5 Flash model (set in settings)
   - Enable multi-account rotation
   - Generate all cities in hours for ~$6-12

3. **If only subscription prompts**:
   - Enable multi-account rotation
   - Generate ~200 cities/day
   - Complete in ~3 months
   - No additional cost

## Next Steps

The plugin now supports:
- ✅ Multi-account rotation
- ✅ Model selection (Flash/Pro)
- ✅ Automatic key rotation
- ✅ Load balancing between accounts

Just configure your keys and start generating!


