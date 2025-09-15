<?php
/**
 * README Generator for CitySyncAI
 */

defined('ABSPATH') || exit;

function citysyncai_generate_readme() {
    $plugin_name = 'CitySyncAI';
    $description = 'Generate SEO-optimized city pages using AI and structured schema.';
    $version     = '1.0.0';
    $author      = 'John Masoner';

    $schema_type = get_option('citysyncai_schema_type', 'LocalBusiness');
    $ai_provider = get_option('citysyncai_ai_provider', 'openai');
    $content_type = get_option('citysyncai_content_type', 'overview');
    $webhook_url = get_option('citysyncai_webhook_url', '');

    $readme = <<<MD
# {$plugin_name}

**Version:** {$version}  
**Author:** {$author}  
**Description:** {$description}

---

## 🔧 Features

- AI-powered content generation using {$ai_provider}
- Schema injection with type: `{$schema_type}`
- Per-post overrides for schema and AI
- REST API endpoints for dynamic rendering
- Webhook support for external sync
- Admin settings panel with live preview

---

## 🚀 Usage

### Shortcodes

```php
[citysyncai]              // Renders schema + AI content
[citysyncai_block]        // Renders AI block only
[citysyncai_schema]       // Renders schema block only