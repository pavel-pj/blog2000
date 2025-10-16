import { defineStore } from 'pinia'
import { ref } from 'vue'
import { useHttpRequest } from '@/utils/http-request'
import { wordsURL } from '@/config/request-urls'
import { WordItem } from '@/types/words'
//import {useRoute, useRouter} from 'vue-router';


 //const route = useRoute();
 
 
interface Subject {
  id: string;
  name: string;
  
}

// Main response interface
interface WordsResponse {
  subject: Subject, 
  data: WordItem[];
}

export const useWordsStore = defineStore('wordsStore', () => {
  // Fix: Change the type to match your API response
  const data = ref<WordItem[]>([])  
  const subject = ref<Subject | null>(null)
  const loading = ref(false);
  const error = ref<string | null>(null); // Added type for error

  //const loadingDelete = ref(false);
  //const errorDelete = ref<string | null>(null); // Added type for error

  const { sendRequest } = useHttpRequest<WordsResponse>({
    showErrorToast: true,
    showSuccessToast: false
  })

  const fetchData = async ( subject_id : string) => {
    loading.value = true
    error.value = null
    
    try {
      const response = await sendRequest({ 
        url: wordsURL(subject_id)
      })
      
      if (response?.isOk && response.data) {
        data.value = response.data.data // This should now work
        subject.value = response.data.subject
      }
    } catch (err) { // Changed from 'error' to 'err' to avoid variable name conflict
      
      error.value = err
    } finally {
      loading.value = false
    }
  }

  const deleteWord = ( word_id: string) =>{
     data.value =  data.value.filter(item => item.id !== word_id)

  }
 
  const addNewWord = (word: WordItem) =>{
     data.value.push(word)

  } 

 

  return {
    data,
    subject,
    loading,
    error,
    fetchData,

    //loadingDelete,
   // errorDelete,
    deleteWord,
    addNewWord
    
  }
})