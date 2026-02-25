// read more button
(function () {
    var readMoreBtn = document.getElementById("readMoreBtn");
    var overviewBlock = document.getElementById("overviewBlock");
    if (readMoreBtn && overviewBlock) {
        readMoreBtn.addEventListener("click", function () {
            var expanded = overviewBlock.classList.toggle("is-expanded");
            readMoreBtn.setAttribute("aria-expanded", expanded);
        });
    }
})();

// sticky bar on scroll
(function () {
    var stickyBar = document.getElementById("eventStickyBar");
    if (!stickyBar) return;
    var threshold = 280;
    function updateSticky() {
        var show = window.pageYOffset > threshold;
        stickyBar.classList.toggle("is-visible", show);
        stickyBar.setAttribute("aria-hidden", !show);
    }
    window.addEventListener(
        "scroll",
        function () {
            requestAnimationFrame(updateSticky);
        },
        { passive: true }
    );
    updateSticky();
})();

// share link copy
(function () {
    var shareCopyBtn = document.getElementById("shareCopyBtn");
    var shareLinkInput = document.getElementById("shareLinkInput");
    if (shareCopyBtn && shareLinkInput) {
        shareCopyBtn.addEventListener("click", function () {
            shareLinkInput.select();
            shareLinkInput.setSelectionRange(0, 99999);
            try {
                document.execCommand("copy");
                shareCopyBtn.innerHTML =
                    '<i class="fas fa-check" aria-hidden="true"></i> Copied';
                setTimeout(function () {
                    shareCopyBtn.innerHTML =
                        '<i class="fas fa-copy" aria-hidden="true"></i> Copy';
                }, 2000);
            } catch (e) {
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard
                        .writeText(shareLinkInput.value)
                        .then(function () {
                            shareCopyBtn.innerHTML =
                                '<i class="fas fa-check" aria-hidden="true"></i> Copied';
                            setTimeout(function () {
                                shareCopyBtn.innerHTML =
                                    '<i class="fas fa-copy" aria-hidden="true"></i> Copy';
                            }, 2000);
                        });
                }
            }
        });
    }
})();

// choose date & location - toggle active card
(function () {
    var dateCards = document.querySelectorAll(
        ".nebule-event-details__date-card"
    );
    if (!dateCards.length) return;

    dateCards.forEach(function (card) {
        // initial aria state
        card.setAttribute(
            "aria-pressed",
            card.classList.contains("nebule-event-details__date-card--active")
                ? "true"
                : "false"
        );

        card.addEventListener("click", function () {
            dateCards.forEach(function (c) {
                c.classList.remove("nebule-event-details__date-card--active");
                c.setAttribute("aria-pressed", "false");
            });
            card.classList.add("nebule-event-details__date-card--active");
            card.setAttribute("aria-pressed", "true");
        });
    });
})();

// I am interested - AJAX toggle, update UI without reload
(function () {
    var section = document.getElementById("interestedSection");
    var btn = document.getElementById("interestedBtn");
    var doneBlock = document.getElementById("interestedDone");
    var countEl = document.getElementById("interestedCountNum");
    if (!btn) return;

    var btnText = btn.querySelector(
        ".nebule-event-details__interested-btn-text"
    );
    var eventId = btn.getAttribute("data-event-id");
    var url = btn.getAttribute("data-url");
    var meta = document.querySelector('meta[name="csrf-token"]');
    var csrfToken = meta ? meta.getAttribute("content") : null;

    function setInterestedState(interested) {
        if (interested) {
            btn.classList.add("is-interested");
            btn.setAttribute("aria-pressed", "true");
            if (btnText) btnText.textContent = "Interested";
            if (section) section.classList.add("is-done");
            if (doneBlock) doneBlock.setAttribute("aria-hidden", "false");
        } else {
            btn.classList.remove("is-interested");
            btn.setAttribute("aria-pressed", "false");
            if (btnText) btnText.textContent = "I am interested";
            if (section) section.classList.remove("is-done");
            if (doneBlock) doneBlock.setAttribute("aria-hidden", "true");
        }
    }

    function updateCount(count) {
        if (countEl && count !== undefined) countEl.textContent = count;
    }

    btn.addEventListener("click", function () {
        if (!eventId || !url) return;
        if (!csrfToken) {
            if (typeof window.alert !== "undefined") window.alert("Security token missing. Please refresh the page.");
            return;
        }

        btn.disabled = true;

        var formData = new FormData();
        formData.append("event_id", eventId);
        formData.append("_token", csrfToken);

        var headers = {
            "X-Requested-With": "XMLHttpRequest",
            "Accept": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        };

        fetch(url, {
            method: "POST",
            body: formData,
            headers: headers,
            credentials: "same-origin",
        })
            .then(function (res) {
                var contentType = res.headers.get("content-type");
                if (contentType && contentType.indexOf("application/json") !== -1) {
                    return res.json().then(function (data) {
                        return { ok: res.ok, status: res.status, data: data };
                    });
                }
                return { ok: false, status: res.status, data: { message: "Invalid response" } };
            })
            .then(function (result) {
                btn.disabled = false;
                if (result.status === 401 && result.data && result.data.login_required) {
                    window.location.href = (window.location.origin + "/login?redirect=" + encodeURIComponent(window.location.href));
                    return;
                }
                if (result.ok && result.data && result.data.status === "success") {
                    setInterestedState(result.data.interested);
                    updateCount(result.data.count);
                } else {
                    var msg = (result.data && result.data.message) ? result.data.message : "Something went wrong.";
                    if (typeof window.alert !== "undefined") window.alert(msg);
                }
            })
            .catch(function () {
                btn.disabled = false;
                if (typeof window.alert !== "undefined") window.alert("Request failed. Please try again.");
            });
    });
})();
