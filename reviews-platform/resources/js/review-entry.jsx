import React, { useState } from 'react';
import { createRoot } from 'react-dom/client';
import { motion } from 'framer-motion';
import { Star } from 'lucide-react';

function StarRating({ max = 5, onChange }) {
    const [value, setValue] = useState(0);
    const [hover, setHover] = useState(0);

    const handleSelect = (rating) => {
        setValue(rating);
        onChange(rating);
    };

    return (
        <div className="flex justify-center gap-2 sm:gap-3" role="group" aria-label="Rating">
            {Array.from({ length: max }, (_, i) => {
                const rating = i + 1;
                const filled = rating <= (hover || value);
                return (
                    <motion.button
                        key={rating}
                        type="button"
                        className="rounded-full p-1 text-amber-400 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-accent/40"
                        whileHover={{ scale: 1.08 }}
                        whileTap={{ scale: 0.95 }}
                        onMouseEnter={() => setHover(rating)}
                        onMouseLeave={() => setHover(0)}
                        onClick={() => handleSelect(rating)}
                        aria-label={`${rating} stars`}
                    >
                        <Star
                            size={36}
                            className={`sm:h-10 sm:w-10 ${filled ? 'fill-amber-400 text-amber-400' : 'text-neutral-300'}`}
                            strokeWidth={1.5}
                        />
                    </motion.button>
                );
            })}
        </div>
    );
}

function mountReviewStars() {
    const rootEl = document.getElementById('reviewStarsRoot');
    const hiddenInput = document.getElementById('rating');
    if (!rootEl || !hiddenInput) return;

    const root = createRoot(rootEl);
    root.render(
        <StarRating
            onChange={(rating) => {
                hiddenInput.value = String(rating);
                if (window.reviewSystemInstance?.selectRating) {
                    window.reviewSystemInstance.selectRating(rating);
                }
            }}
        />
    );
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', mountReviewStars);
} else {
    mountReviewStars();
}
