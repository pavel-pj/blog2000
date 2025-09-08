import js from "@eslint/js";
import globals from "globals";
import tseslint from "typescript-eslint";
import pluginVue from "eslint-plugin-vue";
import { defineConfig } from "eslint/config";


export default defineConfig([
  { files: ["**/*.{js,mjs,cjs,ts,mts,cts,vue}"], plugins: { js }, extends: ["js/recommended"] },
  { files: ["**/*.{js,mjs,cjs,ts,mts,cts,vue}"], languageOptions: { globals: globals.browser } },
  tseslint.configs.recommended,
  pluginVue.configs["flat/essential"],
  { files: ["**/*.vue"], languageOptions: { parserOptions: { parser: tseslint.parser } } },
  {
    files: ["**/*.vue"],
    rules: {
      "vue/multi-word-component-names": "off",
      "indent": ["error", 2],
      "semi": ["error", "always"],
      "quotes": ["error", "single"],
      "comma-dangle": ["error", "never"],
      "no-trailing-spaces": "error",
      "eol-last": ["error", "always"],
      '@typescript-eslint/no-explicit-any': 'off', // Полностью отключает ошибку
      '@typescript-eslint/no-unused-vars': 'warn'
    }
  },
  {
    ignores: [
      'src/env.d.ts',
      "**/assets/**",  // Игнорировать всю папку assets
      "**/dist/**",   // Игнорировать всю папку dist
      "**/*.min.js"    // Дополнительно можно игнорировать минифицированные файлы
    ]
  }
]);
