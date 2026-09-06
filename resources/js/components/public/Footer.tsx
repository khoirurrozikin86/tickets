interface FooterProps {
    settings: Record<string, string | null | undefined>;
}

export default function Footer({
    settings,
}: FooterProps) {

    return (
        <footer className="bg-emerald-950 text-white">

            <div className="mx-auto max-w-7xl px-6 py-14 lg:px-8">

                <div className="grid gap-10 md:grid-cols-3">

                    {/* Brand */}
                    <div>

                        <h3 className="text-xl font-bold">
                            {settings.site_name || 'Dusun Semilir'}
                        </h3>

                        <p className="mt-3 max-w-sm text-sm leading-6 text-white/60">
                            {settings.site_tagline ||
                                'Liburan lebih seru bersama OSIL!'}
                        </p>

                    </div>


                    {/* Contact */}
                    <div>

                        <h4 className="font-semibold">
                            Kontak
                        </h4>

                        <div className="mt-4 space-y-2 text-sm text-white/60">

                            {settings.email && (
                                <p>
                                    {settings.email}
                                </p>
                            )}

                            {settings.phone && (
                                <p>
                                    {settings.phone}
                                </p>
                            )}

                            {settings.address && (
                                <p className="leading-6">
                                    {settings.address}
                                </p>
                            )}

                        </div>

                    </div>


                    {/* Social */}
                    <div>

                        <h4 className="font-semibold">
                            Ikuti Kami
                        </h4>

                        <div className="mt-4 flex flex-wrap gap-3">

                            {settings.instagram && (
                                <a
                                    href={settings.instagram}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="rounded-xl bg-white/10 px-4 py-2 text-sm transition hover:bg-white/20"
                                >
                                    Instagram
                                </a>
                            )}

                            {settings.facebook && (
                                <a
                                    href={settings.facebook}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="rounded-xl bg-white/10 px-4 py-2 text-sm transition hover:bg-white/20"
                                >
                                    Facebook
                                </a>
                            )}

                            {settings.tiktok && (
                                <a
                                    href={settings.tiktok}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="rounded-xl bg-white/10 px-4 py-2 text-sm transition hover:bg-white/20"
                                >
                                    TikTok
                                </a>
                            )}

                            {settings.youtube && (
                                <a
                                    href={settings.youtube}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="rounded-xl bg-white/10 px-4 py-2 text-sm transition hover:bg-white/20"
                                >
                                    YouTube
                                </a>
                            )}

                        </div>

                    </div>

                </div>


                <div className="mt-12 border-t border-white/10 pt-6">

                    <p className="text-center text-xs text-white/40">

                        {settings.copyright ||
                            '© 2026 Dusun Semilir. All rights reserved.'}

                    </p>

                </div>

            </div>

        </footer>
    );
}