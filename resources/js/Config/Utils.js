export const utils = {

    /**
    * Converts a camelCase or PascalCase string to kebab-case format.
    * Inserts a hyphen before each uppercase letter and changes all letters to lowercase.
    * 
    * @param {string} text - The input string in camelCase or PascalCase format.
    * @returns {string} - The formatted string in kebab-case.
    */
    toKebabCase(text) {
        return text
            .replace(/([A-Z])/g, "-$1")
            .toLowerCase();
    }
}