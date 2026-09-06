export function formatRupiah(
    value: number | string
): string {

    return new Intl.NumberFormat(
        'id-ID',
        {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }
    ).format(Number(value));
}


export function formatNumber(
    value: number | string
): string {

    return new Intl.NumberFormat(
        'id-ID'
    ).format(Number(value));
}