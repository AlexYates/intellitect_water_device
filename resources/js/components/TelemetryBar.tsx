// src/components/TelemetryBar.tsx

import React from 'react';

interface TelemetryBarProps {
    label: string;
    daysRemaining: number;
    maxDays?: number; // Used to calculate progress width, default 100
}

export const TelemetryBar: React.FC<TelemetryBarProps> = ({
    label,
    daysRemaining,
    maxDays = 100,
}) => {
    // Calculate width, cap at 100%
    const percentage = Math.min((daysRemaining / maxDays) * 100, 100);

    return (
        <div className="flex w-full flex-col text-sm">
            <div className="mb-2 flex">
                <div className="w-2/3 font-semibold text-white">{label}</div>
                <div className="w-1/3 pl-2 text-right text-gray-300">
                    {daysRemaining} days
                </div>
            </div>
            <div className="h-2 overflow-hidden rounded-full bg-black">
                <div
                    className="h-full rounded-full bg-rose-500 transition-all duration-2500"
                    style={{ width: `${percentage}%` }}
                    role="progressbar"
                    aria-valuenow={daysRemaining}
                    aria-valuemin={0}
                    aria-valuemax={maxDays}
                />
            </div>
        </div>
    );
};
