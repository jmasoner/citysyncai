=== CitySyncAI ===
Contributors: johnmasoner  
Tags: ai, schema, seo, city pages, automation  
Requires at least: 6.0  
Tested up to: 6.5  
Stable tag: 1.0.0  
License: MIT  
License URI: https://opensource.org/licenses/MIT

Generate SEO-optimized city pages using AI-powered content and structured schema. Modular, scalable, and ready for external indexing.

== Description ==

CitySyncAI is a modular WordPress plugin that dynamically generates city-specific content using AI and injects structured schema for SEO and discoverability. Designed for creators, educators, and marketers who want scalable publishing with minimal friction.

**Features:**
* AI-powered content generation (OpenAI, Gemini, Claude, etc.)
* Schema injection with per-post overrides
* REST API endpoints for dynamic rendering
* Webhook support for external sync
* Gutenberg block support
* Admin settings panel with live preview

== Installation ==

1. Upload the `citysyncai` folder to `/wp-content/plugins/`
2. Activate the plugin via the WordPress admin
3. Go to **Settings → CitySyncAI** to configure AI provider, schema type, and webhook

== Usage ==

**Shortcodes:**
- `[citysyncai]` – Renders schema + AI content
- `[citysyncai_block]` – Renders AI block only
- `[citysyncai_schema]` – Renders schema block only

**REST Endpoints:**
- `/citysyncai/v1/generate`
- `/citysyncai/v1/schema`
- `/citysyncai/v1/validate-schema`
- `/citysyncai/v1/trigger-webhook`
- `/citysyncai/v1/export-settings`

== Frequently Asked Questions ==

= Can I override schema per post? =
Yes, use the meta box in the post editor.

= Can I use different AI providers? =
Yes, choose from OpenAI, Gemini, Claude, DeepSeek, Genspark, or Grok.

== Changelog ==

= 1.0.0 =
* Initial release with AI, schema, REST, and block support

== Upgrade Notice ==

= 1.0.0 =
First release. Configure settings before use.
