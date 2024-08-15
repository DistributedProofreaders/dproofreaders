import globals from "globals";
import path from "node:path";
import { fileURLToPath } from "node:url";
import js from "@eslint/js";
import { FlatCompat } from "@eslint/eslintrc";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const compat = new FlatCompat({
  baseDirectory: __dirname,
  recommendedConfig: js.configs.recommended,
  allConfig: js.configs.all,
});

export default [
  {
    ignores: ["pinc/3rdparty", "**/vendor"],
  },
  ...compat.extends("eslint:recommended"),
  {
    languageOptions: {
      globals: {
        ...globals.browser,
        Atomics: "readonly",
        SharedArrayBuffer: "readonly",
      },

      ecmaVersion: 2018,
      sourceType: "script",
    },

    rules: {
      semi: ["error", "always"],
      "no-use-before-define": "error",
      indent: ["warn", 4],
      "brace-style": ["warn", "1tbs"],
      "space-before-blocks": "warn",

      "no-multi-spaces": [
        "warn",
        {
          ignoreEOLComments: true,
        },
      ],

      "no-trailing-spaces": "warn",
      "space-infix-ops": "warn",
      camelcase: "warn",

      "newline-per-chained-call": [
        "warn",
        {
          ignoreChainWithDepth: 2,
        },
      ],
    },
  },
];
