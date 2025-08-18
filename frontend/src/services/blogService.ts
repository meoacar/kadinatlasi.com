import axios from 'axios'

export interface BlogPost {
  id: number
  title: string
  content: string
  excerpt?: string
  featured_image?: string
  created_at: string
  published_at?: string
  views_count?: number
  likes_count?: number
  tags?: string[]
  category?: {
    id: number
    name: string
  }
  user?: {
    id: number
    name: string
  }
}

export interface BlogResponse {
  data: BlogPost[]
  meta?: any
}

class BlogService {
  private baseURL = '/api/blog-posts'

  async getPosts(): Promise<BlogPost[]> {
    try {
      const response = await axios.get<BlogResponse>(this.baseURL)
      return response.data.data || response.data || []
    } catch (error) {
      console.error('Blog posts fetch error:', error)
      return []
    }
  }

  async getPost(id: number): Promise<BlogPost | null> {
    try {
      const response = await axios.get(`${this.baseURL}/${id}`)
      return response.data.data || response.data || null
    } catch (error) {
      console.error('Blog post fetch error:', error)
      return null
    }
  }

  async searchPosts(query: string): Promise<BlogPost[]> {
    try {
      const response = await axios.get(`${this.baseURL}?search=${encodeURIComponent(query)}`)
      return response.data.data || response.data || []
    } catch (error) {
      console.error('Blog search error:', error)
      return []
    }
  }

  async getPostsByCategory(categoryId: number): Promise<BlogPost[]> {
    try {
      const response = await axios.get(`${this.baseURL}?category_id=${categoryId}`)
      return response.data.data || response.data || []
    } catch (error) {
      console.error('Blog category fetch error:', error)
      return []
    }
  }
}

export const blogService = new BlogService()