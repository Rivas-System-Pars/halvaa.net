// ===== Persian (Jalali) Date Picker with Separate Month/Year Clicks + Decade Year Picker =====

// Day names
const daysNames = ["ش", "ی", "د", "س", "چ", "پ", "ج"];
const daysFullNames = ["شنبه", "یک‌شنبه", "دوشنبه", "سه‌شنبه", "چهارشنبه", "پنج‌شنبه", "جمعه"];

// SVG arrows
const arrowSVG = (dir, color, rotate) => `
<svg class="calendar-arrow ${dir}Btn" fill="${color}" style="${rotate||''}" height="20px" width="20px" viewBox="0 0 330 330" xmlns="http://www.w3.org/2000/svg">
  <path class="${dir}Btn" d="M250.606,154.389l-150-149.996c-5.857-5.858-15.355-5.858-21.213,0.001c-5.857,5.858-5.857,15.355,0.001,21.213
  l139.393,139.39L79.393,304.394c-5.857,5.858-5.857,15.355,0.001,21.213C82.322,328.536,86.161,330,90,330
  s7.678-1.464,10.607-4.394l149.999-150.004c2.814-2.813,4.394-6.628,4.394-10.606C255,161.018,253.42,157.202,250.606,154.389z"/>
</svg>`;
const rightArrow = arrowSVG('prev', '#000000');
const rightArrowDark = arrowSVG('prev', '#ffffff');
const leftArrow  = arrowSVG('next', '#000000', 'rotate: 180deg');
const leftArrowDark  = arrowSVG('next', '#ffffff', 'rotate: 180deg;');

let myLeftArrow = leftArrow;
let myRightArrow = rightArrow;

// options (defaults)
let dayTitleFull = false;
let primaryColor = "#3498db";
let darkMode = false;
let closeCalendar = true;

// Public API
function newCalendar(id, option = { dayTitleFull, theme: primaryColor, darkMode, closeCalendar }) {
  if (Array.isArray(id)) id.forEach((item) => createCalender(item, option));
  else createCalender(id, option);
}

// Init one picker
function createCalender(id, option) {
  let m = moment().locale('fa'); // moment-jalaali assumed
  setOption(option);

  const element = document.getElementById(id);
  const newDiv = `<div id="${id}Div" class="hidden"></div>`;
  element.insertAdjacentHTML('afterend', newDiv);

  // set initial Jalali value
  element.value = m.format('jYYYY/jMM/jDD');

  const thisMonth = m.format('jM');
  const thisDay = m.format('jD');

  document.addEventListener("DOMContentLoaded", function () {
    const calendarContainer = document.getElementById(id + "Div");
    renderCalendar(m, thisDay, thisMonth, element, id);

    element.addEventListener("focus", function () {
      calendarContainer.classList.remove("hidden");
    });
  });
}

// ===== helpers =====
function decadeStartJalali(yearNum) {
  return Math.floor(yearNum / 10) * 10; // e.g., 1407 -> 1400
}
function clampDayToMonth(m, day) {
  const dim = m.daysInMonth();
  return Math.min(parseInt(day || 1, 10), dim);
}
function getWeekDay(day) {
  for (let i = 0; i < daysFullNames.length; i++) if (daysFullNames[i] == day) return i + 1;
  return false;
}
function setOption(options) {
  dayTitleFull = options.dayTitleFull ?? dayTitleFull;
  primaryColor  = options.theme ?? primaryColor;
  darkMode      = options.darkMode ?? darkMode;
  closeCalendar = options.closeCalendar ?? closeCalendar;
}

