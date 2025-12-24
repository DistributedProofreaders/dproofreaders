const path = require("path");
const { WebpackManifestPlugin } = require("webpack-manifest-plugin");

module.exports = {
    mode: "production",
    entry: {
        file_resume: "./scripts/file_resume.js",
        page_browser: "./tools/page_browser.js",
        show_all_good_word_suggestions: "./tools/project_manager/show_all_good_word_suggestions.js",
        show_word_context: "./tools/project_manager/show_word_context.js",
        proof: "./tools/proofers/proof.js",
    },
    output: {
        filename: "[name].bundle.[contenthash].js",
        path: path.resolve(__dirname, "dist"),
        clean: true, // Clean the output directory before emit.
    },
    optimization: {
        moduleIds: "deterministic",
        runtimeChunk: "single",
        splitChunks: {
            // these force vendors into its own file regardless of space saved
            minSize: 1,
            minSizeReduction: 1,
            cacheGroups: {
                vendor: {
                    test: /[\\/]node_modules[\\/]/,
                    name: "vendors",
                    chunks: "all",
                },
            },
        },
    },
    plugins: [new WebpackManifestPlugin()],
    target: "browserslist",
    watchOptions: {
        ignored: /node_modules/,
    },
};
