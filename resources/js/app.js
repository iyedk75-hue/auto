import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const initializeAudioPlayers = () => {
    const audioPlayers = document.querySelectorAll('[data-audio-player]');
    const audioStatusLabels = document.querySelectorAll('[data-audio-status-label]');
    const statusTranslations = {
        idle: document.documentElement.lang.startsWith('ar') ? 'غير مبدوء' : 'Non demarre',
        progress: document.documentElement.lang.startsWith('ar') ? 'قيد الاستماع' : 'En cours',
        done: document.documentElement.lang.startsWith('ar') ? 'مكتمل' : 'Termine',
    };

    const readStoredState = (storageKey) => {
        try {
            return JSON.parse(window.localStorage.getItem(storageKey) || 'null');
        } catch {
            return null;
        }
    };

    const statusForState = (state) => {
        if (!state) {
            return 'idle';
        }

        if (state.completed) {
            return 'done';
        }

        if ((state.currentTime || 0) > 0) {
            return 'progress';
        }

        return 'idle';
    };

    const renderStatusLabels = () => {
        audioStatusLabels.forEach((label) => {
            const storageKey = label.dataset.audioStorageKey;

            if (!storageKey) {
                return;
            }

            const state = readStoredState(`massar-audio:${storageKey}`);
            label.textContent = statusTranslations[statusForState(state)];
        });
    };

    const formatTime = (value) => {
        if (! Number.isFinite(value) || value < 0) {
            return '0:00';
        }

        const totalSeconds = Math.floor(value);
        const minutes = Math.floor(totalSeconds / 60);
        const seconds = totalSeconds % 60;

        return `${minutes}:${String(seconds).padStart(2, '0')}`;
    };

    audioPlayers.forEach((player) => {
        const audio = player.querySelector('[data-audio-element]');
        const progress = player.querySelector('[data-audio-progress]');
        const currentLabel = player.querySelector('[data-audio-current]');
        const durationLabel = player.querySelector('[data-audio-duration]');
        const rateSelect = player.querySelector('[data-audio-rate]');
        const skipButtons = player.querySelectorAll('[data-audio-skip]');
        const storageKey = `massar-audio:${player.dataset.audioKey || audio?.currentSrc || ''}`;

        if (! audio || ! progress || ! currentLabel || ! durationLabel || ! rateSelect || storageKey.endsWith(':')) {
            return;
        }

        let savedState = readStoredState(storageKey);
        const currentStatus = player.querySelector('[data-audio-status-current]');

        const persistState = () => {
            const duration = audio.duration || 0;
            const currentTime = audio.currentTime || 0;
            const completed = duration > 0 && currentTime >= Math.max(duration - 1, 0);

            try {
                window.localStorage.setItem(storageKey, JSON.stringify({
                    currentTime,
                    playbackRate: audio.playbackRate || 1,
                    completed,
                }));
            } catch {
                // Ignore storage failures.
            }

            savedState = readStoredState(storageKey);
            if (currentStatus) {
                currentStatus.textContent = statusTranslations[statusForState(savedState)];
            }
            renderStatusLabels();
        };

        const updateProgress = () => {
            const duration = audio.duration || 0;
            const current = audio.currentTime || 0;
            const ratio = duration > 0 ? (current / duration) * 100 : 0;

            progress.value = `${ratio}`;
            currentLabel.textContent = formatTime(current);
            durationLabel.textContent = formatTime(duration);
        };

        audio.addEventListener('loadedmetadata', () => {
            if (savedState?.playbackRate) {
                audio.playbackRate = savedState.playbackRate;
                rateSelect.value = `${savedState.playbackRate}`;
            }

            if (savedState?.currentTime && savedState.currentTime < audio.duration) {
                audio.currentTime = savedState.currentTime;
            }

            updateProgress();
        });

        audio.addEventListener('timeupdate', () => {
            updateProgress();
            persistState();
        });

        audio.addEventListener('ratechange', persistState);
        audio.addEventListener('ended', () => {
            persistState();
            updateProgress();
        });

        progress.addEventListener('input', () => {
            if (! audio.duration) {
                return;
            }

            audio.currentTime = (Number(progress.value) / 100) * audio.duration;
            updateProgress();
        });

        rateSelect.addEventListener('change', () => {
            audio.playbackRate = Number(rateSelect.value);
            persistState();
        });

        skipButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const offset = Number(button.dataset.audioSkip || 0);
                audio.currentTime = Math.max(0, Math.min((audio.duration || Infinity), audio.currentTime + offset));
                updateProgress();
                persistState();
            });
        });

        updateProgress();
        if (currentStatus) {
            currentStatus.textContent = statusTranslations[statusForState(savedState)];
        }
    });

    renderStatusLabels();
};

initializeAudioPlayers();

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
