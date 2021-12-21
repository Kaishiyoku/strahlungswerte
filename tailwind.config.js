const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: [
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/bootstrap.js',
    ],
    safelist: [
        'grow',
        'sm:inline',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            backgroundOpacity: ['dark'],
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
};
