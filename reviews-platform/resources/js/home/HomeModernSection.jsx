import React, { useRef, useCallback } from 'react';
import {
    motion,
    useReducedMotion,
    useMotionValue,
    useSpring,
    useTransform,
    MotionConfig,
} from 'framer-motion';

const smooth = [0.16, 1, 0.3, 1];

const sectionShow = {
    hidden: { opacity: 0, y: 32 },
    show: {
        opacity: 1,
        y: 0,
        transition: { duration: 0.7, ease: smooth },
    },
};

const listContainer = {
    hidden: { opacity: 0 },
    show: {
        opacity: 1,
        transition: { staggerChildren: 0.07, delayChildren: 0.04 },
    },
};

const listItem = {
    hidden: { opacity: 0, y: 16 },
    show: {
        opacity: 1,
        y: 0,
        transition: { duration: 0.45, ease: smooth },
    },
};

const cardItem = {
    hidden: { opacity: 0, y: 20 },
    show: {
        opacity: 1,
        y: 0,
        transition: { duration: 0.5, ease: smooth },
    },
};

const springHover = {
    type: 'spring',
    stiffness: 400,
    damping: 28,
};

function usePreviewTilt(reduced) {
    const ref = useRef(null);
    const mx = useMotionValue(0);
    const my = useMotionValue(0);
    const sMx = useSpring(mx, { stiffness: 280, damping: 32, mass: 0.6 });
    const sMy = useSpring(my, { stiffness: 280, damping: 32, mass: 0.6 });
    const rotateX = useTransform(sMy, (v) => (reduced ? 0 : v * -0.55));
    const rotateY = useTransform(sMx, (v) => (reduced ? 0 : v * 0.55));

    const onMove = useCallback(
        (e) => {
            if (reduced || !ref.current) return;
            const r = ref.current.getBoundingClientRect();
            const px = (e.clientX - r.left) / r.width - 0.5;
            const py = (e.clientY - r.top) / r.height - 0.5;
            mx.set(px * 14);
            my.set(py * 14);
        },
        [mx, my, reduced]
    );

    const onLeave = useCallback(() => {
        mx.set(0);
        my.set(0);
    }, [mx, my]);

    return { ref, onMove, onLeave, rotateX, rotateY };
}

