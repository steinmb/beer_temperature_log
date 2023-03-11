# Generate plot by using the ggplot2 plotting system.

plotggplot <- function(log = "und", sensorer = 0) {
  if (sensorer == 1) {
    log <- melt(log, id.vars = "datestamp")
    result <- ggplot(data = log, aes(x = datestamp, y = value, colour = variable)) +
      geom_line() +
      xlab("Date") +
      ylab("Temperature, degrees Celsius")
      theme(legend.position = "right") +
      scale_colour_discrete(name = "Sensor")
  }

  if (sensorer == 2) {
    log <- melt(log, id.vars = "datestamp")
    result <- ggplot(data = log, aes(x = datestamp, y = value, colour = variable)) +
      geom_line() +
      xlab("Date") +
      ylab("Temperature, degrees Celsius") +
      theme(
        legend.position = "right",
        panel.background = element_rect(fill = '#EFEFEF'),
        panel.grid.major = element_line(size = 0.5, linetype = 'solid', colour = '#666666'),
        panel.grid.minor = element_line(size = 0.25, linetype = 'solid', colour = '#999999')
      ) +
      scale_colour_discrete(name = "Sensor")
  }

  if (sensorer == 0) {
    writeLines("No sensors found. Giving up.")
  }

  return(result)
}

