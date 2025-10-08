import { defineStore } from 'pinia'
import { ref } from 'vue'
import { useHttpRequest } from '@/utils/http-request'
import { topicsURL } from '@/config/request-urls'
 

export const useTopicsStore = defineStore('topics', () => {

  // Новый интерфейс для ответа API topics
interface TopicsResponse {
  subject: {
    id: string
    name: string
  }
  data: TopicItem[]
}

interface TopicItem {
  id: string
  name: string
  subject_id: string
  created_at: string
  updated_at: string
}


  // Состояние
  const topics = ref<Record<string, TopicItem[]>>({})
  const loadingTopics = ref<Record<string, boolean>>({})
  const loadingErrors = ref<Record<string, string | null>>({})

  const { sendRequest } = useHttpRequest<TopicsResponse>({
    showErrorToast: true,
    showSuccessToast: false
  })

  const fetchTopics = async (subjectId: string) => {
    //if (loadingTopics.value ) return
    
    loadingTopics.value[subjectId] = true
    loadingErrors.value[subjectId] = null
    
     try {
      const response = await sendRequest({ 
        url: topicsURL(subjectId)
      })
      
      if (response?.isOk && response.data) {
        topics.value[subjectId] = response.data.data // Сохраняем по subjectId
      }
    } catch (error) {
      console.log(error)  
      loadingErrors.value[subjectId] = 'Error of loading topics'
    } finally {
      loadingTopics.value[subjectId] = false
    }
  }

  // Хелперы для получения состояния
  const getTopics = (subjectId: string) => topics.value[subjectId] || []
  const isLoading = (subjectId: string) => loadingTopics.value[subjectId] || false
  const getError = (subjectId: string) => loadingErrors.value[subjectId] || null

  return {
    topics,
    loadingTopics,
    loadingErrors,
    fetchTopics,
    getTopics,
    isLoading,
    getError
  }
})
 