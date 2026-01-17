#!/usr/bin/env python3
"""Validate JSON-LD schema markup in generated pages"""

from pathlib import Path
import re
import json
from typing import List, Dict, Any

class SchemaValidator:
    """Validates JSON-LD schema markup"""
    
    REQUIRED_LOCALBUSINESS_PROPS = {
        '@context', '@type', 'name', 'description', 'url', 'telephone', 'address', 'areaServed'
    }
    
    REQUIRED_SERVICE_PROPS = {
        '@context', '@type', 'name', 'description', 'provider', 'areaServed'
    }
    
    REQUIRED_FAQPAGE_PROPS = {
        '@context', '@type', 'mainEntity'
    }
    
    def __init__(self):
        self.results = {
            'localbusiness': [],
            'service': [],
            'faqpage': [],
            'errors': []
        }
    
    def extract_schemas(self, html: str) -> List[Dict[str, Any]]:
        """Extract JSON-LD schemas from HTML"""
        schemas = []
        matches = re.findall(r'<script type="application/ld\+json">(.*?)</script>', html, re.DOTALL)
        
        for match in matches:
            try:
                schema = json.loads(match)
                schemas.append(schema)
            except json.JSONDecodeError as e:
                self.results['errors'].append(f"Invalid JSON-LD: {str(e)[:50]}")
        
        return schemas
    
    def validate_localbusiness(self, schema: Dict) -> bool:
        """Validate LocalBusiness schema"""
        if schema.get('@type') != 'LocalBusiness':
            return False
        
        missing = self.REQUIRED_LOCALBUSINESS_PROPS - set(schema.keys())
        if missing:
            self.results['errors'].append(f"LocalBusiness missing: {missing}")
            return False
        
        # Check nested properties
        if not isinstance(schema.get('address'), dict):
            self.results['errors'].append("LocalBusiness address must be PostalAddress object")
            return False
        
        self.results['localbusiness'].append({
            'name': schema.get('name', 'Unknown'),
            'city': schema.get('address', {}).get('addressLocality', 'Unknown'),
            'valid': True
        })
        
        return True
    
    def validate_service(self, schema: Dict) -> bool:
        """Validate Service schema"""
        if schema.get('@type') != 'Service':
            return False
        
        missing = self.REQUIRED_SERVICE_PROPS - set(schema.keys())
        if missing:
            self.results['errors'].append(f"Service missing: {missing}")
            return False
        
        self.results['service'].append({
            'name': schema.get('name', 'Unknown'),
            'valid': True
        })
        
        return True
    
    def validate_faqpage(self, schema: Dict) -> bool:
        """Validate FAQPage schema"""
        if schema.get('@type') != 'FAQPage':
            return False
        
        main_entity = schema.get('mainEntity')
        if not isinstance(main_entity, list) or len(main_entity) == 0:
            self.results['errors'].append("FAQPage mainEntity must be non-empty array")
            return False
        
        # Validate each question
        for qa in main_entity:
            if qa.get('@type') != 'Question':
                self.results['errors'].append(f"Invalid FAQ item type: {qa.get('@type')}")
                return False
            
            if 'name' not in qa or 'acceptedAnswer' not in qa:
                self.results['errors'].append("FAQ items must have name and acceptedAnswer")
                return False
        
        self.results['faqpage'].append({
            'count': len(main_entity),
            'valid': True
        })
        
        return True
    
    def validate_file(self, html_file: Path) -> bool:
        """Validate all schemas in an HTML file"""
        with open(html_file, 'r', encoding='utf-8') as f:
            html = f.read()
        
        schemas = self.extract_schemas(html)
        
        for schema in schemas:
            schema_type = schema.get('@type')
            
            if schema_type == 'LocalBusiness':
                self.validate_localbusiness(schema)
            elif schema_type == 'Service':
                self.validate_service(schema)
            elif schema_type == 'FAQPage':
                self.validate_faqpage(schema)
        
        return len(self.results['errors']) == 0
    
    def validate_all(self, output_dir: Path) -> Dict:
        """Validate all HTML files in directory"""
        html_files = sorted(output_dir.glob('*.html'))
        
        for html_file in html_files:
            self.validate_file(html_file)
        
        return {
            'total_files': len(html_files),
            'localbusiness_count': len(self.results['localbusiness']),
            'service_count': len(self.results['service']),
            'faqpage_count': len(self.results['faqpage']),
            'errors': self.results['errors'],
            'passed': len(self.results['errors']) == 0
        }


def main():
    output_dir = Path('output')
    validator = SchemaValidator()
    
    print('\n' + '='*70)
    print('STEP 4: SCHEMA MARKUP VALIDATION (JSON-LD)')
    print('='*70 + '\n')
    
    results = validator.validate_all(output_dir)
    
    print(f'📋 SCHEMA VALIDATION RESULTS')
    print(f'  Total files scanned: {results["total_files"]}')
    print(f'  LocalBusiness schemas: {results["localbusiness_count"]}')
    print(f'  Service schemas: {results["service_count"]}')
    print(f'  FAQPage schemas: {results["faqpage_count"]}')
    print()
    
    if results['errors']:
        print(f'❌ ERRORS FOUND ({len(results["errors"])}):')
        for error in results['errors'][:10]:
            print(f'  - {error}')
        if len(results['errors']) > 10:
            print(f'  ... and {len(results["errors"]) - 10} more')
    else:
        print(f'✅ NO ERRORS - All schema markup is valid!')
    
    print()
    print('='*70)
    print(f'STATUS: {"SCHEMA VALID" if results["passed"] else "SCHEMA INVALID"}')
    print('='*70 + '\n')
    
    return 0 if results['passed'] else 1


if __name__ == '__main__':
    exit(main())
