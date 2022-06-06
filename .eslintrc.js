// typescript用のeslintルール
// https://github.com/typescript-eslint/typescript-eslint/tree/main/packages/eslint-plugin/docs/rules
module.exports = {
    root: true,
    env: {
        es6: true,
        node: true,
        browser: true,
    },
    parser: '@typescript-eslint/parser',
    parserOptions: {
        sourceType: 'module',
        ecmaVersion: 2019,
        tsconfigRootDir: __dirname,
        project: ['./tsconfig.eslint.json'],
    },
    plugins: ['@typescript-eslint'],
    extends: [
        'eslint:recommended',
        'plugin:@typescript-eslint/recommended',
        'plugin:@typescript-eslint/recommended-requiring-type-checking',
    ],
    rules: {
        'no-console': 'warn',
        'no-extra-semi': 'warn',
        'no-undef': 'warn',
        quotes: ['warn', 'single'],
        'space-before-blocks': [
            'warn',
            {
                functions: 'always',
            },
        ],
        '@typescript-eslint/no-unsafe-call': 'warn',
        '@typescript-eslint/no-unsafe-member-access': 'warn',
        '@typescript-eslint/no-unsafe-return': 'warn',
        '@typescript-eslint/no-non-null-assertion': 'off',
        '@typescript-eslint/no-unnecessary-type-assertion': 'off',
    },
};
