import { defineStore } from 'pinia'
import { ref } from 'vue'
import { useHttpRequest } from '@/utils/http-request'
import { repetitionShowURL } from '@/config/request-urls'
import { TaskItem } from '@/types/tasks'
//import {useRoute, useRouter} from 'vue-router';


 //const route = useRoute();
 

// Main response interface
interface RepetitionsResponse {
  id: string, 
  subject_id: string,
  created_at:string,
  updated_at:string,
  tasks: TaskItem[];
}

export const useRepetitionStore = defineStore('repetitionStore', () => {
  // Fix: Change the type to match your API response
  const data = ref<RepetitionsResponse>()  
  const tasks = ref<TaskItem[]>();
  const loading = ref(false);
  const error = ref<string | null>(null); // Added type for error

  //const loadingDelete = ref(false);
  //const errorDelete = ref<string | null>(null); // Added type for error

  const { sendRequest } = useHttpRequest<RepetitionsResponse>({
    showErrorToast: true,
    showSuccessToast: false
  })

  const fetchData = async ( repetition_id : string) => {
    loading.value = true
    error.value = null
    
    try {
      const response = await sendRequest({ 
        url: repetitionShowURL(repetition_id)
      })
      
      if (response?.isOk && response.data) {
        data.value = response.data// This should now work
        tasks.value = data.value.tasks
        //subject.value = response.data.subject
      }
    } catch (err) { // Changed from 'error' to 'err' to avoid variable name conflict
      
      error.value = err
    } finally {
      loading.value = false
    }
  }

const getNewTask = (): TaskItem | undefined => {  
  if (!tasks.value) {
    return undefined;  
  }
  // get first task with status NEW
  return tasks.value.find(item => item.status === 'NEW')
}

  const deleteWordFromTask = (task_id:string,word_id:string)=>{
    if (!tasks.value ){
        return ;
    }
 
    tasks.value = tasks.value.map(task => {
    if (task.id === task_id) {
        return {
        ...task,
        words: task.words.filter(word => word.id !== word_id)
        };
    }
    return task;
    });
  }

  //if there is no words to check in task - task have to be marked as DONE as well
  const deleteTask = (task_id:string) => {
     if (!tasks.value ){
        return ;
    }
    tasks.value =  tasks.value.filter(task=> task.id !== task_id) ;
   
  }




 

  return {
    data,
    tasks,
    
    loading,
    error,
    fetchData,
    getNewTask,
    deleteWordFromTask,
    deleteTask
 
    
  }
})