import { createElement, render } from '@wordpress/element';
import AnimatedList from './reactbits/AnimatedList/AnimatedList';

// Mapeamento de nomes para componentes React
const componentsMap = {
  'AnimatedList': AnimatedList,
  // Adicione outros componentes aqui
};

document.addEventListener('DOMContentLoaded', () => {
  const containers = document.querySelectorAll('.reactbits-container');
  containers.forEach(container => {
    const compName = container.getAttribute('data-component');
    const Component = componentsMap[compName];
    if (!Component) return;
    let element = createElement(Component, null);
    if (compName === 'AnimatedList') {
      // Exemplo de conteúdo para AnimatedList
      const items = ['Item 1', 'Item 2', 'Item 3'];
      element = createElement(
        Component,
        { items },  // passe items como prop se desejar
        null
      );
    }
    render(element, container);
  });
});