// ===== main renderer =====
function renderCalendar(m, thisDay, thisMonth, element, id) {
  const calendarContainer = document.querySelector(`#${id}Div`);

  const currentYear       = parseInt(m.format('jYYYY'), 10);
  const currentMonth      = parseInt(m.format('jM'), 10);
  const currentMonthLabel = m.format('jMMMM');
  const daysInMonth       = m.daysInMonth();
  const firstDayOfMonth   = getWeekDay(m.clone().startOf('jMonth').format('dddd'));

  let currentDay = thisDay;
  let savedDay   = thisDay;
  let savedMonth = thisMonth;

  renderStyle(primaryColor, id, darkMode);

  if (currentMonth != thisMonth) currentDay = 0;

  // Build HTML
  let calendarHTML = `<div class="calendar-header">`;

  // nav arrows + separate month/year labels (buttons are type="button")
  calendarHTML += `<button type="button" class="calendar-arrow prevMonthBtn" style="float: right;">${myRightArrow}</button>`;
  calendarHTML += `
    <span class="label-wrap">
      <span class="month-label" id="month-label">${currentMonthLabel}</span>
      <span>&nbsp;</span>
      <span class="year-label" id="year-label">${m.format('jYYYY')}</span>
    </span>
  `;
  calendarHTML += `<button type="button" class="calendar-arrow nextMonthBtn" style="float: left;">${myLeftArrow}</button>`;
  calendarHTML += `</div>`;

  // months grid
  calendarHTML += `<div class="calendar-change-body hidden">
    <div class="calendar-month-change" data-month="1">فروردین</div>
    <div class="calendar-month-change" data-month="2">اردیبهشت</div>
    <div class="calendar-month-change" data-month="3">خرداد</div>
    <div class="calendar-month-change" data-month="4">تیر</div>
    <div class="calendar-month-change" data-month="5">مرداد</div>
    <div class="calendar-month-change" data-month="6">شهریور</div>
    <div class="calendar-month-change" data-month="7">مهر</div>
    <div class="calendar-month-change" data-month="8">آبان</div>
    <div class="calendar-month-change" data-month="9">آذر</div>
    <div class="calendar-month-change" data-month="10">دی</div>
    <div class="calendar-month-change" data-month="11">بهمن</div>
    <div class="calendar-month-change" data-month="12">اسفند</div>
  </div>`;

  // years (decade) view
  const startDecade = decadeStartJalali(currentYear);
  calendarHTML += `
  <div class="calendar-years-view hidden">
    <div class="calendar-years-header">
      <button type="button" class="calendar-arrow prevDecadeBtn">${myRightArrow}</button>
      <span class="years-range">${startDecade}–${startDecade + 11}</span>
      <button type="button" class="calendar-arrow nextDecadeBtn">${myLeftArrow}</button>
    </div>
    <div class="calendar-years-grid"></div>
  </div>`;

  // days grid
  calendarHTML += `<div class="calendar-body">`;
  if (dayTitleFull) daysFullNames.forEach(d => calendarHTML += `<div class="day-title day-full-title">${d}</div>`);
  else daysNames.forEach(d => calendarHTML += `<div class="day-title">${d}</div>`);

  for (let i = 1; i < firstDayOfMonth; i++) calendarHTML += `<div class="calendar-hide"></div>`;

  for (let i = 1; i <= daysInMonth; i++) {
    if (parseInt(currentDay, 10) === i) {
      calendarHTML += `<div class="calendar-day selected">${i}</div>`;
    } else if (getWeekDay(moment(`${m.format('jYYYY')}/${m.format('jM')}/${i}`, 'jYYYY/jMM/jDD').locale('fa').format('dddd')) == 7) {
      calendarHTML += `<div class="calendar-day holiday">${i}</div>`;
    } else {
      calendarHTML += `<div class="calendar-day">${i}</div>`;
    }
  }
  calendarHTML += `</div>`;

  // footer
  calendarHTML += `<div class="calendar-footer"><span class="calendar-btn" style="float: left">ثبت</span></div>`;

  // mount
  calendarContainer.innerHTML = calendarHTML;

  // ------- interactions -------
  const showDays = () => {
    document.querySelector(`#${id}Div .calendar-body`).classList.remove('hidden');
    document.querySelector(`#${id}Div .calendar-change-body`).classList.add('hidden');
    document.querySelector(`#${id}Div .calendar-years-view`).classList.add('hidden');
  };
  const showMonths = () => {
    document.querySelector(`#${id}Div .calendar-body`).classList.add('hidden');
    document.querySelector(`#${id}Div .calendar-change-body`).classList.remove('hidden');
    document.querySelector(`#${id}Div .calendar-years-view`).classList.add('hidden');
  };
  const showYears = () => {
    document.querySelector(`#${id}Div .calendar-body`).classList.add('hidden');
    document.querySelector(`#${id}Div .calendar-change-body`).classList.add('hidden');
    document.querySelector(`#${id}Div .calendar-years-view`).classList.remove('hidden');
  };

  // day pick + close
  calendarContainer.addEventListener("click", function (event) {
    const target = event.target;
    if (target.classList.contains("calendar-day")) {
      const allCalendarDays = document.querySelectorAll(`#${id}Div .calendar-day`);
      allCalendarDays.forEach(day => day.classList.remove("selected"));
      target.classList.add("selected");

      element.value = `${m.format('jYYYY')}/${m.format('jM')}/${target.innerHTML}`;
      savedDay = target.innerHTML;
      savedMonth = m.format('jM');

      if (closeCalendar) calendarContainer.classList.add("hidden");
    } else if (target.classList.contains("calendar-btn")) {
      calendarContainer.classList.add("hidden");
    }
  });

  // month nav
  let prevMonthBtn = document.querySelector(`#${id}Div .prevMonthBtn`);
  let nextMonthBtn = document.querySelector(`#${id}Div .nextMonthBtn`);
  prevMonthBtn.addEventListener('click', () => {
    m = m.subtract(1, 'jMonth');
    renderCalendar(m, savedDay, savedMonth, element, id);
  });
  nextMonthBtn.addEventListener('click', () => {
    m = m.add(1, 'jMonth');
    renderCalendar(m, savedDay, savedMonth, element, id);
  });

  // separate triggers for labels
  const monthLabelEl = document.querySelector(`#${id}Div #month-label`);
  const yearLabelEl  = document.querySelector(`#${id}Div #year-label`);
  monthLabelEl.addEventListener('click', showMonths);
  yearLabelEl.addEventListener('click', () => {
    buildYearsGrid(startDecade);
    showYears();
  });

  // choose month -> apply immediately & go back to days
  let chosenMonth = currentMonth;
  document.querySelector(`#${id}Div .calendar-change-body`).addEventListener('click', function (event) {
    const t = event.target;
    if (t.classList.contains('calendar-month-change') && t.dataset.month) {
      chosenMonth = parseInt(t.dataset.month, 10);

      const newDay = clampDayToMonth(moment(`${m.format('jYYYY')}/${chosenMonth}/1`, 'jYYYY/jM/jD').locale('fa'), savedDay);
      const newDateFormat = `${m.format('jYYYY')}/${chosenMonth}/${newDay}`;
      element.value = newDateFormat;

      m = moment(newDateFormat, 'jYYYY/jM/jD').locale('fa');
      renderCalendar(m, newDay, chosenMonth, element, id);
    }
  });

  // years view: build grid for a decade
  let decadeBase = startDecade;
  function buildYearsGrid(decadeStart) {
    const grid = document.querySelector(`#${id}Div .calendar-years-grid`);
    grid.innerHTML = "";
    for (let y = decadeStart; y <= decadeStart + 11; y++) {
      const btn = document.createElement('div');
      btn.className = 'calendar-month-change calendar-year-item';
      btn.textContent = y;
      if (y === currentYear) btn.classList.add('today');
      btn.setAttribute('data-year', String(y));
      grid.appendChild(btn);
    }
    const range = document.querySelector(`#${id}Div .years-range`);
    range.textContent = `${decadeStart}–${decadeStart + 11}`;
  }

  // decade nav (prevent default just in case)
  const prevDecadeBtn = document.querySelector(`#${id}Div .prevDecadeBtn`);
  const nextDecadeBtn = document.querySelector(`#${id}Div .nextDecadeBtn`);
  prevDecadeBtn.addEventListener('click', (event) => {
    event.preventDefault();
    decadeBase -= 10;
    buildYearsGrid(decadeBase);
  });
  nextDecadeBtn.addEventListener('click', (event) => {
    event.preventDefault();
    decadeBase += 10;
    buildYearsGrid(decadeBase);
  });

  // pick a year -> apply immediately & go back to days
  document.querySelector(`#${id}Div .calendar-years-view`).addEventListener('click', function (event) {
    const t = event.target;
    if (t.classList.contains('calendar-year-item')) {
      const pickedYear = parseInt(t.getAttribute('data-year'), 10);

      const newDay = clampDayToMonth(moment(`${pickedYear}/${chosenMonth}/1`, 'jYYYY/jM/jD').locale('fa'), savedDay);
      const newDateFormat = `${pickedYear}/${chosenMonth}/${newDay}`;
      element.value = newDateFormat;

      m = moment(newDateFormat, 'jYYYY/jM/jD').locale('fa');
      renderCalendar(m, newDay, chosenMonth, element, id);
    }
  });
}

