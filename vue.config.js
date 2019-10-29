module.exports = {
  configureWebpack: {
    resolve: {
      alias: {
        '@': __dirname + 'resources/js',
      },
    },
    entry: {
      app: __dirname + '/resources/js/main.js',
    },
  },
  indexPath: __dirname + '/resources/views/index.blade.php',
  assetsDir: 'vendor/tenant',
};
