import {ref, reactive, Ref} from 'vue'
import {api} from 'boot/axios'
import {useQuasar} from 'quasar'
import {useRouter, useRoute} from 'vue-router';
import {useTestStore} from 'stores/test-store'
import {Dialog} from 'quasar'

export const timer = reactive({
    hour: '00',
    minute: '00',
    second: '00',
})

export function useTest() {
    const tests = ref([])
    const starting = ref(false)
    const isSubmitting = ref(false)
    const timeout: Ref<NodeJS.Timeout | undefined> = ref()
    const remainder = ref(0)

    const $q = useQuasar()
    const router = useRouter()
    const testStore = useTestStore()
    const {params} = useRoute()

    const getTests = async () => {
        const response = await api.get('/api/tests')
        tests.value = response.data
    }

    const startTest = async (id: number) => {
        try {
            const response = await api.post(`/api/tests/${id}/start`)
            testStore.setTest(response.data.test)
            testStore.setEndsAt(response.data.ends_at)

            await router.push({name: 'test.start', params: {id}})

        } catch (e) {
            if (e.response.status == 416) {
                $q.notify(
                    {message: e.response.data.message, color: 'negative'}
                )
            } else {
                $q.notify({
                    message: 'There is something wrong. Please try again later.', color: 'negative'
                })
            }
        }
    }

    const loadTest = async () => {
        if (!testStore.loaded) await startTest(params.id)
        const response = await api.get(`/api/questions?test_id=${params.id}`)
        testStore.setQuestions(response.data)
        startCountdown()
    }

    const endTest = async () => {
        Dialog
            .create({
                title: 'Submit Test',
                message: 'Are you sure you want to submit?',
                cancel: true,
                persistent: true
            })
            .onOk(async () => {
                isSubmitting.value = true
                const answers = testStore.questions.map((question) => {
                    return {
                        question_id: question.id,
                        option_id: question.selected
                    }
                })

                const response = await api.post(`/api/tests/${testStore.test.id}/submit`, {answers})
                $q.notify(
                    {message: response.data.message, color: 'positive'}
                )

                await router.push({name: 'test.complete', params: {id: testStore.test.id}})
            })
    }

    const padNum = (num: number | string) => {
        if (num.toString().length <= 1) {
            return ('0' + num.toString());
        }
        return num.toString();
    }

    const startCountdown = () => {
        remainder.value = Number(new Date(testStore.ends_at)) - new Date().getTime();

        timeout.value = setInterval(() => {
            remainder.value -= 1000;

            if (remainder.value < 0) {
                clearInterval(timeout.value)
                timer.hour = '0';
                timer.minute = '0';
                timer.second = '0';
                endTest();
            }

            timer.hour = padNum(Math.floor(remainder.value / (1000 * 60 * 60)));
            timer.minute = padNum(Math.floor((remainder.value % (1000 * 60 * 60 * 24)) % (1000 * 60 * 60) / (1000 * 60)))
            timer.second = padNum(Math.floor((remainder.value % (1000 * 60 * 60 * 24)) % (1000 * 60 * 60) % (1000 * 60) / 1000))
        }, 1000);

    }

    return {
        tests,
        starting,
        isSubmitting,
        getTests,
        startTest,
        loadTest,
        endTest
    }
}
