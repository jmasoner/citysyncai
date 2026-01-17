# Timeout Error Troubleshooting - Still Getting 30 Second Timeout

## 🚨 Current Issue

You're still seeing: `cURL error 28: Operation timed out after 30001 milliseconds`

This means the **90-second timeout change hasn't taken effect yet**.

---

## ✅ Step 1: Verify File Upload

**Make sure you uploaded the updated file:**

1. Check file timestamp on server - should be recent
2. Verify `includes/ai-content-engine.php` is the updated version
3. Check line ~350 in the file - should show `'timeout' => 90,`

---

## 🔍 Step 2: Check Server-Level Timeouts

If the file is updated but you still get 30-second timeout, it's likely a **server-level limit**:

### Check PHP Configuration

The server's PHP `max_execution_time` might be limiting requests to 30 seconds.

**Check via WordPress:**
1. Install "Query Monitor" plugin OR
2. Add this temporarily to `wp-config.php`:
   ```php
   ini_set('max_execution_time', 120);
   ```

### Check cURL Timeout

Some servers have cURL timeout limits in `php.ini`:
- `default_socket_timeout = 30` (common default)

---

## 🔧 Step 3: Temporary Workaround

While we investigate, try **reducing the content length** to speed up generation:

### Option A: Use Grok Instead (May Be Faster)
1. Settings → CitySyncAI
2. Change AI Provider to "Grok"
3. Add Grok API key
4. Test preview

### Option B: Reduce DeepSeek Tokens Further
I can reduce `max_tokens` from 1800 to 1200 to make it faster (still ~900-1000 words).

---

## 📋 What I've Already Fixed

✅ Increased WordPress timeout from 30 to 90 seconds  
✅ Added `set_time_limit(120)` to increase PHP execution time  
✅ Reduced DeepSeek max_tokens from 2000 to 1800  
✅ Better error messages

---

## 🔍 Debugging Steps

1. **Verify file uploaded:**
   - Open `includes/ai-content-engine.php` on server
   - Search for `'timeout' => 90` (should be line ~350)
   - If it says `'timeout' => 30`, file wasn't uploaded

2. **Check server PHP limits:**
   - Add this to a test PHP file on server:
     ```php
     <?php phpinfo(); ?>
     ```
   - Look for `max_execution_time` value
   - Look for `default_socket_timeout` value

3. **Try different provider:**
   - Switch to Grok temporarily to see if it's DeepSeek-specific

---

## 💡 Alternative: Use Async Generation

If timeouts persist, we could implement:
- AJAX-based async generation (doesn't block page load)
- Background processing via WP-Cron
- Queue system for bulk generation

---

## 🎯 Next Steps

1. **First:** Verify the updated file is on the server
2. **If yes:** Check server PHP timeout limits
3. **If limits are 30s:** Contact hosting support to increase `max_execution_time`
4. **Temporary:** Try Grok instead of DeepSeek

Let me know what you find and we'll fix it! 🔧

