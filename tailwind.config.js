import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#0070F3',
                'primary-hover': '#005CC5',
                white: '#FFFFFF',
                'bg-main': '#F8F9FA',
                'text-secondary': '#6C757D',
                'text-primary': '#333333',
                danger: '#DC3545',
                'danger-hover': '#C82333',
                success: '#28A745',
                warning: '#FFC107',
            },
            boxShadow: {
                soft: '0 10px 30px rgba(0,0,0,0.06)',
            },
            borderRadius: {
                xl: '16px',
            },
        },
    },
    plugins: [],
};
