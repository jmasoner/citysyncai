# CitySync AI - Page Template Specification

**Purpose:** Define the HTML structure, React components, SEO markup, and form integration for generated landing pages.

---

## Page Structure Overview

Each generated page follows this template structure:

```
Header
  ├─ Navigation
  ├─ Title + Meta
  
Hero Section
  ├─ Problem statement (H1)
  ├─ Value prop
  ├─ CTA button

Local Context Section
  ├─ City stats
  ├─ Weather widget
  ├─ Local events
  ├─ Chamber info

Service Overview
  ├─ H2: Service description
  ├─ Benefits list
  ├─ Local case study

Provider Comparison
  ├─ Table: Providers vs. features
  ├─ ComBrokers differentiation

Lead Capture Form
  ├─ Form fields
  ├─ Validation
  ├─ Submission tracking

FAQ Section
  ├─ Schema markup
  ├─ Accordion design

Footer
  ├─ Links
  ├─ Privacy policy
```

---

## Base HTML Template

```html
<!-- filepath: templates/base.html -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ page_title }} | ComBrokers</title>
  <meta name="description" content="{{ meta_description }}">
  <meta name="keywords" content="{{ meta_keywords }}">
  
  <!-- Open Graph -->
  <meta property="og:title" content="{{ page_title }}">
  <meta property="og:description" content="{{ meta_description }}">
  <meta property="og:type" content="website">
  <meta property="og:url" content="{{ page_url }}">
  <meta property="og:image" content="{{ og_image }}">
  
  <!-- Schema Markup (LocalBusiness + Service) -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "ComBrokers - {{ service_name }} in {{ city }}, {{ state }}",
    "description": "{{ service_description }}",
    "url": "{{ page_url }}",
    "telephone": "+1-800-XXX-XXXX",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "123 Business Ave",
      "addressLocality": "{{ city }}",
      "addressRegion": "{{ state }}",
      "postalCode": "{{ zip_code }}",
      "addressCountry": "US"
    },
    "geo": {
      "@type": "GeoCoordinates",
      "latitude": {{ latitude }},
      "longitude": {{ longitude }}
    },
    "areaServed": {
      "@type": "City",
      "name": "{{ city }}"
    },
    "priceRange": "$"
  }
  </script>
  
  <!-- Service Schema -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Service",
    "name": "{{ service_name }}",
    "description": "{{ service_description }}",
    "provider": {
      "@type": "Organization",
      "name": "ComBrokers",
      "url": "https://combrokers.com"
    },
    "areaServed": "{{ city }}, {{ state }}"
  }
  </script>
  
  <!-- Canonical -->
  <link rel="canonical" href="{{ page_url }}">
  
  <!-- CSS -->
  <link rel="stylesheet" href="/css/main.css">
  
  <!-- Tracking -->
  <script async src="https://cdn.plausible.io/js/plausible.js" 
    data-domain="combrokers.com"></script>
  <meta name="facebook-domain-verification" content="xxx">
</head>

<body>

<!-- Navigation -->
<nav class="navbar">
  <div class="container">
    <a href="/" class="logo">ComBrokers</a>
    <ul class="nav-links">
      <li><a href="/services/voip">VoIP</a></li>
      <li><a href="/services/fiber">Fiber</a></li>
      <li><a href="/services/internet">Internet</a></li>
      <li><a href="/services/cybersecurity">Security</a></li>
      <li><a href="#form" class="cta-nav">Get Quote</a></li>
    </ul>
  </div>
</nav>

<!-- Hero Section -->
<section class="hero" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
  <div class="container">
    <div class="hero-content">
      <h1>{{ hero_headline }}</h1>
      <p class="hero-subheading">{{ hero_subheading }}</p>
      <a href="#form" class="btn btn-primary btn-lg">{{ cta_button_text }}</a>
    </div>
  </div>
</section>

<!-- Local Context Section -->
<section class="local-context bg-light">
  <div class="container">
    <h2>Why {{ city }}, {{ state }} Businesses Choose {{ service_name }}</h2>
    
    <div class="context-grid">
      <div class="context-card">
        <h3>📊 Local Economy</h3>
        <p>{{ city }} has <strong>{{ population }}</strong> residents and 
           <strong>{{ business_count }}</strong> businesses.</p>
      </div>
      
      <div class="context-card">
        <h3>🌤️ Local Weather</h3>
        <p>{{ weather_insight }}. Keep teams connected with reliable {{ service_name }}.</p>
      </div>
      
      <div class="context-card">
        <h3>🎉 Local Events</h3>
        <p>{{ city }} hosts major events like {{ event_name }}. 
           Scale your services for peak demand.</p>
      </div>
      
      <div class="context-card">
        <h3>🏢 Chamber of Commerce</h3>
        <p>Recommended by the {{ city }} Chamber of Commerce. 
           <a href="{{ chamber_url }}" target="_blank">Learn more</a></p>
      </div>
    </div>
  </div>
</section>

<!-- Service Overview -->
<section class="service-overview">
  <div class="container">
    <div class="service-content">
      <h2>{{ service_name }} Solutions for {{ city }}</h2>
      <p>{{ service_overview_intro }}</p>
      
      <h3>Key Benefits</h3>
      <ul class="benefits-list">
        <li>✓ {{ benefit_1 }}</li>
        <li>✓ {{ benefit_2 }}</li>
        <li>✓ {{ benefit_3 }}</li>
        <li>✓ {{ benefit_4 }}</li>
      </ul>
    </div>
    
    <div class="case-study">
      <h3>{{ case_study_title }}</h3>
      <blockquote>
        "{{ case_study_quote }}"
        <footer>— {{ company_name }}, {{ city }}</footer>
      </blockquote>
    </div>
  </div>
</section>

<!-- Provider Comparison -->
<section class="provider-comparison bg-light">
  <div class="container">
    <h2>{{ service_name }} Providers in {{ city }}</h2>
    
    <div class="comparison-table-wrapper">
      <table class="comparison-table">
        <thead>
          <tr>
            <th>Provider</th>
            <th>Speed</th>
            <th>Pricing</th>
            <th>Support</th>
            <th>ComBrokers</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>{{ provider_1_name }}</td>
            <td>{{ provider_1_speed }}</td>
            <td>{{ provider_1_price }}</td>
            <td>{{ provider_1_support }}</td>
            <td class="highlight">✓ Available</td>
          </tr>
          <!-- Repeat for additional providers -->
        </tbody>
      </table>
    </div>
    
    <div class="combrokers-advantage">
      <h3>Why Choose ComBrokers?</h3>
      <ul>
        <li>✓ <strong>Better Pricing:</strong> {{ advantage_1 }}</li>
        <li>✓ <strong>Local Expertise:</strong> {{ advantage_2 }}</li>
        <li>✓ <strong>24/7 Support:</strong> {{ advantage_3 }}</li>
        <li>✓ <strong>Custom Solutions:</strong> {{ advantage_4 }}</li>
      </ul>
    </div>
  </div>
</section>

<!-- Lead Capture Form -->
<section id="form" class="lead-capture">
  <div class="container">
    <h2>Get Your Free {{ service_name }} Consultation</h2>
    <p>Fill out the form below and our experts will contact you within 2 hours.</p>
    
    <form id="lead-form" class="lead-form" method="POST">
      <input type="hidden" name="page_id" value="{{ page_id }}">
      <input type="hidden" name="service_type" value="{{ service_type }}">
      <input type="hidden" name="city" value="{{ city }}">
      <input type="hidden" name="state" value="{{ state }}">
      
      <div class="form-row">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
      </div>
      
      <div class="form-row">
        <input type="tel" name="phone" placeholder="Phone Number (required)" required>
        <input type="text" name="company" placeholder="Company Name">
      </div>
      
      <div class="form-row full">
        <select name="interest" required>
          <option value="">Select Your Interest...</option>
          <option value="pricing">Pricing Information</option>
          <option value="demo">Live Demo</option>
          <option value="consultation">Free Consultation</option>
          <option value="migration">Migration Help</option>
        </select>
      </div>
      
      <div class="form-row full">
        <textarea name="message" placeholder="Any additional details?" rows="3"></textarea>
      </div>
      
      <button type="submit" class="btn btn-primary btn-lg">
        Schedule Free Consultation
      </button>
      
      <p class="form-disclaimer">
        We respect your privacy. Your information will never be shared.
      </p>
    </form>
  </div>
</section>

<!-- FAQ Section with Schema -->
<section class="faq">
  <div class="container">
    <h2>Frequently Asked Questions</h2>
    
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "{{ faq_1_question }}",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "{{ faq_1_answer }}"
          }
        },
        {
          "@type": "Question",
          "name": "{{ faq_2_question }}",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "{{ faq_2_answer }}"
          }
        },
        {
          "@type": "Question",
          "name": "{{ faq_3_question }}",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "{{ faq_3_answer }}"
          }
        }
      ]
    }
    </script>
    
    <div class="accordion">
      <div class="accordion-item">
        <h3 class="accordion-header">{{ faq_1_question }}</h3>
        <div class="accordion-content">
          <p>{{ faq_1_answer }}</p>
        </div>
      </div>
      
      <div class="accordion-item">
        <h3 class="accordion-header">{{ faq_2_question }}</h3>
        <div class="accordion-content">
          <p>{{ faq_2_answer }}</p>
        </div>
      </div>
      
      <div class="accordion-item">
        <h3 class="accordion-header">{{ faq_3_question }}</h3>
        <div class="accordion-content">
          <p>{{ faq_3_answer }}</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Related Cities -->
<section class="related-cities bg-light">
  <div class="container">
    <h2>{{ service_name }} Services in Nearby Cities</h2>
    <ul class="city-links">
      <li><a href="/{{ service_slug }}/{{ nearby_city_1 }}-{{ state_code }}">{{ nearby_city_1 }}</a></li>
      <li><a href="/{{ service_slug }}/{{ nearby_city_2 }}-{{ state_code }}">{{ nearby_city_2 }}</a></li>
      <li><a href="/{{ service_slug }}/{{ nearby_city_3 }}-{{ state_code }}">{{ nearby_city_3 }}</a></li>
      <li><a href="/services/{{ service_slug }}/{{ state_name }}">View All {{ state_name }} Cities</a></li>
    </ul>
  </div>
</section>

<!-- Footer -->
<footer class="footer">
  <div class="container">
    <div class="footer-content">
      <div class="footer-section">
        <h4>ComBrokers</h4>
        <ul>
          <li><a href="https://combrokers.com">Home</a></li>
          <li><a href="https://combrokers.com/about">About</a></li>
          <li><a href="https://combrokers.com/contact">Contact</a></li>
        </ul>
      </div>
      
      <div class="footer-section">
        <h4>Services</h4>
        <ul>
          <li><a href="/voip">VoIP</a></li>
          <li><a href="/fiber">Fiber</a></li>
          <li><a href="/internet">Internet</a></li>
          <li><a href="/security">Security</a></li>
        </ul>
      </div>
      
      <div class="footer-section">
        <h4>Legal</h4>
        <ul>
          <li><a href="/privacy-policy">Privacy Policy</a></li>
          <li><a href="/terms">Terms of Service</a></li>
          <li><a href="/disclaimer">Disclaimer</a></li>
        </ul>
      </div>
    </div>
    
    <div class="footer-bottom">
      <p>&copy; 2024 ComBrokers. All rights reserved.</p>
    </div>
  </div>
</footer>

<!-- Form Submission Tracking -->
<script>
document.getElementById('lead-form').addEventListener('submit', async function(e) {
  e.preventDefault();
  
  const formData = new FormData(this);
  const data = Object.fromEntries(formData);
  
  try {
    const response = await fetch('/api/leads', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    
    if (response.ok) {
      document.getElementById('lead-form').innerHTML = 
        '<div class="success-message">✓ Thank you! We\'ll contact you shortly.</div>';
      
      // Track in analytics
      plausible('Form Submission', { props: { service: data.service_type } });
    }
  } catch (error) {
    console.error('Form error:', error);
  }
});
</script>

</body>
</html>
```

