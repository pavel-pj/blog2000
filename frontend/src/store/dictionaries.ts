import { defineStore } from 'pinia'
import { ref } from 'vue'
import { useHttpRequest } from '@/utils/http-request'
import { catalogDictionariesURL } from '@/config/request-urls'
import type { DictionaryItem } from '@/types/dictionary'

export const useDictionariesStore = defineStore('dictionaries', () => {
  // Состояние
  const dictionaries = ref<Record<string, DictionaryItem[]>>({})
  const loadingStates = ref<Record<string, boolean>>({})
  const errors = ref<Record<string, string | null>>({})

  const { sendRequest } = useHttpRequest<{ dictionaryCatalog: DictionaryItem[] }>({
    showErrorToast: true,
    showSuccessToast: false
  })

  const fetchDictionary = async (typeDictionary: string) => {
    if (loadingStates.value[typeDictionary]) return
    
    loadingStates.value[typeDictionary] = true
    errors.value[typeDictionary] = null
    
    try {
      const response = await sendRequest({ 
        url: catalogDictionariesURL(typeDictionary)
      })
      
      if (response?.isOk && response.data) {
        dictionaries.value[typeDictionary] = response.data.dictionaryCatalog
      }
    } catch (error) {
      console.log(error)  
      errors.value[typeDictionary] = 'Ошибка загрузки словаря'
    } finally {
      loadingStates.value[typeDictionary] = false
    }
  }

  const getDictionaryByType = (type: string): DictionaryItem[] => {
    return dictionaries.value[type] || []
  }

  return {
    dictionaries,
    loadingStates,
    errors,
    fetchDictionary,
    getDictionaryByType
  }
})