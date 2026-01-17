#!/usr/bin/env python3
"""
Template Renderer - Converts base.html template to city-specific landing pages
Usage: python render_template.py --city "New York" --state "NY" --service "VoIP" --output output/
"""

import json
import argparse
import re
from pathlib import Path
from datetime import datetime
import logging

# Configure logging
logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')
logger = logging.getLogger(__name__)

class TemplateRenderer:
    """Renders Handlebars-style templates to static HTML"""
    
    # Service descriptions mapping
    SERVICE_DESCRIPTIONS = {
        'VoIP': 'Voice over Internet Protocol (VoIP) for clear, cost-effective business communications',
        'Internet': 'High-speed fiber and broadband internet for reliable connectivity',
        'Fiber': 'Ultra-fast fiber optic internet with guaranteed speeds',
        'Network': 'Enterprise networking solutions for seamless operations',
        'Security': 'Advanced cybersecurity protection for your business data',
        'Managed Services': 'Full-service IT management and support',
        'Support': '24/7 technical support and customer service'
    }
    
    # Service types for form
    SERVICE_TYPES = {
        'VoIP': 'voip',
        'Internet': 'internet',
        'Fiber': 'fiber',
        'Network': 'network',
        'Security': 'security',
        'Managed Services': 'managed',
        'Support': 'support'
    }
    
    def __init__(self, template_path):
        """Load the template file"""
        with open(template_path, 'r', encoding='utf-8') as f:
            self.template = f.read()
        logger.info(f"Loaded template from {template_path}")
    
    def render(self, context):
        """Render template with provided context variables"""
        content = self.template
        
        # Replace all Handlebars-style variables {{ variable }}
        for key, value in context.items():
            pattern = r'\{\{\s*' + key + r'\s*\}\}'
            content = re.sub(pattern, str(value) if value else '', content, flags=re.IGNORECASE)
        
        return content
    
    def generate_context(self, city, state, service, zip_code='00000', population='50,000+'):
        """Generate context dictionary for a city/service combination"""
        
        service_desc = self.SERVICE_DESCRIPTIONS.get(service, f'{service} solutions')
        service_type = self.SERVICE_TYPES.get(service, service.lower())
        
        # Create page URL slug
        slug = f"{city.lower().replace(' ', '-')}-{service.lower().replace(' ', '-')}-{state.lower()}"
        page_url = f"https://combrokers.com/pages/{slug}/"
        page_id = hash(slug) % 1000000  # Simple hash for page ID
        
        context = {
            # Page identifiers
            'page_id': page_id,
            'page_title': f'{service} Services in {city}, {state} | ComBrokers',
            'page_url': page_url,
            'slug': slug,
            
            # Location
            'city': city,
            'state': state,
            'zip_code': zip_code,
            'population': population,
            
            # Service
            'service_name': service,
            'service_type': service_type,
            'service_overview': service_desc,
            
            # Meta tags
            'meta_description': f'Get expert {service} services in {city}, {state}. ComBrokers provides reliable, affordable solutions with 24/7 support.',
            'meta_keywords': f'{service}, {city}, {state}, business solutions, affordable',
            
            # Hero section
            'hero_headline': f'Leading {service} Provider in {city}, {state}',
            'hero_subheading': f'Trusted by hundreds of {city} businesses. Get your free consultation today.',
            'cta_button_text': 'Get Free Consultation',
        }
        
        return context
    
    def save_page(self, content, output_path):
        """Save rendered content to file"""
        output_path = Path(output_path)
        output_path.parent.mkdir(parents=True, exist_ok=True)
        
        with open(output_path, 'w', encoding='utf-8') as f:
            f.write(content)
        
        logger.info(f"Saved page to {output_path} ({len(content)} bytes)")
        return output_path


def main():
    parser = argparse.ArgumentParser(description='Render landing page template')
    parser.add_argument('--city', required=True, help='City name')
    parser.add_argument('--state', required=True, help='State abbreviation')
    parser.add_argument('--service', required=True, help='Service type')
    parser.add_argument('--template', default='templates/base.html', help='Template file path')
    parser.add_argument('--output', default='output/', help='Output directory')
    parser.add_argument('--zip', default='00000', help='ZIP code')
    parser.add_argument('--population', default='50,000+', help='Population estimate')
    
    args = parser.parse_args()
    
    try:
        renderer = TemplateRenderer(args.template)
        context = renderer.generate_context(
            city=args.city,
            state=args.state,
            service=args.service,
            zip_code=args.zip,
            population=args.population
        )
        
        content = renderer.render(context)
        
        # Generate output filename
        slug = f"{args.city.lower().replace(' ', '-')}-{args.service.lower().replace(' ', '-')}-{args.state.lower()}"
        output_file = Path(args.output) / f"{slug}.html"
        
        renderer.save_page(content, output_file)
        
        # Print summary
        print(f"\n✓ Page rendered successfully!")
        print(f"  City: {args.city}, {args.state}")
        print(f"  Service: {args.service}")
        print(f"  Output: {output_file}")
        print(f"  Size: {len(content)} bytes")
        
    except FileNotFoundError as e:
        logger.error(f"Template file not found: {e}")
        exit(1)
    except Exception as e:
        logger.error(f"Error rendering template: {e}")
        exit(1)


if __name__ == '__main__':
    main()
