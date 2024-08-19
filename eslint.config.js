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
  pluginJs.configs.recommended,
  eslintConfigPrettier ,
];
