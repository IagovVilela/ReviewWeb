/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.jsx',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                ink: {
                    DEFAULT: '#0A0A0A',
                    muted: '#525252',
                    subtle: '#737373',
                },
                surface: {
                    DEFAULT: '#FAFAFA',
                    raised: '#FFFFFF',
                    border: '#E5E5E5',
                },
                accent: {
                    DEFAULT: '#7C3AED',
                    hover: '#6D28D9',
                    muted: '#EDE9FE',
                },
                primary: {
                    50: '#faf5ff',
                    100: '#f3e8ff',
                    200: '#e9d5ff',
                    300: '#d8b4fe',
                    400: '#c084fc',
                    500: '#8b5cf6',
                    600: '#7c3aed',
                    700: '#6d28d9',
                    800: '#5b21b6',
                    900: '#4c1d95',
                },
            },
            fontFamily: {
                display: ['"Instrument Serif"', 'Georgia', 'serif'],
                sans: ['"DM Sans"', 'system-ui', 'sans-serif'],
            },
            letterSpacing: {
                display: '-0.03em',
                tight: '-0.02em',
            },
            maxWidth: {
                editorial: '72rem',
                prose: '40rem',
            },
            boxShadow: {
                editorial: '0 1px 2px rgba(10, 10, 10, 0.04), 0 8px 24px rgba(10, 10, 10, 0.06)',
                'editorial-lg': '0 2px 8px rgba(10, 10, 10, 0.06), 0 24px 48px rgba(10, 10, 10, 0.08)',
            },
            transitionTimingFunction: {
                editorial: 'cubic-bezier(0.16, 1, 0.3, 1)',
            },
        },
    },
    plugins: [],
};
