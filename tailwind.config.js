export default {
    content: [
        './app/**/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    DEFAULT: '#606C38',
                    50: '#f7f8f3',
                    100: '#eff1e6',
                    200: '#d9ddc2',
                    300: '#c3c99e',
                    400: '#9faa6f',
                    500: '#7f8f4d',
                    600: '#606C38',
                    700: '#4a5a2d',
                    800: '#3b4924',
                    900: '#283618',
                    950: '#1a230f',
                },
            },
            fontFamily: {
                sans: ['Inter', 'system-ui', 'sans-serif'],
            },
        },
    },
}
