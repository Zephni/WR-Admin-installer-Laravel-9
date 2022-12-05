/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php"
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    DEFAULT: '#166064',
                    '50': '#57D3DA',
                    '100': '#47CFD6',
                    '200': '#2CC0C8',
                    '300': '#25A0A7',
                    '400': '#1D8085',
                    '500': '#166064',
                    '600': '#0C3436',
                    '700': '#020808',
                    '800': '#000000',
                    '900': '#000000'
                },
            }
        },
        screens: {
            'sm': '640px',
            'md': '768px',
            'lg': '1024px',
            'xl': '1280px',
            // '2xl': '1536px'
        },
        container: {
            center: true,
        },
    },
    plugins: [

    ],
}
