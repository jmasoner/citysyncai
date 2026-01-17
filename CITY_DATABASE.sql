-- CitySync AI - PostgreSQL Database Schema
-- Purpose: Create all tables for page generation tracking and lead capture
-- Usage: psql -h localhost -U n8n -d n8n -f CITY_DATABASE.sql

-- Cities Table
CREATE TABLE IF NOT EXISTS cities (
  id SERIAL PRIMARY KEY,
  city_name VARCHAR(100) NOT NULL,
  state_code CHAR(2) NOT NULL,
  state_name VARCHAR(50),
  population INT,
  coordinates POINT,
  zip_codes TEXT[],
  nearby_cities TEXT[],
  status VARCHAR(20) DEFAULT 'active', -- active, inactive, paused
  created_at TIMESTAMP DEFAULT NOW(),
  updated_at TIMESTAMP DEFAULT NOW(),
  UNIQUE(city_name, state_code)
);

-- Services Table
CREATE TABLE IF NOT EXISTS services (
  id SERIAL PRIMARY KEY,
  service_name VARCHAR(100) NOT NULL UNIQUE,
  service_slug VARCHAR(50) NOT NULL UNIQUE,
  description TEXT,
  icon VARCHAR(50),
  priority INT DEFAULT 0,
  active BOOLEAN DEFAULT true,
  created_at TIMESTAMP DEFAULT NOW()
);

-- Generated Pages Table
CREATE TABLE IF NOT EXISTS pages (
  id SERIAL PRIMARY KEY,
  city_id INT NOT NULL REFERENCES cities(id) ON DELETE CASCADE,
  service_id INT NOT NULL REFERENCES services(id) ON DELETE CASCADE,
  title VARCHAR(255),
  slug VARCHAR(255) UNIQUE NOT NULL,
  meta_description VARCHAR(160),
  content_hash VARCHAR(64), -- MD5 for duplicate detection
  html_content TEXT,
  status VARCHAR(20) DEFAULT 'pending', -- pending, generating, generated, deployed, indexed, error
  seo_score INT,
  page_speed FLOAT, -- Load time in seconds
  word_count INT,
  error_message TEXT,
  generated_at TIMESTAMP,
  deployed_at TIMESTAMP,
  indexed_at TIMESTAMP,
  created_at TIMESTAMP DEFAULT NOW(),
  updated_at TIMESTAMP DEFAULT NOW(),
  UNIQUE(city_id, service_id)
);

-- Lead Tracking Table
CREATE TABLE IF NOT EXISTS leads (
  id SERIAL PRIMARY KEY,
  page_id INT NOT NULL REFERENCES pages(id) ON DELETE CASCADE,
  name VARCHAR(100),
  company VARCHAR(100),
  phone VARCHAR(20),
  email VARCHAR(100),
  service_type VARCHAR(50),
  city VARCHAR(100),
  state VARCHAR(2),
  interest_type VARCHAR(50), -- pricing, demo, consultation, migration
  message TEXT,
  quality_score INT DEFAULT 0, -- 1-10, calculated from company size, industry
  status VARCHAR(20) DEFAULT 'new', -- new, contacted, qualified, converted, spam
  submitted_at TIMESTAMP DEFAULT NOW(),
  contacted_at TIMESTAMP,
  updated_at TIMESTAMP DEFAULT NOW(),
  notes TEXT
);

-- API Usage Tracking Table
CREATE TABLE IF NOT EXISTS api_usage (
  id SERIAL PRIMARY KEY,
  provider VARCHAR(50), -- deepseek, claude, ollama, openweather, etc
  model VARCHAR(100),
  endpoint VARCHAR(255),
  tokens_used INT,
  prompt_tokens INT,
  completion_tokens INT,
  cost_cents DECIMAL(10, 4),
  status VARCHAR(20), -- success, error, rate_limited, timeout
  error_message TEXT,
  response_time_ms INT,
  created_at TIMESTAMP DEFAULT NOW()
);

-- Keyword Tracking Table
CREATE TABLE IF NOT EXISTS keywords (
  id SERIAL PRIMARY KEY,
  page_id INT NOT NULL REFERENCES pages(id) ON DELETE CASCADE,
  keyword VARCHAR(255),
  keyword_intent VARCHAR(50), -- commercial, informational, transactional
  search_volume INT,
  difficulty INT, -- 1-100
  current_rank INT,
  current_position INT,
  impressions INT,
  clicks INT,
  ctr FLOAT,
  updated_at TIMESTAMP DEFAULT NOW()
);

