var SttTTS = (function () {
    function SttTTS() {
        this.config = window.sticTtsConfig || {};
        this.strings = {};
        this.endpoints = {
            synth: 'index.php?entryPoint=ttsSynth',
            usage: 'index.php?entryPoint=ttsUsage',
            strings: 'index.php?entryPoint=ttsStrings',
        };
        this.debug = false;
    }

    SttTTS.prototype.init = function () {
        this.log('Initializing SttTTS');
        this.loadStrings();
        this.setupObserver();
        if (this.injectButtons) {
            this.injectButtons();
        }
        this.log('SttTTS initialized');
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
                    Promise.all([resp.blob(), resp.headers.get('X-TTS-Char-Count'), resp.headers.get('X-TTS-Record-Name')]).then(function (results) {
                        var rawName = results[2] || '';
                        var decodedName = rawName;
                        function b64decode(b64) {
                            try {
                                var bin = atob(b64);
                                var bytes = new Uint8Array(bin.length);
                                for (var i = 0; i < bin.length; i++) bytes[i] = bin.charCodeAt(i);
                                return new TextDecoder('utf-8').decode(bytes);
                            } catch (e) { return b64; }
                        }
                        if (rawName.indexOf('%') !== -1) {
                            try { decodedName = decodeURIComponent(rawName); } catch (e) {}
                        } else {
                            decodedName = b64decode(rawName);
                        }
                        resolve({ blob: results[0], charCount: parseInt(results[1]) || 0, recordName: decodedName });
                    });
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

    SttTTS.prototype.reportUsage = function (charCount, language, module, scenario) {
        return this.sstFetch(this.endpoints.usage, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                charCount: charCount,
                language: language || this.config.defaultLanguage || 'es',
                module: module || this.config.module || '',
                scenario: scenario || 'a',
            }),
        });
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
