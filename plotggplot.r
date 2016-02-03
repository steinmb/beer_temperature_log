# Generate plot by using the ggplot2 plotting system.

plotggplot <- function(log = "und", sensorer = 0) {
  if (sensorer == 1) {
    result <- ggplot(data = log) +
      geom_line(aes(x = as.POSIXct(datestamp), y = temp, colour = measurement)) +
      xlab("") +
      ylab("temperature, degrees Celsius") +
      theme_bw() +
      ggtitle("Brewpi temperature log") +
      theme(legend.position = "right")
  }

  if (sensorer == 2) {
    result <- ggplot(data = log) +
      geom_line (aes(x = as.POSIXct(datestamp), y = temp, colour = measurement)) +
      xlab ("") +
      ylab ("ambient temperature, degrees Celsius") +
      theme_bw () +
      ggtitle ("Brewpi temperature log") +
      theme (legend.position = "right")
  }

  if (sensorer == 0) {
    writeLines("No sensors found. Giving up.")
  }

  return(result)
}
