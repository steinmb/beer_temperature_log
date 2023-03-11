# Find number of temperatur sensors.

rename_columns <- function (named_columns) {
  sensorer <- ncol(named_columns) - 1
  cat("Data from", sensorer, "probe(s) found.\n")

  # Rename columns.
  if (sensorer == 1) {
    colnames(named_columns) <- c("datestamp", "temp1")
  }

  if (sensorer == 2) {
    head(named_columns, n = 10)
    colnames(named_columns) <- c("datestamp", "temp1", "temp2")
  }

  return(named_columns)
}
