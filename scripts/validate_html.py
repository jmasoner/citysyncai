#!/usr/bin/env python3
"""Validate generated HTML pages"""

from pathlib import Path
import re
import json

html_file = Path('output/new-york-voip-ny.html')
with open(html_file, 'r', encoding='utf-8') as f:
    html = f.read()

# Extract schema markup
schemas = re.findall(r'<script type="application/ld\+json">(.*?)</script>', html, re.DOTALL)

print('✓ HTML Validation Report')
print('=' * 50)
print(f'File size: {html_file.stat().st_size:,} bytes')
print(f'Has DOCTYPE: {"<!DOCTYPE html>" in html}')
print(f'Has viewport meta: {"viewport" in html}')
print(f'Has title: {"<title>" in html}')
print(f'Has Open Graph: {"og:title" in html}')
print(f'Has form: {"<form" in html}')

print(f'\nSchema Markup Found: {len(schemas)} schemas')
for i, schema in enumerate(schemas, 1):
    try:
        data = json.loads(schema)
        schema_type = data.get('@type', 'Unknown')
        print(f'  {i}. {schema_type} schema ✓')
    except Exception as e:
        print(f'  {i}. Invalid JSON schema ✗: {e}')

print(f'\nTemplate Variables Replaced:')
variables = re.findall(r'\{\{.*?\}\}', html)
if variables:
    print(f'  UNREPLACED: {len(variables)} variables remaining ✗')
    for var in variables[:5]:
        print(f'    - {var}')
else:
    print(f'  All variables replaced ✓')

print(f'\nCSS Inlined: {"<style>" in html}')
print(f'JavaScript Included: {"<script>" in html}')
