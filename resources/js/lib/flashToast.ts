import { router } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';

export function initializeFlashToast(): void {
    router.on('navigate', (event) => {
        const flash = (event.detail.page.props as { flash?: { success?: string; error?: string } }).flash;

        if (flash?.success) {
            toast.success(flash.success, { duration: 5000 });
        }

        if (flash?.error) {
            toast.error(flash.error, { duration: 5000 });
        }
    });
}
