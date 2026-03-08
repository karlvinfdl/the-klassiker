const Encore = require("@symfony/webpack-encore");

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you're used to use the resulting entry points from another app (e.g. React) running with a different Symfony environment.
if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || "dev");
}

Encore
  // directory where compiled assets will be stored
  .setOutputPath("public/build/")
  // public path used by the web server to access the output path
  .setPublicPath("/build")
  // only needed for CDN's or sub-directory deploy
  .addEntry("app", "./assets/script/app.js")

  // When enabled, Webpack "splits" your files into smaller pieces for better optimization.
  .splitEntryChunks()

  // will require an extra script tag for runtime.js
  // but, you probably want this, unless you're building a single-page "app" in the sense that the JS and CSS is all in one file
  .enableSingleRuntimeChunk()

  /*
   * FEATURE CONFIG
   *
   * Enable & Configure other features below. For a quick
   * "what they do" overview, see:
   * https://symfony.com/doc/current/frontend/encore/features/code-splitting.html
   *
   * For details, see
   * https://symfony.com/doc/current/frontend/encore/copy-files.html
   */
  .copyFiles({
    from: "./assets/images",
    to: "images/[path][name].[hash:8].[ext]",
  });
// .enableSassLoader()

// uncomment if you use TypeScript
// .enableTypeScriptLoader()

// uncomment if you use React
// .enableReactPreset()

// uncomment to enable images
// .enableImageMinimizer()
// .configureImageRule({ test: /\.(jpe?g|png|gif|svg)$/i }, {
//     type: 'asset',
//     parser: {
//         dataUrlCondition: {
//             maxSize: 4 * 1024, // 4kiB
//         },
//     },
// })

// Export the configuration
module.exports = Encore.getWebpackConfig();
