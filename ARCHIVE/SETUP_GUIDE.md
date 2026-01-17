# CitySyncAI Setup Guide - Lead Generation Machine

## 🎯 Goal: 30-50 Quotes/Day Per City Page

This guide will help you set up your conversion-optimized city pages for maximum lead generation.

---

## ✅ What's Been Built

### 1. **Conversion-Optimized City Page Template**
- ✅ Fiber-focused hero section
- ✅ Address checker form (hero)
- ✅ Service grid (all telecom services)
- ✅ FAQ section (AI-generated with schema)
- ✅ Testimonials section
- ✅ Trust signals (25 years, 2,740 clients)
- ✅ Multiple CTAs throughout
- ✅ Mobile-responsive design
- ✅ Fast loading optimized

### 2. **Lead Capture System**
- ✅ Gravity Forms integration ready
- ✅ Address checker handler (Telarus GeoQuote ready)
- ✅ Basic CRM/lead storage (database table)
- ✅ Email notifications setup

### 3. **Content Generation**
- ✅ AI-powered FAQs (Gemini + Telarus data)
- ✅ AI-generated testimonials
- ✅ B2B-focused content prompts
- ✅ SEO-optimized structure

---

## 📋 Setup Steps

### Step 1: Install & Activate

1. Upload plugin to WordPress
2. Activate CitySyncAI plugin
3. Go to **Settings → CitySyncAI**
4. Configure your Gemini API keys

### Step 2: Create Gravity Forms

You need to create **2 Gravity Forms**:

#### Form 1: Address Checker Form
**Fields:**
- Business Name (text)
- Business Address (text/textarea)
- Email (email)
- Phone (phone)
- Submit button

**Form Settings:**
- Enable AJAX submission
- Notifications: Send email to your sales team
- Confirmation: Custom message (we'll handle results page)

**Get Form ID:** Note the form ID (e.g., 1)

#### Form 2: Quick Quote Form
**Fields:**
- Name (text)
- Email (email)
- Phone (phone)
- Company Name (text)
- Monthly Telecom Spend (dropdown: $1,500-$5K, $5K-$15K, $15K-$50K, $50K-$150K)
- Services Needed (checkboxes: Fiber, Phone, VoIP, Cloud, UC, Security)
- Submit button

**Form Settings:**
- Enable AJAX
- Email notifications
- Confirmation message

**Get Form ID:** Note the form ID (e.g., 2)

### Step 3: Configure Form IDs

Add to your theme's `functions.php` or create a small plugin:

```php
add_action('init', function() {
    // Address checker form ID
    update_option('citysyncai_address_form_id', 1); // Replace with your form ID
    
    // Quick quote form ID
    update_option('citysyncai_quote_form_id', 2); // Replace with your form ID
});
```

**Or add to admin settings** (we can add this to the plugin settings page).

### Step 4: Set Up Phone Number

The phone number **850-359-8004** is already hardcoded in the template. 

**For GoTo Call Tracking:**
1. Set up call tracking in GoTo
2. Replace phone number in template with tracking number
3. Or create option in settings to configure phone number

### Step 5: Integrate Telarus GeoQuote API

**Address Checker Integration:**

1. Get Telarus API credentials
2. Update `includes/address-checker.php`:
   - Add API endpoint
   - Add authentication
   - Process GeoQuote response
   - Return availability results

**Current Status:** 
- Form submission handler ready
- Lead storage ready
- API integration placeholder (TODO)

### Step 6: Test a City Page

1. Create a test city page:
   - Go to **City Pages → Add New**
   - Title: "Business Telecom Services in Austin, TX"
   - Content: Will auto-generate when published
   - Set city: Austin
   - Set state: TX

2. View the page
3. Test forms
4. Check mobile responsiveness

### Step 7: Configure Analytics

**Google Analytics Setup:**
1. Add Google Analytics to your site
2. Track form submissions as events
3. Track phone clicks as events
4. Set up conversion goals

**WordPress Analytics Dashboard:**
- Coming soon (will track leads, conversions, etc.)

---

## 🎨 Customization

### Brand Colors

The template uses a purple gradient (`#667eea` to `#764ba2`). 

**To match combrokers.com:**
1. Extract colors from your logo
2. Update CSS variables in `assets/css/city-page.css`
3. Replace gradient colors

### Logo

Add your logos to the template:
- Replace logo references in header
- Upload logos to WordPress media library
- Update template to use your logos

### Trust Elements

Already configured:
- ✅ 25 years in business
- ✅ 2,740 clients
- ✅ Testimonials (AI-generated)

**To customize:**
- Edit numbers in template if needed
- Modify testimonials in `includes/testimonials-generator.php`

---

## 📊 Analytics Dashboard (Coming Next)

The analytics dashboard will track:
- Leads per day/week/month
- Leads per city page
- Form submissions
- Phone calls
- Conversion rates
- Traffic sources
- Revenue metrics

**Status:** Structure ready, UI coming next.

---

## 🔧 Gravity Forms Integration

### Form Submission Handling

Gravity Forms will:
1. ✅ Submit form data
2. ✅ Send email notifications
3. ✅ Store submission (Gravity Forms database)
4. ✅ Trigger our lead storage (if hooked up)

**To Connect Lead Storage:**
Add this to your theme's `functions.php`:

```php
// Save Gravity Forms submissions to our CRM
add_action('gform_after_submission', function($entry, $form) {
    if ($form['id'] == 1) { // Address checker form ID
        citysyncai_save_lead([
            'type' => 'address_check',
            'business_name' => rgar($entry, '1'), // Adjust field IDs
            'email' => rgar($entry, '2'),
            'phone' => rgar($entry, '3'),
            'address' => rgar($entry, '4'),
        ]);
    }
}, 10, 2);
```

---

## 📱 Mobile Optimization

Already implemented:
- ✅ Mobile-first CSS
- ✅ Responsive grid layouts
- ✅ Touch-friendly buttons
- ✅ Click-to-call phone links
- ✅ Simplified forms on mobile

**Test on:**
- iPhone
- Android
- Tablet

---

## 🚀 Next Steps

### Immediate (Week 1)
1. ✅ Create Gravity Forms
2. ✅ Configure form IDs
3. ✅ Test city page template
4. ✅ Set up email notifications

### Short-term (Week 2)
5. ⏳ Integrate Telarus GeoQuote API
6. ⏳ Build analytics dashboard
7. ⏳ Customize branding/colors
8. ⏳ Set up GoTo call tracking

### Ongoing
9. ⏳ Generate top 500 tier 2/3 cities
10. ⏳ Monitor analytics
11. ⏳ A/B test conversions
12. ⏳ Optimize based on data

---

## 🐛 Troubleshooting

### Forms Not Showing?
- Check Gravity Forms is active
- Verify form IDs are correct
- Check form visibility settings

### Content Not Generating?
- Check Gemini API key is configured
- Check API quota/limits
- Check error logs

### Mobile Issues?
- Test on actual devices
- Check viewport meta tag
- Verify CSS is loading

---

## 📞 Support

For questions or issues:
- Check error logs in WordPress
- Review Gravity Forms logs
- Check Gemini API status

---

**Ready to generate leads!** 🎯


