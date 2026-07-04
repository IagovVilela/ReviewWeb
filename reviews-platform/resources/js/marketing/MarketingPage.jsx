import React, { useState, useCallback } from 'react';
import { motion, AnimatePresence, MotionConfig, useReducedMotion } from 'framer-motion';
import {
    ArrowRight,
    BarChart3,
    Bell,
    Crosshair,
    Download,
    Globe,
    Moon,
    Palette,
    Rocket,
    Shield,
    Smartphone,
    Star,
    Trophy,
    X,
    Zap,
    TrendingUp,
    DollarSign,
    PieChart,
    Gift,
    Play,
} from 'lucide-react';
import {
    fadeUp,
    sectionReveal,
    staggerContainer,
    staggerItem,
    springHover,
    hoverLift,
    tapScale,
} from '../motion/presets';

const iconMap = {
    crosshairs: Crosshair,
    shield: Shield,
    chart: BarChart3,
    bell: Bell,
    mobile: Smartphone,
    globe: Globe,
    download: Download,
    palette: Palette,
    moon: Moon,
    arrow: TrendingUp,
    dollar: DollarSign,
    bolt: Zap,
    pie: PieChart,
    rocket: Rocket,
};

function ContactModal({ open, onClose, t, csrfToken }) {
    const [status, setStatus] = useState('idle');
    const [form, setForm] = useState({
        contact_name: '',
        company_name: '',
        email: '',
        whatsapp: '',
    });

    const handleSubmit = async (e) => {
        e.preventDefault();
        setStatus('sending');
        try {
            const res = await fetch('/contact-trial', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    Accept: 'application/json',
                },
                body: JSON.stringify(form),
            });
            if (!res.ok) throw new Error('Failed');
            setStatus('success');
            setTimeout(() => {
                onClose();
                setStatus('idle');
                setForm({ contact_name: '', company_name: '', email: '', whatsapp: '' });
            }, 2500);
        } catch {
            setStatus('error');
        }
    };

    if (!open) return null;

    return (
        <div
            className="fixed inset-0 z-[100] flex items-center justify-center bg-primary-900/40 p-4 backdrop-blur-sm"
            onClick={(e) => e.target === e.currentTarget && onClose()}
            role="dialog"
            aria-modal="true"
        >
            <motion.div
                initial={{ opacity: 0, scale: 0.96, y: 12 }}
                animate={{ opacity: 1, scale: 1, y: 0 }}
                exit={{ opacity: 0, scale: 0.96, y: 12 }}
                transition={{ duration: 0.35, ease: [0.16, 1, 0.3, 1] }}
                className="w-full max-w-md rounded-2xl border border-gray-200 bg-white p-8 shadow-editorial-lg dark:border-gray-700 dark:bg-gray-800"
            >
                <div className="mb-6 flex items-start justify-between">
                    <div>
                        <h2 className="editorial-display text-2xl">{t.contact_form_title}</h2>
                        <p className="mt-2 text-sm text-gray-600 dark:text-gray-300">{t.contact_form_subtitle}</p>
                    </div>
                    <button
                        type="button"
                        onClick={onClose}
                        className="rounded-full p-2 text-gray-500 transition hover:bg-gray-100 dark:hover:bg-gray-700"
                        aria-label="Close"
                    >
                        <X size={18} />
                    </button>
                </div>

                {status === 'success' ? (
                    <div className="py-8 text-center">
                        <div className="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-primary-100 dark:bg-primary-900">
                            <Star className="text-primary-600 dark:text-primary-400" size={20} />
                        </div>
                        <h3 className="font-medium">{t.success_title}</h3>
                        <p className="mt-2 text-sm text-gray-600 dark:text-gray-300">{t.success_message}</p>
                    </div>
                ) : (
                    <form onSubmit={handleSubmit} className="space-y-4">
                        {[
                            ['contact_name', t.contact_name, t.contact_name_placeholder],
                            ['company_name', t.company_name, t.company_name_placeholder],
                            ['email', t.email, t.email_placeholder],
                            ['whatsapp', t.whatsapp, t.whatsapp_placeholder],
                        ].map(([key, label, placeholder]) => (
                            <div key={key}>
                                <label className="editorial-label" htmlFor={key}>
                                    {label}
                                </label>
                                <input
                                    id={key}
                                    type={key === 'email' ? 'email' : key === 'whatsapp' ? 'tel' : 'text'}
                                    className="editorial-input"
                                    placeholder={placeholder}
                                    required
                                    value={form[key]}
                                    onChange={(e) => setForm((f) => ({ ...f, [key]: e.target.value }))}
                                />
                            </div>
                        ))}
                        <button
                            type="submit"
                            disabled={status === 'sending'}
                            className="editorial-btn-primary w-full disabled:opacity-60"
                        >
                            {status === 'sending' ? t.sending : t.submit_button}
                        </button>
                        {status === 'error' && (
                            <p className="text-center text-sm text-red-500">{t.error_message}</p>
                        )}
                    </form>
                )}
            </motion.div>
        </div>
    );
}

