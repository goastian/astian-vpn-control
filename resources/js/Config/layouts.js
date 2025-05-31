function importComponents() {
    const requireComponent = require.context(
        "../Layouts", //directoy
        false, //includ subdirectories
        /V[A-Z]\w+\.(vue|js)$/ //expresion to get files name
    );

    const components = requireComponent.keys().map((fileName) => {
        // get file name in PascalCase
        const componentName = fileName.replace(/^\.\/(.*)\.\w+$/, "$1");

        // import component
        const componentConfig = requireComponent(fileName);

        return [componentName, componentConfig.default || componentConfig];
    });

    return components;
}

export const layouts = importComponents();
