// resources/js/components/DeviceStatusCard.test.tsx

// @vitest-environment jsdom
import * as echoReact from '@laravel/echo-react';
import { render, screen, act } from '@testing-library/react';
import { describe, it, expect, vi, beforeEach } from 'vitest';
import { DeviceStatusCard } from './DeviceStatusCard';

// Mock the Echo hook
vi.mock('@laravel/echo-react', () => ({
    useEchoPublic: vi.fn(),
    useEcho: vi.fn(),
}));

describe('DeviceStatusCard', () => {
    const initialData = {
        id: 1,
        name: 'Kitchen Sink',
        model: 'Wtr-100',
        status: {
            has_alert: false,
            is_online: true,
        },
        telemetry: {
            battery_days: 100,
            sensor_days: 100,
        },
        subscription: {
            plan_name: 'Premium',
            is_active: true,
        },
        replacement: {
            status: 'none',
            delivery_date: null,
        },
    };

    beforeEach(() => {
        vi.clearAllMocks();
    });

    it('renders initial data correctly', () => {
        render(<DeviceStatusCard initialData={initialData} />);

        expect(screen.getByText('Kitchen Sink')).toBeInTheDocument();
        expect(screen.getByText('Wtr-100')).toBeInTheDocument();
    });

    it('updates when a telemetry event is received', async () => {
        let capturedCallback: (event: any) => void = () => {};

        (echoReact.useEchoPublic as any).mockImplementation(
            (channel: string, events: string[], callback: (e: any) => void) => {
                capturedCallback = callback;
            },
        );

        render(<DeviceStatusCard initialData={initialData} />);

        // Simulate a telemetry event
        await act(async () => {
            capturedCallback({
                id: 1,
                battery_days_remaining: 5,
                sensor_days_remaining: 80,
            });
        });

        expect(screen.getByText('5 days')).toBeInTheDocument();
        expect(screen.getByText('80 days')).toBeInTheDocument();
    });
});
