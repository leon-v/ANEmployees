const { defineConfig } = require('@vue/cli-service')
const proxy = require('http-proxy-middleware');

module.exports = defineConfig({
    transpileDependencies: true,
    outputDir: '../pub_html',
    devServer: {
        proxy: {
            '/php': {
                target: 'http://localhost/php', // Replace with your local server's address and port
                changeOrigin: true,
                pathRewrite: {
                    '^/php': '', // Remove the '/api' prefix when forwarding the request
                },
            },
        },
    },
});