import './bootstrap';

import Alpine from 'alpinejs';

const applyScheduledTheme = () => {
    const hour = new Date().getHours();
    const isDark = hour >= 18 || hour < 6;
    const theme = isDark ? 'dark' : 'light';

    document.documentElement.classList.toggle('dark', isDark);
    document.documentElement.dataset.theme = theme;
    window.dispatchEvent(new CustomEvent('theme-scheduled', { detail: { theme, isDark } }));
};

applyScheduledTheme();
setInterval(applyScheduledTheme, 60 * 1000);

window.Alpine = Alpine;

Alpine.start();
