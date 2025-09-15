document.addEventListener('DOMContentLoaded', () => {
    const block = document.querySelector('#citysyncai-block');
    if (!block) return;

    const city = block.dataset.city || '';
    const type = block.dataset.type || 'overview';
    const provider = block.dataset.provider || 'openai';

    fetch(CitySyncAI.rest_url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ city, type, provider })
    })
    .then(res => res.json())
    .then(data => {
        block.innerHTML = `<h3>${provider} AI Output</h3><div>${data.content}</div>`;
    })
    .catch(err => {
        block.innerHTML = `<p>Error loading AI content.</p>`;
        console.error(err);
    });
});
fetch(`${CitySyncAI.rest_url.replace('/generate', '/schema')}`)
  .then(res => res.json())
  .then(data => {
    const schemaBlock = document.querySelector('#citysyncai-schema');
    if (schemaBlock) {
      schemaBlock.innerHTML = `<script type="application/ld+json">${data.markup}</script>`;
    }
  });
  schemaBlock.innerHTML = `
  <pre>${data.markup}</pre>
  <script type="application/ld+json">${data.markup}</script>
`;