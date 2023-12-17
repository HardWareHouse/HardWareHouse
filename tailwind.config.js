/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./assets/**/*.js", "./templates/**/*.html.twig"],
  theme: {
    extend: {
      colors: {
        lightBlue: "#A0E1FF",
        darkBlue: "#005A84",
      },
    },
  },
  plugins: [],
};
