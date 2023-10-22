import {defineStore} from 'pinia';
import {ref} from 'vue'
import User = App.User;


export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: ref({
      name: '',
      email: ''
    }),
    token: localStorage.getItem('token') || ''
  }),
  getters: {
    check: (state) => !!state.token,
  },
  actions: {
    setUser(user: User) {
      this.user = user
    },
    setToken(token: string) {
      this.token = token
      localStorage.setItem('token', token)
    },
    removeToken() {
      this.token = ''
      localStorage.removeItem('token')
    },
  }
});
