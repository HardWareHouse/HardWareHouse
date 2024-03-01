/** @type {import('tailwindcss').Config} */
const plugin = require('tailwindcss/plugin');
module.exports = {
  darkMode: 'class',
  content: ["./assets/**/*.js", "./templates/**/*.html.twig"],
  theme: {
    extend: {
      colors: {
        lightBlue: "#A0E1FF",
        darkBlue: "#005A84",
        primaire: "#1190CB",
        secondaire: "#483D3F",
        tertiaire: "#F4EBD9",
        quaternaire: "#001242",
        grey: "#DCDCDD",
      },
    },
  },
    plugins: [
      plugin(function({ addVariant, e }) {
        addVariant('forest', ({ modifySelectors, separator }) => {
          modifySelectors(({ className }) => {
            return `.forest .${e(`forest${separator}${className}`)}`;
          });
        });
      }),
      plugin(function({ addVariant, e }) {
        addVariant('love', ({ modifySelectors, separator }) => {
          modifySelectors(({ className }) => {
            return `.love .${e(`love${separator}${className}`)}`;
          });
        });
      }),
    ],
  };
