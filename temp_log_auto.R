#!/usr/bin/Rscript

# to get log data
# connect to Dragemaskinen
# ssh steinmb@10.0.0.16
# then connect to Øl-Pi
# ssh pi@192.168.1.2 # raspberry
# from Dragemaskinen scp pi@192.168.1.2:/home/pi/temp.log ~/Downloads/
# from local scp steinmb@10.0.0.16:/Users/steinmb/Downloads/temp.log ~/Downloads

# Configuration
plot_height <- 800
plot_width <- 1200

# Read logfile into dataframe.
log<-read.csv("bar.log", header = F)

# Rename columns.
colnames(log)<-c("datestamp", "temp1")

# Find number of temperatur sensors.
sensorer = ncol(log)/2

# Workaround: Create new column in log called "datetime" using datestamp read as
# POSIX date-time factor.
log$datetime<-as.POSIXct(log$datestamp, format = "%Y-%m-%d %H:%M:%S")

# Setup and define plot device.
png("temp_log_plot2.png", plot_width, plot_height, res = 100)
par(mar = c(10,5,5,4) + 1)

# Generate plot.
plot(
  temp1~datetime,
  data = log,
  las = 2,
  type = "n",
  xaxt = "n",
  xlab = "",
  ylab = "temp, degC",
  ylim = c(18,36),
  yaxp = c(18,36,9)
)


if (sensorer > 1) {
  points(
    temp2~datetime,
    data = log,
    type = "l",
    col = "darkgreen"
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
} else {
  points(
    temp1~datetime,
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

axis.POSIXct(
  1,
  log$datetime,
  labels = T,
  las = 2,
  format = "%Y/%m/%d %H:%M:%S",
  at = log$datetime[seq(1, length(log$datetime), 10000)]
)

legend(
  "topleft",
  "Øl-Pi temperaturmålinger",
  bty = "n",
  cex = 1.5
)

dev.off()
