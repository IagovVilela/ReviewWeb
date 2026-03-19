import React from 'react';
import { motion, useReducedMotion } from 'framer-motion';

const sectionReveal = {
    hidden: { opacity: 0, y: 48, scale: 0.985 },
    show: {
        opacity: 1,
        y: 0,
        scale: 1,
        transition: { duration: 0.85, ease: [0.16, 1, 0.3, 1] },
    },
};

const gridReveal = {
    hidden: {},
    show: {
        transition: {
            staggerChildren: 0.08,
            delayChildren: 0.1,
        },
    },
};

const cardReveal = {
    hidden: { opacity: 0, y: 30, scale: 0.97 },
    show: {
        opacity: 1,
        y: 0,
        scale: 1,
        transition: { duration: 0.55, ease: [0.16, 1, 0.3, 1] },
    },
};

function HomeModernSection({ translations, stats, onOpenContact }) {
    const prefersReducedMotion = useReducedMotion();
    const hoverAnimation = prefersReducedMotion
        ? {}
        : {
              y: -10,
              scale: 1.02,
              transition: { type: 'spring', stiffness: 260, damping: 18 },
          };

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
        <div className="home-modern">
            <motion.section
                className="home-modern-hero"
                variants={sectionReveal}
                initial="hidden"
                animate="show"
                viewport={{ once: true, amount: 0.3 }}
            >
                <div className="home-modern-container">
                    <div className="home-modern-hero-glow" />
                    <div className="home-modern-hero-grid">
                        <motion.div
                            className="home-modern-hero-copy"
                            initial={prefersReducedMotion ? false : { opacity: 0, y: 30, x: -24 }}
                            animate={prefersReducedMotion ? {} : { opacity: 1, y: 0, x: 0 }}
                            transition={{ duration: 0.75, delay: 0.05, ease: [0.16, 1, 0.3, 1] }}
                        >
                            <span className="home-modern-kicker">{translations.hero_kicker}</span>
                            <h1>{translations.hero_title}</h1>
                            <p>{translations.hero_description}</p>
                            <div className="hero-buttons">
                                <motion.button
                                    type="button"
                                    className="btn-primary"
                                    whileHover={hoverAnimation}
                                    whileTap={prefersReducedMotion ? {} : { scale: 0.98 }}
                                    animate={
                                        prefersReducedMotion
                                            ? {}
                                            : {
                                                  boxShadow: [
                                                      '0 0 0 rgba(139, 92, 246, 0)',
                                                      '0 12px 32px rgba(139, 92, 246, 0.26)',
                                                      '0 0 0 rgba(139, 92, 246, 0)',
                                                  ],
                                              }
                                    }
                                    transition={
                                        prefersReducedMotion
                                            ? undefined
                                            : { boxShadow: { duration: 2.4, repeat: Infinity, ease: 'easeInOut' } }
                                    }
                                    onClick={onOpenContact}
                                >
                                    <i className="fas fa-rocket" />
                                    {translations.start_now}
                                </motion.button>
                                <motion.a
                                    href="#como-funciona"
                                    className="btn-secondary"
                                    whileHover={hoverAnimation}
                                    whileTap={prefersReducedMotion ? {} : { scale: 0.98 }}
                                >
                                    <i className="fas fa-play-circle" />
                                    {translations.learn_more}
                                </motion.a>
                            </div>
                            <div className="home-modern-inline-stats">
                                {stats.map((item) => (
                                    <div key={`hero-${item.label}`} className="home-modern-inline-stat">
                                        <strong>{item.value}</strong>
                                        <span>{item.label}</span>
                                    </div>
                                ))}
                                <div className="home-modern-inline-stat">
                                    <strong>100%</strong>
                                    <span>{translations.hero_control_total}</span>
                                </div>
                            </div>
                        </motion.div>
                        <motion.div
                            className="home-modern-preview"
                            initial={prefersReducedMotion ? false : { opacity: 0, y: 36, x: 20 }}
                            animate={prefersReducedMotion ? {} : { opacity: 1, y: 0, x: 0 }}
                            transition={{ duration: 0.8, delay: 0.2, ease: [0.16, 1, 0.3, 1] }}
                            whileHover={prefersReducedMotion ? {} : { y: -8, transition: { type: 'spring', stiffness: 220, damping: 20 } }}
                        >
                            <motion.div
                                className="preview-main-card"
                                animate={
                                    prefersReducedMotion
                                        ? {}
                                        : {
                                              y: [0, -5, 0],
                                          }
                                }
                                transition={
                                    prefersReducedMotion
                                        ? undefined
                                        : {
                                              duration: 3.6,
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
                                    <div><span>5</span><i style={{ width: '88%' }} /></div>
                                    <div><span>4</span><i style={{ width: '58%' }} /></div>
                                    <div><span>3</span><i style={{ width: '24%' }} /></div>
                                </div>
                            </motion.div>
                            <div className="preview-mini-grid">
                                <motion.div
                                    className="preview-mini-card"
                                    whileHover={hoverAnimation}
                                    animate={prefersReducedMotion ? {} : { opacity: [0.9, 1, 0.9] }}
                                    transition={prefersReducedMotion ? undefined : { duration: 2.2, repeat: Infinity, ease: 'easeInOut' }}
                                >
                                    <i className="fas fa-shield-alt" />
                                    <div>
                                        <strong>{translations.hero_preview_filter_title}</strong>
                                        <span>{translations.hero_preview_filter_desc}</span>
                                    </div>
                                </motion.div>
                                <motion.div
                                    className="preview-mini-card"
                                    whileHover={hoverAnimation}
                                    animate={prefersReducedMotion ? {} : { opacity: [1, 0.86, 1] }}
                                    transition={prefersReducedMotion ? undefined : { duration: 2.6, repeat: Infinity, ease: 'easeInOut', delay: 0.5 }}
                                >
                                    <i className="fas fa-bell" />
                                    <div>
                                        <strong>{translations.hero_preview_alert_title}</strong>
                                        <span>{translations.hero_preview_alert_desc}</span>
                                    </div>
                                </motion.div>
                            </div>
                        </motion.div>
                    </div>
                </div>
            </motion.section>

            <motion.section
                className="stats home-modern-stats"
                variants={sectionReveal}
                initial="hidden"
                whileInView="show"
                viewport={{ once: true, amount: 0.2 }}
            >
                <div className="stats-container">
                    {stats.map((item) => (
                        <motion.div key={item.label} className="stat-card" whileHover={hoverAnimation}>
                            <div className="stat-info">
                                <h3>{item.label}</h3>
                                <p>{item.value}</p>
                            </div>
                            <div className="stat-icon">
                                <i className={item.icon} />
                            </div>
                        </motion.div>
                    ))}
                </div>
            </motion.section>

            <motion.section
                className="features home-modern-features"
                variants={sectionReveal}
                initial="hidden"
                whileInView="show"
                viewport={{ once: true, amount: 0.18 }}
            >
                <div className="section-title">
                    <h2>{translations.features_title}</h2>
                    <p>{translations.features_description}</p>
                </div>

                <motion.div
                    className="features-grid"
                    variants={gridReveal}
                    initial="hidden"
                    whileInView="show"
                    viewport={{ once: true, amount: 0.1 }}
                >
                    {features.map((feature) => (
                        <motion.article
                            key={feature.title}
                            className="feature-card home-modern-card"
                            variants={cardReveal}
                            whileHover={hoverAnimation}
                        >
                            <div className="feature-icon">
                                <i className={feature.icon} />
                            </div>
                            <h3>{feature.title}</h3>
                            <p>{feature.description}</p>
                        </motion.article>
                    ))}
                </motion.div>
            </motion.section>

            <motion.section
                className="benefits home-modern-benefits"
                variants={sectionReveal}
                initial="hidden"
                whileInView="show"
                viewport={{ once: true, amount: 0.15 }}
            >
                <div className="section-title">
                    <h2>{translations.benefits_title}</h2>
                    <p>{translations.benefits_description}</p>
                </div>

                <motion.div
                    className="benefits-grid"
                    variants={gridReveal}
                    initial="hidden"
                    whileInView="show"
                    viewport={{ once: true, amount: 0.1 }}
                >
                    {benefits.map((benefit) => (
                        <motion.article
                            key={benefit.title}
                            className="benefit-card home-modern-card"
                            variants={cardReveal}
                            whileHover={hoverAnimation}
                        >
                            <div className="benefit-icon">
                                <i className={benefit.icon} />
                            </div>
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
                variants={sectionReveal}
                initial="hidden"
                whileInView="show"
                viewport={{ once: true, amount: 0.2 }}
            >
                <div className="home-modern-container">
                    <h2>{translations.cta_title}</h2>
                    <p>{translations.cta_description}</p>
                    <motion.button
                        type="button"
                        className="btn-primary"
                        whileHover={hoverAnimation}
                        whileTap={prefersReducedMotion ? {} : { scale: 0.98 }}
                        onClick={onOpenContact}
                    >
                        <i className="fas fa-star" />
                        {translations.start_free}
                    </motion.button>
                </div>
            </motion.section>
        </div>
    );
}

export default HomeModernSection;
