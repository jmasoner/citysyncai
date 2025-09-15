import { registerBlockType } from '@wordpress/blocks';
import { useEffect, useState } from '@wordpress/element';

registerBlockType('citysyncai/schema-block', {
    title: 'CitySyncAI – Schema',
    icon: 'editor-code',
    category: 'widgets',
    edit() {
        const [markup, setMarkup] = useState('Loading schema...');

        useEffect(() => {
            fetch('/wp-json/citysyncai/v1/schema')
                .then(res => res.json())
                .then(data => setMarkup(data.markup));
        }, []);

        return <pre>{markup}</pre>;
    },
    save() {
        return null;
    }
});