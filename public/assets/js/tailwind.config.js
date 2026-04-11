/* =============================================================
   tailwind.config.js — Configuration Tailwind CDN
   Inclus via <script> juste APRÈS le CDN Tailwind dans main.php
============================================================= */
 
tailwind.config = {
    darkMode: 'class',
    theme: { extend: {
        fontFamily: {
            display: ['"Playfair Display"', 'Georgia', 'serif'],
            body:    ['"Source Sans 3"', 'sans-serif'],
        },
        colors: { brand1: '#1d8fd8', brand2: '#22d3ee' }
    }}
}
 