(function() {
  var items = document.querySelectorAll(".sda-module");
  for (var i = 0; i < items.length; i++) {
    var el = items[i];
    var moduleValue = el.getAttribute("module");
    var targetDiv = document.getElementById(moduleValue);
    if (targetDiv) {
      var fatalEls = targetDiv.querySelectorAll(".sda-error, .sda-fatal");
      var aElement = el.querySelector("a");
      if (fatalEls.length > 0) {
        aElement.style.backgroundColor = "#c62828";
        aElement.style.color = "#fff";
        aElement.style.borderRadius = "4px";
        aElement.style.padding = "2px 6px";
      }
      el.addEventListener("click", function() {
        var targetId = this.getAttribute("module");
        var targetDiv = document.getElementById(targetId);
        targetDiv.style.display = targetDiv.style.display === "none" ? "block" : "none";
      });
    }
  }

  var list = document.querySelector(".sda-modules");
  if (list) {
    var sorted = Array.from(list.children).sort(function(a, b) { return a.textContent.localeCompare(b.textContent); });
    sorted.forEach(function(item) { list.appendChild(item); });
  }
})();

(function() {
  var modules = document.querySelectorAll(".sda-module");
  var totalModules = modules.length;
  var errorEls = document.querySelectorAll(".sda-error, .sda-fatal");
  var warnEls = document.querySelectorAll(".sda-warning");
  var timeEl = document.querySelector(".sda-time");
  var timeText = timeEl ? timeEl.textContent.trim() : "";
  var dateEl = document.getElementById("sda-run-date");
  var dateText = dateEl ? dateEl.textContent.trim() : "";
  var errorTexts = [];
  errorEls.forEach(function(el) {
    var txt = el.textContent.trim().replace(/^[✓✗▲]\s*/, "");
    if (errorTexts.indexOf(txt) === -1) errorTexts.push(txt);
  });
  var status = errorTexts.length > 0 ? "with errors" : "success";
  var statusColor = errorTexts.length > 0 ? "#c62828" : "#2e7d32";
  var statusBg = errorTexts.length > 0 ? "#ffebee" : "#e8f5e9";
  var html = '<div style="display:flex;flex-wrap:wrap;gap:10px;align-items:center;padding:12px 16px;background:#fff;border:1px solid #e0e0e0;border-radius:8px;margin-bottom:16px;font-size:13px;">';
  html += '<span style="font-weight:700;color:#1a237e;">Summary<\/span><span style="color:#757575;font-size:12px;">' + dateText + '<\/span>';
  html += '<span style="background:#e8eaf6;padding:2px 10px;border-radius:4px;">' + totalModules + ' modules<\/span>';
  if (errorTexts.length > 0) {
    html += '<span style="background:' + statusBg + ';color:' + statusColor + ';padding:2px 10px;border-radius:4px;font-weight:600;">' + errorTexts.length + ' errors<\/span>';
  }
  if (warnEls.length > 0) {
    html += '<span style="background:#fff3e0;color:#e65100;padding:2px 10px;border-radius:4px;">' + warnEls.length + ' warnings<\/span>';
  }
  html += '<span style="background:' + statusBg + ';color:' + statusColor + ';padding:2px 10px;border-radius:4px;font-weight:600;">' + status + '<\/span>';
  html += '<span style="margin-left:auto;color:#757575;">' + timeText + '<\/span>';
  if (errorTexts.length > 0) {
    html += '<div style="width:100%;margin-top:6px;padding-top:8px;border-top:1px solid #e0e0e0;">';
    errorTexts.forEach(function(txt) {
      html += '<div style="color:#c62828;font-size:12px;padding:2px 0;">' + txt + '<\/div>';
    });
    html += '<\/div>';
  }
  html += '<\/div>';
  var container = document.querySelector(".sda-modules") || document.querySelector("h2");
  if (container && container.parentNode) {
    var temp = document.createElement("div");
    temp.innerHTML = html;
    container.parentNode.insertBefore(temp.firstChild, container.parentNode.firstChild);
  }
})();

(function() {
  var wrapper = document.querySelector(".sda-debug-wrapper");
  if (!wrapper) return;
  var headings = wrapper.querySelectorAll("h2");
  for (var i = 0; i < headings.length; i++) {
    var h2 = headings[i];
    var content = [];
    var next = h2.nextElementSibling;
    while (next && next.tagName !== "H2") {
      content.push(next);
      next = next.nextElementSibling;
    }
    if (i > 0) {
      for (var j = 0; j < content.length; j++) {
        content[j].style.display = "none";
      }
    }
    h2.style.cursor = "pointer";
    h2.style.position = "relative";
    h2.style.paddingRight = "30px";
    var toggle = document.createElement("span");
    toggle.style.cssText = "position:absolute;right:10px;top:50%;transform:translateY(-50%);font-size:12px;transition:transform 0.2s;";
    toggle.textContent = i === 0 ? "\u25BC" : "\u25B6";
    h2.appendChild(toggle);
    (function(hd, tg) {
      hd.addEventListener("click", function() {
        var els = [];
        var nxt = hd.nextElementSibling;
        while (nxt && nxt.tagName !== "H2") {
          els.push(nxt);
          nxt = nxt.nextElementSibling;
        }
        var hidden = els.length > 0 && els[0].style.display === "none";
        for (var k = 0; k < els.length; k++) {
          els[k].style.display = hidden ? "" : "none";
        }
        tg.textContent = hidden ? "\u25BC" : "\u25B6";
      });
    })(h2, toggle);
  }
})();
