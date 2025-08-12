import api from './api'

export interface ExpertQuestion {
  id: number
  user_id: number
  expert_id?: number
  category_id: number
  title: string
  question: string
  answer?: string
  status: 'pending' | 'answered' | 'closed'
  is_public: boolean
  answered_at?: string
  created_at: string
  updated_at: string
  user?: {
    id: number
    name: string
    avatar?: string
  }
  expert?: {
    id: number
    name: string
    avatar?: string
    expert_rank?: string
  }
  category?: {
    id: number
    name: string
    slug: string
  }
}

export interface CreateQuestionData {
  category_id: number
  title: string
  question: string
  is_public?: boolean
}

export interface AnswerQuestionData {
  answer: string
}

class ExpertQuestionService {
  async getQuestions(page = 1) {
    const response = await api.get(`/expert-questions?page=${page}`)
    return response.data
  }

  async createQuestion(data: CreateQuestionData) {
    const response = await api.post('/expert-questions', data)
    return response.data
  }

  async getQuestion(id: number) {
    const response = await api.get(`/expert-questions/${id}`)
    return response.data
  }

  async answerQuestion(id: number, data: AnswerQuestionData) {
    const response = await api.post(`/expert-questions/${id}/answer`, data)
    return response.data
  }

  async getMyQuestions(page = 1) {
    const response = await api.get(`/expert-questions/my/questions?page=${page}`)
    return response.data
  }

  async getPendingQuestions(page = 1) {
    const response = await api.get(`/expert-questions/pending/list?page=${page}`)
    return response.data
  }

  async getCategories() {
    const response = await api.get('/expert-questions/categories')
    return response.data
  }

  async getQuestionLimits() {
    const response = await api.get('/expert-questions/limits')
    return response.data
  }
}

export default new ExpertQuestionService()