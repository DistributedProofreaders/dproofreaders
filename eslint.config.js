const globals = require("globals");
const pluginJs = require("@eslint/js");
const eslintConfigPrettier = require("eslint-config-prettier");

module.exports = [
    { files: ["**/*.js"] },
    { ignores: ["pinc/3rdparty", "**/vendor", "eslint.config.js", "dist/**", "webpack.config.js"] },
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
            "SETUP/tests/jsTests/characterValidation.js",
            "SETUP/tests/jsTests/formatPreviewTests.js",
            "SETUP/tests/jsTests/splitControlTests.js",
            "SETUP/tests/manual_web/split_test/switchable_split.js",
            "SETUP/tests/manual_web/split_test/vertical_horizontal_split.js",
            "SETUP/tests/manual_web/split_test/vertical_split.js",
            "scripts/analyse_format.js",
            "scripts/api.js",
            "scripts/character_test.js",
            "scripts/file_resume.js",
            "scripts/gettext.js",
            "scripts/image_widget.js",
            "scripts/page_browse.js",
            "scripts/quiz.js",
            "scripts/show_format.js",
            "scripts/splitControl.js",
            "scripts/text_validator.js",
            "scripts/text_widget.js",
            "scripts/toolbox.js",
            "scripts/validator.js",
            "scripts/view_splitter.js",
            "scripts/word_check.js",
            "tools/page_browser.js",
            "tools/project_manager/handle_bad_page.js",
            "tools/project_manager/show_all_good_word_suggestions.js",
            "tools/project_manager/show_word_context.js",
            "tools/proofers/proof.js",
        ],
    },
    pluginJs.configs.recommended,
    eslintConfigPrettier,
];
