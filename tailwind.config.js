/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/preline/dist/*.js",
        "./storage/framework/views/*.php",
    ],
    darkMode: "class",
    theme: {
        extend: {},
    },
    plugins: [require("@tailwindcss/forms")],
};
