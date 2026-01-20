/// <reference lib="es2021" />

class WeatherAsciinator {
  public prepareWeather(template: string, weather: string): string
  {
    if (weather === 'sunny') {
      return template.replaceAll('   ', function (match: string): string {
        return Math.random() < 0.01 ? "<span class='text-yellow-300 font-bold'> . </span>" : match
      })
    }

    if (weather === 'cloudy') {
      console.log('cloudy')
      return template.replaceAll('   ', function (match: string): string {
        return Math.random() < 0.03 ? "<span class='text-white font-bold'> . </span>" : match
      })
    }

    if (weather === 'rain') {
      template = template.replaceAll('   ', function (match: string): string {
        return Math.random() < 0.07 ? "<span class='text-blue-300 font-bold'> : </span>" : match
      })

      return template.replaceAll('   ', function (match: string): string {
        return Math.random() < 0.07 ? "<span class='text-blue-200 font-bold'> ! </span>" : match
      })
    }

    if (weather === 'storm') {
      template = template.replaceAll('   ', function (match: string): string {
        return Math.random() < 0.10 ? "<span class='text-blue-500 font-bold'> : </span>" : match
      })

      return template.replaceAll('   ', function (match: string): string {
        return Math.random() < 0.08 ? "<span class='text-blue-300 font-bold'> ! </span>" : match
      })
    }

    return template
  }
}

export default new WeatherAsciinator()
