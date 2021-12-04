const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

module.exports = {
    mode: 'jit',
    purge: [
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/bootstrap.js',
    ],
    darkMode: 'media',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            backgroundOpacity: ['dark'],
        },
    },
    variants: {},
    plugins: [
        require('@tailwindcss/forms'),
    ],
};
