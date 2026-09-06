import { Link } from '@inertiajs/react';

interface ProductPrice {
    id: number;
    product_id: number;
    day_type: 'WEEKDAY' | 'WEEKEND' | 'HOLIDAY';
    price: string | number;
}

export interface Product {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    image: string | null;
    is_active: boolean;
    sort_order: number;
    prices: ProductPrice[];
}

interface ProductCardProps {
    product: Product;
}

const formatPrice = (price: string | number) => {
    return new Intl.NumberFormat('id-ID').format(
        Number(price)
    );
};

export default function ProductCard({
    product,
}: ProductCardProps) {

    const weekday =
        product.prices.find(
            (price) => price.day_type === 'WEEKDAY'
        );

    const weekend =
        product.prices.find(
            (price) => price.day_type === 'WEEKEND'
        );


    return (
        <div className="group overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-lg shadow-emerald-950/5 transition duration-300 hover:-translate-y-2 hover:shadow-2xl">

            {/* Image */}
            <div className="relative h-56 overflow-hidden bg-emerald-50">

                {product.image ? (

                    <img
                        src={product.image}
                        alt={product.name}
                        className="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                    />

                ) : (

                    <div className="flex h-full items-center justify-center text-emerald-300">
                        No Image
                    </div>

                )}

            </div>


            {/* Content */}
            <div className="p-6">

                <h3 className="text-xl font-bold text-gray-900">
                    Tiket {product.name}
                </h3>


                {product.description && (
                    <p className="mt-2 line-clamp-2 text-sm leading-6 text-gray-500">
                        {product.description}
                    </p>
                )}


                {/* Price */}
                <div className="mt-5 rounded-2xl bg-emerald-50 p-4">

                    <div className="flex items-center justify-between">

                        <span className="text-xs font-medium text-gray-500">
                            Weekday
                        </span>

                        <span className="font-bold text-emerald-800">
                            Rp {weekday
                                ? formatPrice(weekday.price)
                                : '-'}
                        </span>

                    </div>


                    <div className="mt-2 flex items-center justify-between">

                        <span className="text-xs font-medium text-gray-500">
                            Weekend
                        </span>

                        <span className="font-bold text-emerald-800">
                            Rp {weekend
                                ? formatPrice(weekend.price)
                                : '-'}
                        </span>

                    </div>

                </div>


                {/* Button */}
                <Link
                    href={`/tickets/${product.slug}`}
                    className="mt-5 flex w-full items-center justify-center rounded-2xl bg-emerald-700 px-5 py-3.5 text-sm font-bold text-white transition hover:bg-emerald-800"
                >
                    Lihat Tiket
                </Link>

            </div>

        </div>
    );
}