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
                //
            },
            backgroundOpacity: ['dark'],
        },
    },
    variants: {},
    plugins: [

    ]
}
