/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './resources/**/*.ts',
        './resources/**/*.tsx'
    ],
    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Poppins', 'sans-serif']
            }
        }
    },
    plugins: [require('daisyui')],
    daisyui: {
        themes: ['winter', 'sunset'],
        base: true,
        styled: true,
        utils: true,
        logs: true,
        rtl: false,
        prefix: ''
    }
}
