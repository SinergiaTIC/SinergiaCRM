/**
 * survey-validation.js
 * Client-side validation for required questions in a generic survey form.
 * - Detects required fields via [required] or data-required="true"
 * - Handles radio/checkbox groups
 * - Displays inline error messages
 * - Prevents submission and focuses first invalid field
 *
 * Usage:
 * 1) Add id="survey-form" to your <form> (or leave as the first <form> on the page).
 * 2) Include this script with `defer` or right before </body>.
 * 3) (Optional) Wrap each question in a container with class="question" for nicer errors.
 */

(function () {
    function $(selector, root) { return (root || document).querySelector(selector); }
    function $all(selector, root) { return Array.prototype.slice.call((root || document).querySelectorAll(selector)); }
  
    function getForm() {
      return document.getElementById("survey-form") || document.querySelector("form");
    }

    var I18N_MODULE = (window && window.SURVEY_I18N_MODULE) || 'Surveys';

    function sprintfFallback(str, params) {
        if (!str) return str;
        str = str.replace(/\{(min|max|n)\}/g, function (_, k) {
          return params && params[k] != null ? String(params[k]) : _;
        });
        if (/%d/.test(str)) {
          var value = params && (params.n != null ? params.n : (params.min != null ? params.min : params.max));
          if (value != null) str = str.replace(/%d/g, String(value));
        }
        return str;
      }
      
      function getDictString(key) {
        if (window.SURVEY_I18N) {
          if (window.SURVEY_I18N[key]) return window.SURVEY_I18N[key];
          var alias = KEY_ALIAS[key];
          if (alias && window.SURVEY_I18N[alias]) return window.SURVEY_I18N[alias];
        }
        return null;
      }
      
      function tr(key, fallback, params) {
        // 1) Prioritza el diccionari injectat per PHP/JS en public survey
        var s = getDictString(key);
      
        // 2) Si hi ha API de SuiteCRM, prova també (i els alias)
        if (!s || s === key) {
          try {
            if (window.SUGAR && SUGAR.language && typeof SUGAR.language.translate === 'function') {
              s = SUGAR.language.translate(I18N_MODULE, key);
              if ((!s || s === key) && KEY_ALIAS[key]) {
                s = SUGAR.language.translate(I18N_MODULE, KEY_ALIAS[key]);
              }
            }
          } catch (e) { /* noop */ }
        }
      
        // 3) Fallback final
        if (!s || s === key) s = fallback;
        return sprintfFallback(s, params);
      }
  
    function findQuestionContainer(el) {
      // Prefer a .question container up the tree; fallback to the element's parent
      return el.closest(".question") || el.parentElement;
    }
  
    function clearError(el) {
      const container = findQuestionContainer(el);
      if (!container) return;
      container.classList.remove("has-error");
      const msg = $(".error-msg", container);
      if (msg) msg.remove();
      el.setAttribute("aria-invalid", "false");
      el.removeAttribute("aria-describedby");
    }
  
    function showError(el, message) {
      const container = findQuestionContainer(el);
      if (!container) return;
      container.classList.add("has-error");
      let msg = $(".error-msg", container);
      if (!msg) {
        msg = document.createElement("div");
        msg.className = "error-msg";
        container.appendChild(msg);
      }
      msg.textContent = message;
      const id = el.id || el.name;
      if (id) {
        const msgId = id + "-error";
        msg.id = msgId;
        el.setAttribute("aria-describedby", msgId);
      }
      el.setAttribute("aria-invalid", "true");
    }
  
    function isEmpty(value) {
      return value == null || String(value).trim() === "";
    }
  
    function validateGroup(groupInputs, container) {
      const name = groupInputs[0].name;
      const checked = groupInputs.filter(i => i.checked);
      const min = parseInt(container?.getAttribute("data-min") || "1", 10);
  
      if (checked.length < min) {
        groupInputs.forEach(clearError);
        showError(groupInputs[0], tr('LBL_SURVEY_SELECT_AT_LEAST_ONE', 'Select at least one option'));
        return { valid: false, focusEl: groupInputs[0] };
      }
      groupInputs.forEach(clearError);
      return { valid: true };
    }
  
    function validateField(el) {
      const type = (el.type || "").toLowerCase();
      const tag = el.tagName.toLowerCase();
  
      // Skip hidden/disabled
      if (el.disabled || type === "hidden") return { valid: true };
  
      // Custom visibility check (skips hidden via CSS, e.g., conditional logic)
      const style = window.getComputedStyle(el);
      if (style.display === "none" || style.visibility === "hidden") return { valid: true };
  
      // Radio/checkbox are handled as groups by name
      if (type === "radio" || type === "checkbox") {
        const form = el.form || getForm();
        const group = $all(`input[type="${type}"][name="${CSS.escape(el.name)}"]`, form);
        const container = findQuestionContainer(el) || el.closest("[data-question]");
        return validateGroup(group, container);
      }
  
      // Text-like inputs and selects
      if (tag === "select") {
        if (isEmpty(el.value)) {
          clearError(el);
          showError(el, tr('LBL_SURVEY_REQUIRED_FIELD', 'This field is required'));
          return { valid: false, focusEl: el };
        }
        clearError(el);
        return { valid: true };
      }
  
      if (tag === "textarea" || ["text", "email", "number", "tel", "url", "search", "password", "date", "time", "datetime-local"].includes(type)) {
        if (isEmpty(el.value)) {
          clearError(el);
          showError(el, tr('LBL_SURVEY_REQUIRED_FIELD', 'This field is required'));
          return { valid: false, focusEl: el };
        }
        clearError(el);
        return { valid: true };
      }
  
      // Default: consider valid
      return { valid: true };
    }

    function setSubmitting(form, on) {
        form.dataset.submitting = on ? "true" : "false";
        const submitters = $all('button[type="submit"], input[type="submit"]', form);
        submitters.forEach(el => {
          el.disabled = !!on;
          el.classList.toggle('is-loading', !!on); // opcional, per estil/loading
        });
    }
  
    function setupValidation() {
      const form = getForm();
      if (!form) return;
  
      // Optional: enable custom validation UI
      form.setAttribute("novalidate", "novalidate");
  
      form.addEventListener("submit", function (event) {
        // Evita duplicitats: si ja s'està enviant, talla aquí.
        if (form.dataset.submitting === "true") {
            event.preventDefault();
            return;
        }

        // Bloqueja immediatament per evitar dobles clics mentre validem
        setSubmitting(form, true);
        
        let firstInvalid = null;
        // Strategy: scan required controls
        const requiredControls = $all("[required], [data-required='true']", form)
          // Filter to avoid double-validating radio/checkbox: only the first of each name
          .filter((el, idx, arr) => {
            const t = (el.type || "").toLowerCase();
            if (t === "radio" || t === "checkbox") {
              return arr.findIndex(x => (x.type || "").toLowerCase() === t && x.name === el.name) === idx;
            }
            return true;
          });
  
        let allValid = true;
        for (const el of requiredControls) {
          const res = validateField(el);
          if (!res.valid) {
            allValid = false;
            if (!firstInvalid) firstInvalid = res.focusEl || el;
          }
        }
  
        if (!allValid) {
          event.preventDefault();
          setSubmitting(form, false);

          // Scroll/focus the first invalid element for better UX
          if (firstInvalid && typeof firstInvalid.focus === "function") {
            firstInvalid.scrollIntoView({ behavior: "smooth", block: "center" });
            firstInvalid.focus({ preventScroll: true });
          }
        }
      });
  
      // Live validation on change/input
      form.addEventListener("input", function (e) {
        const target = e.target;
        if (!target) return;
        if (target.hasAttribute("required") || target.getAttribute("data-required") === "true") {
          validateField(target);
        }
      });
      form.addEventListener("change", function (e) {
        const target = e.target;
        if (!target) return;
        if (target.type === "radio" || target.type === "checkbox") {
          // Re-validate group
          validateField(target);
        }
      });
    }
  
    // Basic styles for errors (inject lightweight styles if not present)
    function injectStyles() {
      const css = ".has-error .error-msg{font-size:.9rem;margin-top:.25rem}"
        + ".has-error .error-msg{color:#b00020;}"
        + ".has-error [aria-invalid='true']{outline:1px solid #b00020; box-shadow:0 0 0 2px rgba(176,0,32,.1);}";
      const style = document.createElement("style");
      style.setAttribute("data-survey-validation", "true");
      style.textContent = css;
      document.head.appendChild(style);
    }
  
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", function () {
        injectStyles();
        setupValidation();
      });
    } else {
      injectStyles();
      setupValidation();
    }
  })();
  