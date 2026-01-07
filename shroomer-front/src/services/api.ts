import axios from "axios"

const instance = axios.create({
  baseURL: 'https://localhost:443/',
  headers: {
    'Content-Type': "application/ld+json"
  },
})

export default instance
