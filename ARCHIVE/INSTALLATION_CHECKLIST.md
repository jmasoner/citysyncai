# CitySyncAI Installation Checklist

## ✅ Pre-Installation Requirements

- [ ] WordPress installation (version 6.0+)
- [ ] Web server (local or remote)
- [ ] FTP/SSH access (for remote) OR local WordPress environment
- [ ] WordPress admin access

---

## 📦 Installation Steps

### Step 1: Prepare the Plugin

1. **Ensure all files are in the plugin folder:**
   ```
   citysyncai/
   ├── citysyncai.php (main file)
   ├── includes/
   ├── templates/
   ├── assets/
   └── ... (all other files)
   ```

2. **Plugin folder name:** `citysyncai` (must match)

### Step 2: Upload to WordPress

**Option A: Via FTP/SFTP (Production/Staging)**
1. Connect to your web server
2. Navigate to `/wp-content/plugins/`
3. Upload the entire `citysyncai` folder
4. Ensure folder permissions are correct (755 for folders, 644 for files)

**Option B: Via WordPress Admin (If you zip it)**
1. Zip the `citysyncai` folder → `citysyncai.zip`
2. Go to WordPress Admin → Plugins → Add New
3. Upload Plugin → Choose File → Select `citysyncai.zip`
4. Install Now → Activate Plugin

**Option C: Via WP-CLI (If available)**
```bash
cd /path/to/wordpress/wp-content/plugins
# Copy your plugin folder here
wp plugin activate citysyncai
```

### Step 3: Activate the Plugin

1. Go to **WordPress Admin → Plugins**
2. Find **CitySyncAI**
3. Click **Activate**

**After activation, you should see:**
- New menu item: **City Pages** in admin sidebar
- Settings page: **Settings → CitySyncAI**

---

## ⚙️ Initial Configuration (Critical First Steps)

### Step 4: Configure API Keys

1. Go to **Settings → CitySyncAI**
2. Scroll to **API Keys** section
3. Enter your **Gemini API Key (Primary)**
   - Get your key: https://makersuite.google.com/app/apikey
   - Paste it in the "Google Gemini API Key (Primary)" field
4. (Optional) Enter **Gemini API Key (Secondary)**
   - Second account for more capacity
5. Enable **Multi-Account Rotation** (if using 2 keys)
6. Select **Gemini Model**: Choose "Gemini 1.5 Flash" (cost-efficient)
7. Click **Save Changes**

**⚠️ IMPORTANT:** Without API keys, pages won't generate content!

### Step 5: Configure Basic Settings

1. Still in **Settings → CitySyncAI**
2. Set **AI Provider**: Gemini (or your preferred)
3. Set **Default Content Type**: Overview
4. Enable **Enable AI Content**: Check this box
5. Enable **Enable Schema Injection**: Check this box
6. Click **Save Changes**

---

## 🧪 Test the Plugin

### Step 6: Create Your First City Page

1. Go to **City Pages → Create Cities**
2. You should see the three pre-filled cities:
   - Walla Walla, WA
   - Andalusia, AL
   - Destin, FL
3. Click **"Create: Walla Walla WA, Andalusia AL, Destin FL"** button
4. Wait for pages to generate (may take 30-60 seconds each)
5. You should see success messages with links

### Step 7: View the Generated Pages

1. Click the "View Page" links from the creation results
2. OR go to **City Pages** to see all created pages
3. Verify:
   - Hero section displays
   - Content is generated
   - Forms are visible (even if not connected yet)
   - Mobile responsive design

---

## 🔧 Next Steps After Installation

### Step 8: Set Up Gravity Forms

1. Install/activate **Gravity Forms** plugin (you own it)
2. Create **Address Checker Form** (Form ID: 1)
3. Create **Quick Quote Form** (Form ID: 2)
4. Configure form IDs in settings (or we'll add this to admin)

### Step 9: Customize Branding

1. Add your logos to WordPress Media Library
2. Update template to use your logos
3. Match brand colors (update CSS)

### Step 10: Connect Telarus GeoQuote API

1. Get Telarus API credentials
2. Update `includes/address-checker.php` with API endpoint
3. Test address checker functionality

---

## 🐛 Troubleshooting

### Plugin doesn't appear in admin?
- Check plugin folder is in `/wp-content/plugins/`
- Check `citysyncai.php` exists and is valid PHP
- Check WordPress error logs
- Ensure WordPress version 6.0+

### Settings page not accessible?
- Check plugin is activated
- Clear browser cache
- Check for PHP errors in WordPress debug log

### Pages not generating content?
- Verify API keys are entered correctly
- Check API key is valid (test at Google AI Studio)
- Check error logs: WordPress Admin → Tools → Site Health → Info → Errors
- Verify Gemini API quota isn't exceeded

### Template not loading?
- Check template file exists: `templates/single-citysyncai_city.php`
- Verify rewrite rules: Go to Settings → Permalinks → Save (this flushes rules)
- Check theme compatibility

---

## ✅ Verification Checklist

After installation, verify:

- [ ] Plugin appears in Plugins list
- [ ] Plugin is activated
- [ ] "City Pages" appears in admin menu
- [ ] "Settings → CitySyncAI" page loads
- [ ] API keys can be saved
- [ ] Can create a test city page
- [ ] Generated page displays correctly
- [ ] Mobile responsive works
- [ ] Forms appear (even if not functional yet)

---

## 📞 Quick Reference

**Plugin Location:** `/wp-content/plugins/citysyncai/`

**Main Files:**
- `citysyncai.php` - Main plugin file
- `includes/` - All functionality
- `templates/` - Page templates
- `assets/` - CSS/JS

**Admin Pages:**
- Settings: `Settings → CitySyncAI`
- City Pages: `City Pages → All City Pages`
- Create Cities: `City Pages → Create Cities`
- Bulk Generator: `City Pages → Bulk Generator`

---

**Ready to install!** Follow these steps and you'll be generating city pages in no time. 🚀


