// TelemetryBar.stories.tsx

import type { Meta, StoryObj } from '@storybook/react-vite';
import { TelemetryBar } from './TelemetryBar';

const meta: Meta<typeof TelemetryBar> = {
    title: 'Widget/TelemetryBar',
    component: TelemetryBar,
    parameters: {
        backgrounds: {
            default: 'dark',
            values: [{ name: 'dark', value: '#1E1E2E' }],
        },
    },
    decorators: [
        (Story) => (
            <div className="w-[400px] rounded-lg bg-[#232336] p-4">
                <Story />
            </div>
        ),
    ],
};

export default meta;
type Story = StoryObj<typeof TelemetryBar>;

export const HealthyBattery: Story = {
    args: {
        label: 'Battery charge',
        daysRemaining: 79,
        maxDays: 100,
    },
};

export const CriticalSensor: Story = {
    args: {
        label: 'Sensor pack',
        daysRemaining: 2,
        maxDays: 100,
    },
};
