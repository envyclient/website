const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
    purge: ['./resources/views/**/*.{js,vue,blade.php}'],
    darkMode: false, // or 'media' or 'class'
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter var', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    variants: {
        extend: {},
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
}
