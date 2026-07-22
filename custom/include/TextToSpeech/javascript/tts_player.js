(function () {
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
    SttTTS.prototype._usageReported = false;
    SttTTS.prototype._fragmentData = null;
    SttTTS.prototype._prefetchCount = 1;
    SttTTS.prototype._listUids = null;

    SttTTS.prototype.playQueue = function (fragmentData) {
        this.stop();
        this.totalFragments = fragmentData.totalFragments || 0;
        this.currentIndex = fragmentData.fragmentIndex || 0;
        this.audioQueue = [];
        this.abortController = new AbortController();
        var player = document.querySelector('.tts-player');
        if (player) player.classList.remove('tts-loading');
        this._usageReported = false;
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
            var items = [];
            for (var i = 0; i < this._listUids.length; i++) {
                items.push((this.strings.record || 'LBL_TTS_RECORD') + ' ' + (i + 1));
            }
            this.updatePlaylist(items);
        } else {
            this.updatePlaylist([]);
        }
        if (fragmentData.scenario !== 'a') {
            this.setRecordName(this.strings.loading || 'LBL_TTS_LOADING');
        } else {
            this.setRecordName(this.strings.now_playing || 'LBL_TTS_NOW_PLAYING');
        }
        this._resetIdle();
        this.fetchNext();
    };

    SttTTS.prototype.fetchNext = function () {
        if (!this.abortController || this.abortController.signal.aborted) return;
        if (this._fragmentData.scenario === 'c') {
            if (this.currentIndex >= this.totalFragments) {
                this.finishPlayback();
                return;
            }
            this._fragmentData.fragmentIndex = this.currentIndex;
        }
        var player = document.querySelector('.tts-player');
        if (player) player.classList.add('tts-loading');
        this.fetchFragment(this._fragmentData);
    };

    SttTTS.prototype.fetchFragment = function (fragmentData) {
        var self = this;
        if (this.abortController && this.abortController.signal.aborted) return;
        this.requestWithRetry(fragmentData).then(function (result) {
            if (self.abortController && self.abortController.signal.aborted) return;
            self.totalCharCount += result.charCount;
            if (result.recordName) {
                self.setRecordName(result.recordName);
            }
            self.audioQueue.push(result.blob);
            if (!self.isPlaying && !self.isPaused) {
                var p = document.querySelector('.tts-player');
                if (p) p.classList.remove('tts-loading');
                self.playNext();
            }
            if (self._fragmentData.scenario === 'c' && self.currentIndex < self.totalFragments) {
                self.currentIndex++;
                self.updateProgress();
                self.prefetchNext();
            }
        }).catch(function () {
            self.showError(self.strings.error_generic || 'LBL_TTS_ERROR_GENERIC');
            self.cleanup();
        });
    };

    SttTTS.prototype.prefetchNext = function () {
        if (!this._fragmentData || this._fragmentData.scenario !== 'c') return;
        var self = this;
        var prefetchLimit = Math.min(this._prefetchCount, 2);
        for (var i = 0; i < prefetchLimit; i++) {
            var nextIdx = this.currentIndex + i;
            if (nextIdx < this.totalFragments && this.audioQueue.length < this.totalFragments) {
                (function (idx) {
                    var nextData = JSON.parse(JSON.stringify(self._fragmentData));
                    nextData.fragmentIndex = idx;
                    self.requestWithRetry(nextData).then(function (result) {
                        if (self.abortController && self.abortController.signal.aborted) return;
                        self.totalCharCount += result.charCount;
                        self.audioQueue.push(result.blob);
                    }).catch(function () {});
                })(nextIdx);
            }
        }
    };

    SttTTS.prototype.playNext = function () {
        if (this.audioQueue.length === 0) {
            this.isPlaying = false;
            return;
        }
        var blob = this.audioQueue.shift();
        if (!blob) return;
        this.isPlaying = true;
        this.isPaused = false;
        this.updatePlayButton();
        var url = URL.createObjectURL(blob);
        var audio = new Audio(url);
        this.audioElement = audio;
        audio.playbackRate = this.playbackRate;
        var self = this;
        audio.addEventListener('timeupdate', function () {
            self.updateTimeDisplay(audio);
        });
        audio.addEventListener('ended', function () {
            URL.revokeObjectURL(url);
            self.audioElement = null;
            if (self.audioQueue.length > 0) {
                self.playNext();
            } else {
                self.isPlaying = false;
                self.finishPlayback();
            }
        });
        audio.addEventListener('error', function () {
            URL.revokeObjectURL(url);
            self.audioElement = null;
            self.playNext();
        });
        audio.play().catch(function () {
            URL.revokeObjectURL(url);
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
            this.audioElement.pause();
            this.audioElement = null;
        }
        this.isPlaying = false;
        this.isPaused = false;
        this.updatePlayButton();
        this.audioQueue = [];
        this.reportUsageDelayed();
    };

    SttTTS.prototype.skipNext = function () {
        if (this._fragmentData && this._fragmentData.scenario === 'c') {
            if (this.currentIndex < this.totalFragments - 1) {
                this.stop();
                this.currentIndex++;
                this._fragmentData.fragmentIndex = this.currentIndex;
                this.playQueue(this._fragmentData);
            }
        } else {
            this.stop();
        }
    };

    SttTTS.prototype.skipPrev = function () {
        if (this._fragmentData && this._fragmentData.scenario === 'c' && this.currentIndex > 0) {
            this.stop();
            this.currentIndex--;
            this._fragmentData.fragmentIndex = this.currentIndex;
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
        var el = document.querySelector('.tts-record-name');
        if (!el) return;
        if (this.totalFragments > 1) {
            el.textContent = (this.strings.progress || 'LBL_TTS_PROGRESS')
                .replace('{current}', this.currentIndex + 1)
                .replace('{total}', this.totalFragments);
        }
    };

    SttTTS.prototype.finishPlayback = function () {
        this.isPlaying = false;
        this.updatePlayButton();
        this.reportUsageDelayed();
    };

    SttTTS.prototype.reportUsageDelayed = function () {
        var self = this;
        if (this._usageReported || this.totalCharCount <= 0) return;
        this._usageReported = true;
        setTimeout(function () {
            self.reportUsage(
                self.totalCharCount,
                self.config.defaultLanguage || 'es',
                self.config.module || '',
                self._fragmentData ? self._fragmentData.scenario : 'a'
            );
        }, 500);
    };

    SttTTS.prototype.cleanup = function () {
        this.stop();
        this._fragmentData = null;
        this.totalCharCount = 0;
        this.totalFragments = 0;
        this.currentIndex = 0;
        this.audioQueue = [];
    };
})();
