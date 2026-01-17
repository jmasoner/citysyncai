#!/usr/bin/env python3
"""Generate test pages for all cities and services"""

import subprocess
from itertools import product

# Test cities from database
cities = [
    ('New York', 'NY'),
    ('Los Angeles', 'CA'),
    ('Chicago', 'IL'),
    ('Houston', 'TX'),
    ('Phoenix', 'AZ'),
    ('Philadelphia', 'PA'),
    ('San Antonio', 'TX'),
    ('San Diego', 'CA'),
    ('Dallas', 'TX'),
    ('San Jose', 'CA'),
]

# Services
services = ['VoIP', 'Internet', 'Fiber', 'Network', 'Security']

# Generate all combinations
total = len(cities) * len(services)
print(f'Generating {total} test pages...')
print('=' * 50)

success_count = 0
fail_count = 0

for i, (city_state, service) in enumerate(product(cities, services), 1):
    city, state = city_state
    result = subprocess.run([
        'python', 'scripts/render_template.py',
        '--city', city,
        '--state', state,
        '--service', service,
        '--output', 'output/'
    ], capture_output=True, text=True, cwd='c:\\Users\\john\\OneDrive\\MyProjects\\citysyncai')
    
    # Check if output file was created instead of return code (capture_output sometimes fails)
    import pathlib
    slug = f"{city.lower().replace(' ', '-')}-{service.lower().replace(' ', '-')}-{state.lower()}"
    output_file = pathlib.Path('output') / f"{slug}.html"
    
    if output_file.exists():
        print(f'{i:2d}/{total} OK {city:15s} - {service:15s}')
        success_count += 1
    else:
        print(f'{i:2d}/{total} XX {city:15s} - {service:15s}')
        if result.stderr:
            print(f'        Error: {result.stderr[:100]}')
        fail_count += 1

print('=' * 50)
print(f'Complete! Generated {success_count}/{total} pages in output/')
if fail_count > 0:
    print(f'Failed: {fail_count} pages')
