args <- commandArgs(TRUE)
 
N <- args[1]
x <- rnorm(N,0,1)
 
png(filename="temp.png", width=500, height=500)
hist(x, col="lightblue")
dev.off()

cat("Created the Graph image at the location where this script was found...bye now! Passed value was = ",N)