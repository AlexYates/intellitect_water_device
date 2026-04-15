import { faCheck } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import React from 'react';

interface SubscriptionBadgeProps {
    isSubscribed: boolean;
}

export const SubscriptionBadge: React.FC<SubscriptionBadgeProps> = ({
    isSubscribed,
}) => {
    if (isSubscribed) {
        return (
            <div className="inline-flex items-center gap-1.5 rounded border border-[#8dcc24] bg-gray-800/50 px-3 py-1 text-xs font-medium text-gray-200">
                <FontAwesomeIcon
                    icon={faCheck}
                    className="text-[10px] text-[#A78BFA]"
                />
                Subscribed
            </div>
        );
    }

    return (
        <div className="inline-flex items-center rounded border border-gray-600 bg-transparent px-3 py-1 text-xs font-medium text-gray-400">
            Not subscribed
        </div>
    );
};
