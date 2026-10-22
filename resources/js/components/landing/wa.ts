export const WA_NUMBER = '6283184683492';

export function waLink(message: string): string {
    return `https://wa.me/${WA_NUMBER}?text=${encodeURIComponent(message)}`;
}
