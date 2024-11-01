function importComponents() {
    const requireComponent = import.meta.glob('../Components/V[A-Z]*.vue', { eager: true });
    
    const components = Object.entries(requireComponent).map(([path, module]) => {
        const componentName = path.match(/\/(\w+)\.\w+$/)[1];
        return [componentName, module.default || module];
    });

    return Object.fromEntries(components);
}

export const globalComponents = importComponents();
