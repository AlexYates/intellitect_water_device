// @vitest-environment jsdom
import { render, screen } from '@testing-library/react';
import { describe, it, expect } from 'vitest';
import { TelemetryBar } from './TelemetryBar';

describe('TelemetryBar', () => {
    it('renders the label and days remaining', () => {
        render(
            <TelemetryBar
                label="Battery charge"
                daysRemaining={100}
                maxDays={365}
            />,
        );
        expect(screen.getByText('Battery charge')).toBeInTheDocument();
        expect(screen.getByText('100 days')).toBeInTheDocument();
    });

    it('calculates the correct width percentage', () => {
        const { container } = render(
            <TelemetryBar label="Test" daysRemaining={180} maxDays={360} />,
        );
        const progressLine = container.querySelector(
            'div[style*="width: 50%"]',
        );
        expect(progressLine).toBeInTheDocument();
    });

    it('always uses alert colors', () => {
        const { container } = render(
            <TelemetryBar label="Test" daysRemaining={10} maxDays={365} />,
        );
        // Alert color for bar is bg-rose-500
        const bar = container.querySelector('.bg-rose-500');
        expect(bar).toBeInTheDocument();
    });
});