---

## CSS Styles (main.css)

```css
/* filepath: css/main.css */
:root {
  --primary: #667eea;
  --secondary: #764ba2;
  --success: #10b981;
  --warning: #f59e0b;
  --dark: #1f2937;
  --light: #f3f4f6;
  --white: #ffffff;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  line-height: 1.6;
  color: var(--dark);
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

/* Navigation */
.navbar {
  background: var(--white);
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  position: sticky;
  top: 0;
  z-index: 100;
}

.navbar .container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 20px;
}

.logo {
  font-weight: bold;
  font-size: 1.5rem;
  color: var(--primary);
  text-decoration: none;
}

.nav-links {
  display: flex;
  gap: 30px;
  list-style: none;
}

.nav-links a {
  text-decoration: none;
  color: var(--dark);
  transition: color 0.3s;
}

.nav-links a:hover,
.cta-nav {
  color: var(--primary);
}

/* Hero Section */
.hero {
  padding: 80px 0;
  color: var(--white);
  text-align: center;
}

.hero h1 {
  font-size: 3rem;
  margin-bottom: 20px;
  line-height: 1.2;
}

.hero-subheading {
  font-size: 1.25rem;
  margin-bottom: 30px;
  opacity: 0.95;
}

/* Buttons */
.btn {
  display: inline-block;
  padding: 12px 30px;
  border-radius: 6px;
  text-decoration: none;
  font-weight: 600;
  border: none;
  cursor: pointer;
  transition: all 0.3s;
}

.btn-primary {
  background: var(--primary);
  color: var(--white);
}

.btn-primary:hover {
  background: var(--secondary);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-lg {
  padding: 16px 40px;
  font-size: 1.1rem;
}

/* Sections */
section {
  padding: 60px 0;
}

.bg-light {
  background: var(--light);
}

section h2 {
  font-size: 2rem;
  margin-bottom: 30px;
  color: var(--dark);
}

/* Local Context Grid */
.context-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 30px;
  margin-top: 40px;
}

.context-card {
  background: var(--white);
  padding: 30px;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
  text-align: center;
}

.context-card h3 {
  margin-bottom: 15px;
  color: var(--primary);
}

/* Comparison Table */
.comparison-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 30px;
  background: var(--white);
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.comparison-table thead {
  background: var(--primary);
  color: var(--white);
}

.comparison-table th,
.comparison-table td {
  padding: 15px;
  text-align: left;
  border-bottom: 1px solid var(--light);
}

.comparison-table td.highlight {
  background: rgba(16, 185, 129, 0.1);
  color: var(--success);
  font-weight: 600;
}

/* Form */
.lead-form {
  background: var(--white);
  padding: 40px;
  border-radius: 8px;
  max-width: 600px;
  margin: 0 auto;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
  margin-bottom: 15px;
}

.form-row.full {
  grid-template-columns: 1fr;
}

.lead-form input,
.lead-form select,
.lead-form textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-family: inherit;
  font-size: 1rem;
}

.lead-form input:focus,
.lead-form select:focus,
.lead-form textarea:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-disclaimer {
  font-size: 0.85rem;
  color: #666;
  margin-top: 15px;
  text-align: center;
}

.success-message {
  background: var(--success);
  color: var(--white);
  padding: 20px;
  border-radius: 4px;
  text-align: center;
  font-weight: 600;
}

/* FAQ Accordion */
.accordion {
  max-width: 800px;
  margin: 0 auto;
}

.accordion-item {
  border: 1px solid var(--light);
  margin-bottom: 15px;
  border-radius: 4px;
  overflow: hidden;
}

.accordion-header {
  padding: 20px;
  background: var(--white);
  cursor: pointer;
  font-weight: 600;
  user-select: none;
  transition: background 0.3s;
}

.accordion-header:hover {
  background: var(--light);
}

.accordion-content {
  padding: 20px;
  background: var(--light);
  display: none;
}

.accordion-content.active {
  display: block;
}

/* Footer */
.footer {
  background: var(--dark);
  color: var(--white);
  padding: 60px 0 20px;
}

.footer-content {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 40px;
  margin-bottom: 40px;
}

.footer-section h4 {
  margin-bottom: 15px;
}

.footer-section ul {
  list-style: none;
}

.footer-section a {
  color: #ccc;
  text-decoration: none;
  transition: color 0.3s;
}

.footer-section a:hover {
  color: var(--primary);
}

.footer-bottom {
  text-align: center;
  padding-top: 20px;
  border-top: 1px solid rgba(255,255,255,0.1);
  color: #999;
}

/* Responsive */
@media (max-width: 768px) {
  .hero h1 { font-size: 1.75rem; }
  .form-row { grid-template-columns: 1fr; }
  .nav-links { gap: 15px; }
}
```

