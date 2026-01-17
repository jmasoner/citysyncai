# Clear WordPress Transient Cache - Fix Grok Error

## 🎯 The Real Problem!

The error is **cached in WordPress transients** (30-day cache). Even though the code is fixed, the old error message is still being served from cache!

---

## ✅ Quick Fix: Clear the Cache

The admin preview uses WordPress transients with a 30-day cache. We need to clear it.

### Option 1: Add This to Your Theme's functions.php (Temporary)

Add this temporarily to clear the cache:

```php
// Temporary: Clear CitySyncAI preview cache
add_action('admin_init', function() {
    if (isset($_GET['clear_citysyncai_cache'])) {
        global $wpdb;
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_citysyncai_%'");
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_citysyncai_%'");
        echo '<div class="notice notice-success"><p>Cache cleared!</p></div>';
    }
});
```

Then visit: `wp-admin/options-general.php?page=citysyncai&clear_citysyncai_cache=1`

**Remember to remove this code after!**

---

### Option 2: Use a Cache Clearing Plugin

1. Install "Transients Manager" plugin (or similar)
2. Search for transients containing "citysyncai"
3. Delete them
4. Test preview again

---

### Option 3: Database Direct Query (Advanced)

If you have database access:

```sql
DELETE FROM wp_options WHERE option_name LIKE '_transient_citysyncai_%';
DELETE FROM wp_options WHERE option_name LIKE '_transient_timeout_citysyncai_%';
```

(Replace `wp_options` with your actual table prefix if different)

---

### Option 4: Code Fix (I Just Updated)

I've updated the admin settings to automatically clear the cache before generating preview, so it will always be fresh. **Upload the updated `includes/admin-settings.php` file** and it will fix this automatically.

---

## 📋 What I Fixed

Updated `includes/admin-settings.php` to:
- **Automatically clear the cached preview** before generating
- Always show fresh content (no stale cached errors)

---

## 🚀 After Clearing Cache

1. The preview will regenerate fresh
2. It will use the updated `grok-3` model
3. Should work correctly now!

---

## 📤 Upload This File

**`includes/admin-settings.php`** - I've updated it to auto-clear cache on preview.

---

**The issue is WordPress caching the old error - clear the cache and it will work!** ✅

