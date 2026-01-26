/// <reference lib="es2021" />

/**
 * The class is used to alter given template with the weather condition.
 *
 * Depending on the weather condition, it adds some weather visual into the template, like rain drops.
 */
class WeatherAsciinator {
  static WEATHER_SUNNY = 'sunny'
  static WEATHER_CLOUDY = 'cloudy'
  static WEATHER_RAIN = 'rain'
  static WEATHER_STORM = 'storm'

  public getRefreshRate(weather: string): number
  {
    // const refreshRate = Math.random() * 100
    const refreshRate = 0

    if (weather === WeatherAsciinator.WEATHER_STORM) {
      return refreshRate + 500
    }

    if (weather === WeatherAsciinator.WEATHER_RAIN) {
      return refreshRate + 1000
    }

    if (weather === WeatherAsciinator.WEATHER_CLOUDY) {
      return refreshRate + 2000
    }

    return refreshRate + 4000
  }

  public prepareWeather(template: string, weather: string): string
  {
    if (weather === WeatherAsciinator.WEATHER_SUNNY) {
      return this.applyWeatherEffect(template, 0.01, "<span class='text-yellow-300 font-bold'> . </span>")
    }

    if (weather === WeatherAsciinator.WEATHER_CLOUDY) {
      return this.applyWeatherEffect(template, 0.03, "<span class='text-white font-bold'> . </span>")
    }

    if (weather === WeatherAsciinator.WEATHER_RAIN) {
      template = this.applyWeatherEffect(template, 0.07, "<span class='text-blue-300 font-bold'> : </span>")

      return this.applyWeatherEffect(template, 0.07, "<span class='text-blue-200 font-bold'> ! </span>")
    }

    if (weather === WeatherAsciinator.WEATHER_STORM) {
      template = this.applyWeatherEffect(template, 0.14, "<span class='text-blue-500 font-bold'> : </span>")

      return this.applyWeatherEffect(template, 0.16, "<span class='text-blue-400 font-bold'> ! </span>")
    }

    return template
  }

  private applyWeatherEffect(template: string, probability: number, effect: string): string
  {
    return template.replaceAll('   ', function(match: string): string {
      if (probability <= Math.random()) {
        return match
      }

      return effect
    })
  }
}

export default new WeatherAsciinator()
