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

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
    plugins: [
        require('daisyui'),
      ],
    
      content: [
        "./resources/**/*.{html,js,php}", // Include paths where Tailwind looks for class names
      ],
      theme: {
        extend: {},
      },
      plugins: [require("daisyui")], // DaisyUI plugin included
      daisyui: {
        themes: [
          {
            mytheme: {
              "primary": "#00446b",  // Dark blue primary
              "secondary": "#005f8f",
              "accent": "#00b4d8",
              "neutral": "#2d3748",
              "base-100": "#f8fafc",
              "info": "#3b82f6",
              "success": "#22c55e",
              "warning": "#facc15",
              "error": "#ef4444",
              "text-base": "#1e293b",  // Default text color
            },
          },
        ],
      },
    };
