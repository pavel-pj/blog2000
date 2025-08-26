import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';

export default function useConfirmer() {
  const confirm = useConfirm();
  const toast = useToast();

  return (options: {
    message: string;
    accept: () => Promise<void> | void;
    header?: string;
    successMessage?: string;
  }) => {
    confirm.require({
      header: options.header || 'Danger Zone',
      message: options.message,
      icon: 'pi pi-exclamation-triangle',
      rejectLabel: 'Cancel',
      acceptLabel: 'Delete',
      rejectProps: {
        severity: 'secondary',
        outlined: true
      },
      acceptProps: {
        severity: 'danger',
        icon: 'pi pi-trash'
      },
      accept: async () => {
        try {
          await options.accept();
          toast.add({
            severity: 'success',
            summary: 'Confirmed',
            detail: options.successMessage || 'Record deleted',
            life: 3000
          });
        } catch (error) {
          toast.add({
            severity: 'error',
            summary: 'Error',
            detail: error instanceof Error ? error.message : 'You have rejected',
            life: 3000
          });
        }
      }
    });
  };
}