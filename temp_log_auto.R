#!/usr/local/bin/Rscript

# to get log data
# connect to Dragemaskinen
# ssh steinmb@10.0.0.16
# then connect to Øl-Pi
# ssh pi@192.168.1.2 # raspberry
# from Dragemaskinen scp pi@192.168.1.2:/home/pi/temp.log ~/Downloads/
# from local scp steinmb@10.0.0.16:/Users/steinmb/Downloads/temp.log ~/Downloads
#
# Convert raw data logs. Some temperatur logs might have lines terminated
# only by LF not CRLF. Running them through this awk command take care of that:
# awk '{printf "%s\r\n", $0}' log_file > new_log_file.

# Configuration
args <- commandArgs(TRUE) # Enable reading arguments from shell.
plot_height <- 800
plot_width <- 1200
temp_log <- args[1]
min_temp <- as.numeric(args[2])
max_temp <- as.numeric(args[3])

if (is.na(temp_log)) {
  temp_log <- "demo/demo.csv"
  cat("Name of temperatur file not defined, loading demo data\n")
  cat("Usage:\n  Rscript <this_file> temp_log <min_temperatur> <max_temperatur>\n")
  cat("Basic example:\n  Rscript temp_log_auto.R temperatur.log\n")
  cat("Advanced example:\n  Rscript temp_log_auto.R temperatur.log 17.5 22\n")
}

if (is.na(min_temp)) {
  min_temp <- 12 # Default value if nothing is given on start.
}

if (is.na(max_temp)) {
  max_temp <- 24 # Default value if nothing is given on start.
}

# Read logfile into a dataframe.
log<-read.csv(temp_log, header = F)

# Find number of temperatur sensors.
sensorer = ncol(log)/2

# Rename columns.
if (sensorer > 1) {
  temp1 <- log[,1:2]
  temp1$measurement <- "ambient"
  colnames(temp1)<-c("datestamp", "temp", "measurement")
  temp2 <- log[,c(1,3)]
  temp2$measurement <- "fermenteringskar"
  colnames(temp2)<-c("datestamp", "temp", "measurement")
  log.2 <- rbind(temp1, temp2)
} else {
  log.2 <- log
  log.2$measurement <- "ambient"
  colnames(log.2)<-c("datestamp", "temp", "measurement")
}

# Alter date and time to POSIX standard.
log.2$datestamp <- as.POSIXct(log.2$datestamp)


# Setup and define plot device.
png("temp_log_plot2.png", plot_width, plot_height, res = 100)
par(mar = c(10,5,5,4) + 0.1)


library(ggplot2)

# Generate plot.

if (sensorer > 1) {
plot <- ggplot (data=log.2) +
        geom_line (aes(x=as.POSIXct(datestamp), y=temp, colour=measurement)) +
        xlab ("") +
        ylab ("temperature, degrees Celsius") +
        theme_bw () +
        ggtitle ("Brewpi temperature log") +
        theme (legend.position = c(0.8,0.1))
} else {
  plot <- ggplot (data=log.2) +
          geom_line (aes (x=as.POSIXct(datestamp), y=temp, colour=measurement)) +
          xlab ("") +
          ylab ("ambient temperature, degrees Celsius") +
          theme_bw () +
          ggtitle ("Brewpi temperature log") +
          theme (legend.position = c(0.8,0.1)) 
}

#if (sensorer > 1) {
#  plot <- plot +
#          geom_line(data=log,aes(x=as.POSIXct(datestamp),y=fermenteringskar),colour="tan3",lwd=2)
#} 

#  plot <- plot + guide_legend()
  
#  legend(
#    "bottomright",
#    c("ambient", "gjæringskar"),
#    pch = 22,
#    col = c("red", "darkgreen"),
#    pt.bg = c("red", "darkgreen"),
#    bty = "n",
#    cex = 1.5
#  )
#} else {
#  points(
#    temp1~datestamp,
#    data = log,
#    type = "l",
#    col = "red"
#  )

#  legend(
#    "bottomright",
#    c("gjæringskar"),
#    pch = 22,
#    col = "red",
#    pt.bg = "red",
#    bty = "n",
#    cex = 1.5
#  )
#}

#axis.POSIXct(
#  1,
#  log$datestamp,
#  labels = T,
#  las = 2,
#  format = "%Y/%m/%d %H:%M:%S",
#  at = log$datestamp[seq(1, length(log$datestamp), 10000)]
#)

#legend(
#  "topleft",
#  "Øl-Pi temperaturmålinger",
#  bty = "n",
#  cex = 1.5
#)

# Close file after we are done writing.
dev.off()
