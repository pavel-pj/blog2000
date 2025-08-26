import type { ToastServiceMethods } from 'primevue/toastservice';
//import { useToast } from 'primevue/usetoast';

let toastInstance: ToastServiceMethods | null = null;

export const initToast = (toast: ToastServiceMethods) => {
  toastInstance = toast;
};

export const showToast = (options: {
  severity: 'success' | 'error' | 'warn' | 'info';
  summary: string;
  detail: string;
  life?: number;
}) => {
  if (!toastInstance) {
    console.warn('Toast not initialized! Call initToast() first');
    return;
  }
  toastInstance.add(options);
};