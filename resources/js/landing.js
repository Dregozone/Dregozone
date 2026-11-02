/**
 * Landing page motion engine.
 *
 * Small, dependency-free behaviours wired up through data attributes so the
 * Blade templates stay declarative:
 *
 *  data-reveal            fade/translate in once the element enters the viewport
 *  data-progress          exposes --p (0 → 1) as the element travels through the viewport
 *  data-parallax="0.25"   translates the element against scroll inside the hero
 *  data-tilt              pointer-driven depth shift for the hero plate
 *  data-spotlight         cursor-following light (--mx/--my) on glass surfaces
 *  data-count="30"        counts up to the value once visible
 *  data-typed="whoami"    types the text once visible
 *  data-aurora            renders the live aurora on a <canvas>
 *  data-landing-header    marks the page so the site header can react to scroll
 *
 * Everything degrades to its final state when the visitor prefers reduced motion.
 */

const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const clamp = (value, min = 0, max = 1) => Math.min(max, Math.max(min, value));
const smoothstep = (edge0, edge1, x) => {
    const t = clamp((x - edge0) / (edge1 - edge0));

    return t * t * (3 - 2 * t);
};

function initReveal() {
    const elements = document.querySelectorAll('[data-reveal]');

    if (reduceMotion || !('IntersectionObserver' in window)) {
        elements.forEach((element) => element.classList.add('is-in'));

        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-in');
                observer.unobserve(entry.target);
            }
        });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.12 });

    elements.forEach((element) => observer.observe(element));
}

function initCounters() {
    const counters = document.querySelectorAll('[data-count]');

    const run = (element) => {
        const target = Number(element.dataset.count);

        if (reduceMotion || Number.isNaN(target)) {
            element.textContent = element.dataset.count;

            return;
        }

        const started = performance.now();
        const duration = 1400;
        const tick = (now) => {
            const t = clamp((now - started) / duration);
            const eased = 1 - Math.pow(1 - t, 4);
            element.textContent = Math.round(target * eased).toString();

            if (t < 1) {
                requestAnimationFrame(tick);
            }
        };

        requestAnimationFrame(tick);
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                run(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.6 });

    counters.forEach((counter) => observer.observe(counter));
}

function initTyped() {
    document.querySelectorAll('[data-typed]').forEach((element) => {
        const text = element.dataset.typed;

        if (reduceMotion) {
            element.textContent = text;

            return;
        }

        element.textContent = '';
        let index = 0;
        const delay = Number(element.dataset.typedDelay ?? 900);

        setTimeout(function type() {
            element.textContent = text.slice(0, ++index);

            if (index < text.length) {
                setTimeout(type, 55 + Math.random() * 70);
            }
        }, delay);
    });
}

function initSpotlight() {
    if (reduceMotion || !window.matchMedia('(hover: hover)').matches) {
        return;
    }

    document.querySelectorAll('[data-spotlight]').forEach((element) => {
        element.addEventListener('pointermove', (event) => {
            const rect = element.getBoundingClientRect();
            element.style.setProperty('--mx', `${event.clientX - rect.left}px`);
            element.style.setProperty('--my', `${event.clientY - rect.top}px`);
        });
    });
}

/**
 * Scroll-linked effects share one rAF loop so we never read layout more than once per frame.
 */
function initScrollEffects() {
    const header = document.querySelector('body > header');
    const marker = document.querySelector('[data-landing-header]');
    const progressElements = [...document.querySelectorAll('[data-progress]')];
    const parallaxElements = reduceMotion ? [] : [...document.querySelectorAll('[data-parallax]')];
    const tiltElements = reduceMotion || !window.matchMedia('(hover: hover)').matches ? [] : [...document.querySelectorAll('[data-tilt]')];

    let pointerX = 0;
    let pointerY = 0;
    let currentX = 0;
    let currentY = 0;
    let ticking = false;

    const update = () => {
        ticking = false;
        const viewport = window.innerHeight;
        const scrollY = window.scrollY;

        if (header && marker) {
            header.classList.toggle('landing-header--scrolled', scrollY > 24);
        }

        progressElements.forEach((element) => {
            if (reduceMotion) {
                element.style.setProperty('--p', '1');
                element.dataset.stage = element.dataset.stages ?? '1';

                return;
            }

            const rect = element.getBoundingClientRect();
            // 0 when the top of the element reaches 85% of the viewport, 1 when its bottom passes 75%.
            const start = viewport * 0.85;
            const end = viewport * 0.75 - rect.height;
            const progress = clamp((start - rect.top) / (start - end));
            element.style.setProperty('--p', progress.toFixed(4));
            // Stage n lights once the progress line reaches the nth column.
            element.dataset.stage = Math.ceil(progress * Number(element.dataset.stages ?? 1) - 0.0001).toString();
        });

        parallaxElements.forEach((element) => {
            if (scrollY > viewport * 1.2) {
                return;
            }

            const factor = Number(element.dataset.parallax);
            element.style.transform = `translate3d(0, ${(scrollY * factor).toFixed(2)}px, 0)`;
        });
    };

    const requestUpdate = () => {
        if (!ticking) {
            ticking = true;
            requestAnimationFrame(update);
        }
    };

    if (tiltElements.length) {
        window.addEventListener('pointermove', (event) => {
            pointerX = event.clientX / window.innerWidth - 0.5;
            pointerY = event.clientY / window.innerHeight - 0.5;
        }, { passive: true });

        const settle = () => {
            // Critically-damped follow so the plate drifts rather than snaps.
            currentX += (pointerX - currentX) * 0.06;
            currentY += (pointerY - currentY) * 0.06;
            tiltElements.forEach((element) => {
                const depth = Number(element.dataset.tilt || 12);
                element.style.translate = `${(-currentX * depth).toFixed(2)}px ${(-currentY * depth).toFixed(2)}px`;
            });
            requestAnimationFrame(settle);
        };

        requestAnimationFrame(settle);
    }

    window.addEventListener('scroll', requestUpdate, { passive: true });
    window.addEventListener('resize', requestUpdate);
    update();
}

