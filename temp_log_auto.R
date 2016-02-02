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

# Libraries and includes.
ggplotLibrary <- try(library(ggplot), silent = TRUE)

if (inherits(ggplotLibrary, "try-error")) {
  writeLines("There was an error. ggplot library missing")
  ggplotLibrary <- FALSE
} else {
  writeLines("Everything worked fine!")
  ggplotLibrary <- TRUE
}

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
  min_temp <- 17 # Default value if nothing is given on start.
}

if (is.na(max_temp)) {
  max_temp <- 24 # Default value if nothing is given on start.
}

# Read logfile into a dataframe.
log <- read.csv(temp_log, header = F)

# Find number of temperatur sensors.
sensorer = ncol(log) / 2

# Rename columns.
if (sensorer > 1) {
  colnames(log) <- c("datestamp", "temp1", "temp2")
} else {
  colnames(log) <- c("datestamp", "temp1")
}

# Alter date and time to POSIX standard.
log$datestamp <- as.POSIXct(log$datestamp)

# Setup and define plot device.
png("temp_log_plot2.png", plot_width, plot_height, res = 100)
par(mar = c(10, 5, 5, 4) + 0.1)

if (!ggplotLibrary) {
  source("plotFallback.r")
  plotFallback()
}

if (ggplotLibrary) {
  source(plotggplot.r)
  writeLines("ggplot yay!")
}

# Close file after we are done writing.
dev.off()
