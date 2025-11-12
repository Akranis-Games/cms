/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                'neon-cyan': '#00ffff',
                'neon-purple': '#a855f7',
                'neon-pink': '#ec4899',
            },
        },
    },
    plugins: [],
}

