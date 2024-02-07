/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: ["./assets/**/*.js", "./templates/**/*.html.twig","./node_modules/flowbite/**/*.js"],
  theme: {
    extend: {
      screens: {
        '2xl': {'max': '1535px'},

        'xl': {'max': '1279px'},
        // => @media (max-width: 1279px) { ... }

        'lg': {'max': '1023px'},
        // => @media (max-width: 1023px) { ... }

        'md': {'max': '767px'},
        // => @media (max-width: 767px) { ... }

        'sm': {'max': '639px'},
        // => @media (max-width: 639px) { ... }
      },
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
    require('flowbite/plugin')
  ],
};
