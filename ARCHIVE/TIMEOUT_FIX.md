# Timeout Error Fix - DeepSeek API

## 🐛 Problem

DeepSeek API requests were timing out after 30 seconds with error:
```
cURL error 28: Operation timed out after 30001 milliseconds
```

## ✅ Solution Applied

### 1. Increased Timeout
- **Before:** 30 seconds
- **After:** 90 seconds
- **Why:** DeepSeek can take longer for comprehensive content generation

### 2. Reduced Token Limit
- **Before:** 2000 max_tokens
- **After:** 1800 max_tokens  
- **Why:** Slightly faster generation, still produces 1200-1500 words (plenty for SEO)

### 3. Better Error Messages
- Added specific timeout error handling
- Provides helpful troubleshooting tips
- Suggests trying different providers if timeouts persist

### 4. Request Optimization
- Added `'stream' => false` to avoid streaming delays
- Added `'blocking' => true` to ensure complete response

---

## 📤 Files Updated

**`includes/ai-content-engine.php`**
- Increased timeout from 30 to 90 seconds
- Reduced DeepSeek max_tokens from 2000 to 1800
- Added better timeout error handling
- Applied to both `citysyncai_call_deepseek()` and `citysyncai_call_deepseek_custom()`

---

## 🧪 Testing

After uploading the updated file:

1. **Go to:** Settings → CitySyncAI
2. **Scroll to:** AI Preview section
3. **Click:** Refresh or wait for auto-preview
4. **Expected:** Content should generate within 90 seconds
5. **If still timing out:** Try Grok or Gemini instead

---

## 💡 Alternative Solutions

If timeouts persist:

1. **Try Grok** (may be faster)
2. **Try Gemini** (if you have tokens)
3. **Check server network** connectivity
4. **Contact DeepSeek support** if API is consistently slow
5. **Reduce max_tokens further** to 1500 if needed

---

## 📝 Notes

- 90 seconds should be sufficient for most DeepSeek API calls
- Timeout only affects preview - actual city page generation uses same timeout
- If you consistently get timeouts, consider switching to a faster provider

---

**Upload the updated `includes/ai-content-engine.php` file to your server and test again!** ✅

