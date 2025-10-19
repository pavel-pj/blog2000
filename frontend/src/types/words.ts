// Interface for individual words
export interface WordItem {
  id: string;
  name: string;
  translation:string;
  status: 'NEW'|'REPEATED'|'IMPORTANT';
  repeated_at?:string;
  pivot?: {
     task_id:string,
     word:string,
     status: 'NEW'|'DONE'                   
     id:string
  }
}
 