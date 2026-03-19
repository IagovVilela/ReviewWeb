import React from 'react';
import { createRoot } from 'react-dom/client';
import HomeModernSection from './home/HomeModernSection';

function mountHomeModernSection() {
    const rootElement = document.getElementById('homeModernRoot');
    if (!rootElement) {
        return;
    }

    const translationsRaw = rootElement.dataset.translations ?? '{}';
    const statsRaw = rootElement.dataset.stats ?? '[]';

    let translations = {};
    let stats = [];

    try {
        translations = JSON.parse(translationsRaw);
    } catch (error) {
        console.error('Failed to parse home translations', error);
    }

    try {
        stats = JSON.parse(statsRaw);
    } catch (error) {
        console.error('Failed to parse home stats', error);
    }

    const root = createRoot(rootElement);
    root.render(
        <HomeModernSection
            translations={translations}
            stats={stats}
            onOpenContact={() => window.openContactModal?.()}
        />
    );
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', mountHomeModernSection);
} else {
    mountHomeModernSection();
}
