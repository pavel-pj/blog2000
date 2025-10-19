
import { WordItem } from '@/types/words'

export interface TaskItem {
  id: string;
  repetition_id: string;
  task:string;
  answer:string;
  words: WordItem[];
  status:string;
  position:number;
}
 