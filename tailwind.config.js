/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./node_modules/flowbite/**/*.js"
  ],
  theme: {
    extend: {
      colors: {
        industrial: {
          bg: '#101214',          // Main 
          surface: '#181C20',     // Sidebar 
          card: '#202429',        // Cards 
          hover: '#2A3036',       // Hover 
          border: '#343A40',      // Borders
          text: '#F5F5F5',        // Primary text
          muted: '#A1A1AA',       // Secondary text
          yellow: '#F5C842',      // accent
          'yellow-hover': '#E8B92E', // hover accent
          'yellow-dark': '#101214',  // Butt-Text
          success: '#22C55E',
          warning: '#FB923C',
          error: '#F87171',
          info: '#38BDF8',
        },
        // Sisa warna awal
        ivory: {
          DEFAULT: '#101214',
          50: '#202429',
          100: '#101214',
          200: '#181C20',
        },
        terracotta: {
          DEFAULT: '#F5C842',
          deep: '#F5C842',
          darker: '#E8B92E',
          hover: '#E8B92E',
          active: '#D4A017',
          pale: 'rgba(245, 200, 66, 0.15)',
          dark: '#101214',
          text: '#101214',
        },
        stone: {
          text: '#F5F5F5',
          muted: '#A1A1AA',
          border: '#343A40',
          dark: '#101214',
        },
        primary: {
          50: '#FFFBEB',
          100: '#FEF3C7',
          200: '#FDE68A',
          300: '#FCD34D',
          400: '#FBBF24',
          500: '#F5C842',
          600: '#E8B92E',
          700: '#D4A017',
          800: '#92400E',
          900: '#78350F',
        }
      },
      fontFamily: {
        sans: ['"IBM Plex Sans"', 'system-ui', '-apple-system', 'sans-serif'],
        heading: ['"Space Grotesk"', 'sans-serif'],
        brand: ['"Space Grotesk"', 'sans-serif'],
        editorial: ['"Space Grotesk"', 'sans-serif'],
        mono: ['"IBM Plex Mono"', 'ui-monospace', 'monospace'],
      }
    },
  },
  plugins: [
    require('flowbite/plugin')
  ],
}
