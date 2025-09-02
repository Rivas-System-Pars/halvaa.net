// ===============================
// FULL SCRIPT (Map & QR & Comments & Weather)
// ===============================

// === API base ===
const API_CONFIGS = {
    baseUrl: "https://halvaa.net/",
    endpoints: {
        features: `user/info/${loadId}`,
        comments: `memorials/user/${loadId}`,
        addComment: `memorials/user/${loadId}`,
    },
    headers: {
        "Content-Type": "application/json",
    },
    timeout: 10000,
};

// 🔑 Neshan Key (برای نقشه)
const NESHAN_API_KEY = "web.e26efd0612d64f34a6fc1123c6ba5507";

// --- Map (Leaflet + Neshan) fallback config
const MAP_CONFIG = {
    tileUrl: "https://tile.cdn.neshan.org/v2/tiles/standard-day/{z}/{x}/{y}.png",
    attribution:
        '© <a href="https://neshan.org" target="_blank" rel="noopener">Neshan</a> | © <a href="https://leafletjs.com" target="_blank" rel="noopener">Leaflet</a>',
    defaultZoom: 17,
    fallbackCenter: [35.6892, 51.3890], // Tehran
};

// === Weather Widget Config ===
const WEATHER_CONFIG = {
    enabled: true,                       // اگر نمی‌خوای نمایش داده بشه: false
    token: "547788:68a038c823f21",       // 👈 توکن one-api.ir خودت
    defaultUnit: "metric",               // "metric" یا "imperial"
};

// --- helpers
function toNum(val) {
    if (val === null || val === undefined) return null;
    if (typeof val === "number") return Number.isFinite(val) ? val : null;
    let s = String(val).trim();
    if (s === "") return null;
    // پشتیبانی از اعشار با ویرگول
    s = s.replace(/,/g, ".");
    const cleaned = s.replace(/[^\d.\-]/g, ""); // فقط عدد/نقطه/منفی
    const n = parseFloat(cleaned);
    return Number.isFinite(n) ? n : null;
}
// --- QR helpers (support both instagramProfile & instargamProfile)
function getWindowQR() {
    // A) typo version: window.instargamProfile.qrCode
    const igTypo = window.instargamProfile?.qrCode;
    // B) original version: window.instagramProfile.getQRCode()
    const igOk = typeof window.instagramProfile?.getQRCode === "function"
        ? window.instagramProfile.getQRCode()
        : window.instagramProfile?.qrCode;

    // prefer typo source if exists, else normal one
    const raw = igTypo ?? igOk ?? null;

    if (!raw) return { qrBase64: null, qrText: null };

    // if it's already data URL
    if (typeof raw === "string" && raw.startsWith("data:image/")) {
        return { qrBase64: raw, qrText: null };
    }

    // if it's base64 without prefix
    const looksBase64 = typeof raw === "string" && /^[A-Za-z0-9+/=]+$/.test(raw) && raw.length > 100;
    if (looksBase64) {
        return { qrBase64: `data:image/png;base64,${raw}`, qrText: null };
    }

    // treat it as plain text (to be rendered by QRCode lib)
    return { qrBase64: null, qrText: String(raw) };
}


function pickLatLng(feature) {
    // 1) خود feature
    let lat = toNum(feature?.lat);
    let lng = toNum(feature?.lng);

    // 2) از کلاس پروفایل
    if ((lat == null || lng == null) && window.instagramProfile) {
        const pLat = toNum(window.instagramProfile.getLatitude?.());
        const pLng = toNum(window.instagramProfile.getLongitude?.());
        if (lat == null) lat = pLat;
        if (lng == null) lng = pLng;
    }
    return { lat, lng };
}

let commentsData = [];
let featuresData = [];
let currentModal = null;
let featureSwiper = null;

function init() {
    fetchRemoteData();
    fetchCommentsData();
    initializeModal();
    setupEventListeners();
}

function initializeModal() {
    currentModal = new bootstrap.Modal($("#featureModal")[0], {
        backdrop: true,
        keyboard: true,
    });

    $("#featureModal").on("show.bs.modal", function () {
        $(this).addClass("d-flex");
    });

    $("#featureModal .btn-close").on("click", function () {
        $("#featureModal").removeClass("d-flex");
    });

    $("#featureModal").on("hidden.bs.modal", function () {
        $(this).removeClass("d-flex");
        const $map = $("#leafletMap");
        if ($map.length) $map.empty();
    });
}

