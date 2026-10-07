/**
 * UIU Research Portal — Frontend API Client
 * 
 * Communicates with the unified backend REST API at /api on the same host & port.
 */

const API = {
  baseUrl: '/api',

  getToken() {
    return localStorage.getItem('uiu_token') || '';
  },

  setToken(token) {
    if (token) {
      localStorage.setItem('uiu_token', token);
    } else {
      localStorage.removeItem('uiu_token');
    }
  },

  async request(endpoint, options = {}) {
    const url = `${this.baseUrl}${endpoint}`;
    const headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      ...(options.headers || {})
    };

    const token = this.getToken();
    if (token) {
      headers['Authorization'] = `Bearer ${token}`;
    }

    try {
      const response = await fetch(url, { ...options, headers });
      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.error?.message || data.message || `Request failed (${response.status})`);
      }

      return data;
    } catch (err) {
      console.error(`API Error [${endpoint}]:`, err);
      throw err;
    }
  },

  // Auth
  async login(email, password) {
    const res = await this.request('/auth/login', {
      method: 'POST',
      body: JSON.stringify({ email, password })
    });
    if (res.data?.token) {
      this.setToken(res.data.token);
    }
    return res.data;
  },

  async register(userData) {
    const res = await this.request('/auth/register', {
      method: 'POST',
      body: JSON.stringify(userData)
    });
    if (res.data?.token) {
      this.setToken(res.data.token);
    }
    return res.data;
  },

  async me() {
    return (await this.request('/auth/me')).data;
  },

  async logout() {
    this.setToken(null);
    return await this.request('/auth/logout', { method: 'POST' });
  },

  // Dashboard & Search
  async getDashboard() {
    return (await this.request('/dashboard')).data;
  },

  async search(query) {
    return (await this.request(`/search?q=${encodeURIComponent(query)}`)).data;
  },

  // Projects & Tasks
  async getProjects(params = {}) {
    const q = new URLSearchParams(params).toString();
    return await this.request(`/projects${q ? '?' + q : ''}`);
  },

  async getProject(id) {
    return (await this.request(`/projects/${id}`)).data;
  },

  async getProjectTasks(projectId) {
    return (await this.request(`/projects/${projectId}/tasks`)).data;
  },

  async updateTaskStatus(taskId, status) {
    return await this.request(`/tasks/${taskId}/status`, {
      method: 'PATCH',
      body: JSON.stringify({ status })
    });
  },

  // Resources
  async getResources(params = {}) {
    const q = new URLSearchParams(params).toString();
    return await this.request(`/resources${q ? '?' + q : ''}`);
  },

  async downloadResource(id) {
    return await this.request(`/resources/${id}/download`, { method: 'POST' });
  },

  // Ideas
  async getIdeas(params = {}) {
    const q = new URLSearchParams(params).toString();
    return await this.request(`/ideas${q ? '?' + q : ''}`);
  },

  async upvoteIdea(id) {
    return await this.request(`/ideas/${id}/upvote`, { method: 'POST' });
  },

  // Blogs
  async getBlogs(params = {}) {
    const q = new URLSearchParams(params).toString();
    return await this.request(`/blogs${q ? '?' + q : ''}`);
  },

  async likeBlog(id) {
    return await this.request(`/blogs/${id}/like`, { method: 'POST' });
  },

  // Notifications
  async getNotifications() {
    return (await this.request('/notifications')).data;
  },

  async markNotificationRead(id) {
    return await this.request(`/notifications/${id}/read`, { method: 'PATCH' });
  }
};

window.API = API;
