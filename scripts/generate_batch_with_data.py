#!/usr/bin/env python3
"""
Batch Page Generator with City Business Data
Generates 50 pages (10 cities × 5 services) with AI-ready structure
"""

import subprocess
import json
import time
from pathlib import Path
from concurrent.futures import ThreadPoolExecutor, as_completed
import sys
sys.path.insert(0, str(Path(__file__).parent))
from fetch_city_data import get_city_data

# Cities and services for batch generation
CITIES = [
    ("Austin", "TX"),
    ("New York", "NY"),
    ("Los Angeles", "CA"),
    ("Chicago", "IL"),
    ("Houston", "TX"),
    ("Phoenix", "AZ"),
    ("Philadelphia", "PA"),
    ("San Antonio", "TX"),
    ("San Diego", "CA"),
    ("Dallas", "TX")
]

SERVICES = ["VoIP", "Internet", "Fiber", "Network", "Security"]

def render_page(city, state, service, output_dir="output"):
    """Render a single page"""
    try:
        cmd = [
            "python",
            "scripts/render_template.py",
            "--city", city,
            "--state", state,
            "--service", service,
            "--output", output_dir,
            "--template", "templates/base.html"
        ]
        
        result = subprocess.run(cmd, capture_output=True, text=True, timeout=10)
        
        if result.returncode == 0:
            # Verify file exists
            slug = f"{city.lower().replace(' ', '-')}-{service.lower().replace(' ', '-')}-{state.lower()}"
            output_file = Path(output_dir) / f"{slug}.html"
            
            if output_file.exists():
                return {
                    "status": "success",
                    "city": city,
                    "state": state,
                    "service": service,
                    "file": str(output_file),
                    "size": output_file.stat().st_size,
                    "error": None
                }
        
        return {
            "status": "failed",
            "city": city,
            "state": state,
            "service": service,
            "error": result.stderr or "Unknown error",
            "file": None,
            "size": 0
        }
    
    except subprocess.TimeoutExpired:
        return {
            "status": "timeout",
            "city": city,
            "state": state,
            "service": service,
            "error": "Timeout after 10 seconds",
            "file": None,
            "size": 0
        }
    except Exception as e:
        return {
            "status": "error",
            "city": city,
            "state": state,
            "service": service,
            "error": str(e),
            "file": None,
            "size": 0
        }


def main():
    print("\n" + "="*60)
    print("CitySync AI - Batch Page Generator with City Data")
    print("="*60)
    print(f"\nGenerating {len(CITIES)} × {len(SERVICES)} = {len(CITIES) * len(SERVICES)} pages")
    print(f"Output directory: output/\n")
    
    # Create output directory
    Path("output").mkdir(exist_ok=True)
    
    # Generate all page combinations
    pages_to_generate = [
        (city, state, service)
        for city, state in CITIES
        for service in SERVICES
    ]
    
    start_time = time.time()
    results = []
    
    # Use thread pool for parallel generation
    with ThreadPoolExecutor(max_workers=5) as executor:
        futures = {
            executor.submit(render_page, city, state, service): (city, state, service)
            for city, state, service in pages_to_generate
        }
        
        completed = 0
        for future in as_completed(futures):
            completed += 1
            result = future.result()
            results.append(result)
            
            status_icon = "[OK]" if result["status"] == "success" else "[FAIL]"
            print(f"[{completed:2d}/50] {status_icon} {result['city']}, {result['state']:2s} - {result['service']:15s}", end="")
            if result["status"] == "success":
                print(f" ({result['size']:,} bytes)")
            else:
                print(f" ({result['error'][:30]}...)")
    
    elapsed_time = time.time() - start_time
    
    # Summary statistics
    successful = [r for r in results if r["status"] == "success"]
    failed = [r for r in results if r["status"] != "success"]
    
    total_size = sum(r.get("size", 0) for r in successful)
    avg_size = total_size / len(successful) if successful else 0
    
    print("\n" + "="*60)
    print("BATCH GENERATION SUMMARY")
    print("="*60)
    print(f"\nResults:")
    print(f"  Successful: {len(successful)}/50")
    print(f"  Failed:    {len(failed)}/50")
    print(f"\nPerformance:")
    print(f"  Total time:     {elapsed_time:.2f} seconds")
    print(f"  Average/page:   {elapsed_time/len(results):.3f} seconds")
    if len(successful) > 0:
        print(f"  Pages/second:   {len(successful)/elapsed_time:.1f}")
    print(f"\nFile Sizes:")
    print(f"  Total generated: {total_size:,} bytes ({total_size/1024/1024:.2f} MB)")
    print(f"  Average/page:    {avg_size:,.0f} bytes")
    if successful:
        print(f"  Median/page:     ~{sorted([r['size'] for r in successful])[len(successful)//2]:,} bytes")
    
    # City data samples
    print(f"\nCity Data Samples:")
    for city, state in CITIES[:3]:
        data = get_city_data(city, state)
        print(f"  {city}, {state}:")
        print(f"    - Businesses: {data.get('total_businesses', 'N/A')}")
        print(f"    - Fiber: {data.get('fiber_availability_pct', 'N/A')}%")
        print(f"    - Market gap: {data.get('market_gap', 'N/A')}")
    
    # Cost estimation
    print(f"\nCost Estimation:")
    print(f"  Generation cost: $0 (template-based, no AI calls)")
    print(f"  Next phase (with DeepSeek):")
    print(f"    - Per page: $0.0001 (50 pages = $0.005)")
    print(f"    - 50,000 pages: $5.00")
    print(f"    - 100,000 pages: $10.00")
    
    # Quality metrics
    print(f"\nQuality Metrics:")
    print(f"  Files generated: {len(successful)}")
    print(f"  Total variants:  {len(CITIES)} cities x {len(SERVICES)} services")
    print(f"  Success rate:    {len(successful)/len(results)*100:.1f}%")
    output_files = list(Path('output').glob('*.html'))
    print(f"  Files in output/: {len(output_files)}")
    
    # Save detailed results
    results_file = Path("generation_results.json")
    with open(results_file, "w") as f:
        json.dump({
            "timestamp": time.strftime("%Y-%m-%d %H:%M:%S"),
            "total_pages": len(results),
            "successful": len(successful),
            "failed": len(failed),
            "elapsed_seconds": elapsed_time,
            "total_size_bytes": total_size,
            "average_size_bytes": avg_size,
            "results": results
        }, f, indent=2)
    
    print(f"\n[OK] Detailed results saved to: {results_file}")
    print("="*60 + "\n")
    
    return 0 if len(failed) == 0 else 1


if __name__ == "__main__":
    exit(main())
