import { Head } from '@inertiajs/react';
import { DeviceStatusCard } from '@/components/DeviceStatusCard';

export default function Welcome({ deviceData }: { deviceData: any }) {
    return (
        <>
            <Head title="Welcome">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link
                    href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600"
                    rel="stylesheet"
                />
            </Head>
            <div className="flex min-h-screen flex-col items-center bg-[#0a0a0a] p-6 text-[#EDEDEC] lg:justify-center lg:p-8">
                <div className="flex w-full items-center justify-center opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0">
                    <main className="flex w-full max-w-4xl flex-col items-center gap-8">
                        <div className="mb-4 text-center">
                            <h1 className="mb-2 text-3xl font-bold text-white">
                                Device Status Dashboard
                            </h1>
                            <p className="text-lg text-gray-400">
                                Real-time telemetry monitoring for Intellisonde
                                DI devices
                            </p>
                        </div>

                        {deviceData ? (
                            <DeviceStatusCard initialData={deviceData} />
                        ) : (
                            <div className="rounded-xl border border-gray-800 bg-gray-900 p-12 text-center">
                                <p className="text-gray-400">
                                    No device data available. Run seeders to
                                    populate the database.
                                </p>
                            </div>
                        )}

                        <div className="mt-8 flex gap-6 text-sm text-gray-500">
                            <a
                                href="https://laravel.com/docs"
                                target="_blank"
                                className="transition-colors hover:text-white"
                            >
                                Documentation
                            </a>
                            <a
                                href="https://reverb.laravel.com"
                                target="_blank"
                                className="transition-colors hover:text-white"
                            >
                                Laravel Reverb
                            </a>
                            <a
                                href="https://inertiajs.com"
                                target="_blank"
                                className="transition-colors hover:text-white"
                            >
                                Inertia.js
                            </a>
                        </div>
                    </main>
                </div>
            </div>
        </>
    );
}
