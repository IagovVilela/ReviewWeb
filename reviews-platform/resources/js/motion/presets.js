export const smoothEase = [0.16, 1, 0.3, 1];

export const fadeUp = {
    hidden: { opacity: 0, y: 20 },
    show: {
        opacity: 1,
        y: 0,
        transition: { duration: 0.55, ease: smoothEase },
    },
};

export const sectionReveal = {
    hidden: { opacity: 0, y: 28 },
    show: {
        opacity: 1,
        y: 0,
        transition: { duration: 0.65, ease: smoothEase },
    },
};

export const staggerContainer = {
    hidden: {},
    show: {
        transition: { staggerChildren: 0.06, delayChildren: 0.04 },
    },
};

export const staggerItem = {
    hidden: { opacity: 0, y: 16 },
    show: {
        opacity: 1,
        y: 0,
        transition: { duration: 0.45, ease: smoothEase },
    },
};

export const springHover = {
    type: 'spring',
    stiffness: 400,
    damping: 30,
};

export const hoverLift = {
    y: -3,
    transition: springHover,
};

export const tapScale = { scale: 0.98 };
