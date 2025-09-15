import { registerBlockType } from '@wordpress/blocks';
import { useEffect, useState } from '@wordpress/element';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, SelectControl } from '@wordpress/components';

registerBlockType('citysyncai/ai-block', {
    title: 'CitySyncAI – AI Content',
    icon: 'admin-site-alt3',
    category: 'widgets',
    attributes: {
        city: { type: 'string', default: '' },
        type: { type: 'string', default: 'overview' },
        provider: { type: 'string', default: 'openai' },
    },
    edit({ attributes, setAttributes }) {
        const [content, setContent] = useState('Loading...');
        const { city, type, provider } = attributes;

        useEffect(() => {
            fetch('/wp-json/citysyncai/v1/generate', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ city, type, provider })
            })
            .then(res => res.json())
            .then(data => setContent(data.content));
        }, [city, type, provider]);

        return (
            <>
                <InspectorControls>
                    <PanelBody title="AI Settings">
                        <TextControl label="City" value={city} onChange={(val) => setAttributes({ city: val })} />
                        <SelectControl
                            label="Content Type"
                            value={type}
                            options={[
                                { label: 'Overview', value: 'overview' },
                                { label: 'Services', value: 'services' },
                                { label: 'Testimonials', value: 'testimonials' },
                                { label: 'Custom', value: 'custom' }
                            ]}
                            onChange={(val) => setAttributes({ type: val })}
                        />
                        <SelectControl
                            label="Provider"
                            value={provider}
                            options={[
                                'openai', 'gemini', 'claude', 'deepseek', 'genspark', 'grok'
                            ].map(p => ({ label: p, value: p }))}
                            onChange={(val) => setAttributes({ provider: val })}
                        />
                    </PanelBody>
                </InspectorControls>
                <div className="citysyncai-block-preview">{content}</div>
            </>
        );
    },
    save() {
        return null; // Rendered dynamically
    }
});