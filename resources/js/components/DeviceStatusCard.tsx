// resources/js/components/DeviceStatusCard.tsx

import {
    faTriangleExclamation,
    faBatteryFull,
    faMicrochip,
    faWifi,
} from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { useEchoPublic } from '@laravel/echo-react';
import React, { useState, useEffect } from 'react';
import { SubscriptionBadge } from './SubscriptionBadge';
import { TelemetryBar } from './TelemetryBar';

interface DeviceStatus {
    id: number;
    name: string;
    model: string;
    status: {
        has_alert: boolean;
        is_online: boolean;
    };
    telemetry: {
        battery_days: number;
        sensor_days: number;
    };
    subscription: {
        plan_name: string;
        is_active: boolean;
    };
    replacement: {
        status: string;
        delivery_date: string | null;
    };
}

interface DeviceStatusCardProps {
    initialData: DeviceStatus;
}

/**
 * Inner component that uses Echo hooks.
 * This is only rendered on the client to avoid SSR issues with WebSocket clients.
 */
const RealTimeDeviceStatusCard: React.FC<DeviceStatusCardProps> = ({
    initialData,
}) => {
    const [data, setData] = useState<DeviceStatus>(initialData);

    // Use the declarative hook for public channels
    useEchoPublic(
        'devices',
        ['.telemetry.updated'],
        (event: any) => {
            if (event.id === data.id) {
                setData((prev) => ({
                    ...prev,
                    telemetry: {
                        battery_days: event.battery_days_remaining,
                        sensor_days: event.sensor_days_remaining,
                    },
                    status: {
                        ...prev.status,
                        has_alert:
                            event.battery_days_remaining <= 10 ||
                            event.sensor_days_remaining <= 10,
                    },
                }));
            }
        },
        [data.id],
    );

    return <DeviceStatusUI data={data} />;
};

/**
 * Static UI component shared between SSR and Client
 */
const DeviceStatusUI: React.FC<{ data: DeviceStatus }> = ({ data }) => {
    const isAlert = data.status.has_alert;

    return (
        <div className="${isAlert ? 'border-[#F43F5E]' : 'border-transparent'}`} relative w-full max-w-md overflow-hidden rounded-md border-0 bg-[#20203b] text-white shadow-2xl">
            {/* Top Row: Location and Icons */}
            <div className="flex items-start justify-between p-3">
                <div className="flex items-start gap-4">
                    {isAlert && (
                        <div className="mt-1">
                            <FontAwesomeIcon
                                icon={faTriangleExclamation}
                                className="text-3xl text-[#ec4954]"
                            />
                        </div>
                    )}
                    <div>
                        <h3 className="mb-1 text-lg font-bold tracking-tight">
                            {data.name}
                        </h3>
                        <p className="text-xs font-semibold tracking-wide text-gray-400">
                            {data.model}
                        </p>
                    </div>
                </div>
                <div className="flex gap-2">
                    {/* Battery Icon */}
                    <FontAwesomeIcon
                        icon={faBatteryFull}
                        className="-rotate-90 text-lg"
                        style={{ color: isAlert ? '#ec4954' : '#8dcc24' }}
                    />
                    {/* CPU Icon */}
                    <FontAwesomeIcon
                        icon={faMicrochip}
                        className="text-lg"
                        style={{ color: isAlert ? '#ec4954' : '#8dcc24' }}
                    />
                    {/* Signal Icon */}
                    <FontAwesomeIcon
                        icon={faWifi}
                        className="text-lg"
                        style={{ color: isAlert ? '#ec4954' : '#8dcc24' }}
                    />
                </div>
            </div>

            {/* Telemetry Section */}
            <div className="grid grid-cols-2 gap-x-4 p-3">
                <TelemetryBar
                    label="Battery charge"
                    daysRemaining={data.telemetry.battery_days}
                    maxDays={365}
                />
                <TelemetryBar
                    label="Sensor pack"
                    daysRemaining={data.telemetry.sensor_days}
                    maxDays={180}
                />
            </div>

            {/* Subscription and Replacement */}
            <div className="flex flex-col gap-2 border-t border-gray-800 bg-[#2b2b4e] p-3">
                <div className="flex items-center justify-between">
                    <span className="text-sm font-semibold text-gray-300">
                        {data.subscription.plan_name}
                    </span>
                    <SubscriptionBadge
                        isSubscribed={data.subscription.is_active}
                    />
                </div>
                <hr className="text-gray-700" />
                <div className="flex items-center justify-between">
                    <span className="text-sm font-semibold text-gray-300">
                        Replacement status
                    </span>
                    <span className="text-sm text-gray-400">
                        {data.replacement.status === 'delivered' ? (
                            <span className="border-b border-dotted border-gray-600 pb-0.5">
                                Delivered on {data.replacement.delivery_date}
                            </span>
                        ) : (
                            <button className="rounded-full rounded-sm bg-blue-800 px-3 py-1 text-xs font-bold text-white shadow-sm transition-colors hover:bg-blue-600">
                                Order now
                            </button>
                        )}
                    </span>
                </div>
            </div>
        </div>
    );
};

export const DeviceStatusCard: React.FC<DeviceStatusCardProps> = (props) => {
    const [mounted, setMounted] = useState(false);

    useEffect(() => {
        // eslint-disable-next-line react-hooks/set-state-in-effect
        setMounted(true);
    }, []);

    if (!mounted) {
        return <DeviceStatusUI data={props.initialData} />;
    }

    return <RealTimeDeviceStatusCard {...props} />;
};
