import axios from 'axios'


const apiClient = axios.create({
  baseURL: 'http://soultalk-backend.ddev.site'
})

export default {
  async ask(payload: string) {
    const response = await apiClient.post('/ask', { contents: payload })
    return response.data
  }
}
