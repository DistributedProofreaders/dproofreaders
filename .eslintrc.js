module.exports = {
    env: {
        browser: true,
        es6: true,
    },
    extends: "eslint:recommended",
    globals: {
        Atomics: 'readonly',
        SharedArrayBuffer: 'readonly',
    },
    parserOptions: {
        ecmaVersion: 2018,
    },
    rules: {
        semi: ["error", "always"],
        "no-use-before-define": "error",
        "indent": ["warn", 4],
        'brace-style': ['warn', '1tbs'],
        "space-before-blocks": "warn",
        "no-multi-spaces": ["warn", { ignoreEOLComments: true }],
        "no-trailing-spaces": "warn",
        "space-infix-ops": "warn",
        camelcase: 'warn',
        "newline-per-chained-call": ["warn", { ignoreChainWithDepth: 2 }],
        "comma-spacing": "warn",
    }
};
