// src/components/SubscriptionBadge.stories.tsx

import type { Meta, StoryObj } from '@storybook/react-vite';
import { SubscriptionBadge } from './SubscriptionBadge';

const meta: Meta<typeof SubscriptionBadge> = {
    title: 'Widget/SubscriptionBadge',
    component: SubscriptionBadge,
    parameters: {
        backgrounds: {
            default: 'dark',
            values: [{ name: 'dark', value: '#1E1E2E' }],
        },
    },
};

export default meta;
type Story = StoryObj<typeof SubscriptionBadge>;

export const Subscribed: Story = {
    args: {
        isSubscribed: true,
    },
};

export const NotSubscribed: Story = {
    args: {
        isSubscribed: false,
    },
};
