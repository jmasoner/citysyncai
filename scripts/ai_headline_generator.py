#!/usr/bin/env python3
"""
AI Headline Generator using DeepSeek
Generates compelling, unique headlines for city/service combinations
"""

import requests
import os
import json
import time
from pathlib import Path
from typing import Tuple, Dict, Any

# Load environment variables
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

class HeadlineGenerator:
    """Generate AI headlines for landing pages"""
    
    def __init__(self, api_key=None):
        self.api_key = api_key or API_KEY
        self.api_url = API_URL
        self.total_cost = 0
        self.total_tokens = 0
        self.requests_made = 0
    
    def generate_headline(self, city: str, state: str, service: str, 
                         market_data: Dict[str, Any]) -> Tuple[str, float]:
        """
        Generate a unique headline for a city/service combination
        
        Args:
            city: City name
            state: State abbreviation
            service: Service type (VoIP, Internet, Fiber, etc.)
            market_data: City business metrics from fetch_city_data
        
        Returns:
            Tuple of (headline, cost)
        """
        
        # Build context from market data
        context = f"""
City: {city}, {state}
Service: {service}
Businesses: {market_data.get('total_businesses', 'N/A')}
Growth: {market_data.get('business_growth_rate', 'N/A')}% YoY
Market Gap: {market_data.get('market_gap', 'N/A')}
Fiber Coverage: {market_data.get('fiber_availability_pct', 'N/A')}%
"""
        
        # Craft the prompt for unique, compelling headlines
        prompt = f"""Based on this market context, generate ONE compelling headline (max 70 characters) 
for a {service} landing page. The headline should appeal to business owners.

{context}

Requirements:
- Include or reference the city name
- Focus on business value, not marketing fluff
- Be unique (not "Best X in Y")
- Include a benefit or differentiation
- Maximum 70 characters including spaces

Examples of GOOD headlines for reference:
- "Austin's Fastest VoIP: Save 40% on Phone Costs"
- "Enterprise Fiber for New York Startups"
- "Los Angeles Internet Built for Hollywood"

Return ONLY the headline without quotes or explanation:"""
        
        try:
            response = requests.post(
                self.api_url,
                headers={
                    "Authorization": f"Bearer {self.api_key}",
                    "Content-Type": "application/json"
                },
                json={
                    "model": "deepseek-chat",
                    "messages": [
                        {
                            "role": "system",
                            "content": "You are an expert marketing copywriter for B2B telecom services. Generate compelling, unique headlines that appeal to business decision-makers."
                        },
                        {
                            "role": "user",
                            "content": prompt
                        }
                    ],
                    "temperature": 0.8,  # Adds variation
                    "max_tokens": 60
                },
                timeout=20
            )
            
            if response.status_code == 200:
                data = response.json()
                headline = data["choices"][0]["message"]["content"].strip()
                
                # Remove quotes if present
                headline = headline.strip('"\'')
                
                # Calculate cost
                tokens = data["usage"]["total_tokens"]
                cost = tokens * 0.0000036  # DeepSeek pricing estimate
                
                # Track metrics
                self.total_cost += cost
                self.total_tokens += tokens
                self.requests_made += 1
                
                return headline, cost
            else:
                error = response.json().get("error", {}).get("message", "Unknown error")
                return f"Error: {error}", 0
        
        except requests.exceptions.Timeout:
            return "Error: Request timeout (API slow)", 0
        except Exception as e:
            return f"Error: {str(e)[:50]}", 0
    
    def generate_batch(self, cities_services: list, market_data_func=None) -> list:
        """
        Generate headlines for multiple city/service combinations
        
        Args:
            cities_services: List of tuples [(city, state, service), ...]
            market_data_func: Function to fetch market data for each city
        
        Returns:
            List of dicts with headline and metadata
        """
        
        results = []
        
        for city, state, service in cities_services:
            # Get market data
            market_data = {}
            if market_data_func:
                try:
                    market_data = market_data_func(city, state)
                except:
                    market_data = {}
            
            # Generate headline
            headline, cost = self.generate_headline(city, state, service, market_data)
            
            results.append({
                "city": city,
                "state": state,
                "service": service,
                "headline": headline,
                "cost": cost,
                "success": not headline.startswith("Error")
            })
            
            # Rate limiting (avoid overwhelming API)
            time.sleep(0.5)
        
        return results
    
    def get_stats(self) -> Dict[str, Any]:
        """Get cost and performance statistics"""
        return {
            "requests_made": self.requests_made,
            "total_tokens": self.total_tokens,
            "total_cost": self.total_cost,
            "average_tokens": self.total_tokens / self.requests_made if self.requests_made > 0 else 0,
            "average_cost": self.total_cost / self.requests_made if self.requests_made > 0 else 0
        }


if __name__ == "__main__":
    from fetch_city_data import get_city_data
    
    print("\n" + "="*70)
    print("AI HEADLINE GENERATOR TEST")
    print("="*70)
    
    generator = HeadlineGenerator()
    
    # Test cases
    test_cases = [
        ("Austin", "TX", "VoIP"),
        ("Austin", "TX", "Internet"),
        ("New York", "NY", "Fiber"),
        ("Los Angeles", "CA", "Network"),
        ("Chicago", "IL", "Security"),
    ]
    
    print(f"\nGenerating {len(test_cases)} headlines...\n")
    
    for city, state, service in test_cases:
        market_data = get_city_data(city, state)
        headline, cost = generator.generate_headline(city, state, service, market_data)
        
        print(f"[{city:15s} {service:10s}]")
        print(f"  Headline: {headline}")
        print(f"  Cost: ${cost:.6f}")
        print()
    
    # Print statistics
    stats = generator.get_stats()
    print("="*70)
    print("BATCH STATISTICS")
    print("="*70)
    print(f"Requests made: {stats['requests_made']}")
    print(f"Total tokens: {stats['total_tokens']}")
    print(f"Total cost: ${stats['total_cost']:.6f}")
    print(f"Average tokens/headline: {stats['average_tokens']:.1f}")
    print(f"Average cost/headline: ${stats['average_cost']:.6f}")
    print(f"\nProjected costs:")
    print(f"  50 headlines: ${stats['average_cost'] * 50:.4f}")
    print(f"  1,000 headlines: ${stats['average_cost'] * 1000:.4f}")
    print(f"  50,000 headlines: ${stats['average_cost'] * 50000:.2f}")
