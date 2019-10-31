import 'mdn-polyfills/NodeList.prototype.forEach';

// Import local dependencies
import partials from './partials/*';

const ready = fn => document.readyState !== 'loading'
  ? window.setTimeout(fn, 0)
  : document.addEventListener('DOMContentLoaded', fn);

ready(() => {
  for (let i = partials.length - 1; 0 <= i; i--) {
    if (typeof partials[i].default === 'undefined') {
      continue;
    }

    if (typeof partials[i].default.prototype.init !== 'function') {
      continue;
    }

    let elements;

    if (typeof partials[i].selector === 'string') {
      elements = document.querySelectorAll(partials[i].selector);

      if (!elements.length) {
        continue;
      }
    }

    partials[i].default.instances = partials[i].default.instances || [];

    if (elements) {
      elements.forEach(el => {
        const instance = new partials[i].default();
        instance.element = el;

        partials[i].default.instances.push(instance);

        instance.init();
      });
    } else {
      const instance = new partials[i].default();

      partials[i].default.instances.push(instance);
      partials[i].default.instance = instance;

      instance.init();
    }
  }

  document.body.classList.add('is-loaded');
});
