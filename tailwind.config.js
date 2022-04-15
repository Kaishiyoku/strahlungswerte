const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

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
        colors: {
            inherit: 'inherit',
            current: 'currentColor',
            transparent: 'transparent',
            black: '#000',
            white: '#fff',
            blue: colors.blue,
            red: colors.red,
            gray: colors.gray,
        },

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
