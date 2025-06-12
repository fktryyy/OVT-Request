/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class', // ✅ Aktifkan dark mode berbasis class
  content: [
    './resources/**/*.blade.php', // ✅ Blade templates
    './resources/**/*.js',        // ✅ File JS (misalnya di Alpine.js)
    './resources/**/*.vue',       // ✅ Jika kamu pakai Vue (opsional, aman disertakan)
  ],
  theme: {
    extend: {
      colors: {
        dark: {
          100: '#1f2937', // ✅ Warna kustom gelap (bisa digunakan seperti bg-dark-100)
          200: '#111827',
        },
      },
    },
  },
  plugins: [],
}
