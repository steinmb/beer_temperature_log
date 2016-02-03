# Fallback plot. Used if ggplot is not installed.
# Generate plot.
plotFallback <- function(log = "und", sensorer = 0) {
  plot(
    temp1~datestamp,
    data = log,
    las = 2,
    type = "n",
    xaxt = "n",
    xlab = "",
    ylab = "temp, degC",
    ylim = c(min_temp, max_temp),
    yaxp = c(min_temp, max_temp, 9)
  )

  if (sensorer == 1) {
    points(
      temp1~datestamp,
      data = log,
      type = "l",
      col = "red"
    )

    legend(
      "bottomright",
      c("gjæringskar"),
      pch = 22,
      col = "red",
      pt.bg = "red",
      bty = "n",
      cex = 1.5
    )
  }

  if (sensorer == 2) {
    points(
      temp2~datestamp,
      data = log,
      type = "l",
      col = "darkgreen"
    )

    points(
      temp1~datestamp,
      data = log,
      type = "l",
      col = "red"
    )

    legend(
      "bottomright",
      c("ambient", "gjæringskar"),
      pch = 22,
      col = c("red", "darkgreen"),
      pt.bg = c("red", "darkgreen"),
      bty = "n",
      cex = 1.5
    )
  }

  if (sensorer == 0) {
    writeLines("No sensors found. Giving up.")
  }

  axis.POSIXct(
    1,
    log$datestamp,
    labels = T,
    las = 2,
    format = "%Y/%m/%d %H:%M:%S",
    at = log$datestamp[seq(1, length(log$datestamp), 10000)]
  )

  legend(
    "topleft",
    "Øl-Pi temperaturmålinger",
    bty = "n",
    cex = 1.5
  )
}
