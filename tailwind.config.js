import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Livewire/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
                serif: ['"Lora"', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                cream: '#FAF8F5',
                espresso: '#23201E',
                muted: '#6B635B',
                'warm-border': '#E6E1D6',
                forest: {
                    DEFAULT: '#3A5A40',
                    hover: '#2D4732',
                },
                terracotta: {
                    DEFAULT: '#D97706',
                    hover: '#B45309',
                },
            },
        },
    },

    plugins: [forms],
};
