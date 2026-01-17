# Server Update Instructions - Gemini API Fix

## Files That Need to Be Updated

After fixing the Gemini API model issue, you need to update these 2 files on your server:

### Files to Update:

1. **`includes/ai-content-engine.php`**
   - Fixed Gemini API model names
   - Added proper API version handling (v1beta for gemini-pro, v1 for newer models)
   - Improved error handling

2. **`includes/admin-settings.php`**
   - Changed default model to "gemini-pro" (more compatible)
   - Updated model descriptions and recommendations

---

## How to Update

### Option 1: Manual File Upload (Recommended)

1. **Download the updated files from your local project:**
   - `includes/ai-content-engine.php`
   - `includes/admin-settings.php`

2. **Upload to server via FTP/SFTP/File Manager:**
   - Navigate to: `/wp-content/plugins/citysyncai/`
   - Upload `includes/ai-content-engine.php` (overwrite existing)
   - Upload `includes/admin-settings.php` (overwrite existing)

3. **Verify files uploaded correctly:**
   - Check file dates/timestamps updated
   - Check file sizes match local versions

### Option 2: Via Git (If server has Git)

If your server has Git access:

```bash
# SSH into your server
ssh your-server

# Navigate to plugin directory
cd /path/to/wp-content/plugins/citysyncai/

# Pull latest changes
git pull origin main
```

### Option 3: Via Web Disk (If you have Web Disk set up)

1. Map Web Disk as a network drive
2. Navigate to plugin folder
3. Copy updated files from local to Web Disk location
4. Overwrite existing files

---

## After Updating

1. **Clear any caches** (if you use caching plugins)
2. **Go to Settings → CitySyncAI**
3. **Change Gemini Model to "Gemini Pro"** (if not already set)
4. **Save Changes**
5. **Test the AI Preview** section at the bottom

---

## Verification

To verify the update worked:

1. Check the AI Preview section in settings
2. It should now generate content (no 404 error)
3. If you still get errors, check:
   - API key is correct
   - Model is set to "Gemini Pro"
   - No PHP errors in WordPress debug log

---

## File Locations

**Local path:**
- `C:\Users\john\OneDrive\MyProjects\citysyncai\includes\ai-content-engine.php`
- `C:\Users\john\OneDrive\MyProjects\citysyncai\includes\admin-settings.php`

**Server path:**
- `/wp-content/plugins/citysyncai/includes/ai-content-engine.php`
- `/wp-content/plugins/citysyncai/includes/admin-settings.php`

---

## Quick Checklist

- [ ] Download/identify the 2 updated files locally
- [ ] Connect to server (FTP/SFTP/File Manager)
- [ ] Navigate to plugin directory
- [ ] Upload `ai-content-engine.php`
- [ ] Upload `admin-settings.php`
- [ ] Verify files updated
- [ ] Test in WordPress admin (Settings → CitySyncAI)
- [ ] Change model to "Gemini Pro" if needed
- [ ] Test AI Preview

---

**That's it!** Once these 2 files are updated, the Gemini API should work correctly.

