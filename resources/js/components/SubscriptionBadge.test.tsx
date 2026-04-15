// resources/js/components/SubscriptionBadge.test.tsx

// @vitest-environment jsdom
import { render, screen } from '@testing-library/react';
import { describe, it, expect } from 'vitest';
import { SubscriptionBadge } from './SubscriptionBadge';

describe('SubscriptionBadge', () => {
    it('renders "Subscribed" when subscribed', () => {
        render(<SubscriptionBadge isSubscribed={true} />);
        expect(screen.getByText('Subscribed')).toBeInTheDocument();
    });

    it('renders "Not subscribed" when not subscribed', () => {
        render(<SubscriptionBadge isSubscribed={false} />);
        expect(screen.getByText('Not subscribed')).toBeInTheDocument();
    });
});
