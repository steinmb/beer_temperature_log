# Generate plot by using the ggplot2 plotting system.

plotggplot <- function(log = "und", sensorer = 0) {
  if (sensorer == 1) {
    log <- melt(log, id.vars = "datestamp")
    result <- ggplot(data = log, aes(x = datestamp, y = value)) +
      geom_line(aes(colour = variable)) +
      xlab("Date") +
      ylab("Temperature, degrees Celsius") +
      ggtitle("Brewpi temperature log") +
      theme(legend.position = "right")
  }

  if (sensorer == 2) {
    log <- melt(log, id.vars = "datestamp")
    result <- ggplot(data = log, aes(x = datestamp, y = value)) +
      geom_line(aes(colour = variable)) +
      xlab("Date") +
      ylab("Temperature, degrees Celsius") +
      ggtitle("Brewpi temperature log") +
      theme(legend.position = "right")
  }

  if (sensorer == 0) {
    writeLines("No sensors found. Giving up.")
  }

  return(result)
}

