/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./*.php",
    "./includes/*.php",
    "./admin/*.php"
  ],
  theme: {
    extend: {
      animation: {
        'float': 'float 6s ease-in-out infinite',
        'float-delay-1': 'float 6s ease-in-out 2s infinite',
        'float-delay-2': 'float 6s ease-in-out 4s infinite',
        'spin-slow': 'spin 20s linear infinite',
        'spin-reverse': 'spin 25s linear infinite reverse',
        'gradient': 'gradient 8s ease infinite',
      },
      keyframes: {
        float: {
          '0%, 100%': { transform: 'translateY(0)' },
          '50%': { transform: 'translateY(-20px)' },
        },
        spin: {
          '0%': { transform: 'rotate(0deg)' },
          '100%': { transform: 'rotate(360deg)' },
        },
        gradient: {
          '0%, 100%': { transform: 'translateX(-50%)' },
          '50%': { transform: 'translateX(50%)' },
        },
      },
    },
  },
  plugins: [],
}

