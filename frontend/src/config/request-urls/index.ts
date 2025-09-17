//export const baseUrl = 'http://localhost:8080/api';
export const baseUrl = import.meta.env.VITE_API_URL  
//catalog
export function catalogsURL() {
  return `${baseUrl}/catalogs`;
}

export function catalogCreateURL() {
  return `${baseUrl}/catalogs`;
}

export function catalogItemShowURL(id: string) {
  return `${baseUrl}/catalogs/${id}`;
}

//Удаление раздела каталога
export function deleteCatalogURL(id: string) {
  return `${baseUrl}/catalogs/${id}`;
}

export function updateCatalogURL(id:string, params?: Record<string, string|number|boolean>) {
  return addQueryParams(`${baseUrl}/catalogs/${id}`, {
    ...params,
  });
}

//Article
export function articlesURL() {
  return `${baseUrl}/articles`;
}

export function articleCreateURL() {
  return `${baseUrl}/articles`;
}

export function articleItemShowURL(id: string) {
  return `${baseUrl}/articles/${id}`;
}

//Удаление статьи
export function deleteArticleURL(id: string) {
  return `${baseUrl}/articles/${id}`;
}

export function updateArticleURL(id:string, params?: Record<string, string|number|boolean>) {
  return addQueryParams(`${baseUrl}/articles/${id}`, {
    ...params,
  });
}



//Subjects
export function subjectsURL() {
  return `${baseUrl}/subjects`;
}

export function subjectCreateURL() {
  return `${baseUrl}/subjects`;
}

export function subjectItemShowURL(id: string) {
  return `${baseUrl}/subjects/${id}`;
}

export function deleteSubjectURL(id: string) {
  return `${baseUrl}/subjects/${id}`;
}

export function updateSubjectURL(id:string, params?: Record<string, string|number|boolean>) {
  return addQueryParams(`${baseUrl}/subjects/${id}`, {
    ...params,
  });
}

//Words
export function wordsURL(subject_id:string) {
  return `${baseUrl}/subjects/${subject_id}/words`;
}

export function wordCreateURL() {
  return `${baseUrl}/words`;
}

export function wordItemShowURL(id: string) {
  return `${baseUrl}/words/${id}`;
}
                
export function deleteWordURL(id: string) {
  return `${baseUrl}/words/${id}`;
}

export function updateWordURL(id:string, params?: Record<string, string|number|boolean>) {
  return addQueryParams(`${baseUrl}/words/${id}`, {
    ...params,
  });
}



//dictionaries
export function catalogDictionariesURL(dicntionaryType: string) {
  return addQueryParams(`${baseUrl}/dictionaries`, {
    typeDictionary: dicntionaryType
  });
}



// helper
function addQueryParams(url: string, params?: Record<string, string|number|boolean>): string {
  if (!params) return url;

  const [baseUrl, existingQuery] = url.split("?");
  const existingParams = new URLSearchParams(existingQuery);

  Object.entries(params).forEach(([key, value]) => {
    if (value == null) return;
    if (Array.isArray(value)) {
      value.forEach((v) => {
        existingParams.append(key, v);
      });
    } else {
      existingParams.set(key, String(value));
    }
  });

  const newQuery = decodeURI(existingParams.toString());
  return newQuery ? `${baseUrl}?${newQuery}` : baseUrl;
}