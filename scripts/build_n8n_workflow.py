#!/usr/bin/env python3
"""
n8n Workflow Builder for CitySync AI
Generates a complete 12-node workflow for batch page generation with AI content
"""

import json
from datetime import datetime
from pathlib import Path


class N8nWorkflowBuilder:
    """Builds n8n workflow JSON for page generation"""
    
    def __init__(self, name="CitySync AI - Page Generator"):
        self.name = name
        self.nodes = []
        self.connections = []
        self.node_counter = 0
    
    def add_node(self, name, type_name, params, position_x=0, position_y=0):
        """Add a node to the workflow"""
        node = {
            "id": str(self.node_counter),
            "name": name,
            "type": type_name,
            "typeVersion": 1,
            "position": [position_x, position_y],
            "parameters": params
        }
        self.nodes.append(node)
        node_id = self.node_counter
        self.node_counter += 1
        return node_id
    
    def connect_nodes(self, from_node_id, to_node_id, from_output=0, to_input=0):
        """Connect two nodes"""
        self.connections.append({
            "source_node": from_node_id,
            "target_node": to_node_id,
            "source_index": from_output,
            "target_index": to_input
        })
    
    def build(self):
        """Build the final workflow JSON"""
        return {
            "name": self.name,
            "nodes": self.nodes,
            "connections": self.connections,
            "active": True,
            "settings": {},
            "tags": ["citysyncai", "page-generator", "ai-content"],
            "version": 1,
            "createdAt": datetime.now().isoformat(),
            "updatedAt": datetime.now().isoformat()
        }


