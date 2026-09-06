import HeroSlider, {
    Banner,
} from '@/components/public/HeroSlider';

import ProductCard, {
    Product,
} from '@/components/public/ProductCard';

import PublicLayout from '@/layouts/PublicLayout';

interface HomeProps {
    banners: Banner[];

    products: Product[];

    settings: Record<
        string,
        string | null | undefined
    >;
}


export default function Home({
    banners,
    products,
    settings,
}: HomeProps) {

    return (
        <PublicLayout
            settings={settings}
        >

            {/* HERO */}
            <HeroSlider
                banners={banners}
            />


            {/* PRODUCTS */}
            <section className="relative overflow-hidden bg-white py-20">

                {/* Decorative */}
                <div className="pointer-events-none absolute -left-24 top-20 h-72 w-72 rounded-full bg-emerald-100/50 blur-3xl" />

                <div className="pointer-events-none absolute -right-24 bottom-10 h-72 w-72 rounded-full bg-orange-100/50 blur-3xl" />


                <div className="relative mx-auto max-w-7xl px-6 lg:px-8">

                    <div className="mx-auto max-w-2xl text-center">

                        <span className="text-sm font-bold uppercase tracking-[0.2em] text-emerald-700">
                            Ticket Dusem
                        </span>

                        <h2 className="mt-3 text-3xl font-black tracking-tight text-gray-900 sm:text-4xl">
                            Pilih Tiketmu
                        </h2>

                        <p className="mt-4 text-sm leading-6 text-gray-500 sm:text-base">
                            Pilih tiket yang sesuai dengan kebutuhan
                            liburanmu bersama keluarga dan orang tersayang.
                        </p>

                    </div>


                    {/* Cards */}
                    <div className="mt-12 grid gap-7 md:grid-cols-2 lg:grid-cols-3">

                        {products.map((product) => (

                            <ProductCard
                                key={product.id}
                                product={product}
                            />

                        ))}

                    </div>


                    {/* Empty */}
                    {products.length === 0 && (

                        <div className="mt-10 rounded-3xl bg-emerald-50 p-10 text-center">

                            <p className="text-sm text-emerald-800">
                                Tiket belum tersedia.
                            </p>

                        </div>

                    )}

                </div>

            </section>


            {/* CTA */}
            <section className="bg-emerald-900 py-16">

                <div className="mx-auto max-w-4xl px-6 text-center">

                    <h2 className="text-3xl font-black text-white sm:text-4xl">
                        Siap Liburan Bersama Dusun Semilir?
                    </h2>

                    <p className="mx-auto mt-4 max-w-2xl text-sm leading-6 text-white/70">
                        Pesan tiket secara online dan nikmati
                        pengalaman liburan yang lebih mudah.
                    </p>

                    <a
                        href="/tickets"
                        className="mt-7 inline-flex rounded-2xl bg-orange-500 px-7 py-3.5 text-sm font-bold text-white shadow-xl transition hover:-translate-y-1 hover:bg-orange-600"
                    >
                        Beli Tiket Sekarang
                    </a>

                </div>

            </section>

        </PublicLayout>
    );
}