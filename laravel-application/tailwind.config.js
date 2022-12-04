/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php"
    ],
    theme: {
        extend: {

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
