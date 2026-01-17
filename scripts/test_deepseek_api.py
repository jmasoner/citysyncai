#!/usr/bin/env python3
"""
Test DeepSeek API connectivity and pricing
"""

import requests
import os
import json
from pathlib import Path

# Load environment variables from .env
env_file = Path(__file__).parent.parent / ".env"
if env_file.exists():
    with open(env_file) as f:
        for line in f:
            line = line.strip()
            if line and not line.startswith("#") and "=" in line:
                key, value = line.split("=", 1)
                value = value.strip('"\'')
                os.environ[key] = value

API_KEY = os.getenv("DEEPSEEK_API_KEY")
API_URL = "https://api.deepseek.com/v1/chat/completions"

def test_api():
    """Test basic API connectivity"""
    print("\n" + "="*70)
    print("DEEPSEEK API CONNECTIVITY TEST")
    print("="*70)
    
    if not API_KEY:
        print("\n[ERROR] DEEPSEEK_API_KEY not found in environment")
        print("Please ensure .env file contains: DEEPSEEK_API_KEY=your_key_here")
        return False
    
    print(f"\n[INFO] API Key detected: {API_KEY[:20]}...{API_KEY[-10:]}")
    print(f"[INFO] API URL: {API_URL}")
    
    # Test 1: Simple request
    print("\n[TEST 1] Simple API Request")
    print("-" * 70)
    
    payload = {
        "model": "deepseek-chat",
        "messages": [
            {
                "role": "system",
                "content": "You are a marketing copywriter for telecom services."
            },
            {
                "role": "user",
                "content": "Write one compelling headline (max 70 chars) for VoIP services in Austin, TX. Reply with ONLY the headline."
            }
        ],
        "temperature": 0.7,
        "max_tokens": 50
    }
    
    try:
        response = requests.post(
            API_URL,
            headers={
                "Authorization": f"Bearer {API_KEY}",
                "Content-Type": "application/json"
            },
            json=payload,
            timeout=15
        )
        
        print(f"[RESPONSE] Status Code: {response.status_code}")
        
        if response.status_code == 200:
            data = response.json()
            
            # Extract headline
            headline = data["choices"][0]["message"]["content"].strip()
            tokens_used = data["usage"]["total_tokens"]
            
            print(f"\n[SUCCESS] API is responding correctly!")
            print(f"\nGenerated Headline:")
            print(f"  '{headline}'")
            print(f"\nToken Usage:")
            print(f"  Prompt tokens: {data['usage']['prompt_tokens']}")
            print(f"  Completion tokens: {data['usage']['completion_tokens']}")
            print(f"  Total tokens: {tokens_used}")
            
            # DeepSeek pricing: ~$0.0000036 per token (rough estimate)
            cost_per_token = 0.0000036
            total_cost = tokens_used * cost_per_token
            
            print(f"\nCost Analysis:")
            print(f"  Rate: ${cost_per_token:.7f} per token")
            print(f"  This call cost: ${total_cost:.6f}")
            print(f"  Per 100 headlines: ${total_cost * 100:.4f}")
            print(f"  Per 1,000 headlines: ${total_cost * 1000:.2f}")
            print(f"  Per 50,000 headlines: ${total_cost * 50000:.2f}")
            
            return True
        else:
            error_msg = response.text
            print(f"\n[ERROR] API returned error:")
            print(f"  Status: {response.status_code}")
            print(f"  Message: {error_msg}")
            
            # Try to parse as JSON
            try:
                error_data = response.json()
                if "error" in error_data:
                    print(f"  Error Details: {error_data['error']}")
            except:
                pass
            
            return False
    
    except requests.exceptions.Timeout:
        print(f"\n[ERROR] Request timed out after 15 seconds")
        print(f"Possible causes:")
        print(f"  - DeepSeek API is slow")
        print(f"  - Network connectivity issue")
        print(f"  - API rate limited")
        return False
    
    except requests.exceptions.ConnectionError as e:
        print(f"\n[ERROR] Connection failed: {e}")
        print(f"Possible causes:")
        print(f"  - No internet connection")
        print(f"  - API URL is incorrect")
        print(f"  - Firewall/proxy blocking")
        return False
    
    except Exception as e:
        print(f"\n[ERROR] Unexpected error: {e}")
        return False


def test_multiple_headlines():
    """Test generating multiple headlines to estimate batch costs"""
    print("\n" + "="*70)
    print("BATCH COST ESTIMATION")
    print("="*70)
    
    if not API_KEY:
        print("\n[SKIP] API key not configured")
        return
    
    cities = ["Austin", "New York", "Los Angeles"]
    services = ["VoIP", "Internet", "Fiber"]
    total_cost = 0
    
    print(f"\nGenerating {len(cities)} x {len(services)} = {len(cities)*len(services)} sample headlines\n")
    
    for city in cities:
        for service in services:
            prompt = f"Write a 70-char headline for {service} services in {city}, TX. Reply with ONLY the headline."
            
            payload = {
                "model": "deepseek-chat",
                "messages": [{"role": "user", "content": prompt}],
                "temperature": 0.7,
                "max_tokens": 50
            }
            
            try:
                response = requests.post(
                    API_URL,
                    headers={
                        "Authorization": f"Bearer {API_KEY}",
                        "Content-Type": "application/json"
                    },
                    json=payload,
                    timeout=10
                )
                
                if response.status_code == 200:
                    data = response.json()
                    headline = data["choices"][0]["message"]["content"].strip()
                    tokens = data["usage"]["total_tokens"]
                    cost = tokens * 0.0000036
                    total_cost += cost
                    
                    print(f"[OK] {city:15s} {service:10s} | Tokens: {tokens:3d} | Cost: ${cost:.6f} | Headline: {headline[:50]}")
                else:
                    print(f"[FAIL] {city:15s} {service:10s} | Status {response.status_code}")
            
            except Exception as e:
                print(f"[ERROR] {city:15s} {service:10s} | {str(e)[:40]}")
    
    print(f"\nSample batch summary:")
    print(f"  Total cost for {len(cities)*len(services)} headlines: ${total_cost:.6f}")
    print(f"  Average per headline: ${total_cost/(len(cities)*len(services)):.6f}")
    print(f"  Estimated cost for 50 headlines: ${(total_cost/(len(cities)*len(services))) * 50:.4f}")


if __name__ == "__main__":
    success = test_api()
    
    if success:
        print("\n" + "="*70)
        print("[SUCCESS] DeepSeek API is working!")
        print("="*70)
        test_multiple_headlines()
    else:
        print("\n" + "="*70)
        print("[FAILURE] Could not connect to DeepSeek API")
        print("="*70)
        print("\nTroubleshooting steps:")
        print("1. Verify API key is correct in .env file")
        print("2. Check internet connection")
        print("3. Verify DeepSeek account has available credits")
        print("4. Check rate limits (5 requests/min for free tier)")
