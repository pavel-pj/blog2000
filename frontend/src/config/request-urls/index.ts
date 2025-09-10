//export const baseUrl = 'http://localhost:8080/api';
export const baseUrl = import.meta.env.VITE_API_URL  
//catalog
export function catalogURL() {
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
export function articleURL() {
  return `${baseUrl}/articles`;
}

export function articleCreateURL() {
  return `${baseUrl}/articles`;
}

export function articleItemShowURL(id: string) {
  return `${baseUrl}/articles/${id}`;
}

//Удаление раздела каталога
export function deleteArticleURL(id: string) {
  return `${baseUrl}/articles/${id}`;
}

export function updateArticleURL(id:string, params?: Record<string, string|number|boolean>) {
  return addQueryParams(`${baseUrl}/articles/${id}`, {
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