$(document).ready(function () {
    initializeModal();
});

function fetchRemoteData() {
    $("#featureGrid").addClass("loading");
    $.ajax({
        url: API_CONFIGS.baseUrl + API_CONFIGS.endpoints.features,
        method: "GET",
        dataType: "json",
        timeout: API_CONFIGS.timeout,
        success: function (response) {
            featuresData = mapJsonToFeatures(response);
            renderFeatureSwiper();
        },
        error: function () {
            showError("ارتباط با سرور برقرار نشد.");
        },
        complete: function () {
            $("#featureGrid").removeClass("loading");
        },
    });
}

function fetchCommentsData() {
    $.ajax({
        url: API_CONFIGS.baseUrl + API_CONFIGS.endpoints.comments,
        method: "GET",
        dataType: "json",
        timeout: API_CONFIGS.timeout,
        success: function (response) {
            if (response.success && response.memorials) {
                commentsData = response.memorials;
            }
        },
        error: function (err) {
            console.error("خطا در دریافت نظرات:", err);
        },
    });
}

function mapJsonToFeatures(data) {
    const mapped = [];
    const safeText = "داده‌ای ثبت نشده است";

    const wrapAsCards = (items) => {
        if (!items || items.length === 0) return safeText;
        return items.map((item) => {
            const imageUrl = resolveImageUrl(item.image);
            return `
        <div class="mb-2 p-2">
          ${imageUrl ? `<img src="${imageUrl}" class="img-fluid rounded mb-2" />` : ""}
          ${item.title ? `<h5>${item.title}</h5>` : ""}
          ${item.content ? `<p>${item.content}</p>` : ""}
        </div>
      `;
        }).join("");
    };

    const makeList = (items) => {
        if (!items || items.length === 0) return safeText;
        return `<ul style="list-style:none;">${items
            .map((i) => `<li>روز ${i.day} تاریخ ${i.date} ${i.subject} ${i.value}</li>`)
            .join("")}</ul>`;
    };

    mapped.push({ label: "اطلاعیه ها", description: wrapAsCards(data.UserAnnouncement) });
    mapped.push({ label: "دفتر یاد بود", description: "نظرات و پیام‌های بازدیدکنندگان", isComments: true });
    // Location
    const latFromProfile = window.instagramProfile?.getLatitude?.() ?? null;
    const lngFromProfile = window.instagramProfile?.getLongitude?.() ?? null;
    const lat = toNum(latFromProfile) ?? toNum(data?.location?.latitude) ?? toNum(data?.user?.latitude);
    const lng = toNum(lngFromProfile) ?? toNum(data?.location?.longitude) ?? toNum(data?.user?.longitude);
    mapped.push({ label: "موقعیت مکانی", isLocation: true, lat: toNum(lat), lng: toNum(lng) });

    // Weather (uses profile/data location if available)
    if (WEATHER_CONFIG.enabled) {
        const coords = pickLatLng({ lat, lng });
        mapped.push({
            label: "آب‌وهوا",
            isWeather: true,
            token: WEATHER_CONFIG.token,
            unit: WEATHER_CONFIG.defaultUnit,
            lat: toNum(coords.lat) ?? MAP_CONFIG.fallbackCenter[0],
            lng: toNum(coords.lng) ?? MAP_CONFIG.fallbackCenter[1],
        });
    }
    const winQR = getWindowQR();
    mapped.push({
        label: "Qr کد",
        isQR: true,
        // اولویت با window.instargamProfile/instagramProfile
        qrBase64: (winQR.qrBase64 ?? null)
            || (typeof data.qrCodeBase64 === "string"
                ? (data.qrCodeBase64.startsWith("data:")
                    ? data.qrCodeBase64
                    : `data:image/png;base64,${data.qrCodeBase64}`)
                : null),
        qrText: winQR.qrText || null,
    });
    mapped.push({ label: "زندگینامه", description: data.UserBioLife?.[0]?.life_biography?.trim() || safeText });
    mapped.push({ label: "گاهشمارزندگی", description: makeList(data.UserLifeCalender) });
    mapped.push({ label: "وصیتنامه", description: wrapAsCards(data.UserWill) });
    mapped.push({
    label: "شجره نامه",
    description: (data.family_tree && data.family_tree.length > 0)
        ? wrapAsCards(data.family_tree)
        : "به زودی ..."
});
    mapped.push({ label: "آثار و لوازم", description: wrapAsCards(data.relics) });
    mapped.push({ label: "اسناد هویت", description: wrapAsCards(data.UserNotice) });
    mapped.push({ label: "پیام مدیرارامگاه", description: wrapAsCards((data.UserMessage || []).reverse()) });




    window.featuresData = mapped;
    return mapped;
}

