import { ReactNode } from 'react';

import Navbar from '@/components/public/Navbar';
import Footer from '@/components/public/Footer';

interface PublicLayoutProps {
    children: ReactNode;
    settings: Record<string, string | null | undefined>;
}

export default function PublicLayout({
    children,
    settings,
}: PublicLayoutProps) {

    const logo = settings.logo
        ? (
            settings.logo.startsWith('http')
                ? settings.logo
                : settings.logo.startsWith('/storage/')
                    ? settings.logo
                    : `/${settings.logo.replace(/^\/+/, '')}`
        )
        : null;


    return (
        <div className="min-h-screen bg-white">

            <Navbar
                logo={logo}
                siteName={
                    settings.site_name ||
                    'Dusun Semilir'
                }
            />

            <main>
                {children}
            </main>

            <Footer
                settings={settings}
            />

        </div>
    );
}