-- Analytics Events Table
CREATE TABLE IF NOT EXISTS analytics_events (
  id SERIAL PRIMARY KEY,
  page_id INT REFERENCES pages(id) ON DELETE CASCADE,
  event_type VARCHAR(50), -- page_view, form_submission, cta_click, phone_click
  user_session_id VARCHAR(100),
  user_country VARCHAR(2),
  user_city VARCHAR(100),
  referrer VARCHAR(255),
  device_type VARCHAR(20), -- desktop, mobile, tablet
  timestamp TIMESTAMP DEFAULT NOW()
);

-- Content Generation Log Table
CREATE TABLE IF NOT EXISTS generation_logs (
  id SERIAL PRIMARY KEY,
  batch_id VARCHAR(100),
  cities_count INT,
  services_count INT,
  pages_target INT,
  pages_generated INT,
  pages_failed INT,
  total_cost_cents DECIMAL(10, 2),
  generation_time_seconds INT,
  status VARCHAR(20), -- started, in_progress, completed, failed
  error_message TEXT,
  started_at TIMESTAMP DEFAULT NOW(),
  completed_at TIMESTAMP
);

-- Create Indexes for Performance
CREATE INDEX idx_cities_state ON cities(state_code);
CREATE INDEX idx_cities_status ON cities(status);
CREATE INDEX idx_pages_city_service ON pages(city_id, service_id);
CREATE INDEX idx_pages_status ON pages(status);
CREATE INDEX idx_pages_slug ON pages(slug);
CREATE INDEX idx_pages_deployed ON pages(deployed_at);
CREATE INDEX idx_leads_page ON leads(page_id);
CREATE INDEX idx_leads_status ON leads(status);
CREATE INDEX idx_leads_submitted ON leads(submitted_at);
CREATE INDEX idx_api_usage_provider ON api_usage(provider);
CREATE INDEX idx_api_usage_created ON api_usage(created_at);
CREATE INDEX idx_keywords_page ON keywords(page_id);
CREATE INDEX idx_keywords_rank ON keywords(current_rank);
CREATE INDEX idx_analytics_page ON analytics_events(page_id);
CREATE INDEX idx_analytics_timestamp ON analytics_events(timestamp);

-- Insert Service Categories
INSERT INTO services (service_name, service_slug, description, priority, active)
VALUES 
  ('VoIP & Phone Systems', 'voip', 'Business phone systems and VoIP solutions', 1, true),
  ('Internet & Broadband', 'internet', 'High-speed internet and broadband services', 2, true),
  ('Fiber Optic Solutions', 'fiber', 'Fiber optic internet installation and services', 3, true),
  ('Network Installation & Cabling', 'network', 'Professional network infrastructure setup', 4, true),
  ('Cyber Security & Managed IT', 'security', 'Cybersecurity solutions and managed IT services', 5, true),
  ('Managed Services', 'managed-services', 'Comprehensive managed IT services', 6, true),
  ('Break-Fix & Smart Hands', 'support', 'On-site IT support and break-fix services', 7, true)
ON CONFLICT (service_slug) DO NOTHING;

-- Sample Cities Data (US Major Cities)
INSERT INTO cities (city_name, state_code, state_name, population, nearby_cities)
VALUES 
  ('Austin', 'TX', 'Texas', 961855, ARRAY['San Antonio', 'Dallas', 'Houston']),
  ('Seattle', 'WA', 'Washington', 753675, ARRAY['Tacoma', 'Bellevue', 'Redmond']),
  ('New York', 'NY', 'New York', 8336817, ARRAY['Newark', 'Jersey City', 'Yonkers']),
  ('Los Angeles', 'CA', 'California', 3979576, ARRAY['Long Beach', 'Anaheim', 'Santa Ana']),
  ('Denver', 'CO', 'Colorado', 727211, ARRAY['Boulder', 'Aurora', 'Fort Collins']),
  ('Chicago', 'IL', 'Illinois', 2693976, ARRAY['Evanston', 'Oak Park', 'Naperville']),
  ('San Francisco', 'CA', 'California', 873965, ARRAY['Oakland', 'Berkeley', 'San Jose']),
  ('Miami', 'FL', 'Florida', 442241, ARRAY['Fort Lauderdale', 'Hollywood', 'Doral']),
  ('Boston', 'MA', 'Massachusetts', 692600, ARRAY['Cambridge', 'Brookline', 'Somerville']),
  ('Washington', 'DC', 'District of Columbia', 689545, ARRAY['Arlington', 'Alexandria', 'Bethesda'])
ON CONFLICT (city_name, state_code) DO NOTHING;

-- Verify schema creation
SELECT 'Database schema created successfully' as status;
SELECT COUNT(*) as services_count FROM services;
SELECT COUNT(*) as cities_count FROM cities;
