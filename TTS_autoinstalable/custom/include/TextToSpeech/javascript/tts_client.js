var SttTTS = (function () {
    var SESSION_KEY = 'sticTtsState';
    var AUDIO_CACHE = 'tts-audio-v1';

    function SttTTS() {
        this.config = window.sticTtsConfig || {};
        this.strings = {};
        this.endpoints = {
            synth: 'index.php?entryPoint=ttsSynth',
            strings: 'index.php?entryPoint=ttsStrings',
            recordNames: 'index.php?entryPoint=ttsRecordNames',
        };
        this.debug = false;
        this._restoredState = null;
    }

    SttTTS.prototype.init = function () {
        this.log('init() called');
        if (this.config.barColor) {
            document.documentElement.style.setProperty('--tts-bar-bg', this.config.barColor);
        }
        this.loadStrings();
        this.tryRestoreSession();
        this.setupObserver();
        if (typeof this.injectButtons === 'function') {
            this.injectButtons();
        } else {
            console.error('[TTS] injectButtons is not a function');
        }
        this.setupBeforeUnload();
        this.log('init completed');
    };

    SttTTS.prototype.tryRestoreSession = function () {
        try {
            var raw = sessionStorage.getItem(SESSION_KEY);
            if (!raw) return;
            try {
                var nav = performance.getEntriesByType('navigation');
                if (nav.length > 0 && nav[0].type === 'reload') {
                    sessionStorage.removeItem(SESSION_KEY);
                    this.log('Reload detected, session cleared');
                    return;
                }
            } catch (e) {}
            var state = JSON.parse(raw);
            var age = Date.now() - (state.timestamp || 0);
            if (age > 300000) {
                sessionStorage.removeItem(SESSION_KEY);
                return;
            }
            this._restoredState = state;
            if (!this.config.module) {
                this.config.module = state.module || '';
            }
            if (!this.config.defaultLanguage) {
                this.config.defaultLanguage = state.language || 'es';
            }
            this.log('Session restored from navigation');
        } catch (e) {
            this.log('Session restore error: ' + e);
        }
    };

    SttTTS.prototype.setupBeforeUnload = function () {
        var self = this;
        window.addEventListener('beforeunload', function () {
            if (self.saveSessionState) {
                self.saveSessionState();
            }
        });
    };

    SttTTS.prototype.log = function (msg) {
        if (this.debug && console) {
            console.log('[SttTTS] ' + msg);
        }
    };

    SttTTS.prototype.sstFetch = function (url, options) {
        if (!options) options = {};
        if (!options.headers) options.headers = {};
        options.headers['X-Requested-With'] = 'XMLHttpRequest';
        return fetch(url, options);
    };

    SttTTS.prototype.loadStrings = function () {
        var self = this;
        var xhr = new XMLHttpRequest();
        xhr.open('GET', this.endpoints.strings, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onload = function () {
            if (xhr.status === 200) {
                try {
                    var data = JSON.parse(xhr.responseText);
                    if (data.success && data.strings) {
                        self.strings = data.strings;
                        self.log('Strings loaded');
                        if (self.updateLabels) self.updateLabels();
                    }
                } catch (e) {
                    self.log('Error parsing strings: ' + e);
                }
            }
        };
        xhr.send();
    };

    SttTTS.prototype.requestFragment = function (fragmentData) {
        return this.sstFetch(this.endpoints.synth, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(fragmentData),
        });
    };

    SttTTS.prototype.supportsMediaSource = function () {
        return typeof MediaSource !== 'undefined' &&
               MediaSource.isTypeSupported &&
               MediaSource.isTypeSupported('audio/mpeg');
    };

    SttTTS.prototype.createStreamingSession = function (fragmentData) {
        var self = this;
        var cacheKey = this.buildCacheKey(fragmentData);

        function startStream(resolve, reject) {
            var audio = new Audio();
            audio.preload = 'auto';
            var mediaSource = null;
            var sourceUrl = null;
            var useStream = self.supportsMediaSource();
            var msReady = false;
            var pendingReader = null;
            var pendingCharCount = 0;
            var pendingDecodedName = '';
            var allChunks = [];
            var resolved = false;

            function startPump(reader, charCount, decodedName) {
                var sourceBuffer;
                try {
                    sourceBuffer = mediaSource.addSourceBuffer('audio/mpeg');
                } catch (e) {
                    useStream = false;
                    self.accumulateToBlob(reader, audio, charCount, decodedName, resolve, reject, cacheKey);
                    return;
                }
                function pump() {
                    reader.read().then(function (result) {
                        if (result.done) {
                            if (mediaSource.readyState === 'open') {
                                try { mediaSource.endOfStream(); } catch (e) {}
                            }
                            if (!resolved) {
                                resolved = true;
                                resolve({ audio: audio, charCount: charCount, recordName: decodedName, type: 'stream' });
                            }
                            self.cachePut(cacheKey, allChunks);
                            return;
                        }
                        allChunks.push(result.value);
                        if (sourceBuffer.updating) {
                            sourceBuffer.addEventListener('updateend', function handler() {
                                sourceBuffer.removeEventListener('updateend', handler);
                                if (mediaSource.readyState !== 'closed') pump();
                            }, { once: true });
                            return;
                        }
                        try {
                            sourceBuffer.appendBuffer(result.value);
                            if (!resolved) {
                                resolved = true;
                                resolve({ audio: audio, charCount: charCount, recordName: decodedName, type: 'stream' });
                            }
                            pump();
                        } catch (e) {
                            if (!resolved) reject(e);
                        }
                    }).catch(function (e) {
                        if (!resolved) reject(e);
                    });
                }
                pump();
            }

            if (useStream) {
                mediaSource = new MediaSource();
                sourceUrl = URL.createObjectURL(mediaSource);
                audio.src = sourceUrl;
                mediaSource.addEventListener('sourceopen', function () {
                    msReady = true;
                    if (pendingReader) {
                        startPump(pendingReader, pendingCharCount, pendingDecodedName);
                        pendingReader = null;
                    }
                });
            }

            self.requestFragment(fragmentData).then(function (resp) {
                if (!resp.ok) {
                    resp.text().then(function (bodyText) {
                        var msg = 'HTTP ' + resp.status;
                        try {
                            var data = JSON.parse(bodyText);
                            if (data.error) msg = data.error;
                        } catch (e) {}
                        reject(new Error(msg));
                    }).catch(function () {
                        reject(new Error('HTTP ' + resp.status));
                    });
                    return;
                }

                var charCount = parseInt(resp.headers.get('X-TTS-Char-Count')) || 0;
                var rawName = resp.headers.get('X-TTS-Record-Name') || '';
                var decodedName = self.b64decode(rawName);
                var reader = resp.body.getReader();

                if (useStream && mediaSource) {
                    if (msReady) {
                        startPump(reader, charCount, decodedName);
                    } else {
                        pendingReader = reader;
                        pendingCharCount = charCount;
                        pendingDecodedName = decodedName;
                    }
                } else {
                    self.accumulateToBlob(reader, audio, charCount, decodedName, resolve, reject, cacheKey);
                }
            }).catch(function (e) {
                if (!resolved) reject(e);
            });
        }

        return new Promise(function (resolve, reject) {
            var audio = new Audio();
            audio.preload = 'auto';
            self.cacheGet(cacheKey, audio).then(function (cached) {
                if (cached) {
                    resolve(cached);
                    return;
                }
                startStream(resolve, reject);
            }).catch(function () {
                startStream(resolve, reject);
            });
        });
    };

    SttTTS.prototype.accumulateToBlob = function (reader, audio, charCount, decodedName, resolve, reject, cacheKey) {
        var self = this;
        var chunks = [];
        function pump() {
            reader.read().then(function (result) {
                if (result.done) {
                    var blob = new Blob(chunks, { type: 'audio/mpeg' });
                    var url = URL.createObjectURL(blob);
                    audio.src = url;
                    self._currentObjectUrl = url;
                    audio.addEventListener('ended', function () {
                        URL.revokeObjectURL(url);
                        self._currentObjectUrl = null;
                    });
                    resolve({ audio: audio, charCount: charCount, recordName: decodedName, type: 'blob' });
                    if (cacheKey) self.cachePut(cacheKey, chunks);
                    return;
                }
                chunks.push(result.value);
                pump();
            }).catch(reject);
        }
        pump();
    };

    SttTTS.prototype.b64decode = function (b64) {
        if (!b64) return '';
        if (b64.indexOf('%') !== -1) {
            try { return decodeURIComponent(b64); } catch (e) { return b64; }
        }
        try {
            var bin = atob(b64);
            var bytes = new Uint8Array(bin.length);
            for (var i = 0; i < bin.length; i++) bytes[i] = bin.charCodeAt(i);
            return new TextDecoder('utf-8').decode(bytes);
        } catch (e) {
            return b64;
        }
    };

    SttTTS.prototype._hashStr = function (str) {
        var hash = 0;
        for (var i = 0; i < str.length; i++) {
            hash = ((hash << 5) - hash) + str.charCodeAt(i);
            hash |= 0;
        }
        return Math.abs(hash).toString(36);
    };

    SttTTS.prototype.buildCacheKey = function (fd) {
        var parts = [fd.scenario || 'a', fd.module || '', fd.language || 'es'];
        if (fd.text) {
            parts.push(fd.text);
        } else {
            parts.push(fd.record || '');
            if (fd.fields && fd.fields.length) parts.push(fd.fields.join(','));
        }
        if (fd.scenario === 'c' && fd.listContext && fd.listContext.uids && fd.fragmentIndex != null) {
            var uid = fd.listContext.uids[fd.fragmentIndex];
            if (uid) parts.push(uid);
        }
        return 'tts-' + this._hashStr(parts.join('|'));
    };

    SttTTS.prototype.cachePut = function (key, chunks) {
        var self = this;
        if (!chunks || chunks.length === 0) return;
        var blob = new Blob(chunks, { type: 'audio/mpeg' });
        if (!self._audioCache) self._audioCache = {};
        self._audioCache[key] = blob;
        try {
            caches.open(AUDIO_CACHE).then(function (cache) {
                cache.put(key, new Response(blob, {
                    headers: { 'Content-Type': 'audio/mpeg' }
                }));
            }).catch(function () {});
        } catch (e) {}
    };

    SttTTS.prototype.cacheGet = function (key, audio) {
        var self = this;
        if (self._audioCache && self._audioCache[key]) {
            var cachedBlob = self._audioCache[key];
            var url = URL.createObjectURL(cachedBlob);
            audio.src = url;
            self._currentObjectUrl = url;
            audio.addEventListener('ended', function () { URL.revokeObjectURL(url); self._currentObjectUrl = null; });
            return Promise.resolve({ audio: audio, charCount: 0, recordName: '', type: 'blob' });
        }
        try {
            return caches.open(AUDIO_CACHE).then(function (cache) {
                return cache.match(key).then(function (response) {
                    if (!response) return null;
                    return response.blob().then(function (blob) {
                        if (!self._audioCache) self._audioCache = {};
                        self._audioCache[key] = blob;
                        var url = URL.createObjectURL(blob);
                        audio.src = url;
                        self._currentObjectUrl = url;
                        audio.addEventListener('ended', function () { URL.revokeObjectURL(url); self._currentObjectUrl = null; });
                        return { audio: audio, charCount: 0, recordName: '', type: 'blob' };
                    });
                });
            }).catch(function () { return null; });
        } catch (e) {
            return Promise.resolve(null);
        }
    };

    SttTTS.prototype.requestWithRetry = function (fragmentData, maxRetries) {
        if (maxRetries === undefined) maxRetries = 2;
        var self = this;
        return new Promise(function (resolve, reject) {
            function attempt(remaining) {
                self.requestFragment(fragmentData).then(function (resp) {
                    if (!resp.ok) {
                        if (remaining > 0) {
                            var delay = Math.pow(2, maxRetries - remaining) * 500;
                            self.log('Retry in ' + delay + 'ms (' + remaining + ' left)');
                            setTimeout(function () { attempt(remaining - 1); }, delay);
                        } else {
                            reject(new Error('HTTP ' + resp.status));
                        }
                        return;
                    }
                    var charCount = parseInt(resp.headers.get('X-TTS-Char-Count')) || 0;
                    var rawName = resp.headers.get('X-TTS-Record-Name') || '';
                    var decodedName = self.b64decode(rawName);
                    resolve({ resp: resp, charCount: charCount, recordName: decodedName });
                }).catch(function (err) {
                    if (remaining > 0) {
                        var delay = Math.pow(2, maxRetries - remaining) * 500;
                        self.log('Retry in ' + delay + 'ms (' + remaining + ' left): ' + err);
                        setTimeout(function () { attempt(remaining - 1); }, delay);
                    } else {
                        reject(err);
                    }
                });
            }
            attempt(maxRetries);
        });
    };

    SttTTS.prototype.requestRecordNames = function (data) {
        return this.sstFetch(this.endpoints.recordNames, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data),
        }).then(function (resp) {
            if (!resp.ok) throw new Error('HTTP ' + resp.status);
            return resp.json();
        });
    };

    SttTTS.prototype.clearCache = function () {
        if (this._audioCache) {
            for (var key in this._audioCache) {
                if (this._audioCache.hasOwnProperty(key)) {
                    var url = this._audioCache[key];
                    if (url instanceof Blob) {
                        // in-memory blob, no URL to revoke
                    }
                }
            }
            this._audioCache = {};
        }
        try {
            caches.delete(AUDIO_CACHE);
        } catch (e) {}
    };

    SttTTS.prototype.setupObserver = function () {
        var self = this;
        var observer = new MutationObserver(function () {
            if (self.injectButtons) {
                self.injectButtons();
            }
        });
        observer.observe(document.body, { childList: true, subtree: true });
    };

    return SttTTS;
})();

document.addEventListener('DOMContentLoaded', function () {
    if (typeof SttTTS !== 'undefined') {
        var tts = new SttTTS();
        window.SttTTSInstance = tts;
        tts.init();
    }
});
