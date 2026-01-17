# Contributing to CitySyncAI

Thanks for your interest in contributing! Here's how to get started:

## 🧱 Setup
- Clone the repo: `git clone https://github.com/jmasoner/citysyncai.git`
- Activate the plugin in WordPress
- Use Live Preview for frontend testing

## 🧩 Structure
- `citysyncai.php`: Main loader
- `includes/`: Modular logic (admin, AI, schema)
- `templates/`: Frontend layouts
- `assets/`: CSS/JS for admin UI

## 🧪 Testing
- Use `[citysyncai city="Austin, TX" type="overview"]` to test shortcodes
- Trigger REST API via `POST /wp-json/citysyncai/v1/generate`

## 🧠 Guidelines
- Keep logic modular
- Use inline documentation
- Avoid hardcoded strings — use filters and options

## 📬 Questions?
Open an issue or email John Masoner