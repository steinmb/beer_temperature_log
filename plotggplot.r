# Generate plot by using the ggplot library.

plotggplot <- function() {
  if (sensorer > 1) {
    plot <- ggplot(data = log.2) +
    geom_line(aes(x = as.POSIXct(datestamp), y = temp, colour = measurement)) +
    xlab("") +
    ylab("temperature, degrees Celsius") +
    theme_bw() +
    ggtitle("Brewpi temperature log") +
    theme(legend.position = c(0.8, 0.1))
  }
  else {
    plot <- ggplot(data = log.2) +
    geom_line (aes(x = as.POSIXct(datestamp), y = temp, colour = measurement)) +
    xlab ("") +
    ylab ("ambient temperature, degrees Celsius") +
    theme_bw () +
    ggtitle ("Brewpi temperature log") +
    theme (legend.position = c(0.8, 0.1))
  }
}
