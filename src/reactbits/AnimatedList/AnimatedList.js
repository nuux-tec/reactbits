import { useState, useEffect, useRef, Children, createElement } from '@wordpress/element';
import './AnimatedList.css';

const AnimatedList = ({ items = [], className = '' }) => {
  const ref = useRef(null);
  const [visible, setVisible] = useState(false);

  useEffect(() => {
    const obs = new IntersectionObserver(entries => {
      if (entries[0]?.isIntersecting) {
        setVisible(true);
        obs.disconnect();
      }
    }, { threshold: 0.1 });
    obs.observe(ref.current);
    return () => obs.disconnect();
  }, []);

  return createElement(
    'ul',
    {
      ref,
      className: `reactbits-animated-list ${visible ? 'show' : ''} ${className}`
    },
    items.map((text, i) =>
      createElement('li', { key: i }, text)
    )
  );
};

export default AnimatedList;