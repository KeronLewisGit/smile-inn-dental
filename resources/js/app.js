import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';

Alpine.plugin(focus);

/** Scroll-reveal for sections marked .reveal. */
const io = new IntersectionObserver((entries) => entries.forEach((e) => { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } }), { threshold: 0.12 });
document.querySelectorAll('.reveal').forEach((el) => io.observe(el));

/** Three-step booking widget: type → date & time → details. Slots come from /book/slots. */
Alpine.data('booking', (opts) => ({
    types: opts.types,
    type: opts.initialType || null,
    date: opts.initialDate || null,
    time: opts.initialTime || null,
    step: opts.initialType ? (opts.initialDate ? 3 : 2) : 1,
    openDays: [],
    slots: [],
    loading: false,
    monthCursor: null,
    init() {
        const today = new Date();
        this.monthCursor = new Date(today.getFullYear(), today.getMonth(), 1);
        if (this.type) this.loadDays();
        if (this.type && this.date) this.loadSlots();
    },
    get selected() { return this.types.find((t) => t.slug === this.type); },
    choose(slug) { this.type = slug; this.date = null; this.time = null; this.slots = []; this.step = 2; this.loadDays(); },
    async loadDays() {
        this.loading = true;
        try { const r = await fetch(`${opts.slotsUrl}?type=${encodeURIComponent(this.type)}`); const j = await r.json(); this.openDays = j.open_days || []; } catch { this.openDays = []; }
        this.loading = false;
        if (this.openDays.length && !this.date) { const first = new Date(this.openDays[0] + 'T00:00:00'); this.monthCursor = new Date(first.getFullYear(), first.getMonth(), 1); }
    },
    async pick(day) { if (!this.openDays.includes(day)) return; this.date = day; this.time = null; await this.loadSlots(); },
    async loadSlots() {
        this.loading = true;
        try { const r = await fetch(`${opts.slotsUrl}?type=${encodeURIComponent(this.type)}&date=${this.date}`); const j = await r.json(); this.slots = j.slots || []; } catch { this.slots = []; }
        this.loading = false;
    },
    pickTime(t) { this.time = t; this.step = 3; this.$nextTick(() => document.getElementById('details')?.scrollIntoView({ behavior: 'smooth', block: 'start' })); },
    get monthLabel() { return this.monthCursor.toLocaleDateString('en-GB', { month: 'long', year: 'numeric' }); },
    get grid() {
        const y = this.monthCursor.getFullYear(), m = this.monthCursor.getMonth();
        const first = new Date(y, m, 1), lead = (first.getDay() + 6) % 7, days = new Date(y, m + 1, 0).getDate();
        const cells = Array.from({ length: lead }, () => null);
        for (let d = 1; d <= days; d++) { const iso = `${y}-${String(m + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`; cells.push({ d, iso, open: this.openDays.includes(iso) }); }
        return cells;
    },
    prevMonth() { this.monthCursor = new Date(this.monthCursor.getFullYear(), this.monthCursor.getMonth() - 1, 1); },
    nextMonth() { this.monthCursor = new Date(this.monthCursor.getFullYear(), this.monthCursor.getMonth() + 1, 1); },
    get canPrev() { const t = new Date(); return this.monthCursor > new Date(t.getFullYear(), t.getMonth(), 1); },
    get canNext() { const last = this.openDays[this.openDays.length - 1]; if (!last) return false; const l = new Date(last + 'T00:00:00'); return this.monthCursor < new Date(l.getFullYear(), l.getMonth(), 1); },
    get summary() {
        if (!this.selected) return '';
        const d = this.date ? new Date(this.date + 'T00:00:00').toLocaleDateString('en-GB', { weekday: 'long', day: 'numeric', month: 'long' }) : '';
        const t = this.time ? this.slots.find((s) => s.time === this.time)?.label : '';
        return [this.selected.name, d, t].filter(Boolean).join(' · ');
    },
}));

/** Admin: simple slot picker for the manual booking form. */
Alpine.data('adminSlots', (url) => ({
    slots: [], loading: false,
    async load(type, date, current) { if (!type || !date) { this.slots = []; return; } this.loading = true; try { const r = await fetch(`${url}?type=${type}&date=${date}`); this.slots = (await r.json()).slots.map((s) => ({ ...s, available: s.available || s.time === current })); } catch { this.slots = []; } this.loading = false; },
}));

window.Alpine = Alpine;
Alpine.start();
