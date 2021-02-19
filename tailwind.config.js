const colors = require('tailwindcss/colors')

module.exports = {
    purge: [
        './resources/views/**/*.blade.php',
        './resources/css/**/*.css',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', 'sans-serif'],
            },
            colors: {
                gray: colors.gray,
            },
            backgroundOpacity: ['dark'],
        },
    },
    variants: {},
    plugins: [

    ]
}
