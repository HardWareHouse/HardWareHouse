/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./assets/**/*.js", "./templates/**/*.html.twig"],
  theme: {
    extend: {
      width: {
        "w-lg": "28rem",
      },
    },
  },
  plugins: [],
};
