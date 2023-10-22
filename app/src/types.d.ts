declare namespace App {
  type User = {
    id: number
    name: string
    email: string
  }

  type Test = {
    id: number
    subject: string
    title: string
    duration: string
    starts_at: string
    ends_at: string
    questions_count?: number | null
  }

  type Question = {
    id: number
    body: string
    options: Option[]

    selected: boolean | null
  }

  type Option = {
    id: number
    body: string
    question_id: number
  }
}
