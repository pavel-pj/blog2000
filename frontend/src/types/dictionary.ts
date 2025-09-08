export interface DictionaryItem {
  id: number | string;
  label: string;
  [key: string]: unknown;
}

/**
 * Тип для параметров запроса словарей
 */
export interface DictionaryParams {
  /**
   * Тип запрашиваемого словаря
   * @example "cities", "countries", "catalogs"
   */
  typeDictionary: string
  
  /**
   * Дополнительные параметры фильтрации
   */
  filters?: Record<string, any>
}

/**
 * Ответ API для словарей
 */
export interface DictionaryResponse {
  dictionaryCatalog: DictionaryItem[];
}