import { ref } from "vue";
import axios  from "axios";
import type { AxiosRequestConfig,AxiosError } from "axios";
import type { Ref } from "vue";
import { useAuthStore } from '@/store/auth'; // Добавляем импорт хранилища
import { showToast } from '@/utils/showToast';

type Method = AxiosRequestConfig["method"];


interface HttpRequestOptions {
    showSuccessToast:boolean,
    showErrorToast: boolean,
    toastSuccessMsg:string,
}

interface SendRequestParams<R = unknown> {
  url?: string;
  method?: Method;
  data?: R | null;
  params?: Record<string, unknown>;
  headers?: Record<string, string>;
  showSuccessToast?:boolean;
  showErrorToast?:boolean;
  toastSuccessMsg?:string
}
 

interface HttpResponse<T = unknown> {
  data: T,
  status: number,
  isOk: boolean,
}


interface ErrorResponse {
  message: string;
 
}

export function useHttpRequest<T = unknown>(

 options: Partial<HttpRequestOptions> = {},
) {
  
  const defaultOptions: HttpRequestOptions = {
    showErrorToast: false,
    showSuccessToast: false,
    toastSuccessMsg:"Success Message",
  };

  const finalOptions = { ...defaultOptions, ...options };
 
  console.log( finalOptions.showErrorToast)

  const authStore = useAuthStore(); // Получаем хранилище
  const data: Ref<T | null> = ref(null);
  const loading = ref(false);
  const error = ref<string | null>(null);

  const handleError = (err: unknown) => {
    if (axios.isAxiosError(err)) {
      const axiosError = err as AxiosError<ErrorResponse>;
      error.value = axiosError.response?.data?.message || axiosError.message;
    } else if (err instanceof Error) {
      error.value = err.message;
    } else {
      error.value = 'Unknown error occurred';
    }
    loading.value = false;
  };

  const sendRequest = async <R = T>(
    {
      url = '',
      method = "GET",
      data: requestData = null,
      headers = {},
      showErrorToast = finalOptions.showErrorToast,
      showSuccessToast = finalOptions.showSuccessToast,
      toastSuccessMsg = finalOptions.toastSuccessMsg,

    }: SendRequestParams<R> = {}
  ) : Promise<HttpResponse<R> | null> => {
    
    
    loading.value = true;
    error.value = null;

    try {
      const config: AxiosRequestConfig = {
        url,
        method,
        headers: {
          ...headers,
          ...(authStore.token ? {
            'Authorization': `Bearer ${authStore.token}`
          } : {})
        },
        ...(method !== "GET" && requestData ? { data: requestData } : {}),

      };

      const response = await axios<R>(config);
      data.value = response.data as unknown as T;
      
       
       if(showSuccessToast) { 
        showToast({
              severity: 'success',
              summary: 'Success Message',
              detail: toastSuccessMsg,
              life: 3000
            });
      }
 
      return {
        data: response.data,
        status: response.status,
        isOk:
          response.status === 200 ||
          response.status === 202 ||
          response.status === 201,

      };
    } catch (err) {
    console.log("ОШИБКА")
      handleError(err );
      if(showErrorToast) {
        showToast({
              severity: 'error',
              summary: 'Error',
              detail: 'Message Content',
              life: 3000
            });
        }

      return err;
    } finally {
      loading.value = false;
    }
  };

  return {
    data,
    loading,
    error,
    sendRequest,
  };

}

