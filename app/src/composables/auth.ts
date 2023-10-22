import {ref, reactive} from 'vue';
import {api} from 'boot/axios';
import {useQuasar} from 'quasar';
import {useRouter, useRoute} from 'vue-router';
import {useAuthStore} from 'stores/auth-store';

export function useAuth() {
    const user = ref({name: '', email: '', password: '', password_confirmation: ''})
    const errors = reactive({})
    const $q = useQuasar()
    const router = useRouter()
    const route = useRoute()
    const submitting = ref(false)
    const auth = useAuthStore()

    const init = async () => {
        if (auth.token) {
            api.defaults.headers.common.Authorization = 'Bearer ' + auth.token
            try {
                const response = await api.get('api/user')
                auth.setUser(response.data)
            } catch (e) {
                await logout()
            }
        } else {
            await router.push({name: 'login'})
        }
    }

    const register = async () => {
        submitting.value = true
        try {
            await api.post('api/register', user.value)

            submitting.value = false
            for (const key in errors) {
                delete errors[key];
            }

            $q.notify({
                message: 'Registration successful, Login to continue',
                color: 'positive'
            })

            await router.push({name: 'login'})

        } catch (e) {
            console.log(e)
            submitting.value = false
            if (e.response.status == 422) {
                for (const key in e.response.data.errors) {
                    errors[key] = e.response.data.errors[key][0]
                }
            }
        }
    }

    const login = async () => {
        submitting.value = true
        try {
            const response = await api.post('api/login', {
                email: user.value.email,
                password: user.value.password
            })

            submitting.value = false
            for (const key in errors) {
                delete errors[key];
            }

            $q.notify({
                message: 'Logged in successfully!',
                color: 'positive'
            })

            auth.setUser(response.data.user)
            auth.setToken(response.data.access_token)
            api.defaults.headers.common.Authorization = 'Bearer ' + response.data.access_token


            await router.push(route.query.next || {name: 'dashboard'})

        } catch (e) {
            submitting.value = false
            console.log(e)

            if (e.response.status == 422) {
                for (const key in e.response.data.errors) {
                    errors[key] = e.response.data.errors[key][0]
                }
            }
        }
    }

    const logout = async () => {
        api.defaults.headers.common.Authorization = ''

        await api.post('api/logout')

        auth.removeToken()

        await router.push({name: 'login'})
    }

    return {
        user,
        submitting,
        errors,
        init,
        register,
        login,
        logout
    }
}
