const globals = require("globals");
const pluginJs = require("@eslint/js");
const eslintConfigPrettier = require("eslint-config-prettier");

module.exports = [
    { files: ["**/*.js"] },
    { ignores: ["pinc/3rdparty", "**/vendor", "eslint.config.js"] },
    {
        languageOptions: {
            globals: globals.browser,
            sourceType: "script",
        },

        rules: {
            "no-use-before-define": "error",
            camelcase: "warn",
        },
    },
    {
        languageOptions: {
            globals: globals.browser,
            sourceType: "module",
        },
        files: [
            "SETUP/tests/jsTests/ajaxTests.js",
            "SETUP/tests/jsTests/formatPreviewTests.js",
            "SETUP/tests/jsTests/splitControlTests.js",
            "SETUP/tests/manual_web/split_test/switchable_split.js",
            "SETUP/tests/manual_web/split_test/vertical_horizontal_split.js",
            "SETUP/tests/manual_web/split_test/vertical_split.js",
            "scripts/analyse_format.js",
            "scripts/api.js",
            "scripts/control_bar.js",
            "scripts/file_resume.js",
            "scripts/gettext.js",
            "scripts/page_browse.js",
            "scripts/splitControl.js",
            "scripts/text_view.js",
            "scripts/view_splitter.js",
            "tools/page_browser.js",
            "tools/project_manager/show_all_good_word_suggestions.js",
            "tools/project_manager/show_word_context.js",
            "tools/proofers/previewControl.js",
            "tools/proofers/proof_image.js",
        ],
    },
    pluginJs.configs.recommended,
    eslintConfigPrettier,
];
