import './bootstrap';

import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
window.Alpine = Alpine;

Alpine.plugin(focus);

Alpine.start();
import './sweetAlert2';
import {delegate} from 'tippy.js';
import 'tippy.js/dist/tippy.css';
import 'tippy.js/animations/shift-toward-subtle.css';

// Default configuration for Tippy with event delegation (https://atomiks.github.io/tippyjs/v6/addons/#event-delegation
delegate('body', {
    interactive: true,
    allowHTML: true,
    animation: 'shift-toward-subtle',
    target: '[data-tippy-content]',
});

/* header & go-top-btn active* when window scroll down to 400px*/
const goTopBtn = document.querySelector("[data-go-top]");
window.addEventListener("scroll", function () {
    if (window.scrollY >= 200) {
        goTopBtn.classList.add("active");
    } else {
        goTopBtn.classList.remove("active");
    }
});
