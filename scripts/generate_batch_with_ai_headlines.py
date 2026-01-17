#!/usr/bin/env python3
"""
Batch Page Generator with AI Headlines
Generates 50 pages (10 cities × 5 services) with unique AI headlines
"""

import subprocess
import json
import time
from pathlib import Path
from concurrent.futures import ThreadPoolExecutor, as_completed
import sys
sys.path.insert(0, str(Path(__file__).parent))
from fetch_city_data import get_city_data
from ai_headline_generator import HeadlineGenerator

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

def render_page_with_ai(city, state, service, generator, output_dir="output_ai"):
    """Render a single page with AI headline"""
    try:
        # Fetch city data
        city_data = get_city_data(city, state)
        
        # Generate AI headline
        ai_headline, ai_cost = generator.generate_headline(city, state, service, city_data)
        
        if ai_headline.startswith("Error"):
            return {
                "status": "error",
                "city": city,
                "state": state,
                "service": service,
                "error": ai_headline,
                "ai_headline": None,
                "ai_cost": 0
            }
        
        # Render page with AI headline
        cmd = [
            "python",
            "scripts/render_template.py",
            "--city", city,
            "--state", state,
            "--service", service,
            "--output", output_dir,
            "--template", "templates/base.html",
            "--ai-headline", ai_headline
        ]
        
        result = subprocess.run(cmd, capture_output=True, text=True, timeout=15)
        
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
                    "ai_headline": ai_headline,
                    "ai_cost": ai_cost,
                    "error": None
                }
        
        return {
            "status": "failed",
            "city": city,
            "state": state,
            "service": service,
            "ai_headline": ai_headline,
            "ai_cost": ai_cost,
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
            "error": "Timeout after 15 seconds",
            "file": None,
            "size": 0,
            "ai_cost": 0
        }
    except Exception as e:
        return {
            "status": "error",
            "city": city,
            "state": state,
            "service": service,
            "error": str(e),
            "file": None,
            "size": 0,
            "ai_cost": 0
        }


def main():
    print("\n" + "="*70)
    print("AI-POWERED BATCH PAGE GENERATOR")
    print("="*70)
    print(f"\nGenerating {len(CITIES)} × {len(SERVICES)} = {len(CITIES) * len(SERVICES)} pages with AI headlines")
    print(f"Output directory: output_ai/\n")
    
    # Create output directory
    Path("output_ai").mkdir(exist_ok=True)
    
    # Initialize AI headline generator
    generator = HeadlineGenerator()
    
    # Generate all page combinations
    pages_to_generate = [
        (city, state, service)
        for city, state in CITIES
        for service in SERVICES
    ]
    
    start_time = time.time()
    results = []
    
    # Use thread pool for parallel generation
    with ThreadPoolExecutor(max_workers=3) as executor:  # Reduced to 3 for API rate limiting
        futures = {
            executor.submit(render_page_with_ai, city, state, service, generator): (city, state, service)
            for city, state, service in pages_to_generate
        }
        
        completed = 0
        for future in as_completed(futures):
            completed += 1
            result = future.result()
            results.append(result)
            
            status_icon = "[OK]" if result["status"] == "success" else "[FAIL]"
            headline_preview = result.get("ai_headline", "N/A")[:40] if result.get("ai_headline") else "N/A"
            print(f"[{completed:2d}/50] {status_icon} {result['city']:15s} {result['service']:10s} | {headline_preview}")
    
    elapsed_time = time.time() - start_time
    
    # Summary statistics
    successful = [r for r in results if r["status"] == "success"]
    failed = [r for r in results if r["status"] != "success"]
    
    total_size = sum(r.get("size", 0) for r in successful)
    total_ai_cost = sum(r.get("ai_cost", 0) for r in results)
    avg_size = total_size / len(successful) if successful else 0
    
    print("\n" + "="*70)
    print("BATCH GENERATION RESULTS")
    print("="*70)
    print(f"\nPage Generation:")
    print(f"  Successful: {len(successful)}/50")
    print(f"  Failed:     {len(failed)}/50")
    print(f"\nPerformance:")
    print(f"  Total time:     {elapsed_time:.2f} seconds")
    print(f"  Average/page:   {elapsed_time/len(results):.2f} seconds")
    if len(successful) > 0:
        print(f"  Pages/second:   {len(successful)/elapsed_time:.1f}")
    
    print(f"\nFile Sizes:")
    print(f"  Total generated: {total_size:,} bytes ({total_size/1024/1024:.2f} MB)")
    print(f"  Average/page:    {avg_size:,.0f} bytes")
    if successful:
        sizes = sorted([r['size'] for r in successful])
        print(f"  Median/page:     ~{sizes[len(sizes)//2]:,} bytes")
    
    print(f"\nAI Headline Costs:")
    print(f"  Total AI cost:   ${total_ai_cost:.4f}")
    print(f"  Average/page:    ${total_ai_cost/len(results):.6f}")
    print(f"  Cost for 50:     ${total_ai_cost:.4f}")
    print(f"  Projected 1K:    ${(total_ai_cost/len(results))*1000:.2f}")
    print(f"  Projected 50K:   ${(total_ai_cost/len(results))*50000:.2f}")
    
    # Sample headlines
    print(f"\nSample AI Headlines Generated:")
    for r in successful[:3]:
        print(f"  {r['city']:15s} {r['service']:10s}: {r.get('ai_headline', 'N/A')}")
    
    # Quality metrics
    print(f"\nQuality Metrics:")
    print(f"  Files generated: {len(successful)}")
    print(f"  Total variants:  {len(CITIES)} cities × {len(SERVICES)} services")
    print(f"  Success rate:    {len(successful)/len(results)*100:.1f}%")
    
    # Save detailed results
    results_file = Path("generation_results_ai.json")
    with open(results_file, "w") as f:
        json.dump({
            "timestamp": time.strftime("%Y-%m-%d %H:%M:%S"),
            "total_pages": len(results),
            "successful": len(successful),
            "failed": len(failed),
            "elapsed_seconds": elapsed_time,
            "total_size_bytes": total_size,
            "average_size_bytes": avg_size,
            "total_ai_cost": total_ai_cost,
            "results": results
        }, f, indent=2)
    
    print(f"\n[OK] Detailed results saved to: {results_file}")
    
    # AI Generator stats
    gen_stats = generator.get_stats()
    print(f"\nAI Generator Statistics:")
    print(f"  Requests made: {gen_stats['requests_made']}")
    print(f"  Total tokens: {gen_stats['total_tokens']}")
    print(f"  Average tokens/headline: {gen_stats['average_tokens']:.1f}")
    print(f"  API cost: ${gen_stats['total_cost']:.4f}")
    
    print("="*70 + "\n")
    
    return 0 if len(failed) == 0 else 1


if __name__ == "__main__":
    exit(main())