/* ---------------------------
   Swiper renderer
----------------------------*/
function renderFeatureSwiper() {
    const iconMap = {
        "زندگینامه": "ri-user-3-line",
        "گاهشمارزندگی": "ri-timeline-view",
        "وصیتنامه": "ri-file-text-line",
        "دفتر یاد بود": "ri-quill-pen-line",
        "اطلاعیه ها": "ri-notification-3-line",
        "اسناد هویت": "ri-passport-line",
        "پیام مدیرارامگاه": "ri-chat-4-line",
        "Qr کد": "ri-qr-code-line",
        "شجره نامه": "ri-tree-line",
        "آثار و لوازم": "ri-article-line",
        "موقعیت مکانی": "ri-map-pin-line",
        "آب‌وهوا": "ri-sun-cloudy-line", // NEW
    };

    const $grid = $("#featureGrid");
    const swiperHtml = `
    <div class="swiper w-100">
      <div class="swiper-wrapper">
        ${featuresData.map((f, i) => `
          <div class="swiper-slide d-flex alig-items-center justify-content-center">
            <div class="FeatureItem d-flex alig-items-center justify-content-center">
              <div class="FeatureCircle" data-index="${i}" data-bs-toggle="modal" data-bs-target="#featureModal">
                <i class="${iconMap[f.label] || "ri-information-line"}"></i>
                <div class="FeatureName">${f.label}</div>
              </div>
            </div>
          </div>
        `).join("")}
      </div>

      <div class="features-swiper-button-prev">
        <div class="flex">
          <svg style="width: 24px; height: 24px; fill: #424750;">
            <use xlink:href="#chevronRight">
              <symbol id="chevronRight" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12.583 12L8.29 16.293l1.414 1.414 5-5a1 1 0 000-1.414l-5-5L8.29 7.707 12.583 12z"></path></symbol>
            </use>
          </svg>
        </div>
      </div>

      <div class="features-swiper-button-next">
        <div class="flex">
          <svg style="width: 24px; height: 24px; fill: #424750;">
            <use xlink:href="#chevronLeft">
              <symbol id="chevronLeft" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M11.414 12l4.293 4.293-1.414 1.414-5-5a1 1 0 010-1.414l5-5 1.414 1.414L11.414 12z"></path></symbol>
            </use>
          </svg>
        </div>
      </div>
    </div>
  `;
    $grid.empty().append(swiperHtml);

    if (featureSwiper?.destroy) featureSwiper.destroy(true, true);

    featureSwiper = new Swiper("#featureGrid .swiper", {
        slidesPerView: 3,
        spaceBetween: 16,
        loop: false,
        grabCursor: true,
        navigation: {
            nextEl: "#featureGrid .features-swiper-button-next",
            prevEl: "#featureGrid .features-swiper-button-prev",
        },
        breakpoints: {
            0: { slidesPerView: 2.17, spaceBetween: 12 },
            360: { slidesPerView: 2.45, spaceBetween: 12 },
            480: { slidesPerView: 3.3, spaceBetween: 12 },
            576: { slidesPerView: 3.68, spaceBetween: 14 },
            768: { slidesPerView: 4.88, spaceBetween: 16 },
            992: { slidesPerView: 6.45, spaceBetween: 18 },
            1200: { slidesPerView: 7.57, spaceBetween: 20 },
            1400: { slidesPerView: 8.67, spaceBetween: 22 },
        },
        on: {
            init() { updateNavButtons(this); },
            slideChange() { updateNavButtons(this); },
        },
    });

    function updateNavButtons(swiper) {
        const $prev = $("#featureGrid .features-swiper-button-prev");
        const $next = $("#featureGrid .features-swiper-button-next");
        swiper.isBeginning ? $prev.hide() : $prev.show();
        swiper.isEnd ? $next.hide() : $next.show();
    }
}

function setupEventListeners() {
    $(document).on("click", ".FeatureCircle", function () {
        const idx = $(this).data("index");
        window.fillModal && window.fillModal(featuresData[idx]);
    });
}

