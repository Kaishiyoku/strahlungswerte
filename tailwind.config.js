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
                gray: {
                    50: '#fdfdfd',
                    100: '#fcfcfc',
                    200: '#f7f7f7',
                    300: '#f0f0f0',
                    400: '#e0e0e0',
                    450: '#cccccc',
                    500: '#bfbfbf',
                    600: '#969696',
                    700: '#696969',
                    800: '#474747',
                    900: '#2b2b2b',
                    950: '#1a1a1a',
                },
            },
            shadowOutline: {
                'shadow': '0 0 0 .2rem',
                'alpha': '.4',
            },
            backgroundOpacity: ['dark'],
        },
    },
    variants: {},
    plugins: [
        require('tailwindcss-shadow-outline-colors')(),
    ]
}
