import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './resources/vue/**/*.vue',
    ],

    safelist: [
        'from-green-400', 'to-green-600',
        'from-green-500', 'to-green-700',
        'from-amber-400', 'to-amber-600',
        'from-amber-500', 'to-amber-700',
        'from-rose-400', 'to-rose-600',
        'from-rose-500', 'to-rose-700',

        'bg-green-500', 'bg-amber-500', 'bg-rose-500',
        'text-green-700', 'text-amber-700', 'text-rose-700',
        'bg-green-100', 'bg-amber-100', 'bg-rose-100',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Cairo', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
