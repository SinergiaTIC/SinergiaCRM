(function () {
    var SVG_PLAY = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><polygon points="5,3 19,12 5,21"/></svg>';
    var SVG_PAUSE = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>';
    var SVG_STOP = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><rect x="6" y="6" width="12" height="12"/></svg>';

    SttTTS.prototype.injectButtons = function () {
        var cfg = this.config;
        if (!cfg) return;

        this.injectTextareaButtons();

        if (cfg.hasHighlight && cfg.isDetailView) {
            this.injectDetailAction();
        }

        if (cfg.isListView) {
            this.injectListviewAction();
        }
    };

    SttTTS.prototype.injectTextareaButtons = function () {
        var self = this;
        var textareas = document.querySelectorAll('textarea:not([class*="mce"]):not([class*="htmlarea"])');
        if (this.config.textareaFields && this.config.textareaFields.length > 0) {
            var isAll = this.config.textareaFields.length === 1 &&
                (this.config.textareaFields[0] === 'ALL' || this.config.textareaFields[0] === '*');
            if (!isAll) {
                textareas = [];
                for (var i = 0; i < this.config.textareaFields.length; i++) {
                    var field = this.config.textareaFields[i];
                    var el = document.querySelector('textarea[name="' + field + '"], textarea[id="' + field + '"]');
                    if (el) textareas.push(el);
                }
            }
        }
        var languages = this.config.languages || [];
        var defaultLang = this.config.defaultLanguage || 'es';
        for (var i = 0; i < textareas.length; i++) {
            var ta = textareas[i];
            if (ta._ttsWrapper) continue;
            var wrapper = document.createElement('div');
            wrapper.className = 'tts-textarea-wrapper';
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'tts-textarea-btn';
            btn.title = self.strings.listen || 'LBL_TTS_LISTEN';
            btn.innerHTML = SVG_PLAY;
            btn.addEventListener('click', function (tb) {
                return function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    self.playTextarea(tb);
                };
            }(ta));
            wrapper.appendChild(btn);
            var langSelect = document.createElement('select');
            langSelect.className = 'tts-textarea-lang';
            langSelect.setAttribute('aria-label', self.strings.language || 'LBL_TTS_LANGUAGE');
            for (var j = 0; j < languages.length; j++) {
                var opt = document.createElement('option');
                opt.value = languages[j].code;
                opt.textContent = languages[j].label;
                if (languages[j].code === defaultLang) opt.selected = true;
                langSelect.appendChild(opt);
            }
            wrapper.appendChild(langSelect);
            ta.parentNode.insertBefore(wrapper, ta);
            ta._ttsWrapper = wrapper;
            ta._ttsLang = langSelect;
        }
    };

    SttTTS.prototype.injectDetailAction = function () {
        var self = this;
        if (document.querySelector('.tts-detail-btn')) return;
        var menu = document.querySelector('#tab-actions ul.dropdown-menu');
        if (!menu) return;
        var li = document.createElement('li');
        li.className = 'tts-detail-btn';
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'button';
        btn.textContent = (self.strings.listen_highlighted || 'LBL_TTS_LISTEN_HIGHLIGHTED');
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            self.playDetail();
        });
        li.appendChild(btn);
        menu.appendChild(li);
    };

    SttTTS.prototype.injectListviewAction = function () {
        var self = this;
        if (document.querySelector('.tts-list-btn')) return;
        var topMenu = document.querySelector('ul#actionLinkTop li.sugar_action_button ul.subnav');
        if (!topMenu) return;
        var li = document.createElement('li');
        li.className = 'tts-list-btn';
        var a = document.createElement('a');
        a.href = '#';
        a.innerHTML = (self.strings.listen_mass || 'LBL_TTS_LISTEN_MASS');
        a.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            self.playListview();
        });
        li.appendChild(a);
        topMenu.appendChild(li);
    };

    SttTTS.prototype.getSelectedRecords = function () {
        if (typeof sugarListView !== 'undefined' && sugarListView.get_checks) {
            sugarListView.get_checks();
        }
        var mu = document.MassUpdate;
        if (!mu || !mu.uid || !mu.uid.value) {
            this.showError(this.strings.error_no_selection || 'LBL_TTS_ERROR_NO_SELECTION');
            return null;
        }
        var uids = mu.uid.value;
        if (!uids) {
            this.showError(this.strings.error_no_selection || 'LBL_TTS_ERROR_NO_SELECTION');
            return null;
        }
        var ids = uids.split(',');
        var maxRecords = this.config.maxRecords || 50;
        if (ids.length > maxRecords) {
            this.showError((this.strings.error_too_many || 'LBL_TTS_ERROR_TOO_MANY').replace('{max}', maxRecords));
            return null;
        }
        var orderBy = '';
        if (typeof sugarListView !== 'undefined' && sugarListView.orderBy) {
            orderBy = sugarListView.orderBy;
        }
        return {
            uids: ids,
            current_query_by_page: mu.current_query_by_page ? mu.current_query_by_page.value : '',
            lvso: mu.lvso ? mu.lvso.value : 'ASC',
            orderBy: orderBy,
            select_entire_list: mu.select_entire_list ? mu.select_entire_list.value : '0',
        };
    };

    SttTTS.prototype.renderPlayer = function () {
        var self = this;
        if (document.querySelector('.tts-player')) return;
        var container = document.createElement('div');
        container.className = 'tts-player';

        var left = document.createElement('div');
        left.className = 'tts-player-left';
        var playlistBtn = document.createElement('button');
        playlistBtn.className = 'tts-playlist-btn';
        playlistBtn.title = self.strings.playlist || 'LBL_TTS_PLAYLIST';
        playlistBtn.setAttribute('aria-label', self.strings.playlist || 'LBL_TTS_PLAYLIST');
        playlistBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><line x1="3" y1="6" x2="21" y2="6" stroke="currentColor" stroke-width="2"/><line x1="3" y1="12" x2="21" y2="12" stroke="currentColor" stroke-width="2"/><line x1="3" y1="18" x2="21" y2="18" stroke="currentColor" stroke-width="2"/></svg>';
        var nameEl = document.createElement('span');
        nameEl.className = 'tts-record-name';
        nameEl.textContent = self.strings.now_playing || 'LBL_TTS_NOW_PLAYING';
        left.appendChild(playlistBtn);
        left.appendChild(nameEl);

        var center = document.createElement('div');
        center.className = 'tts-player-center';
        var progressContainer = document.createElement('div');
        progressContainer.className = 'tts-progress-container';
        var progressBar = document.createElement('div');
        progressBar.className = 'tts-progress-bar';
        progressBar.tabIndex = 0;
        progressBar.setAttribute('role', 'slider');
        progressBar.setAttribute('aria-label', self.strings.seek || 'LBL_TTS_SEEK');
        var progressFill = document.createElement('div');
        progressFill.className = 'tts-progress-fill';
        progressBar.appendChild(progressFill);
        var timeDisplay = document.createElement('span');
        timeDisplay.className = 'tts-time';
        timeDisplay.textContent = '0:00 / 0:00';
        progressContainer.appendChild(progressBar);
        progressContainer.appendChild(timeDisplay);
        center.appendChild(progressContainer);

        var right = document.createElement('div');
        right.className = 'tts-player-right';

        var prevBtn = document.createElement('button');
        prevBtn.className = 'tts-prev-btn';
        prevBtn.title = self.strings.prev || 'LBL_TTS_PREV';
        prevBtn.setAttribute('aria-label', self.strings.prev || 'LBL_TTS_PREV');
        prevBtn.innerHTML = '⏮';
        prevBtn.addEventListener('click', function () {
            if (self.skipPrev) self.skipPrev();
        });

        var playBtn = document.createElement('button');
        playBtn.className = 'tts-play-btn';
        playBtn.setAttribute('aria-label', self.strings.play || 'LBL_TTS_PLAY');
        playBtn.innerHTML = SVG_PLAY;
        playBtn.addEventListener('click', function () {
            if (self.isPlaying && !self.isPaused) {
                if (self.pause) self.pause();
            } else if (self.isPaused) {
                if (self.resume) self.resume();
            } else {
                var fd = self._fragmentData;
                if (fd) self.playQueue(fd);
            }
        });

        var stopBtn = document.createElement('button');
        stopBtn.className = 'tts-stop-btn';
        stopBtn.title = self.strings.stop || 'LBL_TTS_STOP';
        stopBtn.setAttribute('aria-label', self.strings.stop || 'LBL_TTS_STOP');
        stopBtn.innerHTML = SVG_STOP;
        stopBtn.addEventListener('click', function () {
            if (self.stop) self.stop();
        });

        var nextBtn = document.createElement('button');
        nextBtn.className = 'tts-next-btn';
        nextBtn.title = self.strings.next || 'LBL_TTS_NEXT';
        nextBtn.setAttribute('aria-label', self.strings.next || 'LBL_TTS_NEXT');
        nextBtn.innerHTML = '⏭';
        nextBtn.addEventListener('click', function () {
            if (self.skipNext) self.skipNext();
        });

        var speedSelect = document.createElement('select');
        speedSelect.className = 'tts-speed';
        speedSelect.setAttribute('aria-label', self.strings.speed || 'LBL_TTS_SPEED');
        var speeds = [0.5, 0.75, 1.0, 1.25, 1.5];
        for (var i = 0; i < speeds.length; i++) {
            var opt = document.createElement('option');
            opt.value = speeds[i];
            opt.textContent = speeds[i] + 'x';
            if (speeds[i] === 1.0) opt.selected = true;
            speedSelect.appendChild(opt);
        }
        speedSelect.addEventListener('change', function () {
            if (self.setSpeed) self.setSpeed(parseFloat(this.value));
        });

        right.appendChild(prevBtn);
        right.appendChild(playBtn);
        right.appendChild(stopBtn);
        right.appendChild(nextBtn);
        right.appendChild(speedSelect);

        var dropdown = document.createElement('div');
        dropdown.className = 'tts-playlist-dropdown';
        dropdown.style.display = 'none';

        container.appendChild(left);
        container.appendChild(center);
        container.appendChild(right);
        container.appendChild(dropdown);
        document.body.appendChild(container);

        progressBar.addEventListener('click', function (e) {
            var rect = this.getBoundingClientRect();
            var fraction = (e.clientX - rect.left) / rect.width;
            if (self.seek) self.seek(fraction);
        });

        playlistBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            var isVisible = dropdown.style.display !== 'none';
            dropdown.style.display = isVisible ? 'none' : 'block';
        });

        document.addEventListener('click', function () {
            dropdown.style.display = 'none';
        });
        dropdown.addEventListener('click', function (e) {
            e.stopPropagation();
        });

        var idleTimer = null;
        function resetIdle() {
            container.classList.remove('tts-player-idle');
            if (idleTimer) clearTimeout(idleTimer);
            if (!self.isPlaying && !self.isPaused) {
                idleTimer = setTimeout(function () {
                    container.classList.add('tts-player-idle');
                }, 5000);
            }
        }
        container.addEventListener('mouseenter', function () {
            container.classList.remove('tts-player-idle');
            if (idleTimer) clearTimeout(idleTimer);
        });
        container.addEventListener('mouseleave', resetIdle);
        self._resetIdle = resetIdle;

        return container;
    };

    SttTTS.prototype.updatePlayButton = function () {
        var btn = document.querySelector('.tts-play-btn');
        if (!btn) return;
        if (this.isPlaying && !this.isPaused) {
            btn.innerHTML = SVG_PAUSE;
            btn.title = this.strings.pause || 'LBL_TTS_PAUSE';
            btn.setAttribute('aria-label', this.strings.pause || 'LBL_TTS_PAUSE');
        } else {
            btn.innerHTML = SVG_PLAY;
            btn.title = this.strings.play || 'LBL_TTS_PLAY';
            btn.setAttribute('aria-label', this.strings.play || 'LBL_TTS_PLAY');
        }
    };

    SttTTS.prototype.updatePlaylist = function (items) {
        var self = this;
        var dropdown = document.querySelector('.tts-playlist-dropdown');
        var playlistBtn = document.querySelector('.tts-playlist-btn');
        if (!dropdown) return;
        dropdown.innerHTML = '';
        if (!items || items.length === 0) {
            dropdown.style.display = 'none';
            if (playlistBtn) playlistBtn.style.display = 'none';
            return;
        }
        if (playlistBtn) playlistBtn.style.display = '';
        for (var i = 0; i < items.length; i++) {
            var item = document.createElement('div');
            item.className = 'tts-playlist-item';
            item.textContent = items[i];
            item.dataset.index = i;
            if (i === this.currentIndex) {
                item.classList.add('tts-playlist-item-active');
            }
            item.addEventListener('click', function () {
                var idx = parseInt(this.dataset.index);
                if (!isNaN(idx) && self._listUids && idx < self._listUids.length) {
                    self.jumpToRecord(idx);
                }
            });
            dropdown.appendChild(item);
        }
    };

    SttTTS.prototype.jumpToRecord = function (index) {
        if (!this._listUids || index >= this._listUids.length) return;
        var fd = this._fragmentData;
        if (!fd) return;
        var newData = JSON.parse(JSON.stringify(fd));
        newData.fragmentIndex = index;
        this.playQueue(newData);
    };

    SttTTS.prototype.updateLabels = function () {
        var btns = document.querySelectorAll('.tts-textarea-btn');
        for (var i = 0; i < btns.length; i++) {
            btns[i].title = this.strings.listen || 'LBL_TTS_LISTEN';
        }
        var detailBtn = document.querySelector('.tts-detail-btn button');
        if (detailBtn) {
            detailBtn.textContent = (this.strings.listen_highlighted || 'LBL_TTS_LISTEN_HIGHLIGHTED');
        }
        var massLink = document.querySelector('.tts-list-btn a');
        if (massLink) {
            massLink.innerHTML = (this.strings.listen_mass || 'LBL_TTS_LISTEN_MASS');
        }
    };

    SttTTS.prototype.showError = function (msg) {
        if (console) console.error('[SttTTS] ' + msg);
        if (typeof SugarMessages !== 'undefined') {
            SugarMessages(msg, 'error');
        } else {
            alert(msg);
        }
    };

    SttTTS.prototype.playTextarea = function (textarea) {
        var text = textarea.value || textarea.textContent || '';
        if (!text.trim()) {
            this.showError(this.strings.error_empty || 'LBL_TTS_ERROR_EMPTY');
            return;
        }
        var lang = (textarea._ttsLang && textarea._ttsLang.value) || this.config.defaultLanguage || 'es';
        this.startPlayback({
            scenario: 'a',
            text: text,
            fields: [],
            language: lang,
        });
    };

    SttTTS.prototype.playDetail = function () {
        this.startPlayback({
            scenario: 'b',
            record: this.getRecordId(),
            fields: this.config.fields || [],
            language: this.config.defaultLanguage || 'es',
            text: '',
        });
    };

    SttTTS.prototype.playListview = function () {
        var selection = this.getSelectedRecords();
        if (!selection) return;
        this.startPlayback({
            scenario: 'c',
            listContext: selection,
            fields: this.config.fields || [],
            language: this.config.defaultLanguage || 'es',
            text: '',
        });
    };

    SttTTS.prototype.getRecordId = function () {
        var el = document.querySelector('input[name="record"], input#record');
        return el ? el.value : '';
    };

    SttTTS.prototype.startPlayback = function (fragmentData) {
        fragmentData.module = this.config.module || '';
        fragmentData.fragmentIndex = 0;
        this.currentPlayback = fragmentData;
        if (typeof this.playQueue === 'function') {
            this.playQueue(fragmentData);
        } else {
            this.singleRequest(fragmentData);
        }
    };

    SttTTS.prototype.singleRequest = function (fragmentData) {
        var self = this;
        this.renderPlayer();
        this.requestWithRetry(fragmentData).then(function (result) {
            var url = URL.createObjectURL(result.blob);
            var audio = new Audio(url);
            audio.playbackRate = 1.0;
            audio.addEventListener('ended', function () {
                URL.revokeObjectURL(url);
            });
            audio.play();
        }).catch(function (err) {
            self.showError(self.strings.error_generic || 'LBL_TTS_ERROR_GENERIC');
        });
    };
})();
