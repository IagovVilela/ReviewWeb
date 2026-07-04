import React from 'react';
import { createRoot } from 'react-dom/client';
import MarketingPage from './marketing/MarketingPage';

function mountMarketingPage() {
    const rootElement = document.getElementById('marketingRoot');
    if (!rootElement) {
        return;
    }

    const parse = (key, fallback) => {
        try {
            return JSON.parse(rootElement.dataset[key] ?? fallback);
        } catch {
            console.error(`Failed to parse ${key}`);
            return JSON.parse(fallback);
        }
    };

    const translations = parse('translations', '{}');
    const stats = parse('stats', '[]');
    const assets = parse('assets', '{}');
    const csrfToken = rootElement.dataset.csrf ?? '';

    const root = createRoot(rootElement);
    root.render(
        <MarketingPage
            translations={translations}
            stats={stats}
            assets={assets}
            csrfToken={csrfToken}
        />
    );
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', mountMarketingPage);
} else {
    mountMarketingPage();
}