// ===== styles injector (uses your theme + darkMode) =====
function renderStyle(color, id, isDark) {
  const style = document.createElement('style');
  style.innerHTML = `
    #${id}Div {
      width: 350px;
      border-radius: 15px;
      border: 1px solid rgba(77, 87, 169, 0.08);
      background: rgb(252, 252, 255);
      padding: 16px;
      position: absolute;
    }
    @media (max-width: 800px) { #${id}Div { max-width: 340px; } }

    .today, .selected { background-color: ${color}; color: #ffffff; }

    .calendar-btn, .calendar-btn-controlyear, .calendar-btn-apply {
      background-color: ${color};
      color: #ffffff;
      padding: 6px 9px;
      border-radius: 5px;
    }
  `;

  if (isDark) {
    style.innerHTML += `
      #${id}Div { background: rgb(17 24 39); }
      .calendar-day, .calendar-month-change {
        background: rgb(36 42 56);
        color: #ffffff;
        border-color: #444;
      }
      .holiday { color: rgb(255, 63, 63); }
      .calendar-header, .day-title { color: #fff; }
      .today, .selected { background-color: ${color}; color: #ffffff; }
    `;
    myRightArrow = rightArrowDark;
    myLeftArrow  = leftArrowDark;
  } else {
    myRightArrow = rightArrow;
    myLeftArrow  = leftArrow;
  }

  document.head.appendChild(style);
}

// (optional) legacy validate kept for any stray inputs elsewhere
function validate(evt) {
  var theEvent = evt || window.event;
  let key;
  if (theEvent.type === 'paste') {
    key = event.clipboardData.getData('text/plain');
  } else {
    var code = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode(code);
  }
  var regex = /[0-9]|\./;
  if (!regex.test(key)) {
    theEvent.returnValue = false;
    if (theEvent.preventDefault) theEvent.preventDefault();
  }
}
