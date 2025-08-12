import api from './api'

export interface Partnership {
  company_name: string
  partnership_type: string
  description: string
}

export interface PartnershipStats {
  total_partners: number
  total_revenue: number
  partnership_types: Array<{
    partnership_type: string
    count: number
  }>
}

export const partnershipService = {
  async getPartnerships(): Promise<Partnership[]> {
    const response = await api.get('/partnerships')
    return response.data.data
  },

  async getStats(): Promise<PartnershipStats> {
    const response = await api.get('/partnerships/stats')
    return response.data.data
  }
}