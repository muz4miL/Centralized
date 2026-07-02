import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                // Primary accent — Indigo-Violet spectrum (distinctive, not default blue)
                primary: {
                    50:  '#f0f0ff',
                    100: '#e0e1ff',
                    200: '#c7c8fe',
                    300: '#a5a3fc',
                    400: '#8b7df8',
                    500: '#7c5df2',
                    600: '#6d3de6',
                    700: '#5e30cb',
                    800: '#4e29a4',
                    900: '#412782',
                    950: '#28174f',
                },
                // Neutral base — Slate (not generic gray)
                surface: {
                    50:  '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    300: '#cbd5e1',
                    400: '#94a3b8',
                    500: '#64748b',
                    600: '#475569',
                    700: '#334155',
                    800: '#1e293b',
                    900: '#0f172a',
                    950: '#020617',
                },
                // Semantic status colors — custom-tinted, not raw Tailwind defaults
                success: {
                    light: '#d1fae5',
                    DEFAULT: '#10b981',
                    dark: '#047857',
                },
                warning: {
                    light: '#fef3c7',
                    DEFAULT: '#f59e0b',
                    dark: '#b45309',
                },
                danger: {
                    light: '#fee2e2',
                    DEFAULT: '#ef4444',
                    dark: '#b91c1c',
                },
                info: {
                    light: '#cffafe',
                    DEFAULT: '#06b6d4',
                    dark: '#0e7490',
                },
                // Sidebar dark
                sidebar: {
                    bg: '#0f172a',
                    hover: '#1e293b',
                    active: '#1e293b',
                    text: '#94a3b8',
                    textActive: '#f8fafc',
                },
            },
            fontFamily: {
                heading: ['"Space Grotesk"', ...defaultTheme.fontFamily.sans],
                sans: ['"Inter"', ...defaultTheme.fontFamily.sans],
            },
            borderRadius: {
                'card': '1rem',    // rounded-xl for cards
                'btn': '0.625rem', // rounded-lg for buttons/inputs
            },
            boxShadow: {
                'card': '0 1px 3px 0 rgba(0,0,0,0.06), 0 1px 2px 0 rgba(0,0,0,0.04)',
                'card-hover': '0 4px 12px 0 rgba(0,0,0,0.08), 0 2px 4px 0 rgba(0,0,0,0.04)',
                'sidebar': '2px 0 8px 0 rgba(0,0,0,0.12)',
            },
            spacing: {
                '4.5': '1.125rem',
                '13': '3.25rem',
                '15': '3.75rem',
                '18': '4.5rem',
            },
        },
    },

    plugins: [forms],
};