function showError(msg) {
    $("#featureGrid").removeClass("loading").html(`
    <div class="FeatureError">
      <h4>خطا در بارگذاری</h4>
      <p>${msg}</p>
      <button class="btn btn-outline-danger" onclick="location.reload()">تلاش مجدد</button>
    </div>
  `);
}

function resolveImageUrl(path) {
    if (!path) return "";
    let trimmed = path.startsWith("/") ? path.slice(1) : path;
    if (trimmed.startsWith("user/profile/")) {
        trimmed = trimmed.replace(/^user\/profile\//, "");
    }
    return API_CONFIGS.baseUrl + trimmed;
}

/* =========================
   Modal Fill
========================= */
function fillModal(feature) {
    $("#featureModalLabel").text(feature.label);
    $("#modalImage").hide();
    let html = "";

    // QR
    if (feature.isQR) {
        html += `
      <div class="d-flex flex-column align-items-center justify-content-center p-3 w-100 h-100">
        <div id="qrContainer" class="shadow" style="width:240px;height:240px;display:flex;align-items:center;justify-content:center;background:#fff;border:2px dashed #333;border-radius:12px;overflow:hidden;"></div>
      
      </div>
    `;
        $("#modalDescription").html(html);

        if (feature.qrBase64) {
            const src = feature.qrBase64.startsWith("data:")
                ? feature.qrBase64
                : `data:image/png;base64,${feature.qrBase64}`;
            $("#qrContainer").html(`<img src="${src}" alt="QR" style="width:100%;height:100%;">`);
        } else if (feature.qrText) {
            if (window.QRCode) {
                $("#qrContainer").empty();
                new QRCode(document.getElementById("qrContainer"), {
                    text: String(feature.qrText),
                    width: 260,
                    height: 260,
                    correctLevel: QRCode.CorrectLevel.M,
                });
            } else {
                console.warn("QRCode library not found. Showing raw text instead.");
                $("#qrContainer").html(
                    `<div class="text-center p-2 small text-muted">کتابخانه تولید QR لود نشده است.</div>`
                );
            }
        } else {
            $("#qrContainer").html(`<div class="text-center p-2 small text-muted">داده‌ای برای QR موجود نیست.</div>`);
        }
        return;
    }

    // Location (Neshan SDK)
    if (feature.isLocation) {
        html += `
      <div class="mb-2 small text-muted">
        <i class="ri-map-pin-line me-1"></i> مکان روی نقشه
      </div>
      <div id="leafletMap" class="w-100 h-100 rounded-4 overflow-hidden border border-2 border-white"></div>
    `;
        $("#modalDescription").html(html);

        const { lat, lng } = pickLatLng(feature);
        const hasCoords = Number.isFinite(lat) && Number.isFinite(lng);
        const center = hasCoords ? [lat, lng] : MAP_CONFIG.fallbackCenter;

        if (!window.L) {
            $("#leafletMap").html(
                `<div class="alert alert-danger m-2">Leaflet/SDK لود نشده است. فایل‌های CSS/JS نشان را به صفحه اضافه کنید.</div>`
            );
            return;
        }

        if (!NESHAN_API_KEY || /YOUR_NESHAN_API_KEY_HERE/.test(NESHAN_API_KEY)) {
            $("#leafletMap").html(
                `<div class="alert alert-danger m-2">API Key نشان تنظیم نشده است. مقدار <code>NESHAN_API_KEY</code> را در بالای فایل قرار دهید.</div>`
            );
            return;
        }

        setTimeout(() => {
            try {
                const map = new L.Map("leafletMap", {
                    key: NESHAN_API_KEY,
                    maptype: "standard-day",
                    poi: true,
                    traffic: false,
                    center,
                    zoom: MAP_CONFIG.defaultZoom,
                });
                if (hasCoords) L.marker(center).addTo(map);
                setTimeout(() => map.invalidateSize(), 0);
            } catch (e) {
                console.error("Neshan map init error:", e);
                const map = L.map("leafletMap", { zoomControl: true, scrollWheelZoom: true }).setView(center, MAP_CONFIG.defaultZoom);
                L.tileLayer(MAP_CONFIG.tileUrl, { attribution: MAP_CONFIG.attribution, maxZoom: 19 }).addTo(map);
                if (hasCoords) L.marker(center).addTo(map);
                setTimeout(() => map.invalidateSize(), 0);
            }
        }, 0);

        console.debug("Map center decided:", { lat, lng, usedCenter: center });
        return;
    }

    // Weather Widget (NEW)
    if (feature.isWeather) {
        const uid = `weather_${Date.now()}`;
        const htmlWeather = `<div id="${uid}" class="weather-host"></div>`;
        $("#modalDescription").html(htmlWeather);

        renderWeatherWidget(`#${uid}`, {
            token: feature.token,
            lat: feature.lat,
            lon: feature.lng,
            unit: feature.unit || "metric",
        });

        return;
    }

    // موارد عادی
    if (feature.image) {
        const imgSrc = resolveImageUrl(feature.image);
        $("#modalImage").attr("src", imgSrc).show();
    }
    let bodyHTML = "";
    if (feature.title) bodyHTML += `<h5 class="modal-inner-title">${feature.title}</h5>`;
    if (feature.description) bodyHTML += `<div class="modal-inner-description">${feature.description}</div>`;
    if (feature.isComments) {
        bodyHTML += `
      <div class="FeatureComments mt-4">
        <div id="commentMessages"></div>
        <div id="commentsList" class="mb-3 comments-list"></div>
        <form id="commentForm" class="comment-form-main d-flex align-items-center justify-content-center flex-column gap-0 mb-4">
          <div class="comment-form-main-inner w-100">
            <textarea class="form-control" id="commentInput" rows="3" placeholder="نظر خود را بنویسید..." required></textarea>
          </div>
          <button type="submit" class="btn btn-primary comment-form-main-button w-100">
            ارسال <i class="ri-send-plane-line me-2"></i>
          </button>
        </form>
      </div>
    `;
    }
    $("#modalDescription").html(bodyHTML);

    if (feature.isComments) {
        renderComments();
        setupCommentForm();
    }
}

// نمایش لیست کامنت‌ها
function renderComments() {
    const $commentsList = $("#commentsList");
    if (!commentsData || commentsData.length === 0) {
        $commentsList.html(`
      <div class="text-center py-4">
        <i class="ri-chat-3-line text-muted" style="font-size: 3rem;"></i>
        <p class="text-muted mt-2">هنوز نظری ثبت نشده است.</p>
        <p class="text-muted small">اولین نفری باشید که نظر می‌دهد!</p>
      </div>
    `);
        return;
    }

    const commentsHtml = commentsData.map((comment) => {
        const w = comment.writer || {};
        const profileImage =
            w.profile_image || w.profile_photo_url || "/placeholder.svg?height=50&width=50";
        const displayName =
            w.username || [w.first_name, w.last_name].filter(Boolean).join(" ") || "کاربر ناشناس";

        return `
      <div class="comment-item border rounded p-3 mb-3 bg-light">
        <div class="d-flex align-items-start">
          <img
            src="${profileImage}"
            alt="pic"
            class="rounded-circle me-3"
            style="width:50px;height:50px;object-fit:cover;border:2px solid #dee2e6;"
          />
          <div class="flex-grow-1 arvin">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <h6 class="mb-0 text-primary fw-bold">${displayName}</h6>
            </div>
            <p class="mb-0 text-dark p-div-comment" style="line-height:1.6;">
              ${comment.text}
            </p>
          </div>
        </div>
      </div>
    `;
    }).join("");

    $commentsList.html(commentsHtml);
}

// بایند فرم ارسال کامنت
function setupCommentForm() {
    $("#commentForm").off("submit").on("submit", function (e) {
        e.preventDefault();
        submitComment();
    });
}

function submitComment() {
    const text = $("#commentInput").val().trim();
    if (!text) {
        showMessage("لطفاً نظر خود را وارد کنید.", "warning");
        return;
    }

    const $btn = $("#commentForm button[type='submit']");
    const original = $btn.html();
    $btn.prop("disabled", true).html('<i class="ri-loader-4-line me-2"></i>در حال ارسال...');

    const targetId = parseInt(loadId, 10);
    const payload = { text, target_user_id: targetId, writer_user_id: 1 };

    $.ajax({
        url: API_CONFIGS.baseUrl + API_CONFIGS.endpoints.addComment,
        method: "POST",
        headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
        contentType: "application/json",
        data: JSON.stringify(payload),
        success: function (res) {
            if (res.success) {
                const mem = res.memorial;
                const newComment = {
                    text: mem.text,
                    writer: { username: res.username, profile_image: res.profile_image },
                    id: mem.id,
                    target_user_id: mem.target_user_id,
                    writer_user_id: mem.writer_user_id,
                    created_at: mem.created_at,
                };
                commentsData.unshift(newComment);
                renderComments();
                $("#commentInput").val("");
                showMessage(res.message || "نظر شما با موفقیت ثبت شد.", "success");
            } else {
                showMessage(res.message || "خطا در ارسال نظر. لطفاً دوباره تلاش کنید.", "danger");
            }
        },
        error: function () {
            showMessage("خطا در ارسال نظر. لطفاً دوباره تلاش کنید.", "danger");
        },
        complete: function () {
            $btn.prop("disabled", false).html(original);
        },
    });
}

// نمایش پیغام‌های موفقیت/خطا
function showMessage(message, type) {
    const $cont = $("#commentMessages");
    if (!$cont.length) return;
    $cont.empty();
    const icon = type === "success" ? "check" : type === "warning" ? "alert" : "error-warning";
    const $alert = $(`
    <div class="alert alert-${type} alert-dismissible fade show mt-3">
      <i class="ri-${icon}-line me-2"></i>${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  `);
    $cont.append($alert);
    setTimeout(() => { $alert.alert("close"); }, 4000);
}
/* =========================
   Weather Widget Renderer (JS only)
========================= */
function renderWeatherWidget(rootSelector, opts) {
    const root = document.querySelector(rootSelector);
    if (!root) return;

    // ensure the root has the CSS scope class (for safety)
    root.classList.add('weather-widget-root');

    const TOKEN = opts?.token || "";
    let UNIT = (opts?.unit === "imperial" ? "imperial" : "metric");
    const LAT = Number(window.instagramProfile?.getLatitude?.());
    const LON = Number(window.instagramProfile?.getLongitude?.());

    // ---------- markup ----------
    root.innerHTML = `
    <div class="widget-main mb-3">
      <div class="d-flex  justify-content-center flex-column gap-3 header-widget">
        <div class="brand"><span class="dot"></span> ویجت آب‌وهوا</div>
        <div class="d-flex  justify-content-center flex-column flex-lg-row controls">
          <select id="w_unit" class="col-lg-6">
            <option value="metric">سلسیوس</option>
            <option value="imperial">فارنهایت</option>
          </select>
          <button id="w_refresh" class="col-lg-6" title="بروزرسانی">بروزرسانی</button>
        </div>
      </div>

      <div class="card">
        <div id="w_state" class="status">در حال آماده‌سازی…</div>

        <div class="main" id="w_main" style="display:none">
          <div>
            <div id="w_location" style="font-weight:700;font-size:18px"></div>
            <div class="temp" id="w_temp">--°</div>
            <div class="desc" id="w_desc">—</div>
          </div>
          <img id="w_icon" class="icon" alt="icon" src="" />
        </div>

        <div class="meta" id="w_meta" style="display:none">
          <div class="kv"><div class="k">دما احساس‌شده</div><div class="v" id="w_feels">—</div></div>
          <div class="kv"><div class="k">رطوبت</div><div class="v" id="w_humidity">—</div></div>
          <div class="kv"><div class="k">سرعت باد</div><div class="v" id="w_wind">—</div></div>
          <div class="kv"><div class="k">فشار</div><div class="v" id="w_pressure">—</div></div>
        </div>
      </div>


    </div>
  `;

    // ---------- refs ----------
    const els = {
        unit: root.querySelector('#w_unit'),
        refreshBtn: root.querySelector('#w_refresh'),
        state: root.querySelector('#w_state'),
        main: root.querySelector('#w_main'),
        meta: root.querySelector('#w_meta'),
        location: root.querySelector('#w_location'),
        temp: root.querySelector('#w_temp'),
        desc: root.querySelector('#w_desc'),
        icon: root.querySelector('#w_icon'),
        feels: root.querySelector('#w_feels'),
        humidity: root.querySelector('#w_humidity'),
        wind: root.querySelector('#w_wind'),
        pressure: root.querySelector('#w_pressure'),
        rawLink: root.querySelector('#w_raw'),
    };
    els.unit.value = UNIT;

    // ---------- helpers ----------
    function setStatus(msg, type = '') {
        els.state.textContent = msg;
        els.state.className = `status ${type}`.trim();
    }

    function withTimeout(promise, ms = 12000) {
        const ctrl = new AbortController();
        const t = setTimeout(() => ctrl.abort(), ms);
        return Promise.race([promise(ctrl.signal).finally(() => clearTimeout(t))]);
    }

    function buildUrl() {
        const base = 'https://one-api.ir/weather/';
        const q = new URLSearchParams({
            token: TOKEN,
            action: 'currentbylocation',
            lat: LAT,
            lon: LON
        });
        return `${base}?${q.toString()}`;
    }

    function pick(n, fallback = '—') { return (n === 0 || n) ? n : fallback; }
    function toIconUrl(code) { return code ? `https://openweathermap.org/img/wn/${code}@2x.png` : ''; }

    function parseResponse(json) {
        const root = json?.result || json;
        const name = root.name || root.city || root.timezone || '—';
        const sys = root.sys || {};
        const weather = Array.isArray(root.weather) ? root.weather[0] : (root.weather || {});
        const main = root.main || {};
        const wind = root.wind || {};
        return {
            location: [name, sys.country].filter(Boolean).join('، '),
            temp: main.temp,
            feels_like: main.feels_like,
            humidity: main.humidity,
            pressure: main.pressure,
            wind_speed: wind.speed,
            description: weather.description || weather.main || '—',
            icon: weather.icon || null,
        };
    }

    async function fetchWeather() {
        if (!TOKEN) {
            setStatus('⚠️ توکن را تنظیم کنید.', 'warn');
            return;
        }
        const url = buildUrl();
        //    els.rawLink.href = url;

        try {
            els.state.innerHTML = '';
            const sp = document.createElement('span');
            sp.className = 'spinner';
            els.state.appendChild(sp);
            setStatus(' در حال دریافت داده...');

            const res = await withTimeout((signal) => fetch(url, { signal }));
            if (!res.ok) throw new Error('HTTP ' + res.status);
            const json = await res.json();

            if (json.status && json.status !== 200) {
                const msg = json.message || 'پاسخ نامعتبر از سرور';
                throw new Error(msg);
            }

            const data = parseResponse(json);

            let { temp, feels_like: feels, wind_speed: wind } = data;
            const maybeKelvin = (x) => (typeof x === 'number' && x > 200 && x < 400);
            const K2C = (k) => k - 273.15;
            const C2F = (c) => (c * 9 / 5) + 32;
            const MS2MPH = (m) => m * 2.23694;

            if (maybeKelvin(temp)) {
                temp = K2C(temp);
                feels = maybeKelvin(feels) ? K2C(feels) : feels;
            }
            if (els.unit.value === 'imperial') {
                temp = C2F(temp);
                feels = C2F(feels);
                wind = typeof wind === 'number' ? MS2MPH(wind) : wind;
            }

            els.location.textContent = data.location || '—';
            els.temp.textContent =
                (typeof temp === 'number' ? Math.round(temp) : '--') +
                (els.unit.value === 'imperial' ? '°F' : '°C');
            els.desc.textContent = data.description;
            els.icon.src = toIconUrl(data.icon) || '';
            els.icon.style.display = data.icon ? 'block' : 'none';
            els.feels.textContent =
                typeof feels === 'number'
                    ? Math.round(feels) + (els.unit.value === 'imperial' ? '°F' : '°C')
                    : '—';
            els.humidity.textContent = pick(data.humidity, '—') + (typeof data.humidity === 'number' ? '٪' : '');
            els.wind.textContent = typeof wind === 'number'
                ? wind.toFixed(1) + (els.unit.value === 'imperial' ? ' mph' : ' m/s')
                : '—';
            els.pressure.textContent = pick(data.pressure, '—') + (typeof data.pressure === 'number' ? ' hPa' : '');

            els.main.style.display = 'flex';
            els.meta.style.display = 'grid';
            setStatus('بروزرسانی شد.', 'ok');
        } catch (err) {
            console.error(err);
            els.main.style.display = 'none';
            els.meta.style.display = 'none';
            setStatus('خطا در دریافت داده: ' + err.message, 'err');
        }
    }

    // events
    els.refreshBtn.addEventListener('click', fetchWeather);
    els.unit.addEventListener('change', fetchWeather);

    // نخستین بار
    fetchWeather();
}


// آغاز فرآیند
init();
