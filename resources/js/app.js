import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const protectedViewer = document.querySelector('[data-protected-course-viewer]');

if (protectedViewer) {
    const feedback = document.querySelector('[data-protection-feedback]');
    const feedbackMessage = protectedViewer.dataset.protectionMessage || 'Blocked.';
    let feedbackTimer = null;

    const showFeedback = () => {
        if (! feedback) {
            return;
        }

        feedback.textContent = feedbackMessage;
        feedback.hidden = false;

        if (feedbackTimer) {
            window.clearTimeout(feedbackTimer);
        }

        feedbackTimer = window.setTimeout(() => {
            feedback.hidden = true;
        }, 1800);
    };

    const blockEvent = (event) => {
        event.preventDefault();
        showFeedback();
    };

    protectedViewer.addEventListener('contextmenu', blockEvent);
    protectedViewer.addEventListener('copy', blockEvent);
    protectedViewer.addEventListener('cut', blockEvent);
    protectedViewer.addEventListener('dragstart', blockEvent);

    document.addEventListener('keydown', (event) => {
        const key = event.key.toLowerCase();
        const hasPrimaryModifier = event.ctrlKey || event.metaKey;
        const blocksCommonSaveCopy = hasPrimaryModifier && ['s', 'c', 'p', 'u'].includes(key);
        const blocksDevtoolsShortcut = hasPrimaryModifier && event.shiftKey && ['i', 'j', 'c'].includes(key);

        if (blocksCommonSaveCopy || blocksDevtoolsShortcut) {
            blockEvent(event);
        }
    });
}
