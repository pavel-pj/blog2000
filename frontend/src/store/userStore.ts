import { defineStore } from 'pinia'
import { ref } from 'vue'
import { useHttpRequest } from '@/utils/http-request'
import { userDictionariesURL } from '@/config/request-urls'

// Interface for individual topic items
interface TopicItem {
  value: string;
  label: string;
}

// Interface for each topic category (like "English admin", "Php admin")
interface TopicCategory {
  id: string;
  name: string;
  user_id: string;
  topics: TopicItem[]; // Nested topics array
}

// Main response interface
interface TopicsResponse {
  data: TopicCategory[];
}

export const useUserStore = defineStore('userStore', () => {
  // Fix: Change the type to match your API response
  const data = ref<TopicCategory[]>([]) // Changed from Record<string, TopicsResponse[]> to TopicCategory[]
  const loading = ref(false);
  const error = ref<string | null>(null); // Added type for error

  const { sendRequest } = useHttpRequest<TopicsResponse>({
    showErrorToast: true,
    showSuccessToast: false
  })

  const fetchData = async () => {
    loading.value = true
    error.value = null
    
    try {
      const response = await sendRequest({ 
        url: userDictionariesURL('user')
      })
      
      if (response?.isOk && response.data) {
        data.value = response.data.data // This should now work
      }
    } catch (err) { // Changed from 'error' to 'err' to avoid variable name conflict
      console.log(err)  
      error.value = 'Error of loading topics'
    } finally {
      loading.value = false
    }
  }

  const  getTopic = (subject_id: string) =>
  {
    if (!data.value) {
      return []
    }

    const subject = data.value.filter((item)=> item.id === subject_id)[0] 
    return subject?.topics



  }

  return {
    data,
    loading,
    error,
    fetchData,
    getTopic 
  }
})