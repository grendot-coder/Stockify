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
          // Bright Soft Terracotta Warehouse 60-30-10 Rule
          ivory: {
            DEFAULT: '#FAF7F4', // 60% Main background
            50: '#FFFFFF',
            100: '#FAF7F4',
            200: '#F5EFEB',
          },
          terracotta: {
            DEFAULT: '#BD6745', // 10% Soft Terracotta (buttons, links, active states)
            deep: '#A34E30',    // Deep Terracotta for Primary buttons and active navigation
            darker: '#8C3D22',  // Hover state for Deep Terracotta
            hover: '#A55636',
            active: '#8E482D',
            pale: '#F5E6DE',    // Subtle highlights, active nav backgrounds, light badges
            dark: '#1C1917',    // Dark brown text for contrast on terracotta buttons
            text: '#1C1917',
          },
          stone: {
            text: '#292524',    // Dark Text
            muted: '#78716C',   // Muted Text
            border: '#E7E0DA',  // Light Borders
            dark: '#1C1917',    // Dark Brown
          },
          primary: {
            50: '#FDF8F6',
            100: '#F5E6DE',
            200: '#EBCDC0',
            300: '#DEA592',
            400: '#CE7E63',
            500: '#BD6745',     // Main Soft Terracotta
            600: '#A55636',
            700: '#8E482D',
            800: '#723B25',
            900: '#5A2F1E',
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
