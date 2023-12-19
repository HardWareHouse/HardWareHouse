/** @type {import('tailwindcss').Config} */
module.exports = {
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
  plugins: [],
};