---

## Template Variables Reference

All variables passed to template during generation:

```
{{ page_title }} - SEO-optimized title
{{ meta_description }} - Page meta description
{{ meta_keywords }} - Keywords list
{{ page_url }} - Full page URL
{{ page_id }} - Database ID for form tracking

{{ city }} - City name
{{ state }} - State abbreviation (e.g., "TX")
{{ service_name }} - Service name (e.g., "VoIP")
{{ service_type }} - Service type for form
{{ service_slug }} - URL slug (e.g., "voip")

{{ hero_headline }} - Main headline
{{ hero_subheading }} - Subheading
{{ cta_button_text }} - CTA button text

{{ population }} - City population
{{ business_count }} - Number of businesses
{{ weather_insight }} - Weather-based insight
{{ event_name }} - Major local event
{{ chamber_url }} - Chamber of commerce URL

{{ service_overview_intro }} - Service description
{{ benefit_1 }} to {{ benefit_4 }} - Service benefits
{{ case_study_title }} - Case study title
{{ case_study_quote }} - Case study quote
{{ company_name }} - Company in case study

{{ provider_1_name }} - Provider name
{{ provider_1_speed }} - Speed info
{{ provider_1_price }} - Price info
{{ provider_1_support }} - Support level

{{ advantage_1 }} to {{ advantage_4 }} - ComBrokers advantages

{{ faq_1_question }}, {{ faq_1_answer }} - FAQ items
...

{{ nearby_city_1 }} to {{ nearby_city_3 }} - Nearby cities
{{ state_code }} - State code (e.g., "tx")
{{ state_name }} - Full state name
```

---

## SEO Checklist

- [ ] Unique title per page (< 60 chars)
- [ ] Unique meta description (< 160 chars)
- [ ] H1 tag (exactly 1 per page)
- [ ] Local schema markup (LocalBusiness + Service)
- [ ] FAQ schema markup
- [ ] Internal linking to nearby cities
- [ ] Image alt tags
- [ ] Mobile responsive
- [ ] Page speed < 2 seconds
- [ ] HTTPS only
- [ ] Sitemap XML included

---

**Last Updated:** [Current Date]  
**Status:** Ready for n8n Integration
