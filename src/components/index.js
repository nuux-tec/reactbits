 import { render, createElement } from '@wordpress/element';
import AnimatedList from './components/AnimatedList/AnimatedList';
import './components/AnimatedList/AnimatedList.css';

// 1) Importa o novo Threads
import Threads from './components/Threads/Threads';
import './components/Threads/Threads.css';

 const componentesDisponiveis = {
  'AnimatedList': AnimatedList,
  // 2) Adiciona Threads ao mapa
  'Threads':       Threads,
 };

 document.addEventListener('DOMContentLoaded', () => {
   document.querySelectorAll('.reactbits-componente').forEach(container => {
     const nome = container.dataset.component;
     const Component = componentesDisponiveis[nome];
     if (!Component) return;

     // props (items) continuam iguais
     const props = {};
     if (container.dataset.items) {
       props.items = container.dataset.items.split(',').map(i => i.trim());
     }

     // 3) Cria e monta
     const element = createElement(Component, props);
     render(element, container);
   });
 });
