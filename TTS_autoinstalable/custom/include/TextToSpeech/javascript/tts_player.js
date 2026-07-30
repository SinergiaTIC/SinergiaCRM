(function () {
    var SESSION_KEY = 'sticTtsState';

    SttTTS.prototype.audioQueue = [];
    SttTTS.prototype.currentBlob = null;
    SttTTS.prototype.isPlaying = false;
    SttTTS.prototype.isPaused = false;
    SttTTS.prototype.currentIndex = 0;
    SttTTS.prototype.totalFragments = 0;
    SttTTS.prototype.audioElement = null;
    SttTTS.prototype.playbackRate = 1.0;
    SttTTS.prototype.abortController = null;
    SttTTS.prototype.totalCharCount = 0;
    SttTTS.prototype._fragmentData = null;
    SttTTS.prototype._listUids = null;
    SttTTS.prototype._sessionRestoring = false;
    SttTTS.prototype._playGen = 0;
    SttTTS.prototype._fetchIndex = 0;
    SttTTS.prototype._recordNames = null;
    SttTTS.prototype._seekPosition = 0;

    SttTTS.prototype.saveSessionState = function () {
        if (!this._fragmentData) return;
        try {
            var state = {
                scenario: this._fragmentData.scenario || '',
                module: this._fragmentData.module || '',
                fields: this._fragmentData.fields || [],
                language: this._fragmentData.language || '',
                currentIndex: this.currentIndex,
                totalFragments: this.totalFragments,
                recordId: this._fragmentData.record || '',
                text: this._fragmentData.text || '',
                recordName: this.getRecordName() || '',
                seekPosition: this.audioElement ? this.audioElement.currentTime : 0,
                listUids: this._listUids || null,
                listContext: this._fragmentData.listContext || null,
                recordNames: this._recordNames || null,
                isPlaying: this.isPlaying,
                isPaused: this.isPaused,
                playbackRate: this.playbackRate,
                timestamp: Date.now(),
            };
            sessionStorage.setItem(SESSION_KEY, JSON.stringify(state));
        } catch (e) {
            this.log('saveSessionState error: ' + e);
        }
    };

    SttTTS.prototype.isStateValid = function (state) {
        if (!state) return false;
        if (state.module && this.config.module && state.module !== this.config.module) return false;
        return true;
    };

    SttTTS.prototype.canAutoPlay = function (state) {
        if (state.scenario === 'b') {
            var currentRecord = this.getRecordId();
            if (currentRecord && state.recordId && currentRecord !== state.recordId) return false;
        }
        return true;
    };

    SttTTS.prototype.restoreSessionState = function () {
        var state = this._restoredState;
        this.log('restoreSessionState, state: ' + (state ? 'present' : 'null'));
        if (!state) return;
        if (!this.isStateValid(state)) {
            this.log('restoreSessionState: state discarded (module/record mismatch)');
            this._restoredState = null;
            try { sessionStorage.removeItem(SESSION_KEY); } catch (e) {}
            return;
        }
        this._restoredState = null;
        this._sessionRestoring = true;

        var fragmentData = {
            scenario: state.scenario || 'a',
            module: state.module || '',
            fields: state.fields || [],
            language: state.language || 'es',
            record: state.recordId || '',
            text: state.text || '',
            listContext: state.listContext || null,
            fragmentIndex: state.currentIndex || 0,
            seekPosition: state.seekPosition || 0,
        };

        this.currentIndex = state.currentIndex || 0;
        this.totalFragments = state.totalFragments || 0;
        this.playbackRate = state.playbackRate || 1.0;
        this._seekPosition = state.seekPosition || 0;

        this.renderPlayer();
        if (state.recordNames) {
            this._recordNames = {};
            for (var uid in state.recordNames) {
                if (state.recordNames.hasOwnProperty(uid)) {
                    this._recordNames[uid] = state.recordNames[uid];
                }
            }
        }
        if (state.listUids) {
            this._listUids = state.listUids;
            this.totalFragments = state.listUids.length;
            if (!this._recordNames) this._recordNames = {};
            this.refreshPlaylistNames();
        }

        var hasRestoredName = false;
        if (state.recordName) {
            this.setRecordName(state.recordName);
            hasRestoredName = true;
        }

        if (this.canAutoPlay(state)) {
            if (state.isPlaying && !state.isPaused) {
                this.playQueue(fragmentData);
            } else if (state.isPaused) {
                this.playQueue(fragmentData);
                this.isPaused = true;
                this.isPlaying = false;
                this.currentIndex = state.currentIndex || 0;
                this.updateProgress();
                this.updatePlayButton();
                this.updateActivePlaylistItem();
            }
        } else {
            this.isPlaying = false;
            this.isPaused = true;
            this.updatePlayButton();
        }

        if (!hasRestoredName) {
            this.setRecordName(this.strings.loading || 'LBL_TTS_LOADING');
        }

        try {
            sessionStorage.removeItem(SESSION_KEY);
        } catch (e) {}
        this._sessionRestoring = false;
    };

    SttTTS.prototype.getRecordName = function () {
        var el = document.querySelector('.tts-record-name');
        return el ? el.textContent : '';
    };

    SttTTS.prototype.playQueue = function (fragmentData) {
        this.log('playQueue called, scenario: ' + fragmentData.scenario + ' module: ' + fragmentData.module);
        this.stop();
        this._playGen++;
        fragmentData._playGen = this._playGen;
        this.totalFragments = fragmentData.totalFragments || 0;
        this.currentIndex = 0;
        this._fetchIndex = fragmentData.fragmentIndex || 0;
        this.audioQueue = [];
        this.abortController = new AbortController();
        var player = document.querySelector('.tts-player');
        if (player) player.classList.remove('tts-loading');
        this.totalCharCount = 0;
        this._fragmentData = fragmentData;
        if (this.totalFragments <= 0 && fragmentData.scenario === 'a') {
            this.totalFragments = 1;
        }
        if (fragmentData.scenario === 'c' && fragmentData.listContext && fragmentData.listContext.uids) {
            this.totalFragments = fragmentData.listContext.uids.length;
            this._listUids = fragmentData.listContext.uids;
        }
        this.updateProgress();
        this.renderPlayer();
        if (fragmentData.scenario === 'c' && this._listUids) {
            if (!this._recordNames) this._recordNames = {};
            var oldNames = this._recordNames;
            this._recordNames = {};
            for (var i = 0; i < this._listUids.length; i++) {
                var uid = this._listUids[i];
                this._recordNames[uid] = oldNames[uid] || null;
            }
            this.refreshPlaylistNames();
            this.fetchRecordNames();
        } else {
            this.updatePlaylist([]);
        }
        this.setRecordName(this.strings.loading || 'LBL_TTS_LOADING');
        this._resetIdle();
        this.fetchNext();
    };

    SttTTS.prototype.fetchRecordNames = function () {
        var self = this;
        if (!this._listUids || this._listUids.length === 0) return;
        if (typeof this.requestRecordNames !== 'function') return;
        var gen = this._playGen;
        this.requestRecordNames({
            module: this.config.module || '',
            uids: this._listUids,
        }).then(function (names) {
            if (self._playGen !== gen) return;
            if (!self._recordNames) self._recordNames = {};
            for (var uid in names) {
                if (names.hasOwnProperty(uid)) {
                    self._recordNames[uid] = names[uid];
                }
            }
            self.refreshPlaylistNames();
        }).catch(function () {});
    };

    SttTTS.prototype.refreshPlaylistNames = function () {
        if (!this._listUids || !this._recordNames) return;
        var items = [];
        for (var i = 0; i < this._listUids.length; i++) {
            var uid = this._listUids[i];
            var name = this._recordNames[uid];
            items.push(name || ((this.strings.record || 'LBL_TTS_RECORD') + ' ' + (i + 1)));
        }
        this.updatePlaylist(items);
        var currentUid = this._listUids[this.currentIndex];
        if (currentUid && this._recordNames[currentUid]) {
            this.setRecordName(this._recordNames[currentUid]);
        }
    };

    SttTTS.prototype.fetchNext = function () {
        if (!this.abortController || this.abortController.signal.aborted) return;
        if (this._fragmentData.scenario === 'c') {
            if (this._fetchIndex >= this.totalFragments) {
                return;
            }
            this._fragmentData.fragmentIndex = this._fetchIndex;
        }
        var player = document.querySelector('.tts-player');
        if (player) player.classList.add('tts-loading');
        this.fetchFragment(this._fragmentData);
    };

    SttTTS.prototype.fetchFragment = function (fragmentData) {
        var self = this;
        if (this.abortController && this.abortController.signal.aborted) return;
        var gen = fragmentData._playGen || this._playGen;

        this.createStreamingSession(fragmentData).then(function (session) {
            if (self._playGen !== gen) {
                if (session.type === 'stream') {
                    try { session.audio.src = ''; } catch (e) {}
                }
                return;
            }
            self.totalCharCount += session.charCount;
            session.fragmentIndex = fragmentData.fragmentIndex;
            if (session.recordName && session.fragmentIndex === self.currentIndex) {
                self.setRecordName(session.recordName);
            }
            session.audio.playbackRate = self.playbackRate;
            self.audioQueue.push(session);
            var p = document.querySelector('.tts-player');
            if (p) p.classList.remove('tts-loading');
            if (!self.isPlaying && !self.isPaused) {
                self.playNext();
            }
            if (self._fragmentData.scenario === 'c') {
                self._fetchIndex++;
                self.fetchNext();
            }
        }).catch(function (err) {
            if (self._playGen !== gen) return;
            self.log('fetchFragment error: ' + (err && err.message ? err.message : 'unknown'));
            if (self._fragmentData && self._fragmentData.scenario === 'c' && self._fetchIndex < self.totalFragments) {
                self._fetchIndex++;
                self.fetchNext();
            } else {
                self.showError(err && err.message ? err.message : (self.strings.error_generic || 'LBL_TTS_ERROR_GENERIC'));
                self.cleanup();
            }
        });
    };

    SttTTS.prototype.updateActivePlaylistItem = function () {
        var items = document.querySelectorAll('.tts-playlist-item');
        for (var i = 0; i < items.length; i++) {
            items[i].classList.remove('tts-playlist-item-active');
        }
        var active = document.querySelector('.tts-playlist-item[data-index="' + this.currentIndex + '"]');
        if (active) {
            active.classList.add('tts-playlist-item-active');
        }
    };

    SttTTS.prototype.playNext = function () {
        if (this.audioQueue.length === 0) {
            this.isPlaying = false;
            return;
        }
        var item = this.audioQueue.shift();
        if (!item || !item.audio) return;
        this.isPlaying = true;
        this.isPaused = false;
        this.currentIndex = item.fragmentIndex || 0;
        this.updateActivePlaylistItem();
        this.updateProgress();
        this.updatePlayButton();
        var name = null;
        var uid = this._listUids ? this._listUids[this.currentIndex] : null;
        if (uid && this._recordNames && this._recordNames[uid]) {
            name = this._recordNames[uid];
        }
        if (!name && item.recordName) {
            name = item.recordName;
        }
        if (name) {
            this.setRecordName(name);
        }
        var audio = item.audio;
        this.audioElement = audio;
        audio.playbackRate = this.playbackRate;
        var self = this;
        this._audioListeners = {
            timeupdate: function () { self.updateTimeDisplay(audio); },
            ended: function () {
                self.audioElement = null;
                self._audioListeners = null;
                if (self.audioQueue.length > 0) {
                    self.playNext();
                } else {
                    self.isPlaying = false;
                    self.finishPlayback();
                }
            },
            error: function () {
                self.audioElement = null;
                self._audioListeners = null;
                self.playNext();
            },
        };
        audio.addEventListener('timeupdate', this._audioListeners.timeupdate);
        audio.addEventListener('ended', this._audioListeners.ended);
        audio.addEventListener('error', this._audioListeners.error);
        var seekPos = this._seekPosition;
        this._seekPosition = 0;
        if (seekPos > 0) {
            var seekOnce = function () {
                audio.currentTime = Math.min(seekPos, audio.duration || 0);
                audio.removeEventListener('loadedmetadata', seekOnce);
            };
            audio.addEventListener('loadedmetadata', seekOnce);
            if (audio.readyState >= 1) {
                seekOnce();
            }
        }
        audio.play().catch(function () {
            self.audioElement = null;
            self.isPlaying = false;
            self.showError(self.strings.error_generic || 'LBL_TTS_ERROR_GENERIC');
        });
    };

    SttTTS.prototype.pause = function () {
        if (this.audioElement && this.isPlaying) {
            this.audioElement.pause();
            this.isPaused = true;
            this.updatePlayButton();
        }
    };

    SttTTS.prototype.resume = function () {
        if (this.audioElement && this.isPaused) {
            this.audioElement.play().catch(function () {});
            this.isPaused = false;
            this.updatePlayButton();
        } else if (!this.isPlaying && this.audioQueue.length > 0) {
            this.playNext();
        }
    };

    SttTTS.prototype.stop = function () {
        if (this.abortController) {
            this.abortController.abort();
            this.abortController = null;
        }
        if (this.audioElement) {
            if (this._audioListeners) {
                this.audioElement.removeEventListener('timeupdate', this._audioListeners.timeupdate);
                this.audioElement.removeEventListener('ended', this._audioListeners.ended);
                this.audioElement.removeEventListener('error', this._audioListeners.error);
                this._audioListeners = null;
            }
            this.audioElement.pause();
            if (this._currentObjectUrl) {
                try { URL.revokeObjectURL(this._currentObjectUrl); } catch (e) {}
                this._currentObjectUrl = null;
            }
            this.audioElement = null;
        }
        this.isPlaying = false;
        this.isPaused = false;
        this.updatePlayButton();
        this.audioQueue = [];
    };

    SttTTS.prototype.closePlayer = function () {
        this.stop();
        this._fragmentData = null;
        this.totalCharCount = 0;
        this.totalFragments = 0;
        this.currentIndex = 0;
        this._fetchIndex = 0;
        this._listUids = null;
        this._recordNames = null;
        this._seekPosition = 0;
        this.audioQueue = [];
        var player = document.querySelector('.tts-player');
        if (player) player.remove();
        try { sessionStorage.removeItem(SESSION_KEY); } catch (e) {}
        if (typeof this.clearCache === 'function') {
            this.clearCache();
        }
    };

    SttTTS.prototype.skipNext = function () {
        if (this._fragmentData && this._fragmentData.scenario === 'c') {
            if (this.currentIndex < this.totalFragments - 1) {
                this.stop();
                this._fetchIndex = this.currentIndex + 1;
                this._fragmentData.fragmentIndex = this._fetchIndex;
                this.playQueue(this._fragmentData);
            }
        } else {
            this.stop();
        }
    };

    SttTTS.prototype.skipPrev = function () {
        if (this._fragmentData && this._fragmentData.scenario === 'c' && this.currentIndex > 0) {
            this.stop();
            this._fetchIndex = this.currentIndex - 1;
            this._fragmentData.fragmentIndex = this._fetchIndex;
            this.playQueue(this._fragmentData);
        }
    };

    SttTTS.prototype.setSpeed = function (rate) {
        this.playbackRate = rate;
        if (this.audioElement) {
            this.audioElement.playbackRate = rate;
        }
    };

    SttTTS.prototype.setRecordName = function (name) {
        var el = document.querySelector('.tts-record-name');
        if (el && name) {
            el.textContent = name;
            el.title = name;
        }
    };

    SttTTS.prototype.seek = function (fraction) {
        if (this.audioElement) {
            this.audioElement.currentTime = fraction * this.audioElement.duration;
        }
    };

    SttTTS.prototype.formatTime = function (seconds) {
        if (!seconds || isNaN(seconds) || !isFinite(seconds)) return '0:00';
        var m = Math.floor(seconds / 60);
        var s = Math.floor(seconds % 60);
        return m + ':' + (s < 10 ? '0' : '') + s;
    };

    SttTTS.prototype.updateTimeDisplay = function (audio) {
        var fill = document.querySelector('.tts-progress-fill');
        var timeEl = document.querySelector('.tts-time');
        if (!fill || !timeEl || !audio) return;
        var current = audio.currentTime || 0;
        var duration = audio.duration || 0;
        var pct = duration > 0 ? (current / duration * 100) : 0;
        fill.style.width = Math.min(pct, 100) + '%';
        timeEl.textContent = this.formatTime(current) + ' / ' + this.formatTime(duration);
    };

    SttTTS.prototype.updateProgress = function () {
        var el = document.querySelector('.tts-progress-text');
        if (!el) return;
        if (this.totalFragments > 1) {
            el.textContent = (this.strings.progress || 'LBL_TTS_PROGRESS')
                .replace('{current}', this.currentIndex + 1)
                .replace('{total}', this.totalFragments);
            el.style.display = '';
        } else {
            el.style.display = 'none';
        }
    };

    SttTTS.prototype.finishPlayback = function () {
        this.isPlaying = false;
        this.updatePlayButton();
        this.saveSessionState();
    };

    SttTTS.prototype.cleanup = function () {
        this.stop();
        this._fragmentData = null;
        this.totalCharCount = 0;
        this.totalFragments = 0;
        this.currentIndex = 0;
        this._fetchIndex = 0;
        this.audioQueue = [];
        try { sessionStorage.removeItem(SESSION_KEY); } catch (e) {}
    };
})();
