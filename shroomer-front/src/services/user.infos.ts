import authService from "@/services/auth.service.ts";
import {ref} from "vue";

class UserInfos
{
  public user = ref({
    email: '',
    resourceFlora: 0,
    resourceFauna: 0,
    resourceEntomofauna: 0,
  })

  async refresh() {
    this.user.value = await authService.get('/api/user')
      .then(response => response.data)
      .then(data => {
        return data
      })
  }

  affordable(cost: {resourceFlora: number, resourceFauna: number, resourceEntomofauna: number}): boolean {
    if (this.user.value.resourceFlora < cost.resourceFlora) {
      return false
    }

    if (this.user.value.resourceFauna < cost.resourceFauna) {
      return false
    }

    return this.user.value.resourceEntomofauna >= cost.resourceEntomofauna
  }
}

export default new UserInfos()
