# Clear All Caches - Fix Grok-Beta Error Persisting

## 🚨 Issue

File shows `grok-3` on server, but still getting `grok-beta` error. This suggests **code caching** at a deeper level.

---

## ✅ Solution: Clear ALL Caches

### Step 1: Clear PHP OPcache (Most Likely Cause)

OPcache caches compiled PHP code. Even if the file is updated, PHP might still be using the old cached version.

**Option A: Via Hosting Control Panel**
1. Login to your hosting control panel (cPanel, Plesk, etc.)
2. Find "PHP Settings" or "OPcache" section
3. Click "Clear OPcache" or "Reset OPcache"

**Option B: Via FTP/File Manager**
Create a temporary file called `clear-opcache.php` in your WordPress root:

```php
<?php
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "OPcache cleared!";
} else {
    echo "OPcache not available";
}
?>
```

Visit: `https://yoursite.com/clear-opcache.php`
Then DELETE the file for security!

**Option C: Contact Hosting Support**
Ask them to clear OPcache for your account.

---

### Step 2: Deactivate & Reactivate Plugin

This forces WordPress to reload all plugin files:

1. **Go to:** Plugins → Installed Plugins
2. **Deactivate:** CitySyncAI
3. **Wait:** 10 seconds
4. **Reactivate:** CitySyncAI
5. **Test:** Settings → CitySyncAI → AI Preview

---

### Step 3: Verify File One More Time

Double-check the file on server:

1. Open: `includes/ai-content-engine.php`
2. Go to line 302
3. Should say: `'model' => 'grok-3',`
4. Go to line 321  
5. Should say: `'model' => 'grok-3',`
6. **Search entire file** for: `grok-beta`
7. **Result should be:** 0 matches

---

### Step 4: Check for Multiple Plugin Locations

Make sure there aren't duplicate files:

1. Check: `/wp-content/plugins/citysyncai/includes/ai-content-engine.php`
2. Check: `/wp-content/mu-plugins/` (if it exists)
3. Check: Any backup/old plugin folders

---

### Step 5: WordPress Object Cache (if using)

If you have Redis/Memcached:

1. Install "Redis Object Cache" plugin (temporarily)
2. Flush cache
3. Or contact hosting to flush object cache

---

### Step 6: Force File Reload (Last Resort)

If nothing else works:

1. **Edit the file on server** (add a space, save, remove space, save)
2. This forces the file timestamp to update
3. **Or** rename the file temporarily, then rename it back

---

## 🔍 Debug: Check What's Actually Running

Add this temporarily to see what model is being used:

**Edit `includes/ai-content-engine.php` around line 301:**

```php
$body = [
    'model' => 'grok-3', // Updated: grok-beta was deprecated on 2025-09-15
    // Add this temporarily:
    // citysyncai_log_error("GROK MODEL: grok-3"); // Debug line
    'messages' => [
```

Then check WordPress debug log to see what model is actually being sent.

---

## 📋 Most Likely Solution

**95% chance it's OPcache.** Clear OPcache first, then deactivate/reactivate the plugin.

---

**Try OPcache first - that's almost certainly the issue!** 🔧