function MarketingPage({ translations: t, stats, assets, csrfToken }) {
    const reduced = useReducedMotion();
    const [modalOpen, setModalOpen] = useState(false);
    const [dark, setDark] = useState(() => {
        if (typeof document === 'undefined') return false;
        return document.documentElement.classList.contains('dark');
    });

    const toggleDark = useCallback(() => {
        const next = !document.documentElement.classList.contains('dark');
        document.documentElement.classList.toggle('dark', next);
        localStorage.setItem('darkMode', next ? 'true' : 'false');
        setDark(next);
    }, []);

    const openModal = useCallback(() => setModalOpen(true), []);
    const closeModal = useCallback(() => setModalOpen(false), []);

    const features = [
        { icon: 'crosshairs', title: t.feature_redirect_title, desc: t.feature_redirect_desc },
        { icon: 'shield', title: t.feature_protection_title, desc: t.feature_protection_desc },
        { icon: 'chart', title: t.feature_dashboard_title, desc: t.feature_dashboard_desc },
        { icon: 'bell', title: t.feature_notifications_title, desc: t.feature_notifications_desc },
        { icon: 'mobile', title: t.feature_contacts_title, desc: t.feature_contacts_desc },
        { icon: 'globe', title: t.feature_multilang_title, desc: t.feature_multilang_desc },
        { icon: 'download', title: t.feature_export_title, desc: t.feature_export_desc },
        { icon: 'palette', title: t.feature_customization_title, desc: t.feature_customization_desc },
        { icon: 'moon', title: t.feature_darkmode_title, desc: t.feature_darkmode_desc },
    ];

    const benefits = [
        { icon: 'arrow', title: t.benefit1_title, desc: t.benefit1_desc },
        { icon: 'shield', title: t.benefit2_title, desc: t.benefit2_desc },
        { icon: 'dollar', title: t.benefit3_title, desc: t.benefit3_desc },
        { icon: 'bolt', title: t.benefit4_title, desc: t.benefit4_desc },
        { icon: 'pie', title: t.benefit5_title, desc: t.benefit5_desc },
        { icon: 'rocket', title: t.benefit6_title, desc: t.benefit6_desc },
    ];

    const steps = [
        { n: '01', title: t.step1_title, desc: t.step1_desc },
        { n: '02', title: t.step2_title, desc: t.step2_desc },
        { n: '03', title: t.step3_title, desc: t.step3_desc },
        { n: '04', title: t.step4_title, desc: t.step4_desc },
    ];

    return (
        <MotionConfig reducedMotion="user">
            <div className="min-h-screen bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100">
                <header className="fixed inset-x-0 top-0 z-50 border-b border-gray-200/80 bg-white/90 backdrop-blur-md dark:border-gray-700 dark:bg-gray-900/90">
                    <div className="editorial-container flex h-16 items-center justify-between md:h-[4.5rem]">
                        <a href="/" className="flex items-center gap-3">
                            <img src={assets.logo} alt={t.app_name} className="h-8 w-auto" />
                            <span className="hidden text-sm font-semibold tracking-tight sm:inline">{t.app_name}</span>
                        </a>
                        <div className="flex items-center gap-2">
                            <button
                                type="button"
                                onClick={toggleDark}
                                className="rounded-full p-2.5 text-gray-500 transition hover:bg-primary-50 hover:text-primary-600 dark:hover:bg-gray-800"
                                aria-label="Toggle theme"
                            >
                                <Moon size={18} />
                            </button>
                            <a href="/login" className="editorial-btn-primary !py-2.5 !text-xs md:!text-sm">
                                {t.access_panel}
                            </a>
                        </div>
                    </div>
                </header>

                <main className="pt-16 md:pt-[4.5rem]">
                    {/* Hero */}
                    <section className="editorial-section rw-gradient-bg border-b border-gray-200 dark:border-gray-700">
                        <div className="editorial-container">
                            <div className="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
                                <motion.div
                                    initial={reduced ? false : { opacity: 0, y: 24 }}
                                    animate={{ opacity: 1, y: 0 }}
                                    transition={{ duration: 0.7, ease: [0.16, 1, 0.3, 1] }}
                                >
                                    <p className="editorial-kicker mb-6">{t.hero_kicker}</p>
                                    <h1 className="editorial-display text-4xl leading-[1.08] md:text-5xl lg:text-6xl">
                                        {t.hero_title}
                                    </h1>
                                    <p className="mt-6 max-w-prose text-base leading-relaxed text-gray-600 dark:text-gray-300 md:text-lg">
                                        {t.hero_description}
                                    </p>
                                    <div className="mt-10 flex flex-wrap gap-3">
                                        <motion.button
                                            type="button"
                                            className="editorial-btn-primary"
                                            whileHover={reduced ? {} : hoverLift}
                                            whileTap={reduced ? {} : tapScale}
                                            onClick={openModal}
                                        >
                                            <Rocket size={16} />
                                            {t.start_now}
                                        </motion.button>
                                        <motion.a
                                            href="#how-it-works"
                                            className="editorial-btn-ghost"
                                            whileHover={reduced ? {} : hoverLift}
                                            whileTap={reduced ? {} : tapScale}
                                        >
                                            <Play size={16} />
                                            {t.learn_more}
                                        </motion.a>
                                    </div>
                                    <div className="mt-12 flex flex-wrap gap-8 border-t border-gray-200 pt-8 dark:border-gray-700">
                                        {stats.map((s) => (
                                            <div key={s.label}>
                                                <p className="text-2xl font-semibold tracking-tight text-primary-600 dark:text-primary-400">{s.value}</p>
                                                <p className="mt-1 text-xs text-gray-500">{s.label}</p>
                                            </div>
                                        ))}
                                        <div>
                                            <p className="text-2xl font-semibold tracking-tight text-primary-600 dark:text-primary-400">100%</p>
                                            <p className="mt-1 text-xs text-gray-500">{t.hero_control_total}</p>
                                        </div>
                                    </div>
                                </motion.div>

                                <motion.div
                                    initial={reduced ? false : { opacity: 0, y: 32 }}
                                    animate={{ opacity: 1, y: 0 }}
                                    transition={{ duration: 0.75, delay: 0.1, ease: [0.16, 1, 0.3, 1] }}
                                    className="editorial-card shadow-editorial"
                                >
                                    <p className="text-xs font-medium uppercase tracking-widest text-gray-500">
                                        {t.hero_preview_title}
                                    </p>
                                    <div className="mt-4 flex items-baseline gap-3">
                                        <span className="text-4xl font-semibold">4.9</span>
                                        <span className="text-amber-500">★★★★★</span>
                                    </div>
                                    <div className="mt-6 space-y-2">
                                        {[
                                            ['5', '88%'],
                                            ['4', '58%'],
                                            ['3', '24%'],
                                        ].map(([star, w]) => (
                                            <div key={star} className="flex items-center gap-3 text-xs text-gray-500">
                                                <span className="w-3">{star}</span>
                                                <div className="h-px flex-1 bg-gray-200 dark:bg-gray-600">
                                                    <div className="h-full bg-gradient-to-r from-primary-500 to-pink-500" style={{ width: w }} />
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                    <div className="mt-6 grid grid-cols-2 gap-3">
                                        <div className="rounded-xl border border-gray-200 p-3 dark:border-gray-600">
                                            <Shield size={14} className="text-primary-500" />
                                            <p className="mt-2 text-xs font-medium">{t.hero_preview_filter_title}</p>
                                            <p className="text-[11px] text-gray-500">{t.hero_preview_filter_desc}</p>
                                        </div>
                                        <div className="rounded-xl border border-gray-200 p-3 dark:border-gray-600">
                                            <Bell size={14} className="text-primary-500" />
                                            <p className="mt-2 text-xs font-medium">{t.hero_preview_alert_title}</p>
                                            <p className="text-[11px] text-gray-500">{t.hero_preview_alert_desc}</p>
                                        </div>
                                    </div>
                                </motion.div>
                            </div>
                        </div>
                    </section>

                    {/* Prize */}
                    <motion.section
                        className="editorial-section rw-prize-band border-b border-primary-700 text-white"
                        variants={sectionReveal}
                        initial="hidden"
                        whileInView="show"
                        viewport={{ once: true, margin: '-80px' }}
                    >
                        <div className="editorial-container text-center">
                            <Trophy className="mx-auto text-white/90" size={32} strokeWidth={1.5} />
                            <h2 className="editorial-display mt-6 text-3xl text-white md:text-4xl">{t.prize_draw_title}</h2>
                            <p className="editorial-display mt-4 text-5xl text-white md:text-6xl lg:text-7xl">{t.prize_amount_display}</p>
                            <p className="mx-auto mt-6 max-w-2xl text-base text-white/85 md:text-lg">{t.prize_draw_description}</p>
                            <div className="mt-8 inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/10 px-4 py-2 text-sm text-white">
                                <Gift size={14} />
                                {t.prize_draw_badge}
                            </div>
                        </div>
                    </motion.section>

                    {/* Features */}
                    <motion.section
                        id="features"
                        className="editorial-section border-b border-gray-200 dark:border-gray-700"
                        variants={sectionReveal}
                        initial="hidden"
                        whileInView="show"
                        viewport={{ once: true, margin: '-60px' }}
                    >
                        <div className="editorial-container">
                            <div className="editorial-section-head">
                                <h2>{t.features_title}</h2>
                                <p>{t.features_description}</p>
                            </div>
                            <motion.div
                                className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3"
                                variants={staggerContainer}
                                initial="hidden"
                                whileInView="show"
                                viewport={{ once: true }}
                            >
                                {features.map((f) => {
                                    const Icon = iconMap[f.icon] || Star;
                                    return (
                                        <motion.article
                                            key={f.title}
                                            variants={staggerItem}
                                            whileHover={reduced ? {} : hoverLift}
                                            className="editorial-card group"
                                        >
                                            <Icon size={20} className="text-primary-500 transition group-hover:text-primary-600" strokeWidth={1.5} />
                                            <h3 className="mt-4 font-medium tracking-tight text-gray-900 dark:text-white">{f.title}</h3>
                                            <p className="mt-2 text-sm leading-relaxed text-gray-600 dark:text-gray-300">{f.desc}</p>
                                        </motion.article>
                                    );
                                })}
                            </motion.div>
                        </div>
                    </motion.section>

                    {/* Benefits */}
                    <motion.section
                        className="editorial-section border-b border-gray-200 dark:border-gray-700"
                        variants={sectionReveal}
                        initial="hidden"
                        whileInView="show"
                        viewport={{ once: true, margin: '-60px' }}
                    >
                        <div className="editorial-container">
                            <div className="editorial-section-head">
                                <h2>{t.benefits_title}</h2>
                                <p>{t.benefits_description}</p>
                            </div>
                            <motion.div className="grid gap-4 md:grid-cols-2" variants={staggerContainer} initial="hidden" whileInView="show" viewport={{ once: true }}>
                                {benefits.map((b) => {
                                    const Icon = iconMap[b.icon] || Star;
                                    return (
                                        <motion.article key={b.title} variants={staggerItem} whileHover={reduced ? {} : hoverLift} className="editorial-card flex gap-4">
                                            <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-primary-200 bg-primary-50 text-primary-600 dark:border-primary-700 dark:bg-primary-900/40">
                                                <Icon size={18} strokeWidth={1.5} />
                                            </div>
                                            <div>
                                                <h3 className="font-medium text-gray-900 dark:text-white">{b.title}</h3>
                                                <p className="mt-1 text-sm text-gray-600 dark:text-gray-300">{b.desc}</p>
                                            </div>
                                        </motion.article>
                                    );
                                })}
                            </motion.div>
                        </div>
                    </motion.section>

                    {/* How it works */}
                    <motion.section
                        id="how-it-works"
                        className="editorial-section border-b border-gray-200 dark:border-gray-700"
                        variants={sectionReveal}
                        initial="hidden"
                        whileInView="show"
                        viewport={{ once: true, margin: '-60px' }}
                    >
                        <div className="editorial-container">
                            <div className="editorial-section-head">
                                <h2>{t.how_title}</h2>
                                <p>{t.how_description}</p>
                            </div>
                            <div className="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
                                {steps.map((step, i) => (
                                    <motion.div
                                        key={step.n}
                                        initial={reduced ? false : { opacity: 0, y: 16 }}
                                        whileInView={{ opacity: 1, y: 0 }}
                                        viewport={{ once: true }}
                                        transition={{ delay: i * 0.08, duration: 0.5 }}
                                    >
                                        <span className="text-4xl font-bold text-primary-300 dark:text-primary-600">{step.n}</span>
                                        <h3 className="mt-4 font-medium text-gray-900 dark:text-white">{step.title}</h3>
                                        <p className="mt-2 text-sm leading-relaxed text-gray-600 dark:text-gray-300">{step.desc}</p>
                                    </motion.div>
                                ))}
                            </div>
                        </div>
                    </motion.section>

                    {/* CTA */}
                    <motion.section
                        className="editorial-section"
                        variants={sectionReveal}
                        initial="hidden"
                        whileInView="show"
                        viewport={{ once: true }}
                    >
                        <div className="editorial-container rounded-3xl rw-gradient-cta px-8 py-16 text-center text-white shadow-editorial-lg md:px-16">
                            <h2 className="text-3xl font-bold text-white md:text-4xl">{t.cta_title}</h2>
                            <p className="mx-auto mt-4 max-w-xl text-sm text-white/85 md:text-base">{t.cta_description}</p>
                            <motion.button
                                type="button"
                                className="mt-8 inline-flex items-center gap-2 rounded-xl bg-white px-8 py-3.5 text-sm font-semibold text-primary-600 shadow-md"
                                whileHover={reduced ? {} : hoverLift}
                                whileTap={reduced ? {} : tapScale}
                                onClick={openModal}
                            >
                                <Star size={16} />
                                {t.start_free}
                                <ArrowRight size={16} />
                            </motion.button>
                        </div>
                    </motion.section>
                </main>

                <footer className="border-t border-gray-200 bg-white py-16 dark:border-gray-700 dark:bg-gray-900">
                    <div className="editorial-container grid gap-10 md:grid-cols-4">
                        <div className="md:col-span-2">
                            <p className="font-medium text-gray-900 dark:text-white">{t.app_name}</p>
                            <p className="mt-3 max-w-sm text-sm text-gray-600 dark:text-gray-300">{t.footer_description}</p>
                            <p className="mt-6 text-xs text-gray-500">© 2025 {t.app_name}. {t.all_rights}</p>
                        </div>
                        <div>
                            <p className="text-xs font-semibold uppercase tracking-widest text-primary-600 dark:text-primary-400">{t.product}</p>
                            <ul className="mt-4 space-y-2 text-sm text-gray-600 dark:text-gray-300">
                                <li><a href="#how-it-works" className="hover:text-primary-600">{t.how_works}</a></li>
                                <li><a href="/login" className="hover:text-primary-600">{t.control_panel}</a></li>
                                <li><a href="/login" className="hover:text-primary-600">{t.create_account}</a></li>
                            </ul>
                        </div>
                        <div>
                            <p className="text-xs font-semibold uppercase tracking-widest text-primary-600 dark:text-primary-400">{t.contact}</p>
                            <p className="mt-4 text-sm text-gray-600 dark:text-gray-300">{t.technical_support}</p>
                        </div>
                    </div>
                    <div className="editorial-container mt-10 border-t border-gray-200 pt-8 text-center text-xs text-gray-500 dark:border-gray-700">
                        {t.developed_with} {t.by} Iago Vilela & Mateus Bittencourt
                    </div>
                </footer>

                <AnimatePresence>
                    {modalOpen && (
                        <ContactModal open={modalOpen} onClose={closeModal} t={t} csrfToken={csrfToken} />
                    )}
                </AnimatePresence>
            </div>
        </MotionConfig>
    );
}

export default MarketingPage;