def create_citysync_workflow():
    """Create the complete CitySync AI workflow"""
    
    builder = N8nWorkflowBuilder()
    
    # Node 0: Trigger (Manual)
    trigger = builder.add_node(
        "Trigger",
        "core:n8n-nodes-base.manualTrigger",
        {},
        100, 100
    )
    
    # Node 1: Set batch parameters
    batch_params = builder.add_node(
        "Set Batch Parameters",
        "core:n8n-nodes-base.set",
        {
            "values": {
                "object": [
                    {
                        "key": "cities",
                        "value": "Austin,New York,Los Angeles,Chicago,Houston"
                    },
                    {
                        "key": "services",
                        "value": "VoIP,Internet,Fiber,Network,Security"
                    },
                    {
                        "key": "batch_size",
                        "value": "50"
                    },
                    {
                        "key": "max_retries",
                        "value": "3"
                    }
                ]
            },
            "options": {},
            "newPropertyName": "batch"
        },
        250, 100
    )
    builder.connect_nodes(trigger, batch_params)
    
    # Node 2: Query available cities from PostgreSQL
    query_cities = builder.add_node(
        "Query Cities from DB",
        "core:n8n-nodes-base.postgres",
        {
            "host": "localhost",
            "port": 5432,
            "user": "{{$env.DB_USER}}",
            "password": "{{$env.DB_PASSWORD}}",
            "database": "{{$env.DB_NAME}}",
            "query": "SELECT id, name, state FROM cities ORDER BY name LIMIT 50"
        },
        400, 100
    )
    builder.connect_nodes(batch_params, query_cities)
    
    # Node 3: Loop through cities and services
    loop_cities = builder.add_node(
        "For Each City/Service",
        "core:n8n-nodes-base.executeCode",
        {
            "jsCode": """
// Get cities from database
const cities = items[0].json.rows || [];
const services = ['VoIP', 'Internet', 'Fiber', 'Network', 'Security'];

// Generate combinations
const combinations = [];
for (const city of cities) {
    for (const service of services) {
        combinations.push({
            city: city.name,
            state: city.state,
            service: service,
            service_id: service.toLowerCase()
        });
    }
}

return combinations.map((combo, idx) => ({
    json: combo,
    pairedItem: { item: 0 }
}));
            """
        },
        550, 100
    )
    builder.connect_nodes(query_cities, loop_cities)
    
    # Node 4: Fetch city business data
    fetch_city_data = builder.add_node(
        "Fetch City Business Data",
        "core:n8n-nodes-base.executeCommand",
        {
            "command": "python scripts/fetch_city_data.py {{$json.city}} {{$json.state}} {{$json.service}}"
        },
        700, 100
    )
    builder.connect_nodes(loop_cities, fetch_city_data)
    
    # Node 5: Generate AI-enhanced headline via DeepSeek
    ai_headline = builder.add_node(
        "Generate AI Headline",
        "core:n8n-nodes-base.httpRequest",
        {
            "url": "https://api.deepseek.com/v1/chat/completions",
            "method": "POST",
            "headers": {
                "Authorization": "Bearer {{$env.DEEPSEEK_API_KEY}}",
                "Content-Type": "application/json"
            },
            "body": {
                "model": "deepseek-chat",
                "messages": [
                    {
                        "role": "system",
                        "content": "You are an expert marketing copywriter specializing in B2B telecom services. Generate compelling, SEO-optimized headlines for landing pages."
                    },
                    {
                        "role": "user",
                        "content": "Generate a unique, compelling headline for a {{$json.service}} landing page in {{$json.city}}, {{$json.state}}. {{$json.market_gap}} Focus on business value, not generic marketing. Return ONLY the headline, no quotes."
                    }
                ],
                "temperature": 0.7,
                "max_tokens": 100
            }
        },
        850, 100
    )
    builder.connect_nodes(fetch_city_data, ai_headline)
    
    # Node 6: Extract headline from AI response
    extract_headline = builder.add_node(
        "Extract Headline",
        "core:n8n-nodes-base.set",
        {
            "values": {
                "object": [
                    {
                        "key": "ai_headline",
                        "value": "={{$json.body.choices[0].message.content}}"
                    }
                ]
            }
        },
        1000, 100
    )
    builder.connect_nodes(ai_headline, extract_headline)
    
    # Node 7: Render HTML template
    render_template = builder.add_node(
        "Render HTML Template",
        "core:n8n-nodes-base.executeCommand",
        {
            "command": "python scripts/render_template.py --city {{$json.city}} --state {{$json.state}} --service {{$json.service}} --output output/ --template templates/base.html"
        },
        1150, 100
    )
    builder.connect_nodes(extract_headline, render_template)
    
    # Node 8: Validate HTML output
    validate_html = builder.add_node(
        "Validate HTML",
        "core:n8n-nodes-base.executeCommand",
        {
            "command": "python scripts/validate_schema.py"
        },
        1300, 100
    )
    builder.connect_nodes(render_template, validate_html)
    
    # Node 9: Store page metadata in database
    store_metadata = builder.add_node(
        "Store Page Metadata",
        "core:n8n-nodes-base.postgres",
        {
            "host": "localhost",
            "port": 5432,
            "user": "{{$env.DB_USER}}",
            "password": "{{$env.DB_PASSWORD}}",
            "database": "{{$env.DB_NAME}}",
            "query": "INSERT INTO pages (city_id, service_id, title, slug, url, ai_headline, generated_at) VALUES ((SELECT id FROM cities WHERE name=:city AND state=:state), (SELECT id FROM services WHERE name=:service), :title, :slug, :url, :ai_headline, NOW()) ON CONFLICT DO NOTHING",
            "parameters": {
                "city": "{{$json.city}}",
                "state": "{{$json.state}}",
                "service": "{{$json.service}}",
                "title": "{{$json.ai_headline}} | {{$json.city}}, {{$json.state}}",
                "slug": "{{$json.city}}-{{$json.service}}-{{$json.state}}",
                "url": "https://combrokers.com/pages/{{$json.city}}-{{$json.service}}-{{$json.state}}/",
                "ai_headline": "{{$json.ai_headline}}"
            }
        },
        1450, 100
    )
    builder.connect_nodes(validate_html, store_metadata)
    
    # Node 10: Update success counter
    update_counter = builder.add_node(
        "Update Counter",
        "core:n8n-nodes-base.executeCode",
        {
            "jsCode": """
// Increment counter in Redis
const current = items[0].json.counter || 0;
return [{json: {counter: current + 1, status: 'success'}, pairedItem: {item: 0}}];
            """
        },
        1600, 100
    )
    builder.connect_nodes(store_metadata, update_counter)
    
    # Node 11: Log completion
    log_completion = builder.add_node(
        "Log Completion",
        "core:n8n-nodes-base.set",
        {
            "values": {
                "object": [
                    {
                        "key": "status",
                        "value": "COMPLETED"
                    },
                    {
                        "key": "pages_generated",
                        "value": "={{$json.counter}}"
                    },
                    {
                        "key": "timestamp",
                        "value": "={{new Date().toISOString()}}"
                    }
                ]
            }
        },
        1750, 100
    )
    builder.connect_nodes(update_counter, log_completion)
    
    # Node 12: Error handler
    error_handler = builder.add_node(
        "Error Handler",
        "core:n8n-nodes-base.catch",
        {
            "errorMessage": "Page generation failed: {{$error.message}}"
        },
        1750, 300
    )
    
    return builder.build()


def main():
    workflow = create_citysync_workflow()
    
    # Save to file
    output_file = Path("n8n_workflow_phase2.json")
    with open(output_file, "w") as f:
        json.dump(workflow, f, indent=2)
    
    print(f"✓ Workflow created: {output_file}")
    print(f"  Nodes: {len(workflow['nodes'])}")
    print(f"  Connections: {len(workflow['connections'])}")
    print(f"\nTo import into n8n:")
    print(f"  1. Open http://localhost:5678")
    print(f"  2. Click 'Import' → 'From file'")
    print(f"  3. Select {output_file}")
    print(f"  4. Configure any missing credentials")


if __name__ == "__main__":
    main()
