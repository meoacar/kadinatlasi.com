import api from './api'

export interface BMIData {
  weight: number
  height: number
}

export interface MenstrualCycleData {
  last_period_date: string
  cycle_length?: number
}

export interface PregnancyData {
  last_period_date: string
  cycle_length?: number
}

export interface CalorieData {
  age: number
  gender: string
  weight: number
  height: number
  activity_level: string
  goal: string
}

export interface WaterIntakeData {
  weight: number
  age: number
  activity_level: string
  climate: string
  is_pregnant?: boolean
  is_breastfeeding?: boolean
  is_sick?: boolean
}

export interface FinancialPlannerData {
  monthly_income: number
  monthly_expenses: number
  savings_goal: number
  goal_months: number
}

export const calculatorService = {
  async calculateBMI(data: BMIData) {
    const response = await api.post('/calculator/bmi', data)
    return response.data
  },

  async calculateMenstrualCycle(data: MenstrualCycleData) {
    const response = await api.post('/calculator/menstrual-cycle', data)
    return response.data
  },

  async calculatePregnancy(data: PregnancyData) {
    const response = await api.post('/calculator/pregnancy', data)
    return response.data
  },

  async calculateCalorie(data: CalorieData) {
    const response = await api.post('/calculator/calorie', data)
    return response.data
  },

  async calculateWaterIntake(data: WaterIntakeData) {
    const response = await api.post('/calculator/water-intake', data)
    return response.data
  },

  async financialPlanner(data: FinancialPlannerData) {
    const response = await api.post('/calculator/financial-planner', data)
    return response.data
  }
}