function HomeModernSection({ translations, stats, onOpenContact }) {
    const prefersReducedMotion = useReducedMotion();
    const reduced = !!prefersReducedMotion;
    const tilt = usePreviewTilt(reduced);

    const hoverLift = reduced
        ? {}
        : {
              y: -5,
              transition: springHover,
          };

    const hoverCard = reduced
        ? {}
        : {
              y: -4,
              scale: 1.01,
              transition: springHover,
          };

    const tapSm = reduced ? {} : { scale: 0.985 };

    const features = [
        { icon: 'fas fa-crosshairs', title: translations.feature_redirect_title, description: translations.feature_redirect_desc },
        { icon: 'fas fa-shield-alt', title: translations.feature_protection_title, description: translations.feature_protection_desc },
        { icon: 'fas fa-chart-bar', title: translations.feature_dashboard_title, description: translations.feature_dashboard_desc },
        { icon: 'fas fa-bell', title: translations.feature_notifications_title, description: translations.feature_notifications_desc },
        { icon: 'fas fa-mobile-alt', title: translations.feature_contacts_title, description: translations.feature_contacts_desc },
        { icon: 'fas fa-globe', title: translations.feature_multilang_title, description: translations.feature_multilang_desc },
        { icon: 'fas fa-download', title: translations.feature_export_title, description: translations.feature_export_desc },
        { icon: 'fas fa-palette', title: translations.feature_customization_title, description: translations.feature_customization_desc },
        { icon: 'fas fa-moon', title: translations.feature_darkmode_title, description: translations.feature_darkmode_desc },
    ];

    const benefits = [
        { icon: 'fas fa-arrow-up', title: translations.benefit1_title, description: translations.benefit1_desc },
        { icon: 'fas fa-shield-alt', title: translations.benefit2_title, description: translations.benefit2_desc },
        { icon: 'fas fa-dollar-sign', title: translations.benefit3_title, description: translations.benefit3_desc },
        { icon: 'fas fa-bolt', title: translations.benefit4_title, description: translations.benefit4_desc },
        { icon: 'fas fa-chart-pie', title: translations.benefit5_title, description: translations.benefit5_desc },
        { icon: 'fas fa-rocket', title: translations.benefit6_title, description: translations.benefit6_desc },
    ];

    return (
        <MotionConfig reducedMotion="user" transition={{ duration: 0.4 }}>
            <div className="home-modern">
                <motion.section
                    className="home-modern-hero"
                    variants={sectionShow}
                    initial="hidden"
                    animate="show"
                    viewport={{ once: true, amount: 0.25 }}
                >
                    <div className="home-modern-container">
                        <div className="home-modern-hero-glow" aria-hidden />
                        <div className="home-modern-hero-grid">
                            <motion.div
                                className="home-modern-hero-copy"
                                initial={reduced ? false : { opacity: 0, y: 22 }}
                                animate={reduced ? {} : { opacity: 1, y: 0 }}
                                transition={{ duration: 0.65, ease: smooth, delay: 0.02 }}
                            >
                                <motion.span
                                    className="home-modern-kicker"
                                    initial={reduced ? false : { opacity: 0, letterSpacing: '0.28em' }}
                                    animate={reduced ? {} : { opacity: 1, letterSpacing: '0.16em' }}
                                    transition={{ duration: 0.8, ease: smooth }}
                                >
                                    {translations.hero_kicker}
                                </motion.span>
                                <motion.h1
                                    initial={reduced ? false : { opacity: 0, y: 12 }}
                                    animate={reduced ? {} : { opacity: 1, y: 0 }}
                                    transition={{ duration: 0.55, delay: 0.06, ease: smooth }}
                                >
                                    {translations.hero_title}
                                </motion.h1>
                                <motion.p
                                    initial={reduced ? false : { opacity: 0 }}
                                    animate={reduced ? {} : { opacity: 1 }}
                                    transition={{ duration: 0.5, delay: 0.12 }}
                                >
                                    {translations.hero_description}
                                </motion.p>
                                <motion.div
                                    className="hero-buttons home-modern-hero-actions"
                                    initial={reduced ? false : { opacity: 0, y: 10 }}
                                    animate={reduced ? {} : { opacity: 1, y: 0 }}
                                    transition={{ duration: 0.45, delay: 0.18, ease: smooth }}
                                >
                                    <motion.button
                                        type="button"
                                        className="btn-primary home-modern-btn-primary"
                                        whileHover={
                                            reduced
                                                ? {}
                                                : {
                                                      y: -3,
                                                      boxShadow: '0 12px 28px rgba(139, 92, 246, 0.22)',
                                                  }
                                        }
                                        whileTap={tapSm}
                                        transition={springHover}
                                        onClick={onOpenContact}
                                    >
                                        <i className="fas fa-rocket" />
                                        {translations.start_now}
                                    </motion.button>
                                    <motion.a
                                        href="#como-funciona"
                                        className="btn-secondary home-modern-btn-secondary"
                                        whileHover={hoverLift}
                                        whileTap={tapSm}
                                    >
                                        <motion.span
                                            className="home-modern-play-icon"
                                            whileHover={reduced ? {} : { rotate: [0, -8, 8, 0] }}
                                            transition={{ duration: 0.45 }}
                                        >
                                            <i className="fas fa-play-circle" />
                                        </motion.span>
                                        {translations.learn_more}
                                        <span className="home-modern-link-line" aria-hidden />
                                    </motion.a>
                                </motion.div>
                                <motion.div
                                    className="home-modern-inline-stats"
                                    variants={listContainer}
                                    initial="hidden"
                                    animate="show"
                                >
                                    {stats.map((item) => (
                                        <motion.div
                                            key={`hero-${item.label}`}
                                            className="home-modern-inline-stat"
                                            variants={listItem}
                                            whileHover={reduced ? {} : { x: 2 }}
                                            transition={springHover}
                                        >
                                            <strong>{item.value}</strong>
                                            <span>{item.label}</span>
                                        </motion.div>
                                    ))}
                                    <motion.div
                                        className="home-modern-inline-stat"
                                        variants={listItem}
                                        whileHover={reduced ? {} : { x: 2 }}
                                        transition={springHover}
                                    >
                                        <strong>100%</strong>
                                        <span>{translations.hero_control_total}</span>
                                    </motion.div>
                                </motion.div>
                            </motion.div>

                            <motion.div
                                ref={tilt.ref}
                                className="home-modern-preview home-modern-preview-tilt"
                                onPointerMove={tilt.onMove}
                                onPointerLeave={tilt.onLeave}
                                initial={reduced ? false : { opacity: 0, y: 28, scale: 0.98 }}
                                animate={reduced ? {} : { opacity: 1, y: 0, scale: 1 }}
                                transition={{ duration: 0.75, delay: 0.12, ease: smooth }}
                                style={{
                                    perspective: 1100,
                                }}
                            >
                                <motion.div
                                    className="preview-stack"
                                    style={{
                                        rotateX: tilt.rotateX,
                                        rotateY: tilt.rotateY,
                                        transformStyle: 'preserve-3d',
                                    }}
                                    whileHover={reduced ? {} : { y: -4 }}
                                    transition={springHover}
                                >
                                    <motion.div
                                        className="preview-main-card"
                                        animate={
                                            reduced
                                                ? {}
                                                : {
                                                      y: [0, -3, 0],
                                                  }
                                        }
                                        transition={
                                            reduced
                                                ? undefined
                                                : {
                                                      duration: 5,
                                                      repeat: Infinity,
                                                      ease: 'easeInOut',
                                                  }
                                        }
                                    >
                                        <p className="preview-label">{translations.hero_preview_title}</p>
                                        <div className="preview-score">
                                            <span>4.9</span>
                                            <small>★★★★★</small>
                                        </div>
                                        <div className="preview-bars">
                                            <div>
                                                <span>5</span>
                                                <i style={{ width: '88%' }} />
                                            </div>
                                            <div>
                                                <span>4</span>
                                                <i style={{ width: '58%' }} />
                                            </div>
                                            <div>
                                                <span>3</span>
                                                <i style={{ width: '24%' }} />
                                            </div>
                                        </div>
                                    </motion.div>
                                    <div className="preview-mini-grid">
                                        <motion.div
                                            className="preview-mini-card"
                                            whileHover={hoverCard}
                                            whileTap={tapSm}
                                            transition={springHover}
                                        >
                                            <motion.i
                                                className="fas fa-shield-alt"
                                                whileHover={reduced ? {} : { scale: 1.08 }}
                                                transition={springHover}
                                            />
                                            <div>
                                                <strong>{translations.hero_preview_filter_title}</strong>
                                                <span>{translations.hero_preview_filter_desc}</span>
                                            </div>
                                        </motion.div>
                                        <motion.div
                                            className="preview-mini-card"
                                            whileHover={hoverCard}
                                            whileTap={tapSm}
                                            transition={springHover}
                                        >
                                            <motion.i
                                                className="fas fa-bell"
                                                whileHover={reduced ? {} : { rotate: [0, -14, 14, 0] }}
                                                transition={{ duration: 0.5 }}
                                            />
                                            <div>
                                                <strong>{translations.hero_preview_alert_title}</strong>
                                                <span>{translations.hero_preview_alert_desc}</span>
                                            </div>
                                        </motion.div>
                                    </div>
                                </motion.div>
                            </motion.div>
                        </div>
                    </div>
                </motion.section>

                <motion.section
                    className="stats home-modern-stats"
                    initial={{ opacity: 0, y: 24 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: true, margin: '-60px', amount: 0.2 }}
                    transition={{ duration: 0.55, ease: smooth }}
                >
                    <motion.div
                        className="stats-container home-modern-stats-inner"
                        variants={listContainer}
                        initial="hidden"
                        whileInView="show"
                        viewport={{ once: true, amount: 0.25 }}
                    >
                        {stats.map((item) => (
                            <motion.div
                                key={item.label}
                                className="stat-card home-modern-stat-card"
                                variants={cardItem}
                                whileHover={hoverCard}
                                whileTap={tapSm}
                                transition={springHover}
                            >
                                <div className="stat-info">
                                    <h3>{item.label}</h3>
                                    <p>{item.value}</p>
                                </div>
                                <motion.div
                                    className="stat-icon"
                                    whileHover={reduced ? {} : { scale: 1.06, rotate: -4 }}
                                    transition={springHover}
                                >
                                    <i className={item.icon} />
                                </motion.div>
                            </motion.div>
                        ))}
                    </motion.div>
                </motion.section>

                <motion.section
                    id="features"
                    className="features home-modern-features"
                    variants={sectionShow}
                    initial="hidden"
                    whileInView="show"
                    viewport={{ once: true, margin: '-40px', amount: 0.12 }}
                >
                    <motion.div
                        className="section-title home-modern-section-head"
                        initial={reduced ? false : { opacity: 0, y: 14 }}
                        whileInView={reduced ? {} : { opacity: 1, y: 0 }}
                        viewport={{ once: true, amount: 0.9 }}
                        transition={{ duration: 0.5, ease: smooth }}
                    >
                        <h2>{translations.features_title}</h2>
                        <p>{translations.features_description}</p>
                    </motion.div>

                    <motion.div
                        className="features-grid"
                        variants={listContainer}
                        initial="hidden"
                        whileInView="show"
                        viewport={{ once: true, amount: 0.08 }}
                    >
                        {features.map((feature) => (
                            <motion.article
                                key={feature.title}
                                className="feature-card home-modern-card"
                                variants={cardItem}
                                whileHover={hoverCard}
                                whileTap={tapSm}
                                transition={springHover}
                            >
                                <motion.div
                                    className="feature-icon"
                                    whileHover={reduced ? {} : { scale: 1.06 }}
                                    transition={springHover}
                                >
                                    <i className={feature.icon} />
                                </motion.div>
                                <h3>{feature.title}</h3>
                                <p>{feature.description}</p>
                            </motion.article>
                        ))}
                    </motion.div>
                </motion.section>

                <motion.section
                    className="benefits home-modern-benefits"
                    variants={sectionShow}
                    initial="hidden"
                    whileInView="show"
                    viewport={{ once: true, margin: '-40px', amount: 0.12 }}
                >
                    <motion.div
                        className="section-title home-modern-section-head"
                        initial={reduced ? false : { opacity: 0, y: 14 }}
                        whileInView={reduced ? {} : { opacity: 1, y: 0 }}
                        viewport={{ once: true, amount: 0.9 }}
                        transition={{ duration: 0.5, ease: smooth }}
                    >
                        <h2>{translations.benefits_title}</h2>
                        <p>{translations.benefits_description}</p>
                    </motion.div>

                    <motion.div
                        className="benefits-grid"
                        variants={listContainer}
                        initial="hidden"
                        whileInView="show"
                        viewport={{ once: true, amount: 0.08 }}
                    >
                        {benefits.map((benefit) => (
                            <motion.article
                                key={benefit.title}
                                className="benefit-card home-modern-card"
                                variants={cardItem}
                                whileHover={hoverCard}
                                whileTap={tapSm}
                                transition={springHover}
                            >
                                <motion.div
                                    className="benefit-icon"
                                    whileHover={reduced ? {} : { scale: 1.05 }}
                                    transition={springHover}
                                >
                                    <i className={benefit.icon} />
                                </motion.div>
                                <div className="benefit-content">
                                    <h4>{benefit.title}</h4>
                                    <p>{benefit.description}</p>
                                </div>
                            </motion.article>
                        ))}
                    </motion.div>
                </motion.section>

                <motion.section
                    className="cta home-modern-cta"
                    variants={sectionShow}
                    initial="hidden"
                    whileInView="show"
                    viewport={{ once: true, margin: '-80px', amount: 0.35 }}
                >
                    <motion.div
                        className="home-modern-container home-modern-cta-inner"
                        initial={reduced ? false : { opacity: 0, y: 18 }}
                        whileInView={reduced ? {} : { opacity: 1, y: 0 }}
                        viewport={{ once: true }}
                        transition={{ duration: 0.55, ease: smooth }}
                    >
                        <motion.span
                            className="home-modern-cta-glow"
                            aria-hidden
                            animate={
                                reduced
                                    ? {}
                                    : {
                                          opacity: [0.35, 0.55, 0.35],
                                          scale: [1, 1.02, 1],
                                      }
                            }
                            transition={{ duration: 4, repeat: Infinity, ease: 'easeInOut' }}
                        />
                        <h2>{translations.cta_title}</h2>
                        <p>{translations.cta_description}</p>
                        <motion.button
                            type="button"
                            className="btn-primary home-modern-cta-btn"
                            whileHover={
                                reduced
                                    ? {}
                                    : {
                                          y: -4,
                                          scale: 1.02,
                                          boxShadow: '0 14px 36px rgba(0, 0, 0, 0.18)',
                                      }
                            }
                            whileTap={tapSm}
                            transition={springHover}
                            onClick={onOpenContact}
                        >
                            <i className="fas fa-star" />
                            {translations.start_free}
                        </motion.button>
                    </motion.div>
                </motion.section>
            </div>
        </MotionConfig>
    );
}

export default HomeModernSection;
