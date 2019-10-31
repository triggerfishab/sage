export default class AjaxHandler {
  static async request(payload, method = 'POST', options = {}) {
    try {
      const response = await fetch(
        theme.ajaxurl,
        {
          method,
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload),
          ...options,
        }
      );

      return response.json();
    } catch(error) {
      console.error(error);
    }
  }

  static async get(payload) {
    return await this.request(payload, 'GET');
  }

  static async post(payload) {
    return await this.request(payload, 'POST');
  }
}
