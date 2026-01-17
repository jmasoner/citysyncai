#!/usr/bin/env python3
"""Comprehensive Schema and SEO Validation Report"""

from pathlib import Path
import re
import json
from collections import defaultdict

# Analyze all generated pages
output_dir = Path('output')
html_files = list(output_dir.glob('*.html'))

print('=' * 70)
print('STEP 3: TEMPLATE VALIDATION & SEO CHECK')
print('=' * 70)
print()

# Statistics
total_files = len(html_files)
total_bytes = sum(f.stat().st_size for f in html_files)
avg_size = total_bytes / total_files if total_files else 0

print(f'📊 GENERATION STATISTICS')
print(f'  Total pages: {total_files}')
print(f'  Total size: {total_bytes:,} bytes ({total_bytes/1024/1024:.2f} MB)')
print(f'  Average page: {avg_size:,.0f} bytes')
print()

# Validation checks
validation_results = {
    'has_doctype': 0,
    'has_viewport': 0,
    'has_title': 0,
    'has_og_tags': 0,
    'has_localbusiness_schema': 0,
    'has_service_schema': 0,
    'has_faqpage_schema': 0,
    'has_form': 0,
    'has_internal_links': 0,
    'unreplaced_variables': 0,
    'duplicate_ids': defaultdict(int),
}

all_page_data = []

for html_file in sorted(html_files):
    with open(html_file, 'r', encoding='utf-8') as f:
        html = f.read()
    
    page_name = html_file.stem
    
    # Extract key data
    title_match = re.search(r'<title>(.*?)</title>', html)
    title = title_match.group(1) if title_match else 'NO TITLE'
    
    meta_desc_match = re.search(r'<meta name="description" content="(.*?)"', html)
    meta_desc = meta_desc_match.group(1) if meta_desc_match else ''
    
    # Extract city and service
    city_match = re.search(r"'city', '([^']*)'", html)
    service_match = re.search(r"'service_type', '([^']*)'", html)
    
    city = city_match.group(1) if city_match else 'Unknown'
    service = service_match.group(1) if service_match else 'Unknown'
    
    all_page_data.append({
        'file': page_name,
        'title': title,
        'city': city,
        'service': service,
        'size': html_file.stat().st_size,
    })
    
    # Validation checks
    if '<!DOCTYPE html>' in html:
        validation_results['has_doctype'] += 1
    
    if 'viewport' in html:
        validation_results['has_viewport'] += 1
    
    if '<title>' in html and '</title>' in html:
        validation_results['has_title'] += 1
    
    if 'og:title' in html and 'og:description' in html:
        validation_results['has_og_tags'] += 1
    
    # Schema checks
    if '"@type": "LocalBusiness"' in html:
        validation_results['has_localbusiness_schema'] += 1
    
    if '"@type": "Service"' in html:
        validation_results['has_service_schema'] += 1
    
    if '"@type": "FAQPage"' in html:
        validation_results['has_faqpage_schema'] += 1
    
    if '<form' in html:
        validation_results['has_form'] += 1
    
    if 'href="/' in html or 'href="/voip' in html:
        validation_results['has_internal_links'] += 1
    
    # Check for unreplaced variables
    unreplaced = re.findall(r'\{\{.*?\}\}', html)
    if unreplaced:
        validation_results['unreplaced_variables'] += len(unreplaced)

print(f'✓ VALIDATION RESULTS')
print(f'  DOCTYPE declaration: {validation_results["has_doctype"]}/{total_files}')
print(f'  Viewport meta tag: {validation_results["has_viewport"]}/{total_files}')
print(f'  Title tag: {validation_results["has_title"]}/{total_files}')
print(f'  Open Graph tags: {validation_results["has_og_tags"]}/{total_files}')
print()

print(f'📋 SCHEMA MARKUP')
print(f'  LocalBusiness schema: {validation_results["has_localbusiness_schema"]}/{total_files}')
print(f'  Service schema: {validation_results["has_service_schema"]}/{total_files}')
print(f'  FAQPage schema: {validation_results["has_faqpage_schema"]}/{total_files}')
print()

print(f'🔗 FUNCTIONALITY')
print(f'  Lead capture forms: {validation_results["has_form"]}/{total_files}')
print(f'  Internal links: {validation_results["has_internal_links"]}/{total_files}')
print()

print(f'⚠️ QUALITY CHECKS')
print(f'  Unreplaced variables: {validation_results["unreplaced_variables"]}')
print(f'  Error rate: {(validation_results["unreplaced_variables"]/total_files):.2%}')
print()

# Sample pages
print(f'📄 SAMPLE PAGE TITLES (first 5)')
for page in all_page_data[:5]:
    print(f'  {page["file"]:35s} | {page["title"][:50]}')
print()

# Size distribution
sizes = sorted([p['size'] for p in all_page_data])
print(f'📊 SIZE DISTRIBUTION')
print(f'  Min: {min(sizes):,} bytes')
print(f'  Max: {max(sizes):,} bytes')
print(f'  Median: {sizes[len(sizes)//2]:,} bytes')
print(f'  Std Dev: {(sum((x - avg_size)**2 for x in sizes) / len(sizes))**0.5:,.0f}')
print()

# Summary
total_checks = 9 * total_files
passed_checks = sum([
    validation_results['has_doctype'],
    validation_results['has_viewport'],
    validation_results['has_title'],
    validation_results['has_og_tags'],
    validation_results['has_localbusiness_schema'],
    validation_results['has_service_schema'],
    validation_results['has_faqpage_schema'],
    validation_results['has_form'],
    validation_results['has_internal_links'],
])

print(f'=' * 70)
print(f'RESULT: {passed_checks}/{total_checks} checks passed ({passed_checks/total_checks:.1%})')
print(f'STATUS: READY FOR SEO VALIDATION')
print('=' * 70)
