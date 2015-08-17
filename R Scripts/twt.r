# Install and Activate Packages
install.packages("twitteR")
install.packages("RCurl")
install.packages("RJSONIO")
install.packages("stringr")
library(twitteR)
library(RCurl)
library(RJSONIO)
library(stringr)

# Declare Twitter API Credentials
api_key <- "XXX" # From dev.twitter.com
api_secret <- "XXX" # From dev.twitter.com
token <- "XXX" # From dev.twitter.com
token_secret <- "XXX" # From dev.twitter.com


# Run Twitter Search. Format is searchTwitter("Search Terms", n=100, lang="en", geocode="lat,lng", also accepts since and until).

tweets <- searchTwitter("#GOT -RT Game of Thrones", n=1000, lang="en", since="2015-08-01")

# Transform tweets list into a data frame
tweets.df <- twListToDF(tweets)

View(tweets.df)

# Use the searchTwitter function to only get tweets within 50 miles of Los Angeles
#tweets_geolocated <- searchTwitter("Obamacare OR ACA OR 'Affordable Care Act' OR #ACA", n=100, lang="en", geocode='34.04993,-118.24084,50mi', since="2014-08-20")
#tweets_geoolocated.df <- twListToDF(tweets_geolocated)