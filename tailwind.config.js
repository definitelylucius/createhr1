import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        "./resources/**/*.js",
        "./resources/**/*.vue"
    ],

    darkMode: false, // Disables Tailwind's dark mode

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    200: '#bae6fd',
                    300: '#7dd3fc',
                    400: '#38bdf8',
                    500: '#0ea5e9',
                    600: '#0284c7',
                    700: '#0369a1',
                    800: '#075985',
                    900: '#0c4a6e',
                },
            }
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('daisyui'),
    ],

    daisyui: {
        themes: [
            {
                corporate: {
                    "primary": "#2563eb",
                    "secondary": "#4b5563",
                    "accent": "#1d4ed8",
                    "neutral": "#1f2937",
                    "base-100": "#ffffff",
                    "info": "#3b82f6",
                    "success": "#10b981",
                    "warning": "#f59e0b",
                    "error": "#ef4444",
                    "--rounded-box": "0.5rem",
                    "--rounded-btn": "0.25rem",
                },
            },
            "light"
        ],
        darkTheme: false, // Disables DaisyUI's dark theme
        base: true,
        styled: true,
        utils: true,
        prefix: "",
        logs: true,
    },
};
