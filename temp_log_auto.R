#!/usr/local/bin/Rscript

# Hint:
# Convert raw data logs. Some temperatur logs might have lines terminated
# only by LF not CRLF. Running them through this awk command take care of that:
# awk '{printf "%s\r\n", $0}' log_file > new_log_file.

# Libraries and includes.
ggplotLibrary <- try(library(ggplot2), silent = TRUE)

if (inherits(ggplotLibrary, "try-error")) {
  writeLines("There was an error. ggplot library missing")
  ggplotLibrary <- FALSE
} else {
  writeLines("ggplot2 plotting system found (http://ggplot2.org)")
  ggplotLibrary <- TRUE
}

# Configuration
args <- commandArgs(TRUE) # Enable reading arguments from shell.
plot_filename <- "temp_log_plot2.png"
plot_height <- 800
plot_width <- 1200
temp_log <- args[1]
min_temp <- as.numeric(args[2])
max_temp <- as.numeric(args[3])

if (is.na(temp_log)) {
  temp_log <- "demo/demo.csv"
  cat("Name of temperature file not defined, loading demo data\n")
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
sensorer = ncol(log) - 1

# Rename columns.
if (sensorer == 1) {
  cat("Data from", sensorer, "probe(s) found.\n")
  colnames(log) <- c("datestamp", "temp1")
}

if (sensorer == 2) {
  cat("Data from", sensorer, "probe(s) found.\n")
  head(log, n = 10)
  colnames(log) <- c("datestamp", "temp1", "temp2")
}

# Alter date and time to POSIX standard.
log$datestamp <- as.POSIXct(log$datestamp)

if (!ggplotLibrary) {
  cat("Plotting using fallback method.\n")
  png(
    filename = plot_filename,
    width = plot_width,
    height = plot_height,
    res = 100
  )
  par(mar = c(10, 5, 5, 4) + 0.1)
  source("plotFallback.r")
  plotFallback(log, sensorer)
  dev.off() #Cleaning up. Close device(s) after we are done using it.
}

if (ggplotLibrary) {
  cat("Plotting using ggplot2.\n")
  source("plotggplot.r")
  log$measurement <- "und"
  colnames(log) <- c("datestamp", "temp", "measurement")
  tempPlot <- plotggplot(log, sensorer)
  ggsave(
    filename = plot_filename,
    dpi = 150,
    plot = tempPlot
  )
}
