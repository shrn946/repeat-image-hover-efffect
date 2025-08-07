/**
 * Linear interpolation
 */
const lerp = (a, b, n) => (1 - n) * a + n * b;

/**
 * Class representing an image that has the repetitive hover effect
 */
class ImageHover {
    constructor(DOM_el) {
        this.DOM = {
            el: DOM_el,
            innerElems: null
        };

        // Defaults and data attribute overrides
        this.duration = this.DOM.el.dataset.repetitionDuration || 0.5;
        this.ease = this.DOM.el.dataset.repetitionEase || 'power2.inOut';
        this.stagger = this.DOM.el.dataset.repetitionStagger || 0;
        this.scaleInterval = this.DOM.el.dataset.repetitionScaleInterval || 0.1;
        this.rotationInterval = this.DOM.el.dataset.repetitionRotationInterval || 0;
        this.innerTotal = this.DOM.el.dataset.repetitionCount || 4;
        this.transformOrigin = this.DOM.el.dataset.repetitionOrigin || '50% 50%';

        // Extract background image URL
        const bgMatch = /(?:\(['"]?)(.*?)(?:['"]?\))/.exec(this.DOM.el.style.backgroundImage);
        this.bgImage = bgMatch ? bgMatch[1] : '';

        // Remove the original background image
        gsap.set(this.DOM.el, { backgroundImage: 'none' });

        // Create inner elements
        this.innerTotal = Math.max(2, this.innerTotal);
        let innerHTML = '';
        for (let i = 0; i < this.innerTotal; i++) {
            innerHTML += `<div class="image__element" style="background-image:url(${this.bgImage})"></div>`;
        }
        this.DOM.el.innerHTML = innerHTML;

        // Store inner elements and set transform origin
        this.DOM.innerElems = this.DOM.el.querySelectorAll('.image__element');
        gsap.set(this.DOM.el, { transformOrigin: this.transformOrigin });

        // Build animation and events
        this.createHoverTimeline();
        this.initEvents();
    }

    createHoverTimeline() {
        const getScaleValue = i => Math.max(0, 1 - this.scaleInterval * i);
        const getRotationValue = i => i * this.rotationInterval;

        this.hoverTimeline = gsap.timeline({ paused: true }).to(this.DOM.innerElems, {
            scale: i => getScaleValue(i),
            rotation: i => getRotationValue(i),
            duration: this.duration,
            ease: this.ease,
            stagger: this.stagger
        });
    }

    initEvents() {
        this.DOM.el.addEventListener('mouseenter', () => this.hoverTimeline.play());
        this.DOM.el.addEventListener('mouseleave', () => this.hoverTimeline.reverse());
    }
}

// Initialize all image hover instances on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.image').forEach(el => {
        new ImageHover(el);
    });
});
