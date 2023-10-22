import {Ref, ref} from 'vue'
import {defineStore} from 'pinia';
import Test = App.Test;
import Question = App.Question;

type TestStoreState = {
  test: Ref<Test>;
  questions: Ref<Question[]>;
  ends_at: Ref<string>
}
export const useTestStore = defineStore('test', {
  state: (): TestStoreState => ({
    test: ref({id: 0, title: '', subject: '', duration: '', starts_at: '', ends_at: ''}),
    questions: ref([]),
    ends_at: ref('')
  }),
  getters: {
    loaded: (state) => !!state.ends_at,
  },
  actions: {
    setTest(test: Test) {
      this.test = test
    },
    setQuestions(questions: Question[]) {
      this.questions = questions
    },
    setEndsAt(time: string) {
      this.ends_at = time
    },
  },
});