/**
 * Live aurora: layered curtains of light drawn at low resolution (the browser's
 * upscale gives the soft, diffuse glow for free) and blended additively.
 */
function initAurora() {
    document.querySelectorAll('canvas[data-aurora]').forEach((canvas) => {
        const context = canvas.getContext('2d');
        const scale = 0.25;
        const curtains = [
            { hue: 165, base: 0.34, amplitude: 0.07, frequency: 1.6, speed: 0.00011, height: 0.34, alpha: 0.55, phase: 0 },
            { hue: 186, base: 0.26, amplitude: 0.05, frequency: 2.4, speed: -0.00008, height: 0.26, alpha: 0.42, phase: 2.1 },
            { hue: 268, base: 0.2, amplitude: 0.06, frequency: 1.1, speed: 0.00006, height: 0.3, alpha: 0.38, phase: 4.2 },
        ];
        const horizon = Number(canvas.dataset.horizon ?? 0);
        const envelopeStart = Number(canvas.dataset.envelopeStart ?? 0.2);
        const envelopeEnd = Number(canvas.dataset.envelopeEnd ?? 0.75);
        const fpsElement = canvas.dataset.fps ? document.querySelector(canvas.dataset.fps) : null;
        let width = 0;
        let height = 0;
        let visible = true;

        const resize = () => {
            const rect = canvas.getBoundingClientRect();
            width = Math.max(1, Math.round(rect.width * scale));
            height = Math.max(1, Math.round(rect.height * scale));
            canvas.width = width;
            canvas.height = height;
        };

        const draw = (time) => {
            context.clearRect(0, 0, width, height);
            context.globalCompositeOperation = 'lighter';

            curtains.forEach((curtain) => {
                const gradient = context.createLinearGradient(0, 0, 0, -height * curtain.height);
                // Feathered base: real curtains have a bright lower edge that still fades into the sky.
                gradient.addColorStop(0, `hsla(${curtain.hue}, 90%, 62%, 0)`);
                gradient.addColorStop(0.08, `hsla(${curtain.hue}, 90%, 64%, ${curtain.alpha})`);
                gradient.addColorStop(0.3, `hsla(${curtain.hue}, 90%, 58%, ${curtain.alpha * 0.5})`);
                gradient.addColorStop(1, `hsla(${curtain.hue + 30}, 90%, 50%, 0)`);
                context.fillStyle = gradient;

                const step = 2;
                for (let x = 0; x < width; x += step) {
                    const u = x / width;
                    // Keep the left third calm so the headline always reads cleanly.
                    const envelope = 0.18 + 0.82 * smoothstep(envelopeStart, envelopeEnd, u);
                    const wave = Math.sin(u * Math.PI * curtain.frequency + time * curtain.speed * 6 + curtain.phase)
                        + 0.5 * Math.sin(u * Math.PI * curtain.frequency * 3.1 - time * curtain.speed * 9 + curtain.phase * 1.7);
                    const y = height * (curtain.base + wave * curtain.amplitude);
                    // Vertical "rays" flicker independently, like real auroral curtains.
                    const flicker = 0.55 + 0.45 * Math.sin(x * 0.37 + time * 0.0011 + curtain.phase) * Math.sin(x * 0.11 - time * 0.0007);
                    context.globalAlpha = clamp(flicker, 0.1, 1) * envelope;
                    context.save();
                    context.translate(x, y + height * 0.18);
                    context.fillRect(0, -height * curtain.height, step + 0.5, height * curtain.height);
                    context.restore();
                }
            });

            context.globalCompositeOperation = 'source-over';

            // Mirror the sky into the lake of the plate below the horizon.
            if (horizon > 0) {
                const horizonY = Math.round(height * horizon);
                context.save();
                context.globalAlpha = 0.28;
                context.translate(0, horizonY * 2);
                context.scale(1, -1);
                context.drawImage(canvas, 0, 0, width, horizonY, 0, 0, width, horizonY);
                context.restore();
            }

            context.globalAlpha = 1;
        };

        let frames = 0;
        let lastSample = performance.now();

        const loop = (time) => {
            if (visible) {
                draw(time);
                frames++;

                if (fpsElement && time - lastSample >= 1000) {
                    fpsElement.textContent = Math.round((frames * 1000) / (time - lastSample)).toString();
                    frames = 0;
                    lastSample = time;
                }
            }

            requestAnimationFrame(loop);
        };

        resize();
        window.addEventListener('resize', resize);

        if (reduceMotion) {
            draw(12000);

            return;
        }

        new IntersectionObserver(([entry]) => {
            visible = entry.isIntersecting;
        }).observe(canvas);

        requestAnimationFrame(loop);
    });
}

function initHeroPlate() {
    document.querySelectorAll('img[data-plate]').forEach((image) => {
        const reveal = () => image.closest('[data-plate-frame]')?.classList.add('is-loaded');

        if (image.complete) {
            reveal();
        } else {
            image.addEventListener('load', reveal, { once: true });
        }
    });
}

function boot() {
    document.documentElement.classList.add('js-motion');
    initHeroPlate();
    initReveal();
    initCounters();
    initTyped();
    initSpotlight();
    initScrollEffects();
    initAurora();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
} else {
    boot();
}
