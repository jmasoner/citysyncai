#!/usr/bin/env python3
"""SEO Compliance Check for Generated Pages"""

from pathlib import Path
import re
from collections import defaultdict
from typing import Dict

class SEOValidator:
    """Checks SEO best practices"""
    
    # SEO thresholds
    TITLE_MIN_LENGTH = 30
    TITLE_MAX_LENGTH = 60
    META_DESC_MIN_LENGTH = 50
    META_DESC_MAX_LENGTH = 160
    H1_COUNT = 1
    
    def __init__(self):
        self.results = {
            'title_optimal': 0,
            'meta_desc_optimal': 0,
            'h1_present': 0,
            'internal_links': 0,
            'image_alt_text': 0,
            'mobile_ready': 0,
            'schema_markup': 0,
        }
        self.issues = []
    
    def check_file(self, html_file: Path) -> Dict:
        """Check SEO compliance for a single file"""
        with open(html_file, 'r', encoding='utf-8') as f:
            html = f.read()
        
        filename = html_file.stem
        issues = []
        
        # Check 1: Title tag
        title_match = re.search(r'<title>(.*?)</title>', html)
        if title_match:
            title = title_match.group(1)
            title_len = len(title)
            if self.TITLE_MIN_LENGTH <= title_len <= self.TITLE_MAX_LENGTH:
                self.results['title_optimal'] += 1
            else:
                issues.append(f"Title length {title_len} (optimal: {self.TITLE_MIN_LENGTH}-{self.TITLE_MAX_LENGTH})")
        
        # Check 2: Meta description
        meta_desc = re.search(r'<meta name="description" content="(.*?)"', html)
        if meta_desc:
            desc = meta_desc.group(1)
            desc_len = len(desc)
            if self.META_DESC_MIN_LENGTH <= desc_len <= self.META_DESC_MAX_LENGTH:
                self.results['meta_desc_optimal'] += 1
            else:
                issues.append(f"Meta desc {desc_len} (optimal: {self.META_DESC_MIN_LENGTH}-{self.META_DESC_MAX_LENGTH})")
        
        # Check 3: H1 tags
        h1_count = len(re.findall(r'<h1[^>]*>(.*?)</h1>', html, re.IGNORECASE))
        if h1_count >= 1:
            self.results['h1_present'] += 1
        else:
            issues.append(f"H1 count: {h1_count} (should have exactly 1)")
        
        # Check 4: Internal links
        internal_links = len(re.findall(r'href="(/[^"]*)"', html))
        if internal_links > 0:
            self.results['internal_links'] += 1
        else:
            issues.append("No internal links found")
        
        # Check 5: Image alt text (for linked images)
        images = re.findall(r'<img[^>]*>', html)
        images_with_alt = sum(1 for img in images if 'alt=' in img)
        if len(images) == 0 or images_with_alt == len(images):
            self.results['image_alt_text'] += 1
        else:
            issues.append(f"Images without alt: {len(images) - images_with_alt}/{len(images)}")
        
        # Check 6: Mobile ready (viewport meta)
        if 'viewport' in html:
            self.results['mobile_ready'] += 1
        else:
            issues.append("No viewport meta tag")
        
        # Check 7: Schema markup
        schemas = re.findall(r'"@type":\s*"(LocalBusiness|Service|FAQPage)"', html)
        if len(schemas) >= 2:
            self.results['schema_markup'] += 1
        else:
            issues.append(f"Schema markup: {len(schemas)} types found")
        
        return {'file': filename, 'issues': issues}
    
    def validate_all(self, output_dir: Path):
        """Validate all HTML files"""
        html_files = sorted(output_dir.glob('*.html'))
        all_files_results = []
        
        for html_file in html_files:
            result = self.check_file(html_file)
            all_files_results.append(result)
            if result['issues']:
                self.issues.extend([(result['file'], issue) for issue in result['issues']])
        
        return all_files_results, html_files


def main():
    output_dir = Path('output')
    validator = SEOValidator()
    
    print('\n' + '='*70)
    print('STEP 4.5: SEO COMPLIANCE CHECK')
    print('='*70 + '\n')
    
    all_results, html_files = validator.validate_all(output_dir)
    total_files = len(html_files)
    
    print(f'📱 SEO OPTIMIZATION RESULTS')
    print(f'  Total pages checked: {total_files}')
    print()
    
    print(f'✓ OPTIMIZATIONS VERIFIED')
    print(f'  Title length: {validator.results["title_optimal"]}/{total_files}')
    print(f'  Meta description: {validator.results["meta_desc_optimal"]}/{total_files}')
    print(f'  H1 tags: {validator.results["h1_present"]}/{total_files}')
    print(f'  Internal links: {validator.results["internal_links"]}/{total_files}')
    print(f'  Image alt text: {validator.results["image_alt_text"]}/{total_files}')
    print(f'  Mobile responsive: {validator.results["mobile_ready"]}/{total_files}')
    print(f'  Schema markup: {validator.results["schema_markup"]}/{total_files}')
    print()
    
    # Calculate score
    total_checks = sum(validator.results.values())
    max_checks = len(validator.results) * total_files
    score = (total_checks / max_checks * 100) if max_checks > 0 else 0
    
    print(f'📊 OVERALL SEO SCORE: {score:.1f}%')
    print()
    
    if validator.issues:
        print(f'⚠️ ISSUES FOUND ({len(validator.issues)} total):')
        # Group by issue type
        issue_groups = defaultdict(int)
        for file, issue in validator.issues:
            issue_groups[issue.split(':')[0]] += 1
        
        for issue_type, count in sorted(issue_groups.items(), key=lambda x: x[1], reverse=True)[:5]:
            print(f'  - {issue_type}: {count} pages')
    else:
        print(f'✅ NO ISSUES - All SEO checks passed!')
    
    print()
    print('='*70)
    status = 'PASSED' if score >= 90 else 'REVIEW NEEDED'
    print(f'STATUS: SEO COMPLIANCE {status}')
    print('='*70 + '\n')


if __name__ == '__main__':
    main()
